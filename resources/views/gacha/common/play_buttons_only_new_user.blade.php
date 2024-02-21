{{--
ーーーーーーーーーーーーーー
　新規会委員限定ガチャ　ボタン　only_new_user
ーーーーーーーーーーーーーー
--}}
<div class="row g-2 mt-1">
    @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp

    <div class="col-6">
        <form action="{{ route('gacha.play', $params) }}" method="post">
            @csrf

            @if ($gacha->remaining_count >=1 && !$gacha->played_one_time && !Auth::user()->sevendays_affter_registar)
                <button type="submit" name="play_count" value="{{ 1 }}"
                class="btn btn-light bg-gradient fw-bold w-100 pb-0
                rounded-pill border-secondary border-3
                position-relative shiny overflow-hidden
                ">
                    <div class="">1回ガチャる</div>
                    <div class="text-warning">{{number_format($gacha->one_play_point).'pt'}}</div>
                </button>
            @else
                <button type="submit" name="play_count" disabled
                class="btn btn-light bg-gradient fw-bold w-100 pb-0 text-danger
                rounded-pill border-secondary border-3"
                >
                    <div class="">終了</div>
                    <div class="invisible">{{number_format($gacha->one_play_point).'pt'}}</div>
                </button>
            @endif

        </form>
    </div>


    <div class="col-6">
        <button type="submit" name="play_count" disabled
        class="btn btn-dark bg-gradient text- fw-bold w-100 pb-0 text-danger
        rounded-pill border-secondary border-3"
        >
            <div class="">終了</div>
            <div class="text-white invisible">{{number_format($gacha->one_play_point*10).'pt'}}</div>
        </button>
    </div>
</div>
