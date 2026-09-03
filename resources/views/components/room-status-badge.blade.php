@props(['status'])

<span class="badge badge-priority-low inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $status->color() }}-100 text-{{ $status->color() }}-800 border border-{{ $status->color() }}-200">
    <span class="w-1.5 h-1.5 rounded-full bg-{{ $status->color() }}-500"></span>
    {{ $status->label() }}
</span>
