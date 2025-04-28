@props(['post'])

<x-panel class="flex flex-col text-center">
    <div class="self-start text-sm">{{ $post->user->name }}</div>
    <div class="py-8 font-bold">
        <h3 class="group-hover:text-blue-400 text-lg">{{ $post->content_front }}</h3>
        <p class="text-sm mt-4"></p>
    </div>

    <div class="flex justify-between items-center mt-auto">
        <div class="space-x-1">
            @foreach($post->tags as $tag)
                <x-tag size="small" :$tag/>
            @endforeach
        </div>
        <x-employer-logo :employer="$post->employer" :width="42"/>
    </div>
</x-panel>
