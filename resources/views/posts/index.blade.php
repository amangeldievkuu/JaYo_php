<x-layout>
    <div class="space-y-10">
        <section class="text-center pt-6">
            <h1 class="font-bold text-4xl">Lets find your next flashcard</h1>
            <form action="" class="mt-6">
                <input type="text" placeholder="Software Engineer..." class="rounded-xl bg-white/10 border-white/10 px-5 py-4 w-full max-w-xl">
            </form>
        </section>
        <section class="pt-10">
            <x-section-heading>Top Posts</x-section-heading>
            <div class="grid lg:grid-cols-3 gap-8 mt-6">
               @foreach($posts as $post)
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
