@props(['post'])
<x-panel class="flex gap-x-6 bg-white/15">
    <div>
        <x-employer-logo :width="90"/>
    </div>

    <div class="flex-1 flex flex-col">
        <a href="/" class="self-start text-sm text-gray-400">{{ $post->user->name }}</a>
        <a href="/posts/{{ $post->id }}" class="font-bold text-xl mt-3 group-hover:text-(--color-card-bg)">{{ $post->content_front }}</a>
        <p class="text-sm text-gray-400 mt-auto">{{ $post->pinyin }}</p>
{{--        <div class="flex items-baseline gap-x-4 mt-2">--}}
{{--            <h3 class="font-bold text-xl group-hover:text-blue-400 whitespace-nowrap">--}}
{{--                {{ $post->content_front }}--}}
{{--            </h3>--}}
{{--            <p class="text-sm text-gray-400 whitespace-nowrap">--}}
{{--                {{ $post->translation }}--}}
{{--            </p>--}}
{{--        </div>--}}
    </div>

    <div class="space-x-1">
        @foreach($post->tags as $tag)
            <x-tag size="small" :$tag/>
        @endforeach
    </div>
</x-panel>
