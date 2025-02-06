<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- meta -->
    @yield('meta')
    @include('includes.meta')


    <title>@yield('title') - {{env('APP_NAME')}}</title>




    <!-- wbマニフェスト -->
    @if ( config('app.manifest') )
        <link rel="manifest" href="/manifest.json">
    @endif

    <!--共通CSS-->
    @include('includes.css')

    <style>
        /* サイトデフォルト背景 */
        #bgWindow{
            background: no-repeat center center / cover;
            background-image: url({{asset('storage/site/image/bg00.jpg')}});
        }
    </style>

    @yield('style')



    <!-- Googleタグ -->
    @include('includes.google_tag')


</head>
<body class="bg-">

    <!--背景画像-->
    <div id="bgWindow"
    class="position-fixed top-0 start-0 w-100 h-100"
    style="z-index: -1;"
    ></div>



    <header class="mx-auto p-2" style="max-width:600px;">
        <h1 class="m-0 fs-6">
            <a href="{{route('home')}}" class="navbar-brand  fs-2 fw-bold">
                <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}"
                class="d-brock" style="height:4rem;">
            </a>
        </h1>
    </header>
    <main >

        <div id="app" class="mx-auto p-3" style="max-width:600px;">

            @yield('content')

        </div>


    </main>
    <footer cclass="bg-dark py-3">
        <p class="m-0 text-secondary text-center">&copy;{{config('app.company_name')}}</p>
    </footer>

    <!-- フェードインアラート -->
    @include('includes.fadein-alert')

    <!-- bootstrap JavaScript -->
    @yield('script')

</body>
</html>
