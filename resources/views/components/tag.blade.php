@props(['tag','size' => 'base'])

@php
    $classes = "bg-white/10 hover:bg-white/25 rounded-xl font-bold transition-colors duration-300";
        if ($size === 'base') {
            $classes .= " px-5 py-2 text-sm";
        }

        if ($size === 'small') {
            $classes .= " text-xs px-3 py-1";
        }
@endphp

<a href="/tags/{{ strtolower($tag->name) }}" class="{{ $classes }}">
    {{ $tag->name }}
</a>
