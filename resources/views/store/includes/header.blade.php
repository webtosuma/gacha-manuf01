<header class="position-fixed bg-info-subtle w-100" style="z-index:100;">
    @if ( config('app.debug') )
        <h6 class="text-danger text-center m-0 bg- position-fixed w-100"  style="z-index:101;">TEST MODE</h6>
    @endif

    <div class="container py- px-0">

        <nav class="d-flex justify-content-between align-items-center">

            <h1 class="d-flex align-items-center gap-3 m-0 ms-3">
                <a class="navbar-brand" href="{{ route('store') }}" style="shad">
                    <img src="{{asset('storage/site/image/logo.png')}}"
                    alt="{{ config('app.name') }}" class="d-brock" style="height:3.4rem;">
                </a>
            </h1>


            <!-- 検索ボタン(PC) -->
            <div class="col d-none d-md-block">
                <div class="ps-3 me-3 position-relative">
                    <button class="btn btn-lg btn-light border text-start w-100 overflow-hidden position-relative"
                    type="button"
                    style="height:3rem; line-height:1rem;"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvaSearch"
                    aria-controls="offcanvaSearch"
                    >
                        {{ isset( $search_inputs['keyword'] ) ? $search_inputs['keyword'] : '商品を検索' }}
                        <div class="position-absolute top-50 end-0 translate-middle-y pe-4">
                            <i class="bi bi-search"></i>
                        </div>
                    </button>

                </div>
            </div>


            <div class="d-flex align-items-center ">

                <!-- 検索ボタン(mobile) -->
                {{-- <button class="d-md-none btn bg- px-2 py-0 text- rounded-0"
                type="button"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvaSearch"
                aria-controls="offcanvaSearch">
                    <i class="bi bi-search fs-3"></i>
                </button> --}}



                @if(config('store.r_gacha'))
                    <!--ガチャサイト-->
                    <a href="{{config('store.r_gacha')}}"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="ガチャサイト"
                    class="btn px-0 position-relative  d-none d-md-block"
                    target="_blank"
                    >
                        <img src="{{asset('storage/site/image/icon/gacha-black.png')}}"
                        alt="ガチャサイト" class="d-block" style="height:2.4rem;">
                    </a>
                @endif


                <!--買い物カート-->
                <a href="{{route('store.keep')}}"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="買い物カート"
                class="btn fs-3 px-1 position-relative  d-none d-md-block">
                    <i class="bi bi-cart4"></i>

                    @if( auth()->check() && auth()->user()->store_keeps->count()>0 )
                        <div class="position-absolute bottom-0 end-0">
                            <i class="bi bi-circle-fill text-warning fs-6"></i>
                        </div>
                    @endif
                </a>


                @guest<!-- ログイン前 -->

                    <a class="btn btn-sm rounded- border fw-bold me-2"
                    href="{{ route('login') }}">{{ __('会員登録/ログイン') }}</a>


                @else<!-- ログイン中 -->

                    <!-- ハンバーガーボタン -->
                    <button class="btn bg- px-2 py-0 text- rounded-0  d-none d-md-block" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                    aria-controls="offcanvasHumberge">
                        <i class="bi bi-list fs-1"></i>
                    </button>

                @endguest
            </div>

        </nav>
    </div>
</header>



@include('store.includes.offcanvas_search')



<!--ボトムメニュー-->
<div class="position-fixed bottom-0 end-0 w-100 bg-white border d-md-none"
style="z-index:50; border-radius: 1rem 1rem 0 0;">
    <div class="container mx-auto" style="max-width:900px;">
        <div class="row">

            <!--買い物カート-->
            <div class="col">
                <a href="{{route('store.keep')}}"
                class="btn fs-3 px-1 position-relative w-100">
                    <i class="bi bi-cart4"></i>

                    @if( auth()->check() && auth()->user()->store_keeps->count()>0 )
                        <div class="position-absolute bottom-0 end-0">
                            <i class="bi bi-circle-fill text-warning fs-6"></i>
                        </div>
                    @endif
                    <div style="font-size:8px;">買物カート</div>
                </a>
            </div>


            <!--検索-->
            <div class="col">
                <button class="btn fs-3 px-1 position-relative w-100"
                type="button"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvaSearch"
                aria-controls="offcanvaSearch">
                    <i class="bi bi-search fs-3"></i>
                    <div style="font-size:8px;">検索</div>
                </button>
            </div>


            <!--ガチャサイト-->
            @if(config('store.r_gacha'))
                <div class="col">
                    <a href="{{config('store.r_gacha')}}"
                    class="btn fs-3 px-1 position-relative w-100">
                        <img src="{{asset('storage/site/image/icon/gacha-black.png')}}"
                        alt="ガチャサイト" class="d-block mx-auto" style="height:2.2rem;">

                        <div style="font-size:8px;">ガチャ</div>
                    </a>
                </div>
            @endif


            <!--メニュー-->
            @auth
                <div class="col">
                    <button class="btn fs-3 px-1 position-relative w-100"
                    type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                        aria-controls="offcanvasHumberge">
                        <i class="bi bi-list fs-3"></i>
                        <div style="font-size:8px;">メニュー</div>
                    </button>
                </div>
            @endauth
        </div>
    </div>
</div>
