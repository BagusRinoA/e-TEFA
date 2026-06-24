<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-gray-600 text-sm">{{ $label ?? 'Poin Anda' }}</p>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($points, 0, ',', '.') }}</p>
        </div>
        @if (isset($action) && $action)
            <a href="{{ $actionUrl }}" class="text-blue-500 hover:text-blue-700 font-medium text-sm">
                {{ $actionText ?? 'Lihat →' }}
            </a>
        @endif
    </div>
</div>
