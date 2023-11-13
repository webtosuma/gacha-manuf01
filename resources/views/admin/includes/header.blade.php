<header class="shadow-sm bg-dark">

    <nav class="navbar navbar-expand-lg p-0">
        <div class="container-fluid">

            <!--- サイトロゴ -->
            <a class="navbar-brand  text-white" href="{{ url('admin.home') }}">
                <h1 class="fs-6 m-0 text-center">
                    <img src="" alt="{{ config('app.name', 'Laravel') }}"><br>
                    <span class="fw-bold" style="font-size:.8rem;">サイト管理者</span>
                </h1>
            </a>



            <div class="" id="navbarNav">
                <ul class="d-flex align-items-center ms-auto p-2 m-0" style="list-style:none;">
                    <li class="nav-item me-3 nav-link text-white  d-none d-lg-block">
                        <span>{{ Auth::user()->name }}さん</span>
                    </li>
                    <li class="nav-item nav-link d-lg-none  p-0">
                        <!-- ハンバーガーボタン -->
                        <div class="btn text-white py-0" data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberger" aria-controls="offcanvasHumberger">
                            <i class="bi bi-list fs-1"></i>
                        </div>
                    </li>
                </ul>
            </div>
    </nav>



    <!--[ mobile メニュー ]-->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasHumberger" aria-labelledby="offcanvasHumbergerLabel" style="max-width: 90vw;">
        <div class="offcanvas-header bg-dark py-1">

            <a href="" class="text-decoration-none text-white">
                <h2 class="fs-6 m- text-center">
                    <img src="" alt="{{ config('app.name', 'Laravel') }}">
                    <span class="fw-bold text-light" style="font-size:.8rem;">サイト管理者</span>
                </h2>
                <h6>{{ Auth::user()->name }}さん</h6>
            </a>

            <button type="button" class="btn py-0 fs-3 text-light "
            data-bs-dismiss="offcanvas" aria-label="Close"
            ><i class="bi bi-x-lg"></i></button>

        </div>
        <div class="offcanvas-body p-0 py-3 bg-white">

            <!--side menu-->
            @include('admin.includes.side_menu')

        </div>
    </div>

</header>
