@props([
    'href' => url()->previous(),
    'label' => 'Back',
    'class' => '',
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 px-4 py-3 rounded-lg border border-border bg-white text-sm font-semibold text-foreground hover:bg-accent transition-colors mb-6 ' . $class]) }}>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
    </svg>
    {{ $slot ?? $label }}
</a>
