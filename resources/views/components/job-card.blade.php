@props(['post', 'is_flashcard' => false])

<div onclick="flipCard(this)" class="rounded-xl border-b-4 border-b-transparent hover:border-b-gray-400">
    <div class="relative w-full h-64 transition-transform duration-700 transform-style-preserve-3d"
         style="transform-style: preserve-3d;">

        <!-- Front Side -->
        <div
            class="absolute inset-0 bg-(--color-card-bg) text-(--color-card-text) rounded-xl shadow-lg p-4 backface-hidden flex flex-col justify-between">

            <div class="flex justify-between items-start mb-4">
                <div class="font-semibold text-sm text-black/65">{{ $post->user->name }}</div>
                @if($is_flashcard && auth()->id() === $post->user_id)
                    <form action="{{ route('posts.togglePrivacy', $post) }}" method="POST">
                        @csrf
                        <button type="submit" title="Toggle Public/Private">
                            @if($post->is_public)
                                <span class="material-symbols-outlined hover:text-red-400" style="font-size: 16px;">
                                    lock_open_right
                                </span>
                            @else
                                <span class="material-symbols-outlined hover:text-green-700" style="font-size: 16px;">
                                    lock
                                </span>
                            @endif
                        </button>
                    </form>
                @endif

{{--                Like and Input--}}
                <div class="flex flex-col items-center gap-0">
                    <a href="/posts/{{ $post->id }}" class="hover:text-white" title="View details">
                        <span class="material-symbols-outlined text-base">input</span>
                    </a>

                    <form action="{{ route('posts.like', $post) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1 text-xs hover:text-card-back-bg {{ $post->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'text-black' }} font-medium">
                            <span class="material-symbols-outlined" style="font-size: 13px">favorite</span>
                            <span style="font-size: 10px">{{ $post->likes->count() }}</span>
                        </button>

                    </form>

                </div>
            </div>

            <div class="font-bold text-center">
                <h3 class="text-lg leading-snug" style="font-family: Noto Sans TC, sans-serif;">
                    {{ \Illuminate\Support\Str::limit($post->content_front, 50) }}
                </h3>
                @if($post->translation)
                    <p class="text-sm mt-2 text-black/50 font-medium">{{ \Illuminate\Support\Str::limit($post->translation,50) }}</p>
                @endif
            </div>

            <div class="flex justify-between items-center mt-6">
                <div class="space-x-1">
                    @foreach($post->tags as $tag)
                        <x-tag :square_card="true" size="small" :$tag/>
                    @endforeach
                </div>
                <x-employer-logo :width="42"/>
            </div>
        </div>

        <!-- Back Side -->
        <div
            class="absolute inset-0 bg-(--color-card-back-bg) rounded-lg shadow-lg p-6 rotate-y-180 backface-hidden flex flex-col items-start justify-start text-left overflow-y-auto space-y-6">

            @if($post->pinyin)
                <div>
                    <p class="text-sm font-semibold text-(--color-card-back-section) mb-1">Pinyin:</p>
                    <p class="text-lg">{{ $post->pinyin }}</p>
                </div>
            @endif

            @if($post->translation)
                <div>
                    <p class="text-sm font-semibold text-(--color-card-back-section) mb-1">Translation:</p>
                    <p class="text-lg">{{ $post->translation }}</p>
                </div>
            @endif

            @php
                $breakdown = json_decode($post->word, true);
            @endphp

            @if($breakdown && is_array($breakdown))
                <div>
                    <p class="text-sm font-semibold text-(--color-card-back-section) mb-1">Breakdown:</p>
                    <ul class="list-inside space-y-1 text-base leading-relaxed">
                        @foreach($breakdown as $item)
                            <li>{{ $item['word'] }} ({{ $item['pinyin'] }}) = {{ $item['meaning'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Flipcard Javascript --}}
@once
    <script>
        function flipCard(card) {
            const inner = card.querySelector('.transition-transform');
            if (inner.style.transform === "rotateY(180deg)") {
                inner.style.transform = "rotateY(0deg)";
            } else {
                inner.style.transform = "rotateY(180deg)";
            }
        }
    </script>
@endonce

{{-- Flipcard Styles --}}
@once
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
@endonce
