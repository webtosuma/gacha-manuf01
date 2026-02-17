<!-- ファビコン画像の読み込み -->
<link rel="shortcut icon" href="{{asset('storage/site/image/favicon.png')}}">

<!-- bootstrap アイコン の読み込み-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">



<!-- Styles -->
{{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
<link href="{{ asset('css/default.css') }}" rel="stylesheet">
<link href="{{ asset('css/animation.css') }}" rel="stylesheet">

@php $client = config('app.client'); @endphp
@if($client && $client !== 'default')
    <link href="{{ asset("css/clients/{$client}.css") }}" rel="stylesheet">
@endif


<!----- animation ----->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />


<!-- Google Fontsの読み込み -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@900&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">

<style>
    header,main {
        /* font-family: 'Roboto', sans-serif; */
        font-family: "Noto Sans JP", sans-serif;
    }
    h1,h2,h3,h4,.fs-1,.fs-2,.fs-3,.fs-4{
        font-family: 'Roboto', sans-serif;
    }

    /* ガチャ販売機　頭部 */
    .ratio-6x1{ --bs-aspect-ratio:16.67%; }
</style>

