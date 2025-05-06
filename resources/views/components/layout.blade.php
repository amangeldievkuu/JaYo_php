<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JaYo!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..600;1,100..600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-(--color-background-black) text-(--color-body-text) pb-10">
<div>
    <nav class="fixed top-0 left-0 right-0 z-50 backdrop-blur flex justify-between items-center border-b border-white/10 py-4 px-10">
        <div>
            <a href="/">
                <img src="{{Vite::asset("resources/images/logos.png")}}" class="w-15" alt="">
            </a>
        </div>

        <div class="space-x-6 font-bold">
            <a href="/posts" class="hover:text-(--color-card-bg)">Posts</a>
{{--            <a href="" class="hover:text-(--color-card-bg)">Community</a>--}}
            <a href="{{ route('posts.flashcards') }}" class="hover:text-(--color-card-bg)">My Flashcards</a>
        </div>

        @auth
            <div class="space-x-6 font-bold flex">
                <a href="/posts/create" class="hover:text-(--color-card-bg)">Post a Flashcard</a>
                <form action="/logout" method="POST">
                    @csrf
                    @method("DELETE")

                   <button class="hover:text-(--color-card-bg)">Log out</button>
                </form>
            </div>
        @endauth

        @guest
            <div class="space-x-4 font-bold">
                <a href="/login" class="hover:text-(--color-card-bg)">Login</a>
                <a href="/register" class="hover:text-(--color-card-bg)">Register</a>
            </div>
        @endguest

    </nav>
    <main class="mt-10 max-w-[986px] mx-auto">
        {{ $slot }}
    </main>
</div>

</body>
</html>




