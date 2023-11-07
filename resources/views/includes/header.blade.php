<header>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="btn btn-primary rounded-pill shadow-sm fw-bold border-warning border-3 me-2"
                                href="{{ route('register') }}">{{ __('会員登録') }}</a>
                            </li>
                        @endif
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="btn rounded-pill border-light border-3 fw-bold me-2"
                                href="{{ route('login') }}">{{ __('ログイン') }}</a>
                            </li>
                        @endif
                    @endguest
                </ul>
            </div>

            <!-- ハンバーガーボタン -->
            <button class="btn btn- border-light border-3 py-0 " type="button"
            data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
             aria-controls="offcanvasHumberge"
            ><i class="bi bi-list fs-3"></i></button>


        </div>
    </nav>


    @include('includes.offcanvas_menu')

    <!--[ mobile メニュー ]-->
    @include('includes.mobile_menu')

</header>
