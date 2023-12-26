<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasHumberge" aria-labelledby="offcanvasHumbergeLabel"
style="max-width:90vw; min-width:30vw;">

    <div class="offcanvas-header align-items-center">
        <!--閉じる-->
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-header align-items-center">
        <h5 id="offcanvasHumbergeLabel">
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
                            <div class="form-text">X(旧twitter)ID：{{ Auth::user()->twitter_id }}</div>
                        @endif
                    </div>


                </div>
            </a>
        </h5>
    </div>

    @guest
        <div class="d-flex gap-3 p-3">
            <div class="col">
                <a href="" class="btn btn-lg btn-primary text-white rounded-pill border-warning border-3 shadow w-100">会員登録</a>
            </div>
            <div class="col">
                <a href="" class="btn btn-lg rounded-pill shadow w-100">ログイン</a>
            </div>
        </div>
    @else
        <div class="d-flex justify-content-between align-items-center p-3 bg-white">
            <div class="col">
                <div class="">所持ポイント：</div>
                <div class="">
                    <span class="fs-3 fw-bold">
                        <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                    </span>
                    <span>pt</span>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('point_sail') }}" class="btn btn-warning text-white rounded-pill shadow">ポイント購入</a>
            </div>
        </div>
    @endguest


    <div class="offcanvas-body px-0">
        <div class="list-group list-group-flush">

            {{-- <a href="" class="list-group-item list-group-item-action py-3"
            >最近取得した商品</a> --}}


            <!--お友達紹介キャンペーン-->
            {{-- <div class="list-group-item p-3 px-2">
                <div class="row g-2">
                    <div class="col-4">
                        <a href="{{route('settings.canpaing_introductory')}}" class="d-block rounded-4 overflow-hidden">
                            <ratio-image-component
                            style_class="ratio ratio-4x3"
                            url="{{ asset( 'storage/'.'site/image/campaign_introductory/index.jpg' ) }}"
                            ></ratio-image-component>
                        </a>
                    </div>
                </div>
            </div> --}}



            <a href="{{ route('user_prize') }}" class="list-group-item list-group-item-action py-3"
            >取得した商品</a>

            <a href="{{ route('point_history') }}" class="list-group-item list-group-item-action py-3"
            >ポイント履歴</a>

            <a href="{{ route('shipped') }}" class="list-group-item list-group-item-action py-3">
                発送申請履歴
                @php $unread_count = Auth::user()->unread_send_shippeds_count; @endphp
                @if ( $unread_count )
                    <!--お問い合わせ　未対応-->
                    <span class="badge rounded-pill bg-warning">{{$unread_count}}</span>
                @endif
            </a>

            <a href="{{ route('settings') }}" class="list-group-item list-group-item-action py-3"
            >会員情報設定</a>

            <a href="{{ route('guide') }}" class="list-group-item list-group-item-action py-3"
            >利用ガイド</a>

            <a href="{{ route('infomation') }}" class="list-group-item list-group-item-action py-3"
            >お知らせ</a>

            @if ( Auth::check() )
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="list-group-item list-group-item-action py-3"
                    type="submit">{{ __('ログアウト') }}</button>
                </form>
            @endif


            {{-- <a href="{{route('settings.canpaing_introductory')}}" class="d-block rounded-4 overflow-hidden">
                <ratio-image-component
                style_class="ratio ratio-4x3"
                url="{{ asset( 'storage/'.'site/image/campaign_introductory/index.jpg' ) }}"
                ></ratio-image-component>
            </a> --}}
        </div>
    </div>
</div>
