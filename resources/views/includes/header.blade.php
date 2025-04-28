<header class="position-fixed bg-white w-100" style="z-index:100;">
    @if ( config('app.debug') )
        <h6 class="text-danger text-center m-0 bg-light">TEST MODE</h6>
    @endif

    <div class="container py- px-0">

        <nav class="d-flex justify-content-between align-items-center">

            <h1 class="d-flex align-items-center gap-3 m-0 ms-3">
                <a class="navbar-brand" href="{{ url('/') }}" style="shad">
                    <img src="{{asset('storage/site/image/logo.png')}}"
                    alt="{{ config('app.name') }}" class="d-brock" style="height:3.4rem;">
                </a>
            </h1>


            <div class="d-flex align-items-center ">

                <!--商品ストアー-->
                <a href="{{route('store')}}"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="商品ストアー"
                class="btn fs-2">
                    <i class="bi bi-shop-window"></i>
                </a>

                <!--お知らせ-->
                <a href="{{route('infomation')}}"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="お知らせ"
                class="btn fs-2">
                    <i class="bi bi-bell"></i>
                </a>

                @guest
                    <!-- ログイン前 -->
                    <a class="btn btn-sm rounded- border fw-bold me-2"
                    href="{{ route('login') }}">{{ __('会員登録/ログイン') }}</a>

                @else
                    <!-- ログイン中 -->
                    <a href="{{route('point_sail')}}"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="ポイントを購入する"
                    class="d-block text-decoration-none text-dark ms-2">
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
                    <button class="btn bg- px-2 py-0 ms-2 text- rounded-0" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                    aria-controls="offcanvasHumberge">
                        <i class="bi bi-list fs-1"></i>
                    </button>

                @endguest
            </div>

        </nav>
    </div>
</header>
