<x-layout>
    <x-page-heading>Results</x-page-heading>
    <div class="space-y-6">
        @foreach($posts as $post)
            <x-job-card-wide :$post/>
        @endforeach
    </div>
</x-layout>
