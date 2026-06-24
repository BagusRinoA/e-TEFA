<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Build display rows from session cart [product_id => qty].
     *
     * @return array{0: array<int, array<string, mixed>>, 1: float|int}
     */
    protected function buildCartItems(array $cart): array
    {
        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $subtotal = $product->price * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return [$cartItems, $total];
    }

    /** Halaman keranjang */
    public function cart()
    {
        $cart = session('cart', []);
        [$cartItems, $total] = $this->buildCartItems($cart);

        return view('cart.index', compact('cartItems', 'total', 'cart'));
    }

    /** Simpan item terpilih lalu ke halaman checkout */
    public function prepareCheckout(Request $request)
    {
        $request->validate([
            'selected_product_ids' => 'required|string',
        ]);

        $ids = json_decode($request->selected_product_ids, true);
        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Pilih minimal satu produk.');
        }

        $ids = array_map('intval', $ids);
        $cart = session('cart', []);
        $validIds = [];
        foreach ($ids as $id) {
            if (isset($cart[$id])) {
                $validIds[] = $id;
            }
        }

        if (empty($validIds)) {
            return back()->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        session([
            'checkout_selection' => array_values(array_unique($validIds)),
            'quick_checkout' => false,
        ]);

        return redirect()->route('checkout');
    }

    /** Halaman checkout (alamat + bayar Midtrans) */
    public function checkoutPage()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $quickCheckout = (bool) session('quick_checkout', false);
        $selection = session('checkout_selection');

        if ($quickCheckout) {
            $selectedIds = array_map('intval', array_keys($cart));
        } elseif (is_array($selection) && ! empty($selection)) {
            $cartKeys = array_map('intval', array_keys($cart));
            $selectedIds = array_values(array_intersect(array_map('intval', $selection), $cartKeys));
            if (empty($selectedIds)) {
                return redirect()->route('cart.index')->with('error', 'Pilih produk di keranjang terlebih dahulu.');
            }
        } else {
            return redirect()->route('cart.index')->with('info', 'Pilih produk yang ingin dibeli di halaman keranjang.');
        }

        $checkoutCart = array_intersect_key($cart, array_flip($selectedIds));
        [$checkoutItems, $total] = $this->buildCartItems($checkoutCart);

        if (empty($checkoutItems)) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada item untuk checkout.');
        }

        $selectedProductIdsJson = json_encode(array_map('strval', $selectedIds));

        return view('checkout.index', compact(
            'checkoutItems',
            'total',
            'checkoutCart',
            'selectedProductIdsJson',
            'quickCheckout'
        ));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $cart = session('cart', []);
        $productId = (int) $request->product_id;
        $quantity = (int) $request->quantity;

        $existingQty = $cart[$productId] ?? 0;
        $totalQty = $existingQty + $quantity;

        if ($totalQty > $product->stock) {
            $availableQty = max(0, $product->stock - $existingQty);
            $message = "Stok tidak cukup. Hanya {$availableQty} unit yang bisa ditambahkan (di keranjang sudah ada {$existingQty}).";

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                ], 400);
            }

            return back()->with('error', $message);
        }

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        session(['cart' => $cart]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => '🛒 Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => count($cart),
                'redirect_url' => route('cart.index'),
            ]);
        }

        return redirect()->route('cart.index')->with('success', '🛒 Produk berhasil ditambahkan ke keranjang!');
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $quantity = (int) $request->quantity;

        if ($quantity > $product->stock) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Hanya tersedia {$product->stock} unit.",
                ], 400);
            }

            return back()->with('error', "Hanya tersedia {$product->stock} unit.");
        }

        session([
            'cart' => [(int) $product->id => $quantity],
            'quick_checkout' => true,
            'checkout_selection' => [(int) $product->id],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => '⏳ Mengalihkan ke checkout…',
                'redirect_url' => route('checkout'),
            ]);
        }

        return redirect()->route('checkout');
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = session('cart', []);
        $productId = (int) $request->product_id;

        unset($cart[$productId]);

        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $cart = session('cart', []);
        $productId = (int) $request->product_id;
        $quantity = (int) $request->quantity;

        if ($quantity > $product->stock) {
            return back()->with('error', "Jumlah tidak bisa {$quantity}. Maksimal stok {$product->stock}.");
        }

        if (isset($cart[$productId])) {
            $cart[$productId] = $quantity;
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Jumlah diperbarui.');
    }

    public function process(Request $request)
    {
        try {
            $cart = session('cart', []);

            $selectedIdsRaw = $request->input('selected_product_ids', []);
            if (is_string($selectedIdsRaw)) {
                $decoded = json_decode($selectedIdsRaw, true);
                $selectedIds = is_array($decoded) ? $decoded : [];
            } else {
                $selectedIds = is_array($selectedIdsRaw) ? $selectedIdsRaw : [];
            }

            $selectedIds = array_map('intval', $selectedIds);

            if (empty($cart)) {
                return response()->json(['status' => 'error', 'message' => 'Keranjang kosong!'], 400);
            }

            if (! empty($selectedIds)) {
                $cartToProcess = array_intersect_key($cart, array_flip($selectedIds));
                if (empty($cartToProcess)) {
                    return response()->json(['status' => 'error', 'message' => 'Tidak ada item yang dipilih!'], 400);
                }
            } else {
                $cartToProcess = $cart;
            }

            $user = Auth::user();
            $validated = $request->validate([
                'customer_name' => 'required|string',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
                'shipping_address' => 'required|string',
                'shipping_city' => 'required|string',
                'shipping_postal_code' => 'required|string',
            ]);

            $total = 0;
            $invalidItems = [];

            try {
                \DB::transaction(function () use ($cartToProcess, &$invalidItems, &$total) {
                    foreach ($cartToProcess as $productId => $quantity) {
                        $product = Product::lockForUpdate()->find($productId);

                        if (! $product) {
                            $invalidItems[] = 'Produk tidak ditemukan';
                            throw new \Exception('Product not found');
                        }

                        if ($quantity <= 0) {
                            $invalidItems[] = "{$product->name}: jumlah tidak valid";
                            throw new \Exception("Invalid quantity for {$product->name}");
                        }

                        if ($quantity > $product->stock) {
                            $invalidItems[] = "{$product->name}: minta {$quantity}, stok {$product->stock}";
                            throw new \Exception("Insufficient stock for {$product->name}");
                        }

                        $total += $product->price * $quantity;
                    }
                });
            } catch (\Exception $e) {
                Log::warning('Stock validation failed: '.implode('; ', $invalidItems));

                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak bisa melanjutkan — stok tidak cukup: '.implode('; ', $invalidItems),
                ], 400);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'payment_method' => 'midtrans',
                'status' => 'pending',
                'payment_status' => 'pending',
                'total' => 0,
            ]);

            foreach ($cartToProcess as $productId => $quantity) {
                $product = Product::find($productId);
                if ($product) {
                    $subtotal = $product->price * $quantity;
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            $order->update(['total' => $total]);

            $newCart = array_diff_key($cart, $cartToProcess);
            if (empty($newCart)) {
                session()->forget('cart');
            } else {
                session(['cart' => $newCart]);
            }

            session()->forget(['quick_checkout', 'checkout_selection']);

            return response()->json([
                'status' => 'success',
                'order_id' => $order->id,
                'redirect_url' => route('payment.redirect', ['order' => $order->id]),
            ]);
        } catch (\Exception $e) {
            Log::error('Checkout error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }
}
