<header>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm p-0">
        <div class="container">
            <h1 class="d-flex align-items-center gap-3 m-0">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}" class="d-brock" style="height:4rem;">
                </a>
            </h1>

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

                            <div class="rounded-pill bg- text- fw-bold border
                            d-flex align-items-center justify-content-end px-2
                            " style="width:6rem; height:1.6rem;">
                                {{ Auth::user()->point }}pt
                            </div>
                        </div>
                    </a>

                    <!-- ハンバーガーボタン -->
                    <button class="btn btn-  py-0 " type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                        aria-controls="offcanvasHumberge"
                    ><i class="bi bi-list fs-3"></i></button>
                @endguest
            </div>
        </div>
    </nav>


    @include('includes.offcanvas_menu')

</header>
