<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- meta -->
    @yield('meta')
    @include('includes.meta')



    <title>@yield('title') - {{ config('app.name') }}</title>


    <!-- wbマニフェスト -->
    @if ( config('app.manifest') )
        <link rel="manifest" href="/manifest.json">
    @endif

    <!--共通CSS-->
    @include('includes.css')


    @php
    /* 背景パス */
    $bg_image_path = \App\Http\Controllers\AdminBackGroundController::getBgSub();
    @endphp
    <style>
        a{ text-decoration: none; }
        th,td{ background-color: #fff !important; }

        /* サイトデフォルト背景 */
        #bgWindow{
            background: no-repeat center center / cover;
            background-image: url({{$bg_image_path}});
        }
        main{
            padding-top: 4.2rem;
            min-height: 80vh;
        }
    </style>

    @yield('style')

    <!-- Googleタグ -->
    @include('includes.google_tag')


</head>
<body class="bg-white">
    <div id="app">

        <!--背景画像-->
        <div id="bgWindow"
        class="position-fixed top-0 start-0 w-100 h-100"
        style="z-index: -1;"
        ></div>



        <div class="d-none d-md-block">
            @include('includes.header')
        </div>

        <!--header-->
        @if(Auth::check())

            @if ( env('NEW_TICKET_SISTEM',false) )
                @include('includes.offcanvas_menu02')
            @else
                @include('includes.offcanvas_menu')
            @endif

        @endif


        <div class="d-md-none">
            <header class="position-fixed w-100" style="z-index:100;">
                <div class="row align-items-center p-3 py-2 bg-white border-bottom border- mx-0 px-0">
                    <!--戻る-->
                    <div class="col-auto">
                        @if( isset( $header_back_btn ) )
                            <button class="btn p-0 px-2 borderrr rounded-pill text-secondary
                            d-flex gap-1 align-items-center"
                            type="button" onclick="history.back()"
                            ><i class="bi bi-arrow-left fs-4"></i>
                            {{-- <span>戻る</span> --}}
                            </button>
                        @else
                            <a href="{{route('home')}}" class="btn p-0 px-2 borderr rounded-pill text-primary
                            d-flex flex-column gap-1 align-items-center py-0">

                                <i class="bi bi-arrow-left"></i>
                                <span class="mx-1" style="font-size:8px; line-height:8px;">TOP</span>
                            </a>
                        @endif
                    </div>
                    <!--title-->
                    <div class="col text-centerrr">
                        <h2 class="m-0 fs-6 fw-bold">@yield('title')</h2>
                        @if ( config('app.debug') )
                            {{-- <h6 class="text-danger m-0 mt-2">TEST MODE</h6> --}}
                        @endif
                    </div>
                    <!--menu-->
                    <div class="col-auto">
                        <button class="btn p-0 px-2 borderr rounded-0 bg- text-
                        @if(!Auth::check()) invisible @endif" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                        aria-controls="offcanvasHumberge">
                            <i class="bi bi-list fs-4"></i>
                        </button>
                    </div>
                </div>
            </header>
        </div>




        @if ( isset( $message ) )
            <section class="bg-dark text-warning text-center">{{$message}}</section>
        @endif

        <main>
            @yield('content')
        </main>


        <!--footer-->
        <div class="d-none d-md-block">
            @include('includes.footer')
        </div>


        <!-- フェードインアラート -->
        @include('includes.fadein-alert')

    </div>



    <!-- Scripts -->
    @include('includes.appjs')


    @yield('script')

</body>
</html>
