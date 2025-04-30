@php
    $classes = "p-4 bg-(--color-card-bg) rounded-xl border-b border-transparent hover:border-(--color-card-bg) transition-colors duration-300 group";
@endphp

<div {{ $attributes(['class' => $classes]) }}>
    {{ $slot }}
</div>
