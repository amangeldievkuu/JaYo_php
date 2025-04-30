@props(['tag', 'square_card' => false,'size' => 'base'])

@php
    $classes = "rounded-xl font-bold transition-colors duration-300";
        if ($size === 'base') {
            $classes .= " px-5 py-2 text-sm";
        }

        if ($square_card === true){
            $classes .= " bg-black text-white hover:bg-white hover:text-black";
        }
        if ($square_card === false) {
            $classes .= " text-black bg-(--color-card-bg) hover:bg-white/25 hover:text-white";
        }

        if ($size === 'small') {
            $classes .= " text-xs px-3 py-1";
        }
@endphp

<a href="/tags/{{ strtolower($tag->name) }}" class="{{ $classes }}">
    {{ $tag->name }}
</a>
