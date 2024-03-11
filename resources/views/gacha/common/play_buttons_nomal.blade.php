{{--
ーーーーーーーーーーーーーー
    通常ガチャ　ボタン
ーーーーーーーーーーーーーー
--}}
<div class="row g-2 mt-1">
    @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp

    <div class="col-6">
        <form action="{{ route('gacha.play', $params) }}" method="post">
            @csrf

            @if ($gacha->remaining_count >=1)
                <disabled-button-slot type="submit" name="play_count" value="{{ 1 }}"
                disabled="{{ 0 }}"
                style_class="btn btn-light bg-gradient fw-bold w-100 pb-0
                rounded-pill border-secondary border-3
                position-relative shiny overflow-hidden
                ">
                    <div class="">1回ガチャる</div>
                    <div class="text-warning">{{number_format($gacha->one_play_point).'pt'}}</div>
                </disabled-button-slot>
            @else
                <disabled-button-slot type="submit" name="play_count" value="{{ 1 }}"
                disabled="{{ 1 }}"
                style_class="btn btn-light bg-gradient fw-bold w-100 pb-0
                rounded-pill border-secondary border-3
                position-relative shiny overflow-hidden
                ">
                    <div class="text-danger">終了</div>
                    <div class="invisible">{{number_format($gacha->one_play_point).'pt'}}</div>
                </disabled-button-slot>
            @endif

        </form>
    </div>


    <div class="col-6">
        <form action="{{ route('gacha.play', $params) }}" method="post">
            @csrf

            @if ($gacha->remaining_count >=10)
                <disabled-button-slot
                type="submit" name="play_count" value="{{ 10 }}"
                disabled="{{ 0 }}"
                style_class="btn btn-dark bg-gradient text- fw-bold w-100 pb-0
                rounded-pill border-danger border-3
                position-relative shiny overflow-hidden
                ">
                    <div class="">10連ガチャる</div>
                    <div class="text-warning">{{number_format($gacha->one_play_point*10).'pt'}}</div>
                </disabled-button-slot>
            @else
                <disabled-button-slot
                type="submit" name="play_count" value="{{ 10 }}"
                disabled="{{ 1 }}"
                style_class="btn btn-dark bg-gradient text- fw-bold w-100 pb-0 text-danger
                rounded-pill border-secondary border-3
                ">
                    <div class="">終了</div>
                    <div class="text-white invisible">{{number_format($gacha->one_play_point*10).'pt'}}</div>
                </disabled-button-slot>

            @endif

        </form>
    </div>
</div>
