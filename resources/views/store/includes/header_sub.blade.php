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
                <a href="{{route('store')}}" class="btn p-0 px-2 borderr rounded-pill text-primary
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
