{{--
ーーーーーーーーーーーーーー
　広告ガチャ　ボタン　sponsor_add
ーーーーーーーーーーーーーー
--}}
<div class="row g-2 mt-1">
    @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp

    <div class="col-12 mx-auto">


        {{-- {{$gacha->played_ad_limit?'ture':'false'}} --}}


        <form action="{{ route('gacha.play', $params) }}" method="post">

            @csrf
            @if( $gacha->played_ad_limit )<!--1日の上限回数を超ええたとき-->
                <button type="submit" name="play_count" disabled
                class="btn btn-light bg-gradient fw-bold w-100 pb-0 text-danger
                rounded-pill border-secondary border-3"
                >
                    <div class="">本日は終了</div>
                    <div class="invisible">{{number_format($gacha->one_play_point).'pt'}}</div>
                </button>

            @elseif ($gacha->remaining_count >=1)
                <u-gacha-btn type="submit" name="play_count" value="{{ 1 }}"
                disabled="{{ 0 }}"
                label="1回ガチャる"
                point="{{number_format($gacha->one_play_point).'pt'}}"
                style_class="btn btn-light bg-gradient fw-bold w-100 pb-0
                rounded-pill border-secondary border-3
                position-relative shiny overflow-hidden
                "></u-gacha-btn>
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


    {{-- <div class="col-6">
        <button type="submit" name="play_count" disabled
        class="btn btn-dark bg-gradient text- fw-bold w-100 pb-0 text-danger
        rounded-pill border-secondary border-3"
        >
            <div class="">終了</div>
            <div class="text-white invisible">{{number_format($gacha->one_play_point*10).'pt'}}</div>
        </button>
    </div> --}}
</div>
