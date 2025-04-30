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
