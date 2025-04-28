<x-layout>
    <div class="max-w-3xl mx-auto py-10">

        <!-- Flashcard -->
        <div class="relative w-full h-80 cursor-pointer perspective mb-10" onclick="flipCard(this)">
            <div class="relative w-full h-full transition-transform duration-700 transform-style-preserve-3d"
                 style="transform-style: preserve-3d;">

                <!-- Front -->
                <div
                    class="absolute inset-0 bg-white/10 rounded-lg shadow-lg p-6 backface-hidden flex items-center justify-center text-center">
                    <p class="text-2xl font-semibold">{{ $post->content_front }}</p>
                </div>

                <!-- Back -->
                <div
                    class="absolute inset-0 bg-white/10 rounded-lg shadow-lg p-6 rotate-y-180 backface-hidden flex items-center justify-center text-center">
                    <p class="text-lg">{!! nl2br(e($post->content_back)) !!}</p>
                </div>

            </div>
        </div>

        <!-- Post Info -->
        <div class="text-center mb-10 text-gray-600">
            @if(!$post->is_public)
                🔒 Private |
            @endif
            by {{ $post->user->name }}
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
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Post Comment
                        </button>
                    </div>
                </form>
            </div>
        @endauth

    </div>

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

</x-layout>
