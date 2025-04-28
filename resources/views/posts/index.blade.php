<x-layout>
    <div class="max-w-5xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-8 text-center">Flashcard Feed</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($posts as $post)
                <div class="relative w-full h-64 cursor-pointer perspective" onclick="flipCard(this)">
                    <div class="relative w-full h-full transition-transform duration-700 transform-style-preserve-3d"
                         style="transform-style: preserve-3d;">

                        <!-- Front -->
                        <div
                            class="absolute inset-0 bg-white/10 rounded-lg shadow-lg p-6 backface-hidden flex items-center justify-center text-center">
                            <div>
                                <p class="text-xl font-semibold">{{ Str::limit($post->content_front, 150) }}</p>
                                <p class="mt-4 text-sm text-gray-400">
                                    @if(!$post->is_public)
                                        🔒 Private
                                    @endif
                                    by {{ $post->user->name }}
                                </p>
                            </div>
                        </div>

                        <!-- Back -->
                        <div
                            class="absolute inset-0 bg-white/5 rounded-lg shadow-lg p-6 rotate-y-180 backface-hidden flex items-center justify-center text-center">
                            <div>
                                <p class="text-lg">{{ $post->content_back ? nl2br(e($post->content_back)) : 'No backside yet!' }}</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="flex items-center justify-center mt-4">
                    <button onclick="toggleLike({{ $post->id }}, this)" class="text-2xl">
                        @if($post->likes->where('user_id', auth()->id())->count())
                            ❤️
                        @else
                            🤍
                        @endif
                    </button>
                    <span class="ml-2 text-gray-600">{{ $post->likes->count() }}</span>
                </div>
                <!-- 📚 Vocabulary List -->
                <div id="vocabularies-{{ $post->id }}" class="mt-4">
                    @if($post->vocabularies->count())
                        <h3 class="text-lg font-semibold mb-2">Vocabularies:</h3>
                        <ul class="list-disc pl-6 text-left">
                            @foreach($post->vocabularies as $vocab)
                                <li>{{ $vocab->word }} ({{ $vocab->pinyin }}): {{ $vocab->translation }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- 📚 Generate Vocabulary Button (only for owner) -->
                @if(auth()->id() == $post->user_id)
                    <div class="text-center mt-4">
                        <button onclick="generateVocabulary({{ $post->id }})" class="px-4 py-2 bg-emerald-500 text-white rounded hover:bg-emerald-600">
                            Generate Vocabulary
                        </button>
                    </div>
                @endif

            @endforeach

        </div>

        <div class="mt-10 text-center">
            {{ $posts->links() }}
        </div>
    </div>
</x-layout>

<style>
    .perspective {
        perspective: 1000px;
    }

    .transform-style-preserve-3d {
        transform-style: preserve-3d;
    }

    .backface-hidden {
        backface-visibility: hidden;
    }

    .rotate-y-180 {
        transform: rotateY(180deg);
    }
</style>

<script>
    function flipCard(card) {
        const inner = card.querySelector('div');
        if (inner.style.transform === "rotateY(180deg)") {
            inner.style.transform = "rotateY(0deg)";
        } else {
            inner.style.transform = "rotateY(180deg)";
        }
    }

    function toggleLike(postId, button) {
        fetch(`/api/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.liked) {
                    button.innerHTML = '❤️';
                    let countSpan = button.nextElementSibling;
                    countSpan.textContent = parseInt(countSpan.textContent) + 1;
                } else {
                    button.innerHTML = '🤍';
                    let countSpan = button.nextElementSibling;
                    countSpan.textContent = parseInt(countSpan.textContent) - 1;
                }
            });
    }

    function generateVocabulary(postId) {
        fetch(`/api/posts/${postId}/vocabulary`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload(); // Reload page to show new vocabularies
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to generate vocabulary.');
            });
    }


</script>


