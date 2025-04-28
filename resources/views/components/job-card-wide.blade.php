@props(['post'])
<x-panel class="flex gap-x-6">
    <div>
        <x-employer-logo :width="90"/>
    </div>

    <div class="flex-1 flex flex-col">
        <a href="/" class="self-start text-sm text-gray-400">{{ $post->user->name }}</a>
        <h3 class="font-bold text-xl mt-3 group-hover:text-blue-400">{{ $post->content_front }}</h3>
        <p class="text-sm text-gray-400 mt-auto">{{ $post->content_back }}</p>
    </div>

    <div class="space-x-1">
        @foreach($post->tags as $tag)
            <x-tag size="small" :$tag/>
        @endforeach
    </div>
</x-panel>
