<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- meta -->
    @yield('meta')
    {{-- @include('includes.meta') --}}
    @php
        # 最新のガチャ
        $meta_gachas =
        \App\Http\Controllers\GachaController::getPublishedGachas( $category_code='all' );
        # メタのデフォルト画像
        $meta_default_image = asset('storage/site/image/logo.png');
        # metaタイトル
        $meta_title = "買取表 |".config('app.name');
        # meta説明文
        $meta_description = "買取表 |".config('app.name');
        $meta_image = isset( $meta_image ) ? $meta_image : $meta_default_image;//メタのデフォルト画像
        #metaキーワード
        $meta_keyword = " ";
    @endphp

    <meta  name="description" content="{{ $meta_description }}">
    {{-- <meta  name="keywords"    content="{{ $meta_keyword }}"> --}}

    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
    <meta property="og:url"         content="{{ config('app.url') }}" />
    <meta property="og:site_name"   content="{{ config('app.name') }}" />
    <meta property="og:image"       content="{{ $meta_image }}" />
    <meta property="og:locale"      content="ja_JP"  />
    <meta property="og:type"        content="website">

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{ $meta_title }}" />
    <meta name="twitter:description" content="{{ $meta_description }}" />
    <meta name="twitter:image" content="{{ $meta_image }}" />


    <title>@yield('title') - {{env('APP_NAME')}}</title>




    <!-- wbマニフェスト -->
    {{-- @if ( config('app.manifest') )
        <link rel="manifest" href="/manifest.json">
    @endif --}}

    <!--共通CSS-->
    @include('includes.css')

    @php
    /* 背景パス */
    $bg_image_path = \App\Http\Controllers\AdminBackGroundController::getBgSub();
    @endphp
    <style>
        /* サイトデフォルト背景 */
        #bgWindow{
            background: no-repeat center center / cover;
            background-image: url({{$bg_image_path}});
        }
    </style>

    @yield('style')



    <!-- Googleタグ -->
    @include('includes.google_tag')


</head>
@php $class_bg_dark = config('app.bg_dark') ? 'bg-dark text-white m-0' : 'bg-body';  @endphp
<body class="{{ $class_bg_dark }}">

    <!--背景画像-->
    <div id="bgWindow"
    class="position-fixed top-0 start-0 w-100 h-100"
    style="z-index: -1;"
    ></div>



    <header class="mx-auto container">
        <h1 class="m-0 fs-6 d-flex align-items-center gap-3">
            {{-- <a href="{{route('home')}}" class="navbar-brand  fs-2 fw-bold"> --}}
                <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}"
                class="d-brock" style="height:4rem;">
            {{-- </a> --}}

            <span class="fs-1 fw-bold ">買取表</span>
        </h1>
    </header>
    <main style="min-height:80vh;">

        <div id="app" class="mx-auto container">

            @yield('content')

        </div>


    </main>
    <footer class="bg-white py-3">

        <div class="text-center mb-3">
            <a class="navbar-brand" href="{{ route('gacha_category') }}">
                <img src="{{asset('storage/site/image/logo.png')}}"
                alt="{{ config('app.name') }}" class="d-brock mx-auto" style="height:6rem;">
            </a>
            <small class="d-block text-muteddd">&copy;{{config('app.company_name')}}</small>
        </div>


        <!--SNS Links-->
        @include('includes.sns_links')


        <div style="height:8rem;"></div>

    </footer>

    <!-- Scripts -->
    @include('includes.appjs')

    <!-- フェードインアラート -->
    @include('includes.fadein-alert')

    <!-- bootstrap JavaScript -->
    @yield('script')

</body>
</html>
