<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasHumberge" aria-labelledby="offcanvasHumbergeLabel"
style="max-width:90vw; min-width:30vw;">


    <div class="offcanvas-header align-items-center bg-white text-dark ">
        <h5 id="offcanvasHumbergeLabelll" class="m-0">
            <a href="{{ route('settings.acount') }}" class="d-block text-dark">
                <div class="row align-items-center g-2">


                    <div class="col-auto" style="width: 3rem;">
                        <ratio-image-component
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="ユーザーメニュー"
                        style_class="ratio ratio-1x1 rounded-pill border"
                        url="{{ Auth::user()->image_path }}"
                        ></ratio-image-component>
                    </div>

                    <div class="col">
                        <div class="">{{ Auth::user()->name }}さん</div>
                        @if( Auth::user()->twitter_id )
                            <div class="form-text">
                                {{-- X(旧twitter)ID： --}}
                                <img src="{{asset('storage/site/image/x-logo/logo-black.png')}}"
                                alt="xロゴ" class="d-inline-block" style="height:1rem;">


                                {{ Auth::user()->twitter_id }}
                            </div>
                        @endif
                    </div>


                </div>
            </a>
        </h5>

        <!--閉じる-->
        <button type="button" class="btn-close text-reset border" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>


    <div class="offcanvas-body bg-white tec-tark pt-0">

        <!-- 所持ポイント -->
        <div class="mb-3">
            <div  style="font-size:14px;">所持ポイント：</div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="col-auto pe-2">

                    @include('includes.point_icon')

                </div>
                <div class="col">
                    <div class="">
                        <span class="fs-5 fw-bold">
                            <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                        </span>
                        <span>pt</span>
                    </div>
                </div>
            </div>
            <div class="form-text text-secondary">{{Auth::user()->point_deadline_text}}</div>
        </div>


        <!-- 買い物カート -->
        <div class="mb-3">
            <a href="{{ route('store.keep') }}" class="d-block text-dark mt-3 border-top pt-2  border-bottom pb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="col-auto fs-3 pe-2">
                        <i class="bi bi-cart4"></i>
                    </div>
                    <div class="col-auto">
                        <div  style="font-size:14px;">買い物カート：</div>
                    </div>
                    <div class="col">
                        <span class="fs-5 fw-bold">{{ number_format( Auth::user()->store_keeps->sum('count') ) }}</span>
                        <span>点</span>
                    </div>
                    <div class="col-auto">
                        <div class="text-end" style="font-size:14px;">
                            <span class="">一覧を見る<i class="bi bi-chevron-right"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row g-1 mt-2 px- mx-1">
                    @foreach (Auth::user()->store_limit_keeps as $store_keep)
                        <div class="col-3 text-center">
                            <div class="position-relative">

                                @php $img_url = isset($store_keep->store_item->image_paths[0]) ? $store_keep->store_item->image_paths[0] : ''; @endphp
                                <ratio-image-component
                                style_class="ratio {{$store_keep->store_item->ration}} "
                                url="{{$img_url}}"
                                ></ratio-image-component>

                                <div class="position-absolute top-0 start-0" style="z-index:2;">
                                    <div class="px-2 rounded-pill bg-dark text-white">{{$store_keep->count}}</div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </a>
        </div>


        <!-- メニュー -->
        <div class="mb-3">
            <h6 class="fw-bole pb-0">メニュー</h6>
            <div class="row g-2">
                <!--購入した商品-->
                <div class="col-4">
                    <a href="{{ route('store.purchased') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                        <i class="bi bi-bag-check fs-3"></i>
                        <div class="text-secondary mt-" style="font-size:10px; line-height:18px;">
                            <span>購入商品</span>
                        </div>
                    </a>
                </div>
                <!--発送-->
                <div class="col-4">
                    <a href="{{ route('store.shipped') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                        <i class="bi bi-box-seam fs-3"></i>
                        <div class="text-secondary mt-" style="font-size:10px; line-height:18px;">
                            <span>発送</span>

                            @php $unread_count =  \App\Models\StoreHistory::forUserSendUnReadCount(); @endphp
                            @if ( $unread_count )
                                <!--未読-->
                                <span class="badge rounded-pill bg-warning">{{$unread_count}}</span>
                            @endif
                        </div>
                    </a>
                </div>
                <!--ポイント履歴-->
                <div class="col-4">
                    <a href="{{ route('store.point_history') }}" class="btn rounded-3 text-primary shadow-sm fw-bold p-2 w-100 h-100" style="font-size:11px;">
                        <div class="d-flex flex-column gap-1  justify-content-center align-items-center h-100">
                            <img src="{{asset('storage/site/image/icon/point.png')}}"
                            alt="ポイント履歴" class="d-block mx-auto mt-1" style=" width:28px; height:28px;">
                            <div class="text-secondary" style="font-size:10px; line-height:18px;">ポイント</div>
                        </div>
                    </a>
                </div>
                <!--お知らせ-->
                <div class="col-4">
                    <a href="{{ route('infomation') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                        <i class="bi bi-bell fs-3"></i>
                        <div class="text-secondary mt-" style="font-size:10px; line-height:18px;">お知らせ</div>
                    </a>
                </div>
                <!--利用ガイド-->
                {{-- <div class="col-4">
                    <a href="{{ route('guide') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                        <i class="bi bi-book fs-3"></i>
                        <div class="text-secondary mt-" style="font-size:10px; line-height:18px;">利用ガイド</div>
                    </a>
                </div> --}}

                <!--設定-->
                <div class="col-4">
                    <a href="{{ route('settings') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                        <i class="bi bi-gear-fill fs-3"></i>
                        <div class="text-secondary mt-" style="font-size:10px; line-height:18px;">設定</div>
                    </a>
                </div>
                <!--ログアウト-->
                <div class="col-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;" type="submit">
                            <i class="bi bi-box-arrow-right fs-3"></i>
                            <div class="text-secondary" style="font-size:10px; line-height:18px;">ログアウト</div>
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <!-- PWAインストールボタン -->
        <div class="">
            <pwa-install-btn
            r_about_pwa="{{route('about_pwa')}}"
            ></pwa-install-btn>
        </div>


        <!--ロゴ-->
        <div class="text-center mt-5 mb-3">
            <a class="navbar-brand" href="{{ route('store') }}">
                <img src="{{asset('storage/site/image/logo.png')}}"
                alt="{{ config('app.name') }}" class="d-brock mx-auto" style="height:4rem;">
            </a>
            <small class="d-block text-muteddd">&copy;{{config('app.company_name')}}</small>
        </div>


        <!--SNS Links-->
        @include('includes.sns_links')


        @include('store.includes.footer_menu')


    </div>

</div>
