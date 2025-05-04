<x-layout>
    <div class="max-w-3xl mx-auto mt-20 py-10">

        <h1 class="text-2xl font-bold mb-6 text-center">Edit Flashcard Post</h1>

        <form action="{{ route('posts.update', $post) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Content -->
            <div>
                <label for="content_front" class="block font-semibold mb-1">Your Sentence</label>
                <textarea name="content_front" id="content_front" rows="3"
                          class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                          required>{{ old('content_front', $post->content_front) }}</textarea>
                @error('content_front')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block font-semibold mb-1">Tags (comma separated)</label>
                <input type="text" name="tags" id="tags"
                       class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       value="{{ old('tags', $post->tags->pluck('name')->implode(', ')) }}">
            </div>

            <!-- Visibility -->
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_public" id="is_public" value="1"
                       {{ $post->is_public ? 'checked' : '' }}
                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="is_public" class="text-sm text-white">Make this post public</label>
            </div>

            <!-- Submit -->
            <div class="text-right">
                <x-forms.button>
                    Save Changes
                </x-forms.button>
            </div>
        </form>

        <!-- Preview -->
        <div class="mt-12">
            <h2 class="text-xl font-bold mb-4 text-center">Live Preview</h2>

            <div class="relative w-full h-64 cursor-pointer perspective" onclick="flipCard(this)">
                <div class="relative w-full h-full transition-transform duration-700 transform-style-preserve-3d" style="transform-style: preserve-3d;">

                    <!-- Front -->
                    <div class="absolute inset-0 bg-white/40 rounded-lg shadow-lg p-6 backface-hidden flex items-center justify-center text-center">
                        <p id="preview-front" class="text-xl font-semibold text-white">
                            {{ $post->content_front }}
                        </p>
                    </div>

                    <!-- Back -->
                    <div
                        class="absolute inset-0 bg-(--color-card-back-bg) rounded-lg shadow-lg p-6 rotate-y-180 backface-hidden flex flex-col items-center justify-start text-center overflow-y-auto space-y-6">

                        @if($post->pinyin)
                            <div>
                                <p class="text-lg font-semibold text-(--color-card-back-section) mb-1">Pinyin:</p>
                                <p class="text-xl">{{ $post->pinyin }}</p>
                            </div>
                        @endif

                        @if($post->translation)
                            <div>
                                <p class="text-lg font-semibold text-(--color-card-back-section) mb-1">Translation:</p>
                                <p class="text-xl">{{ $post->translation }}</p>
                            </div>
                        @endif

                        @php
                            $breakdown = json_decode($post->word, true);
                        @endphp

                        @if($breakdown && is_array($breakdown))
                            <div>
                                <p class="text-lg font-semibold text-(--color-card-back-section) mb-1">Breakdown:</p>
                                <ul class="list-inside space-y-1 text-base leading-relaxed">
                                    @foreach($breakdown as $item)
                                        <li class="text-xl">{{ $item['word'] }} ({{ $item['pinyin'] }})
                                            = {{ $item['meaning'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>


    </div>
</x-layout>

<script>
    const contentInput = document.getElementById('content_front');
    const previewFront = document.getElementById('preview-front');

    contentInput.addEventListener('input', () => {
        const text = contentInput.value.trim();
        previewFront.textContent = text || 'Your sentence will appear here.';
    });

    function flipCard(card) {
        const inner = card.querySelector('.transition-transform');
        if (inner.style.transform === "rotateY(180deg)") {
            inner.style.transform = "rotateY(0deg)";
        } else {
            inner.style.transform = "rotateY(180deg)";
        }
    }
</script>
