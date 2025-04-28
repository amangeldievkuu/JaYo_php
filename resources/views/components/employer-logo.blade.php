{{--@props(['employer', 'width' => 90])--}}
{{--<img src="{{ asset('storage/'.$employer->logo) }}" alt="" class="rounded-xl" width="{{ $width }}">--}}

@props(['width' => 90])
<img src="http://picsum.photos/seed/picsum{{ rand(0,300) }}/{{ $width }}" alt="" class="rounded-xl">
