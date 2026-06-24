<div
    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
    @if ($status === 'pending') bg-yellow-100 text-yellow-800
    @elseif($status === 'completed')
        bg-green-100 text-green-800
    @elseif($status === 'cancelled')
        bg-red-100 text-red-800
    @else
        bg-gray-100 text-gray-800 @endif
">
    @if ($status === 'pending')
        ⏳ Pending
    @elseif($status === 'completed')
        ✓ Selesai
    @elseif($status === 'cancelled')
        ✕ Dibatalkan
    @else
        {{ ucfirst($status) }}
    @endif
</div>
