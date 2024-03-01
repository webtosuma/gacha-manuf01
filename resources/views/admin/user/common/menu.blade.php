<section class="mb-5 border-bottom pb-5">
    <div class="row g-2">
        <div class="col-6 col-md-3">
            @if( $user->now_rank )
                <a href="{{route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>21,])}}"
                    class="btn btn-light border py-3 w-100">
                    <h6>会員ランク</h6>
                    <div class="mt-3">{{$user->now_rank->label}}</div>
                </a>
            @else
                <button class="btn btn-light border py-3 w-100 h-100" type="button">
                    <h6>会員ランク</h6>
                    <div class="mt-3 text-danger">*未更新</div>
                </button>
            @endif
        </div>
        <div class="col-6 col-md-3">
            <a href="{{route('admin.user.user_prize',$user->id)}}" class="btn btn-light border py-3 w-100">
                <h6>商品</h6>
                <div class="mt-3">
                    <number-comma-component number="{{ $user->u_prizes_count }}"></number-comma-component>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>21,])}}"
                class="btn btn-light border py-3 w-100">
                <h6>ガチャ履歴</h6>
                <div class="mt-3">
                    <number-comma-component number="{{ $user->gacha_play_count }}"></number-comma-component>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{route('admin.user.point_history',$user->id)}}" class="btn btn-light border py-3 w-100">
                <h6>ポイント履歴</h6>
                <div class="mt-3">
                    <number-comma-component number="{{ $user->point }}"></number-comma-component>pt
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>11,])}}"
            class="btn btn-light border py-3 w-100">
                @php
                $price = \App\Models\PointHistory::where('user_id',$user->id)
                ->where('reason_id',11)->get()->sum('price');
                @endphp

                <h6>購入履歴</h6>
                <div class="mt-3">
                    ¥<number-comma-component number="{{ $price }}"></number-comma-component>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>22,])}}" class="btn btn-light border py-3 w-100">
                @php
                $shipped_count = \App\Models\PointHistory::where('user_id',$user->id)
                ->where('reason_id',22)->get()->count();
                @endphp

                <h6>発送申請履歴</h6>
                <div class="mt-3">
                    <number-comma-component number="{{ $shipped_count }}"></number-comma-component>
                </div>
            </a>
        </div>

    </div>
</section>
