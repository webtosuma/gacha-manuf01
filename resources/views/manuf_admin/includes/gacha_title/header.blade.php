<header class="bg-body border-bottom ">

    <nav class="navbar navbar-expand-lg p-0">
        <div class="container-fluid">

            <!--- 戻る -->
            <a href="{{route('admin.gacha.title')}}" class="btn btn-sm btn-light ">< 一覧に戻る</a>

            <div class="" id="navbarNav">
                <ul class="d-flex align-items-center ms-auto m-0 p-0" style="list-style:none;">
                    <li class="nav-item nav-link d-lg-none ">
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

            <!--- 戻る -->
            <a href="{{route('admin.gacha.title')}}" class="btn btn-sm btn-light ">< 一覧に戻る</a>

            <button type="button" class="btn py-0 fs-3 text-dark "
            data-bs-dismiss="offcanvas" aria-label="Close"
            ><i class="bi bi-x-lg"></i></button>

        </div>
        <div class="offcanvas-body p-0 bg-white">

            <!--side menu-->
            @include('manuf_admin.includes.gacha_title.side_menu')

        </div>
    </div>

</header>
