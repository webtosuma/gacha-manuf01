<div class="">

    <div class="row g-2 justify-content-end mb-" style="font-size:12px;">

        <!--発送-->
        <div class="col-auto">
            <div class="border border-success text-success px-3 ">
                <span class="fs-">X月</span>頃 発送予定
            </div>
        </div>

        <!--発送-->
        <div class="col-auto">
            <div class="bg-dark text-white px-3 ">
                マシン数：<span class="fs-">5</span>
            </div>
        </div>

        <!--在庫-->
        {{-- <div class="col-auto">
            <div class=" bg-light border text-dark px-2 rounded-pill">
                <span class="">残り</span>
                {{number_format($gacha->remaining_count)}}
            </div>
        </div> --}}

        <!--待機中-->
        {{-- <div class="col-auto">
            <div class=" bg-warning px-2 rounded-pill">
                <span class="">待機中</span>
                {{number_format($gacha->waiting_count)}}
            </div>
        </div>
        --}}
    </div>



    <!--在庫・価格-->
    <div id="discription-price"
    class="row align-items-center justify-content-center  g-3 ">

        <div class="col-auto  d-lg-none">
            <button type="button"
            onclick="history.back()"
            style="width:2.2rem; height:2.2rem;"
            class="
            btn btn-outline-secondary border-0 rounded-pill fs-5
            d-flex align-items-center justify-content-center
            "><i class="bi bi-chevron-left"></i><!--戻るボタン--></button>

            <div class="text-secondary text-center fw-bold" style="font-size:11px;">戻る</div>
        </div>



        <div class="col py-2">

            <!--ガチャボタン-->
            {{-- <button class="btn btn-info text-white shadow rounded-pill
            w-100
            d-flex align-items-center justify-content-center gap-2 mx-auto fw-bold"
            data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{$gacha->id}}"
            type="button">
                <i class="bi bi-arrow-repeat fs-3" style="line-height:.8rem;"></i>
                ガチャを回す
            </button> --}}

            @php
            $params=['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key];
            @endphp
            <a href="{{route('gacha.machines',$params)}}"
            class="btn py-2 btn-info text-white shadow rounded-pill
            w-100
            d-flex align-items-center justify-content-center gap-2 mx-auto fw-bold"
            >
                {{-- <i class="bi bi-arrow-repeat fs-3" style="line-height:.8rem;"></i> --}}
                {{-- <i class="bi bi-shuffle fs-3" style="line-height:.8rem;"></i> --}}
                ガチャマシンを選ぶ
            </a>

        </div>



        <!--価格-->
        <div class="col-auto  d-lg-none">
            <div class="text-center">
                <span style="font-size:11px;">１回/</span>
                <span style="font-size:11px;">税込</span>
                <div style="line-height:18px;">
                    <span class="fs-4 text-danger">¥</span>
                    <span class="fs-3 text-danger"> {{number_format($gacha->price)}}</span>
                </div>
            </div>
        </div>


    </div>



    <!--metter-->
    @php $metter_bg_color = ''; @endphp
    @include('manuf.gacha.common.metter')

</div>
