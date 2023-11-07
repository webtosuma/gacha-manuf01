<footer class="bg-dark text-white p-3 py-5">
    <div class="container">

        <div class="row mx-0">
            <div class="col-12 col-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="d-block mb-2" role="img" viewBox="0 0 24 24"><title>Product</title><circle cx="12" cy="12" r="10"></circle><path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"></path></svg>
                <h3>{{ config('app.name') }}</h3>
                <small class="d-block mb-3 text-muteddd">© 2017–2023</small>
            </div>
            <div class="col-12 col-md">
                <h3>{{ __('Category') }}</h3>
                <ul class="list-unstyled fs-4">
                    <li><a class="link-secondary" href="#">ポケモン</a></li>
                    <li><a class="link-secondary" href="#">ワンピース</a></li>
                    <li><a class="link-secondary" href="#">遊戯王</a></li>
                </ul>
            </div>
            <div class="col-12 col-md-6">
                <h3>{{ __('About this site') }}</h3>
                <div class="row">
                    <div class="col">
                        <ul class="list-unstyled ">
                            <li><a class="link-secondary" href="#">利用ガイド</a></li>
                            <li><a class="link-secondary" href="#">利用規約</a></li>
                            <li><a class="link-secondary" href="#">プライバシーポリシー</a></li>
                            <li><a class="link-secondary" href="#">特定商取引法に基づく表記</a></li>
                        </ul>
                    </div>
                    <div class="col">
                        <ul class="list-unstyled ">
                            <li><a class="link-secondary" href="#">お知らせ</a></li>
                            <li><a class="link-secondary" href="#">お問い合わせ</a></li>
                            <li><a class="link-secondary" href="#">運営会社</a></li>
                        </ul>
                    </div>
                </div>
                {{-- <ul class="list-unstyled text-smallll">
                    <li><a class="link-secondary" href="#">利用ガイド</a></li>
                    <li><a class="link-secondary" href="#">利用規約</a></li>
                    <li><a class="link-secondary" href="#">プライバシーポリシー</a></li>
                    <li><a class="link-secondary" href="#">特定商取引法に基づく表記</a></li>
                    <li><a class="link-secondary" href="#">お問い合わせ</a></li>
                    <li><a class="link-secondary" href="#">運営会社</a></li>
                </ul> --}}
            </div>
        </div>
    </div>
</footer>
