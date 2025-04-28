{{--<x-layout>--}}
{{--    <x-page-heading>New Post</x-page-heading>--}}
{{--    <x-forms.form method="POST" action="/posts">--}}
{{--        <x-forms.input label="title" name="title" placeholder="CEO"/>--}}
{{--        <x-forms.input label="salary" name="salary" placeholder="120,000$ USD"/>--}}
{{--        <x-forms.input label="location" name="location" placeholder="San Francisco"/>--}}

{{--        <x-forms.select label="Schedule" name="schedule">--}}
{{--            <option value="Part Time">Part time</option>--}}
{{--            <option value="Full Time">Full time</option>--}}
{{--            <option value="Internship">Internship</option>--}}
{{--        </x-forms.select>--}}

{{--        <x-forms.input label="URL" name="url" placeholder="https://google.com"/>--}}
{{--        <x-forms.checkbox label="Featured" name="featured"/>--}}
{{--        <x-forms.divider/>--}}
{{--        <x-forms.input label="Tags (comma separated)" name="tags" placeholder="Art, Design"/>--}}
{{--        <x-forms.button>Publish</x-forms.button>--}}
{{--    </x-forms.form>--}}
{{--</x-layout>--}}
<x-layout>
    <x-page-heading>Create New Post</x-page-heading>

    <x-forms.form action="{{ route('posts.store') }}" method="POST">
        @csrf
        <label for="content" class="block text-lg font-semibold mb-2">Write your post (中文 / English)</label>
        <textarea name="content_front" id="content_front" rows="6" class="w-full border border-gray-300 p-3 rounded-lg"
                  required></textarea>
        <x-forms.checkbox name="is_public" label="Make this post public"/>
        <x-forms.input label="Tags (comma-separated)" name="tags" placeholder="e.g. travel, food, daily life"/>
        <input type="hidden" name="content_back" id="content_back">
        <x-forms.button type="submit">Save Post</x-forms.button>
    </x-forms.form>

</x-layout>
<!-- Simple JS to call backend -->

<script>
    document.getElementById('generate-backside').addEventListener('click', function () {
        const content = document.getElementById('content').value;
        fetch('/api/generate-backside', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({content: content})
        })
            .then(res => res.json())
            .then(data => {
                document.getElementById('content_back').value = data.backside;
                alert('Backside generated! You can now submit.');
            });
    });

    document.getElementById('generate-tags').addEventListener('click', function () {
        const content = document.getElementById('content').value;
        fetch('/api/generate-tags', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({content: content})
        })
            .then(res => res.json())
            .then(data => {
                document.getElementById('tags').value = data.tags.join(', ');
                alert('Tags generated!');
            });
    });
</script>

