@props(['type'])

@php
    $color = match ($type->value) {
        'CHECKOUT_CLEANING' => 'bg-purple-100 text-purple-800',
        'STAYOVER_CLEANING' => 'bg-pink-100 text-pink-800',
        'DEEP_CLEANING' => 'bg-orange-100 text-orange-800',
        'TURNDOWN' => 'bg-cyan-100 text-cyan-800',
        'INSPECTION' => 'bg-emerald-100 text-emerald-800',
        default => 'bg-slate-100 text-slate-800',
    };
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $color }}">
    {{ $type->label() }}
</span>
