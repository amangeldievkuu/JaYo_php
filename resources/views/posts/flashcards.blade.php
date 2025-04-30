<x-layout>
    <div class="max-w-5xl mx-auto py-20">
        <h1 class="text-3xl font-bold mb-8 text-center">Flashcard Posts</h1>

        <div class="grid lg:grid-cols-3 gap-8 mt-6">
            @foreach ($posts as $post)
                <x-job-card :$post :is_flashcard="true"/>
            @endforeach
        </div>
        <div class="mt-10 text-center">
            {{ $posts->links() }}
        </div>
    </div>
</x-layout>


