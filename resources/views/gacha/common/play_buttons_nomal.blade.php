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
                <button type="submit" name="play_count" value="{{ 1 }}"
                class="btn btn-light bg-gradient fw-bold w-100 py-md-3
                rounded-pill border-secondary border-3"
                >1回ガチャる</button>
            @else
                <button type="submit" name="play_count" disabled
                class="btn btn-light bg-gradient fw-bold w-100 py-md-3 text-danger
                rounded-pill border-secondary border-3"
                >売り切れ</button>
            @endif

        </form>
    </div>


    <div class="col-6">
        <form action="{{ route('gacha.play', $params) }}" method="post">
            @csrf

            @if ($gacha->remaining_count >=10)
                <button type="submit" name="play_count" value="{{ 10 }}"
                class="btn btn-dark bg-gradient text- fw-bold w-100 py-md-3
                rounded-pill border-danger border-3"
                >10連ガチャる</button>
            @else
                <button type="submit" name="play_count" disabled
                class="btn btn-dark bg-gradient text- fw-bold w-100 py-md-3 text-danger
                rounded-pill border-secondary border-3"
                >売り切れ</button>
            @endif

        </form>
    </div>
</div>
