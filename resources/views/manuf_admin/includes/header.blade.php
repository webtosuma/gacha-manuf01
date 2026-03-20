<header class="bg-white border-bottom border-info border-bottom border-3">
    {{-- @if ( config('app.debug') )
        <h6 class="text-danger text-center m-0 bg-light">TEST MODE</h6>
    @endif --}}

    <nav class="navbar navbar-expand-lg p-0">
        <div class="container-fluid">

            <!--- サイトロゴ -->
            <a class="navbar-brand  text-primary" href="{{ route('admin.home') }}">
                <h1 class="fs-6 m-0 text-center d-flex flex- align-items-center gap-2">
                    <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}" class="d-brock" style="height:3rem;">
                    <br>
                    <span class="fw-bold text-secondary" style="font-size:.8rem;">管理者 ダッシュボード</span>
                </h1>
            </a>

            <div class="" id="navbarNav">
                <ul class="d-flex align-items-center ms-auto p-2 m-0" style="list-style:none;">
                    <li class="nav-item me-3 nav-link text-primary  d-none d-lg-block">
                        <span>{{ Auth::user()->name }}さん</span>
                    </li>
                    <li class="nav-item nav-link d-lg-none  p-0">
                        <!-- ハンバーガーボタン -->
                        <div class="btn text-dark py-0" data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberger" aria-controls="offcanvasHumberger">
                            <i class="bi bi-list fs-1"></i>
                        </div>
                    </li>
                </ul>
            </div>
    </nav>



    <!--[ mobile メニュー ]-->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasHumberger" aria-labelledby="offcanvasHumbergerLabel" style="max-width: 90vw;">
        <div class="offcanvas-header bg-white border-bottom py-1">

            <a href="{{route('admin.home')}}" class="d-flex justify-content-between align-items-center w-100 text-decoration-none text-dark">
                <h2 class="fs-6 m- text-center">
                    <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name', 'Laravel') }}" style="height:2rem;">
                    <span class="fw-bold text- ms-3" style="font-size:.8rem;">サイト管理者</span>
                </h2>
            </a>

            <button type="button" class="btn py-0 fs-3 text-dark "
            data-bs-dismiss="offcanvas" aria-label="Close"
            ><i class="bi bi-x-lg"></i></button>

        </div>
        <div class="offcanvas-body p-0 bg-white">

            <!--side menu-->
            @include('manuf_admin.includes.side_menu')

        </div>
    </div>

</header>
