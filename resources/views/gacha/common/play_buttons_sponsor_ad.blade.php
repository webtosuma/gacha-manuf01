{{--
ーーーーーーーーーーーーーー
　広告ガチャ　ボタン　sponsor_add
ーーーーーーーーーーーーーー
--}}
<div class="row g-2 mt-1">
    @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp

    <div class="col-6 mx-auto">
        <form action="{{ route('gacha.sponsor_ad_gacha.movie', $params) }}" method="post">
            @csrf

            @if ($gacha->remaining_count >=1)
                <u-gacha-btn type="submit" name="play_count" value="{{ 1 }}"
                disabled="{{ 0 }}"
                label="1回ガチャる"
                point="{{'広告無料'}}"
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
                    <div class="invisible">{{'広告無料'}}</div>
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
