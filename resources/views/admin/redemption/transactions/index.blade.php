@extends('layouts.app')

@section('title', 'Redemption Transactions')

@section('content')
    <div class="admin-page admin-page--dashboard">
        <div class="admin-container">
            <div class="admin-layout">
                {{-- Sidebar --}}
                @include('admin.partials.sidebar')

                {{-- Main Content --}}
                <div class="admin-main">
                    <div class="admin-page-header">
                        <div>
                            <h1 class="admin-page-title">Redemption Transactions</h1>
                            <p class="admin-page-subtitle">Daftar transaksi penukaran poin pelanggan.</p>
                        </div>
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

                <div class="admin-table-card">
                    <div class="admin-table-scroll">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Poin</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <span class="td-amount">{{ $transaction->user->full_name ?? $transaction->user->username }}</span>
                                            <span class="td-sub td-muted">{{ $transaction->user->email }}</span>
                                        </td>
                                        <td>{{ $transaction->item->name }}</td>
                                        <td>{{ $transaction->quantity }}</td>
                                        <td class="td-amount" style="color:var(--color-primary)">{{ $transaction->points_spent }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $transaction->status }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td class="td-muted">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.redemption.transactions.show', $transaction) }}" class="admin-action-link">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="td-empty text-center">
                                            Tidak ada transaksi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($transactions->hasPages())
                        <div class="admin-pagination">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
