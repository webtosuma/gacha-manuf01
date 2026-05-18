<header class="position-fixed bg-white border-bottom w-100" style="z-index:100;">
    <div class="container py- px-0">

        <nav class="row justify-content-between align-items-center g-0">

            <!--logo-->
            <div class="col-auto">
                <h1 class="d-flex align-items-center m-0 py-1">
                    <a href="{{ route('manuf') }}"
                    class="navbar-brand p-0 w-100" style="max-width:100px;">
                        <img src="{{asset('storage/site/image/logo.png')}}"
                        alt="{{ config('app.name') }}" class="d-brock w-100"
                        style="">
                    </a>
                </h1>
            </div>

            <div class="col">
                <div class="row align-items-center justify-content-end g-0">

                    {{-- <div class="col d-none d-sm-block">
                        <button class="btn btn-sm border rounded-pill border-2 w-100"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvaSearch"
                        aria-controls="offcanvaSearch"
                        type="button">
                            <div class="d-flex align-items-center justify-content-start">
                                <i class="bi bi-search fs-1 " style="line-height:1rem;"></i>
                                キーワード検索
                            </div>
                        </button>
                    </div> --}}

                    <!-- 検索 mobile -->
                    {{-- <div class="col-auto d-sm-none">
                        <a href="#"
                        class="btn text- rounded-0 w-100  px-3
                        d-flex flex-column justify-content- center align-items-center
                        ">

                            <div class="d-flex align-items-center justify-content-center
                            bg- text-info rounded-pill"
                            style="width:2.4rem; height:2.4rem;">
                                <i class="bi bi-search fs-1 " style="line-height:1rem;"></i>
                            </div>


                            <div class="fw-bold mt-1" style="font-size:10px; line-height:10px;"
                            >{{ __('検 索') }}</div>
                        </a>
                    </div> --}}

                    <!-- 発送 -->
                    <div class="col-auto">
                        <a href="{{route('shipped')}}"
                        class="btn text-secondary rounded-0 w-100  px-3
                        d-flex flex-column justify-content- center align-items-center
                        ">

                            <div class="d-flex align-items-center justify-content-center
                            bg- text-secondary rounded-pill"
                            style="width:2.4rem; height:2.4rem;">
                                <i class="bi bi-box-seam fs-1 " style="line-height:1rem;"></i>
                            </div>


                            <div class="fw-bold mt-1" style="font-size:10px; line-height:10px;"
                            >{{ __('発 送') }}</div>
                        </a>
                    </div>


                    <!-- ハンバーガーボタン -->
                    <div class="col-auto">
                        <button class="btn btn-info text-white rounded-0 w-100
                        d-flex flex-column justify-content- center align-items-center
                        "
                        type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                        aria-controls="offcanvasHumberge">

                            <div class="d-flex align-items-center justify-content-center
                            bg- text- rounded-pill"
                            style="width:2.4rem; height:2.4rem;">
                                <i class="bi bi-list fs-1 " style="line-height:1rem;"></i>
                            </div>

                            <div class="fw-bold mt-1" style="font-size:8px; line-height:10px;"
                            >メニュー</div>
                        </button>
                    </div>


                    @guest
                    <!-- ログイン前 -->

                        <!--会員登録-->
                        <div class="col-auto">
                            <a href="{{ route('login') }}"
                            class="btn btn-warning text-dark rounded-0 w-100
                            d-flex flex-column justify-content- center align-items-center
                            " >

                                <div class="d-flex align-items-center justify-content-center
                                bg-dark rounded-pill fs-4"
                                style="width:2.4rem; height:2.4rem;">
                                    <i class="bi bi-person-fill text-warning"></i>
                                </div>

                                <div class="fw-bold mt-1" style="font-size:10px; line-height:10px;">
                                    {{ __('ログイン') }}
                                    <span class="d-none d-lg-inline">/会員登録</span>
                                </div>
                            </a>
                        </div>


                        <!--ログイン-->
                        {{-- <div class="col-auto bg-warning">
                            <a href="{{ route('login') }}"
                            class="btn text- rounded-0 w-100
                            d-flex flex-column justify-content- center align-items-center
                            "
                            type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                            aria-controls="offcanvasHumberge">

                                <div class="d-flex align-items-center justify-content-center
                                bg-white rounded-pill fs-4"
                                style="width:2.4rem; height:2.4rem;">
                                    <i class="bi bi-person-fill text-dark"></i>
                                </div>

                                <div class="fw-bold mt-1" style="font-size:10px; line-height:10px;"
                                >{{ __('会員登録') }}</div>
                            </a>
                        </div> --}}


                    @endguest
                </div>
            </div>

        </nav>
    </div>
</header>

