{{--
ーーーーーーーーーーーーーー
    通常ガチャ　ボタン
ーーーーーーーーーーーーーー
--}}
<div class="row g-2 mt-1">
    @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp

    <div class="col">
        <form action="{{ route('admin.gacha.play', $params) }}" method="post">
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


    <div class="col">
        <form action="{{ route('admin.gacha.play', $params) }}" method="post">
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
    <div class="col-12">
        <button type="button"
        data-bs-toggle="modal" data-bs-target="#gachaModal"
        class="btn btn-info bg-gradient text-white fw-bold w-100 pb-0
        rounded-pill border-danger border-3
        position-relative shiny overflow-hidden
        ">
            <div class="">回数をカスタム</div>
            <div class="text-white">{{'？？？pt'}}</div>
        </button>
    </div>


    <!--所持ポイント-->
    <div class="col-12 mt-3">
        <div class="d-flex align-items-center gap-1">
            <div class="col-auto fw-bold me-3">{{Auth::user()->name}} さん</div>
            <div class="col-auto">
                @include('includes.point_icon')
            </div>

            <div class="col rounded-pill bg-white text- fw-bold border
            d-flex align-items-center justify-content-end px-2
            " style="width:6rem; height:1.6rem;">
                <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                <span>pt</span>
            </div>
        </div>
    </div>

</div>
