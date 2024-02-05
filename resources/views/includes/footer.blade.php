<footer class="bg-light text- px-3 pt-5" style="padding-bottom:10rem;">
    <div class="container">

        <div class="row mx-0 gy-3">
            <div class="col-12 col-lg-3">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('storage/site/image/logo.png')}}"
                    alt="{{ config('app.name') }}" class="d-brock" style="height:4rem;">
                </a>
                <small class="d-block mb-3 text-muteddd">&copy;fobees</small>


                <div class="form-text my-3">
                    古物商営業許可<br>
                    第 931010002606 号<br>
                    熊本県公安委員会<br>
                    合同会社 Fobees<br>
                </div>

            </div>
            <div class="col-12 col-md">
                <h3>{{ __('カテゴリー') }}</h3>
                @php
                $gacha_categories = \App\Models\GachaCategory::where('is_published',1)
                ->orderBy('created_at')->get();
                @endphp
                <ul class="list-unstyled fs-5">
                    @foreach ($gacha_categories as $gacha_category)
                        <li><a class="link-secondary text-decoration-none"
                        href="{{ route('gacha_category',$gacha_category->code_name) }}"
                        >{{ $gacha_category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-12 col-md-6">
                <h3>{{ __('cardFestaについて') }}</h3>
                <div class="row gy-0">
                    <div class="col-12 col-md">
                        <ul class="list-unstyled m-0 gap-3">
                            <li class="mb-2"><a class="link-secondary text-decoration-none"
                            href="{{ route('guide') }}">利用ガイド</a></li>
                            <li class="mb-2"><a class="link-secondary text-decoration-none"
                            href="{{ route('trems') }}">利用規約</a></li>
                            <li class="mb-2"><a class="link-secondary text-decoration-none"
                            href="{{ route('privacy_policy') }}">プライバシーポリシー</a></li>
                            <li class="mb-2"><a class="link-secondary text-decoration-none"
                            href="{{ route('tradelaw') }}">特定商取引法に基づく表記</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-md">
                        <ul class="list-unstyled m-0">
                            <li class="mb-2"><a class="link-secondary text-decoration-none"
                            href="{{ route('infomation') }}">お知らせ</a></li>
                            <li class="mb-2"><a class="link-secondary text-decoration-none"
                            href="{{ route('contact') }}">お問い合わせ</a></li>
                            <li class="mb-2"><a class="link-secondary text-decoration-none"
                            href="{{ route('operating_company') }}">運営会社</a></li>
                            <li class="my-3">
                                <div class="col-auto">
                                    <a href="https://twitter.com/CardFesta7627" rel="nofollow" target="_blank"
                                    class="d-inline-block rounded-pill" style="background-color:#000; border-color:#000;"
                                    >
                                        <div class="d-flex align-items-center rounded-0 p-2 px-3 btn">
                                            <img src="{{asset('storage/site/image/x-logo/logo-white.png')}}"
                                            alt="xロゴ" class="d-block" style=" width:1rem; height:1rem;">

                                            <span class="text-white ms-2"> {{'cardFesta公式'}}</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- <div class="col-12 ">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}" class="d-brock" style="height:6rem;">
                </a>
                <small class="d-block mb-3 text-muteddd">&copy;fobees</small>
            </div>
            --}}
        </div>
    </div>
</footer>
