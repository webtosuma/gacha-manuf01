<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('meta')


    <title>@yield('title') - {{env('APP_NAME')}}</title>

    <!-- wbマニフェスト -->
    <link rel="manifest" href="/manifest.json">

    <!-- ファビコン画像の読み込み -->
    <link rel="shortcut icon" href="{{asset('storage/site/image/favicon.png')}}">

    <!-- bootstrap アイコン の読み込み-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

    <!-- bootstrap CSS の読み込み-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animation.css') }}" rel="stylesheet">

    @yield('style')

    @include('includes.google_tag')



</head>
<body style="background: black;">
        <div id="app" >

            @yield('content')

        </div>
    </main>
    <!-- bootstrap JavaScript -->
    @yield('script')

    @include('includes.appjs')

</body>
</html>
