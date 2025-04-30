<x-layout>
    <div class="max-w-3xl mx-auto mt-20 py-10">
        <!-- Flashcard -->
        <div class="relative w-full h-100 cursor-pointer perspective mb-10" onclick="flipCard(this)">
            <div class="relative w-full h-full transition-transform duration-700 transform-style-preserve-3d"
                 style="transform-style: preserve-3d;">
                <!-- Front -->
                <div class="absolute inset-0 bg-(--color-card-bg) text-(--color-card-text) rounded-lg shadow-lg p-6 backface-hidden flex flex-col items-center justify-center text-center overflow-y-auto h-100">
                    <p class="text-4xl font-semibold">{{ $post->content_front }}</p>
                    <p class="text-sm text-black/50">{{ $post->translation }}</p>
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

        <!-- Post Info -->
        <div class="flex flex-wrap items-center justify-between gap-4 text-sm text-gray-500 mb-10">

            <!-- Left: User name + published date -->
            <div class="flex items-center gap-2">
                <span>By <span class="font-semibold text-base text-white">{{ $post->user->name }}</span></span>
                <span class="text-gray-400">· {{ $post->created_at->diffForHumans() }}</span>

                {{--                Like Button--}}
                <form action="{{ route('posts.like', $post) }}" method="POST" class="flex items-center gap-1">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-1 hover:text-card-back-bg {{ $post->likes->contains('user_id', auth()->id()) ? 'text-card-bg' : 'text-white' }} font-medium">
                        <span class="material-symbols-outlined">
                            favorite
                        </span>
                        <span>
                            {{ $post->likes->count() }}
                        </span>
                    </button>
                </form>
            </div>

            @if(auth()->id() === $post->user_id)
                <!-- Right: Actions (Edit + Lock/Unlock) -->
                <div class="flex items-center gap-4">

                    <!-- Edit -->
                    <a href="{{ route('posts.edit', $post) }}"
                       class="flex items-center gap-1 text-white hover:text-card-bg font-medium">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        Edit
                    </a>

                    <!-- Lock/Unlock Toggle Form -->
                    <form action="{{ route('posts.togglePrivacy', $post) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1 text-sm font-medium
                               {{ $post->is_public ? 'text-white hover:text-card-bg' : 'text-card-back-bg hover:text-card-bg' }}">
                    <span class="material-symbols-outlined text-base">
                        {{ $post->is_public ? 'lock_open_right' : 'lock' }}
                    </span>
                            {{ $post->is_public ? 'Public' : 'Private' }}
                        </button>
                    </form>

                </div>
            @endif
        </div>


        <!-- Comments Section -->
        <div class="space-y-6">
            <h2 class="text-2xl font-bold mb-4">Comments</h2>

            @foreach($post->comments as $comment)
                <div class="bg-white/10 p-4 rounded-lg shadow-md">
                    <p class="font-semibold">{{ $comment->user->name }}</p>
                    <p class="mt-2">{{ $comment->comment_body }}</p>
                    <p class="text-sm text-gray-400 mt-2">{{ $comment->created_at->diffForHumans() }}</p>
                </div>
            @endforeach
        </div>

        <!-- Add Comment Form -->
        @auth
            <div class="mt-10">
                <h2 class="text-xl font-semibold mb-4">Add a Comment</h2>
                <form action="{{ route('comments.store', $post) }}" method="POST" class="space-y-4">
                    @csrf
                    <textarea name="comment_body" rows="3" class="w-full border border-gray-300 p-3 rounded-lg" required
                              placeholder="Write your comment..."></textarea>
                    <div class="text-right">
                        <x-forms.button>
                            Post Comment
                        </x-forms.button>
                    </div>
                </form>
            </div>
        @endauth
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
</script>
