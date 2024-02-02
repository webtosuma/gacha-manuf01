<header class="position-fixed w-100" style="z-index:100;">
    <div class="container py-2 px-0">

        <nav class="d-flex justify-content-between align-items-center p- border border-primary border-3
        rounded-pill ps-3 mx-1 shadow" style="background:rgb(255, 255, 255, .9);">

            <h1 class="d-flex align-items-center gap-3 m-0 ">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('storage/site/image/logo.png')}}"
                    alt="{{ config('app.name') }}" class="d-brock" style="height:2.4rem;">
                </a>
            </h1>


            @if ( config('app.debug') )
                <h6 class="text-danger m-0 mt-2">TEST MODE</h6>
            @endif


            <div class="d-flex align-items-center ">

                @guest
                    <!-- ログイン前 -->
                    <a class="btn btn-sm btn-dark text-white rounded-pill shadow-sm fw-bold border-warning border-3 me-2"
                    href="{{ route('register') }}">{{ __('会員登録') }}</a>

                    <a class="btn btn-sm rounded-pill border-light border-3 fw-bold me-2"
                    href="{{ route('login') }}">{{ __('ログイン') }}</a>

                @else
                    <!-- ログイン中 -->
                    <a href="{{route('point_sail')}}"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="ポイントを購入する"
                    class="d-block text-decoration-none text-dark">
                        <div class="d-flex align-items-center gap-1">

                            @include('includes.point_icon')

                            <div class="rounded-pill bg-white text- fw-bold border
                            d-flex align-items-center justify-content-end px-2
                            " style="width:6rem; height:1.6rem;">
                                <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                                <span>pt</span>
                            </div>
                        </div>
                    </a>

                    <!-- ハンバーガーボタン -->
                    <button class="btn btn- bg-primary rounded-pill text-white ms-2 py-0 " type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                    aria-controls="offcanvasHumberge"style="transform: translateX(1px);">
                        <i class="bi bi-list fs-2"></i>
                    </button>

                @endguest
            </div>

        </nav>
    </div>


    @if(Auth::check())
        @include('includes.offcanvas_menu')
    @endif

</header>
