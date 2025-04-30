<x-layout>
    <div class="space-y-10">
        <section class="text-center pt-20">
            <h1 class="font-bold text-4xl">Lets find your next flashcard</h1>
            <x-forms.form action="/search" class="mt-6">
                <x-forms.input name="q" :label="false" placeholder="Art"/>
            </x-forms.form>
        </section>
        <section class="pt-10">
            <x-section-heading>Most Liked Posts</x-section-heading>
            <div class="grid lg:grid-cols-3 gap-8 mt-6">
                @foreach($mostLiked as $post)
                    <x-job-card :$post/>
                @endforeach
            </div>
        </section>

        <section>
            <x-section-heading>Tags</x-section-heading>
            <div class="mt-6 space-x-1">
                @foreach($tags as $tag)
                    <x-tag :$tag/>
                @endforeach
            </div>
        </section>

        <section>
            <x-section-heading>Recent Posts</x-section-heading>
            <div class="mt-6 space-y-6">
                @foreach($posts as $post)
                    <x-job-card-wide :$post />
                @endforeach
            </div>
        </section>
    </div>
</x-layout>
