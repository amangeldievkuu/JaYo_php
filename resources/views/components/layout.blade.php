<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JaYo!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..600;1,100..600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-(--color-background-black) text-white pb-10">
<div class="px-10">
    <nav class="flex justify-between items-center border-b border-white/10 py-4">
        <div>
            <a href="/">
                <img src="{{Vite::asset("resources/images/logo.svg")}}" alt="">
            </a>
        </div>

        <div class="space-x-6 font-bold">
            <a href="/">Posts</a>
            <a href="">Flashcards</a>
            <a href="">Community</a>
        </div>

        <div>
            <a href="">Post a Flashcard</a>
        </div>

    </nav>
    <main class="mt-10 max-w-[986px] mx-auto">
        {{ $slot }}
    </main>
</div>

</body>
</html>




