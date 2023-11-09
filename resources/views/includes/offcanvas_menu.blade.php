<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasHumberge" aria-labelledby="offcanvasHumbergeLabel"
style="max-width:90vw; min-width:30vw;">
    <div class="offcanvas-header align-items-center">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        <h5 id="offcanvasHumbergeLabel">
            @guest {{ __('ゲスト') }} @else {{ Auth::user()->name }} @endguest {{ __( 'さん') }}
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
            <div class="">所持ポイント：<span class="fs-3 fw-bold">{{ Auth::user()->point.'pt' }}</span></div>
            <a href="{{ route('point_sail') }}" class="btn btn-warning text-white rounded-pill shadow">ポイント購入</a>
        </div>
    @endguest


    <div class="offcanvas-body px-0">
        <div class="list-group list-group-flush">
            {{-- <a href="" class="bg-primary text-white list-group-item list-group-item-action py-3"
            >お知らせ</a>

            <a href="" class="bg-danger text-white list-group-item list-group-item-action py-3"
            >最近ゲットしたカード</a>

            <a href="" class="bg-warning text-white list-group-item list-group-item-action py-3"
            >取得カード履歴</a> --}}


            <a href="" class="list-group-item list-group-item-action py-3"
            >お知らせ</a>

            <a href="" class="list-group-item list-group-item-action py-3"
            >最近ゲットしたカード</a>

            <a href="" class="list-group-item list-group-item-action py-3"
            >取得カード履歴</a>

            <a href="" class="list-group-item list-group-item-action py-3"
            >LOSEカード履歴</a>

            <a href="" class="list-group-item list-group-item-action py-3"
            >発送カード履歴</a>

            <a href="{{ route('point_history') }}" class="list-group-item list-group-item-action py-3"
            >ポイント履歴</a>

            <a href="" class="list-group-item list-group-item-action py-3"
            >会員情報設定</a>

            <a href="" class="list-group-item list-group-item-action py-3"
            >利用ガイド</a>

            @if ( Auth::check() )
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="list-group-item list-group-item-action py-3"
                    type="submit">{{ __('ログアウト') }}</button>
                </form>
            @endif
        </div>
    </div>
</div>
