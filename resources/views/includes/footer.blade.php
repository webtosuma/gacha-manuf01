<footer class="bg-dark text-white p-3 py-5">
    <div class="container">

        <div class="row mx-0">
            <div class="col-12 col-md">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}" class="d-brock" style="height:6rem;">
                </a>
                <small class="d-block mb-3 text-muteddd">&copy;fobees</small>
            </div>
            <div class="col-12 col-md">
                <h3>{{ __('カテゴリー') }}</h3>
                <ul class="list-unstyled fs-4">
                    <li><a class="link-secondary text-decoration-none"
                    href="{{ route('gacha_category','onepiece') }}">ワンピース</a></li>
                </ul>
            </div>
            <div class="col-12 col-md-6">
                <h3>{{ __('cardFestaについて') }}</h3>
                <div class="row gy-0">
                    <div class="col-12 col-md">
                        <ul class="list-unstyled m-0">
                            <li><a class="link-secondary text-decoration-none"
                            href="{{ route('guide') }}">利用ガイド</a></li>
                            <li><a class="link-secondary text-decoration-none"
                            href="{{ route('trems') }}">利用規約</a></li>
                            <li><a class="link-secondary text-decoration-none"
                            href="{{ route('privacy_policy') }}">プライバシーポリシー</a></li>
                            <li><a class="link-secondary text-decoration-none"
                            href="{{ route('tradelaw') }}">特定商取引法に基づく表記</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-md">
                        <ul class="list-unstyled m-0">
                            <li><a class="link-secondary text-decoration-none"
                            href="{{ route('news') }}">お知らせ</a></li>
                            <li><a class="link-secondary text-decoration-none"
                            href="{{ route('contact') }}">お問い合わせ</a></li>
                            <li><a class="link-secondary text-decoration-none"
                            href="{{ route('operating_company') }}">運営会社</a></li>
                        </ul>
                    </div>
                </div>
                {{-- <ul class="list-unstyled text-smallll">
                    <li><a class="link-secondary text-decoration-none"
                    href="#">利用ガイド</a></li>
                    <li><a class="link-secondary text-decoration-none"
                    href="#">利用規約</a></li>
                    <li><a class="link-secondary text-decoration-none"
                    href="#">プライバシーポリシー</a></li>
                    <li><a class="link-secondary text-decoration-none"
                    href="#">特定商取引法に基づく表記</a></li>
                    <li><a class="link-secondary text-decoration-none"
                    href="#">お問い合わせ</a></li>
                    <li><a class="link-secondary text-decoration-none"
                    href="#">運営会社</a></li>
                </ul> --}}
            </div>
        </div>
    </div>
</footer>
