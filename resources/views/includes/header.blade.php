<header>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <div class="d-flex align-items-center gap-3">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                {{-- @if(Auth::check())
                <a href="{{route('point_sail')}}"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="ポイントを購入する"
                class="d-block text-decoration-none text-dark">
                    <div class="d-flex align-items-center gap-2">

                        @include('includes.point_icon')

                        <div class="rounded-pill bg-light text- fw-bold
                        d-flex align-items-center justify-content-end px-2
                        " style="width:6rem; height:1.6rem;">
                            {{ Auth::user()->point }}
                        </div>
                    </div>
                </a>
                @endif --}}
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                {{-- <ul class="navbar-nav me-auto"></ul> --}}

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="btn btn-primary rounded-pill shadow-sm fw-bold border-warning border-3 me-2"
                            href="{{ route('register') }}">{{ __('会員登録') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn rounded-pill border-light border-3 fw-bold me-2"
                            href="{{ route('login') }}">{{ __('ログイン') }}</a>
                        </li>
                    @endguest
                </ul>
            </div>


            <div class="d-flex align-items-center gap-3">
                @if(Auth::check())
                <a href="{{route('point_sail')}}"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="ポイントを購入する"
                class="d-block text-decoration-none text-dark">
                    <div class="d-flex align-items-center gap-2">

                        @include('includes.point_icon')

                        <div class="rounded-pill bg-light text- fw-bold
                        d-flex align-items-center justify-content-end px-2
                        " style="width:6rem; height:1.6rem;">
                            {{ Auth::user()->point }}
                        </div>
                    </div>
                </a>
                @endif

                <!-- ハンバーガーボタン -->
                <button class="btn btn-  py-0 " type="button"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                    aria-controls="offcanvasHumberge"
                ><i class="bi bi-list fs-3"></i></button>
            </div>
        </div>
    </nav>


    @include('includes.offcanvas_menu')

    <!--[ mobile メニュー ]-->
    @include('includes.mobile_menu')

</header>
