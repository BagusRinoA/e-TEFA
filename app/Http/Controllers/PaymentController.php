<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\LoyaltyPoint;
use App\Models\PointEarningConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Configure cURL with CA bundle to avoid SSL errors
        $caCert = storage_path('app/cacert.pem');
        if (file_exists($caCert)) {
            // Convert Windows path to forward slashes for cURL
            $caCertPath = str_replace('\\', '/', realpath($caCert));
            Config::$curlOptions = [
                CURLOPT_CAINFO => $caCertPath,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_TIMEOUT => 60,
            ];
            Log::info('[Midtrans] Using CA bundle at: ' . $caCertPath);
        } else {
            Log::warning('[Midtrans] CA bundle not found at ' . $caCert . '. Attempting to use system CA certificates.');
            // Let cURL use system CA certificates
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ];
        }
    }

    /**
     * Redirect to Midtrans payment page
     */
    public function redirectToMidtrans(Order $order)
    {
        try {
            // Get order details
            $orderId = $order->id;
            $amount = (int) $order->total;
            $customerName = $order->customer_name;
            $customerEmail = $order->customer_email;
            $customerPhone = $order->customer_phone;

            // Load items explicitly
            $order->load('items');
            Log::info('Order loaded for payment', [
                'order_id' => $orderId,
                'item_count' => $order->items->count(),
                'items_data' => $order->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_id_type' => gettype($item->product_id),
                        'product_name' => $item->product_name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                    ];
                })->toArray(),
            ]);

            if ($order->items->isEmpty()) {
                Log::error('Order has no items', ['order_id' => $orderId]);
                throw new \Exception('Order has no items');
            }

            // Build transaction data
            $transactionDetails = [
                'order_id' => 'ORDER-' . $orderId . '-' . time(),
                'gross_amount' => $amount,
            ];

            $customerDetails = [
                'first_name' => $customerName,
                'email' => $customerEmail,
                'phone' => $customerPhone,
                'billing_address' => [
                    'address' => $order->shipping_address,
                    'city' => $order->shipping_city,
                    'postal_code' => $order->shipping_postal_code,
                    'country_code' => 'IDN',
                ],
                'shipping_address' => [
                    'address' => $order->shipping_address,
                    'city' => $order->shipping_city,
                    'postal_code' => $order->shipping_postal_code,
                    'country_code' => 'IDN',
                ],
            ];

            // Build item details
            $items = [];
            $itemsTotal = 0;
            foreach ($order->items as $item) {
                $price = intval($item->price);
                $quantity = intval($item->quantity);
                $productId = strval($item->product_id);
                $productName = trim($item->product_name ?? '');

                if ($price <= 0 || $quantity <= 0) {
                    Log::error('Invalid item price or quantity', [
                        'order_id' => $orderId,
                        'product_id' => $productId,
                        'price' => $price,
                        'quantity' => $quantity,
                    ]);
                    throw new \Exception('Invalid item price or quantity');
                }

                if (empty($productName)) {
                    Log::error('Product name is empty', [
                        'order_id' => $orderId,
                        'product_id' => $productId,
                    ]);
                    throw new \Exception('Product name cannot be empty');
                }

                $itemsTotal += ($price * $quantity);

                $items[] = [
                    'id' => $productId,
                    'price' => $price,
                    'quantity' => $quantity,
                    'name' => $productName,
                ];
            }

            Log::info('Building Midtrans payload', [
                'order_id' => $order->id,
                'item_count' => count($items),
                'items' => $items,
                'total_from_order' => $amount,
                'total_from_items' => $itemsTotal,
                'match' => ($amount === $itemsTotal),
            ]);

            $payload = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $items,
                'callbacks' => [
                    'finish' => route('payment.finish', ['order' => $order->id]),
                    'error' => route('payment.error', ['order' => $order->id]),
                    'pending' => route('payment.pending', ['order' => $order->id]),
                ],
            ];

            Log::info('Sending payload to Midtrans Snap', ['payload' => json_encode($payload)]);

            // Test with minimal payload first
            $minimalPayload = [
                'transaction_details' => [
                    'order_id' => $transactionDetails['order_id'],
                    'gross_amount' => $amount,
                ],
                'item_details' => $items,
                'customer_details' => $customerDetails,
            ];

            Log::info('Attempting Midtrans Snap Token with minimal payload');

            $snapToken = Snap::getSnapToken($minimalPayload);

            // Save transaction token to order
            $order->update([
                'midtrans_transaction_id' => $transactionDetails['order_id'],
                'payment_status' => 'pending',
            ]);

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle payment finish callback
     */
    public function finish(Request $request, Order $order)
    {
        DB::transaction(function () use ($order) {
            // Re-fetch with a lock to avoid race condition with webhook
            $order = Order::lockForUpdate()->find($order->id);

            if ($order->payment_status !== 'completed') {
                $this->decrementStock($order);
                $this->addLoyaltyPoints($order);
                $order->update([
                    'payment_status'      => 'completed',
                    'status'              => 'processing',
                    'payment_completed_at' => now(),
                ]);
            }
        });

        $order->refresh();
        return view('payment.result', compact('order'));
    }

    /**
     * Handle payment error callback
     */
    public function error(Request $request, Order $order)
    {
        $order->update(['payment_status' => 'failed']);

        return view('payment.result', compact('order'));
    }

    /**
     * Handle payment pending callback
     */
    public function pending(Request $request, Order $order)
    {
        $order->update(['payment_status' => 'pending']);

        return view('payment.result', compact('order'));
    }

    /**
     * Handle Midtrans webhook notification
     */
    public function notification(Request $request)
    {
        $notif = json_decode($request->getContent());
        $transactionId = $notif->transaction_id;
        $orderCode = $notif->order_id;
        $statusCode = $notif->status_code;
        $paymentType = $notif->payment_type;
        $fraudStatus = isset($notif->fraud_status) ? $notif->fraud_status : null;

        Log::info('Midtrans Notification: ', (array) $notif);

        // Find order by transaction ID
        $order = Order::query()->where(['midtrans_transaction_id' => $orderCode])->first();

        if (!$order) {
            Log::warning('Order not found for transaction: ' . $orderCode);
            return response('Order not found', 404);
        }

        // Handle different payment statuses
        if ($statusCode == 200 || $statusCode == 201) {
            if ($fraudStatus == 'accept') {
                DB::transaction(function () use ($order) {
                    // Re-fetch with lock to avoid race condition with finish()
                    $order = Order::lockForUpdate()->find($order->id);

                    if ($order->payment_status !== 'completed') {
                        $this->decrementStock($order);
                        $this->addLoyaltyPoints($order);
                        $order->update([
                            'payment_status'      => 'completed',
                            'status'              => 'processing',
                            'payment_completed_at' => now(),
                        ]);
                    }
                });
                Log::info('Payment completed for order: ' . $order->id);
            }
        } elseif ($statusCode == 202) {
            // Pending payment
            $order->update(['payment_status' => 'pending']);
            Log::info('Payment pending for order: ' . $order->id);
        } elseif ($statusCode == 407 || $statusCode == 406) {
            // Payment failed or cancelled
            $order->update(['payment_status' => 'failed']);
            Log::info('Payment failed for order: ' . $order->id);
        }

        return response('Notification processed', 200);
    }

    /**
     * Decrement stock for every item in the order.
     * Must be called inside a DB transaction.
     * Clamps at 0 so stock never goes negative.
     */
    private function decrementStock(Order $order): void
    {
        $order->load('items.product');

        foreach ($order->items as $item) {
            if ($item->product) {
                // Hitung stok baru dan pastikan tidak kurang dari 0 (clamp to zero)
                $newStock = max(0, $item->product->stock - $item->quantity);
                $item->product->update(['stock' => $newStock]);

                Log::info(sprintf(
                    'Stock decremented: product #%d "%s" by %d (remaining: %d)',
                    $item->product->id,
                    $item->product->name,
                    $item->quantity,
                    max(0, $item->product->stock)
                ));
            }
        }
    }

    /**
     * Add loyalty points to user when order payment is completed.
     * Must be called inside a DB transaction.
     */
    private function addLoyaltyPoints(Order $order): void
    {
        try {
            $user = $order->user;
            if (!$user) {
                Log::warning('Order has no associated user: ' . $order->id);
                return;
            }

            // Get or create loyalty point record
            $loyaltyPoint = $user->loyaltyPoint;
            if (!$loyaltyPoint) {
                $loyaltyPoint = LoyaltyPoint::create([
                    'user_id' => $user->id,
                    'current_points' => 0,
                    'total_earned_points' => 0,
                    'total_redeemed_points' => 0,
                ]);
            }

            // Calculate points based on order total
            $pointsEarned = PointEarningConfiguration::calculatePoints($order->total);

            if ($pointsEarned > 0) {
                $loyaltyPoint->addPoints($pointsEarned);

                Log::info(sprintf(
                    'Loyalty points added: user #%d earned %d points for order #%d (total: %d)',
                    $user->id,
                    $pointsEarned,
                    $order->id,
                    $loyaltyPoint->current_points
                ));
            } else {
                Log::info(sprintf(
                    'No loyalty points earned for order #%d (amount: %f)',
                    $order->id,
                    $order->total
                ));
            }
        } catch (\Exception $e) {
            Log::error('Error adding loyalty points: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'user_id' => $order->user_id ?? null,
            ]);
        }
    }
}
