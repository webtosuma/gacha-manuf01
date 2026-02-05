<header class="position-fixed bg-white text-dark w-100" style="z-index:100;">
    @if ( config('app.debug') )
        <h6 class="text-danger text-center m-0 bg- position-fixed w-100"  style="z-index:101;">TEST MODE</h6>
    @endif

    <div class="container py- px-0">

        <nav class="d-flex justify-content-between align-items-center">

            <h1 class="d-flex align-items-center gap-3 m-0 ms-3">
                <a class="navbar-brand" href="{{ route('gacha_category') }}" style="shad">
                    <img src="{{asset('storage/site/image/logo.png')}}"
                    alt="{{ config('app.name') }}" class="d-brock"
                    style="height:3.4rem;">

                    {{-- <img src="{{asset('storage/site/image/logo.png')}}"
                    alt="{{ config('app.name') }}" class="d-brock"
                    style="width:5rem;"> --}}

                </a>
            </h1>


            <div class="d-flex align-items-center ">

                @if(config('store.r_store'))
                    <!--商品ストアー-->
                    <a href="{{config('store.r_store')}}"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="商品ストアー"
                    class="btn px-0 position-relative"
                    target="_blank"
                    >
                        <img src="{{asset('storage/site/image/icon/shop-black.png')}}"
                        alt="商品ストアー" class="d-block" style=" height:2.4rem;">
                    </a>
                @endif

                <!--お知らせ-->
                {{-- <a href="{{route('infomation')}}"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="お知らせ"
                class="btn fs-2">
                    <i class="bi bi-bell"></i>
                </a> --}}

                @guest
                    <!-- ログイン前 -->
                    <a class="btn btn-sm rounded- border fw-bold me-2"
                    href="{{ route('login') }}">{{ __('会員登録/ログイン') }}</a>

                @else
                    <!-- ログイン中 -->
                    <a href="{{route('point_sail')}}"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="ポイントを購入する"
                    class="d-block text-decoration-none text-dark ms-3">

                        <div class="position-relative">

                            <div class="position-absolute top-50 start-0 translate-middle">
                                @include('includes.point_icon')
                            </div>


                            <div class="rounded-pill bg-white text- fw-bold border
                            d-flex align-items-center justify-content-end px-3
                            " style="width:7.4rem; height:1.6rem;">

                                <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                                <span>pt</span>

                            </div>

                            <div class="position-absolute top-50 start-100 translate-middle">
                                <i class="bi bi-plus-circle-fill fs-5"></i>
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
