@extends('layouts.app')

@section('title', 'Detail Redemption Transaction')

@section('content')
    <div class="admin-page">
        <div class="admin-container max-w-5xl">
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">Detail Redemption Transaction</h1>
                    <p class="admin-page-subtitle">Informasi lengkap mengenai transaksi penukaran poin.</p>
                </div>
                <a href="{{ route('admin.redemption.transactions') }}" class="admin-back-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>

        @if (session('success'))
            <div class="admin-alert admin-alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="admin-alert admin-alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="admin-form-grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
            <div class="space-y-6">
                <div class="admin-form-card" style="padding:1.5rem;">
                    <h2 class="text-xl font-bold mb-4 text-foreground" style="border-bottom:1px solid var(--color-border); padding-bottom:0.5rem;">Informasi Transaksi</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Transaction ID:</span>
                            <span class="font-semibold text-foreground">#{{ $transaction->id }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Status:</span>
                            <span>
                                <span class="status-badge status-{{ $transaction->status }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </span>
                        </div>

                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Tanggal Request:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>

                        @if ($transaction->redeemed_at)
                            <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                                <span class="text-muted-foreground">Tanggal Selesai:</span>
                                <span class="font-semibold text-foreground">{{ $transaction->redeemed_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                        @endif
                    </div>

                    <h2 class="text-xl font-bold mt-8 mb-4 text-foreground" style="border-bottom:1px solid var(--color-border); padding-bottom:0.5rem;">User Information</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Nama:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->user->full_name ?? $transaction->user->username }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Username:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->user->username }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Email:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->user->email }}</span>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold mt-8 mb-4 text-foreground" style="border-bottom:1px solid var(--color-border); padding-bottom:0.5rem;">Detail Pengiriman</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Penerima:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->recipient_name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">No. Telepon:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->recipient_phone ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground mb-1">Alamat Lengkap:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->shipping_address ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Kota:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->shipping_city ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Kode Pos:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->shipping_postal_code ?? '-' }}</span>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold mt-8 mb-4 text-foreground" style="border-bottom:1px solid var(--color-border); padding-bottom:0.5rem;">Item Information</h2>

                    <div class="p-4 rounded-lg" style="background:var(--color-secondary); border:1px solid var(--color-border)">
                        <div class="flex space-x-4">
                            @if ($transaction->item->image_url)
                                <img src="{{ asset('storage/' . $transaction->item->image_url) }}" alt="{{ $transaction->item->name }}" class="w-24 h-24 object-cover rounded" style="border:1px solid var(--color-border)">
                            @else
                                <div class="w-24 h-24 rounded flex items-center justify-center" style="background:var(--color-border)">
                                    <span class="text-sm" style="color:var(--color-muted-foreground)">No Image</span>
                                </div>
                            @endif

                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-foreground">{{ $transaction->item->name }}</h3>
                                <p class="text-sm mt-1 text-muted-foreground">{{ Str::limit($transaction->item->description, 100) }}</p>
                                <div class="mt-3 space-y-1">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Harga Per Unit:</span>
                                        <span class="font-semibold text-primary">{{ $transaction->item->points_cost }} poin</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Quantity:</span>
                                        <span class="font-semibold text-foreground">{{ $transaction->quantity }}</span>
                                    </div>
                                    <div class="flex justify-between pt-2 mt-2" style="border-top:1px dashed var(--color-border)">
                                        <span class="font-semibold text-foreground">Total Poin:</span>
                                        <span class="font-bold text-primary">{{ $transaction->points_spent }} poin</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($transaction->notes)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-2 text-foreground">Catatan</h3>
                            <div class="p-4 rounded" style="background:var(--color-secondary); border:1px solid var(--color-border); color:var(--color-foreground)">
                                {{ $transaction->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="admin-form-card" style="padding:1.5rem;">
                    <h3 class="text-lg font-bold mb-4 text-foreground" style="border-bottom:1px solid var(--color-border); padding-bottom:0.5rem;">Aksi</h3>

                    @if ($transaction->status !== 'completed' && $transaction->status !== 'cancelled')
                        <form action="{{ route('admin.redemption.transactions.update-status', $transaction) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-foreground mb-1">Update Status</label>
                                    <select name="status" class="admin-input" required>
                                        <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $transaction->status === 'processing' ? 'selected' : '' }}>Processing (Sedang Disiapkan)</option>
                                        <option value="shipped" {{ $transaction->status === 'shipped' ? 'selected' : '' }}>Shipped (Sedang Dikirim)</option>
                                        <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Completed (Selesai/Diterima)</option>
                                        <option value="cancelled" {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-foreground mb-1">Catatan Tambahan (Resi, dll)</label>
                                    <textarea name="notes" rows="3" class="admin-input" placeholder="Masukkan nomor resi atau alasan pembatalan...">{{ $transaction->notes }}</textarea>
                                </div>

                                <button type="submit" class="admin-btn admin-btn-primary w-full justify-center">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="p-4 rounded" style="background:rgba(59,130,246,0.1); border:1px solid rgba(59,130,246,0.2);">
                            <p class="text-sm" style="color:#1e40af">
                                Transaksi sudah dalam status <strong>{{ ucfirst($transaction->status) }}</strong> dan tidak dapat diubah lagi.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="admin-form-card" style="padding:1.5rem;">
                    <h3 class="text-lg font-bold mb-4 text-foreground" style="border-bottom:1px solid var(--color-border); padding-bottom:0.5rem;">Informasi Poin User</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Poin Saat Ini:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->user->loyaltyPoint->current_points ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b" style="border-color:var(--color-border)">
                            <span class="text-muted-foreground">Total Poin Earned:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->user->loyaltyPoint->total_earned_points ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-muted-foreground">Total Poin Redeemed:</span>
                            <span class="font-semibold text-foreground">{{ $transaction->user->loyaltyPoint->total_redeemed_points ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showCancelForm() {
            document.getElementById('cancelForm').classList.remove('hidden');
        }

        function hideCancelForm() {
            document.getElementById('cancelForm').classList.add('hidden');
        }
    </script>
@endsection
