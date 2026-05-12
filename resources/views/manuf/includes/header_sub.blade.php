<header class="position-fixed w-100" style="z-index:100;">
    <div class="row g-0 align-items-center p-0 bg-white text-dark border-bottom mx-0">
        <!--戻る-->
        <div class="col-auto">
            @if( isset( $header_back_btn ) )
                <button class="btn p-0 px-3 borderrr rounded-pill text-secondary
                d-flex gap-1 align-items-center"
                type="button" onclick="history.back()"
                ><i class="bi bi-arrow-left fs-4"></i>
                {{-- <span>戻る</span> --}}
                </button>
            @else
                <a href="{{route('gacha_category')}}" class="btn p-0 px-3 borderr rounded-pill text-primary
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
                style="width:1.4rem; height:1.4rem;">
                    <i class="bi bi-list fs-1 " style="line-height:1rem;"></i>
                </div>

                <div class="fw-bold mt-1" style="font-size:8px; line-height:10px;"
                >メニュー</div>
            </button>
        </div>
    </div>
</header>
