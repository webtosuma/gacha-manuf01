<div class="p-3 border-0 border-radius rounded-4
h- mb-3 "
style="background:rgba(255, 255, 255, 1);">

    <!--discription head-->
    <div id="discription-head" class="mb-3">

        <!--badge link-->
        <div class="d-flex gap-2 mb-2">
            {{-- @if($gacha->new_label) --}}
                <!--NEW-->
                <div
                class="py-0 text-white bg-danger px-2 rounded-pill"
                style="font-size:11px;">NEW</div>
            {{-- @endif --}}

            <!--カテゴリー-->
            <a href="{{route('manuf.search',[ 'category_code_name'=>$gacha->category->code_name ])}}"
            class="btn btn-sm py-0 bg-white border text-secondary rounded-pill"
            style="font-size:11px;"
            >{{$gacha->category->name}}</a>


        </div>


        <!--商品名-->
        <div class="">
            <h5 class="fs-5 fw-bold mb-0">{{$gacha->name}}</h5>
        </div>


    </div>



    <!--discription resume_text-->
    @if($gacha->resume_text)
        <p id="discription-resume_text" class="border-top  py-3 mt-3 mb-0 form-text">

            {!! str_replace(["\r\n","\r","\n"],"<br>", e( $gacha->resume_text ) )!!}<br>

        </p>
    @endif


    <div class="d-flex justify-content-end">
        <div class="text-center">
            <span style="font-size:16px;">１回/</span>
            <span style="font-size:16px;">税込</span>
            <div class="d-inline-block" style="line-height:18px;">
                <span class="fs-3 text-danger">¥</span>
                <span class="fs-1 text-danger"> {{number_format($gacha->price)}}</span>
            </div>
        </div>
    </div>



    <!-- price btn metter -->
    <div class="py-3 d-none d-lg-block">
        @include('manuf.gacha.show.price_metter')
    </div>



    <div id="discription-table" class="border- rounded oberflow-hidden mb-3">
        <table class="table border-white text-dark m-0 rounded overflow-hidden" style="font-size:12px">
            <tbody>
                <tr>
                    <th class="bg-body text- p-" style="width:7rem;">お届け時期</th>
                    <td class="p-">xxxxxx</td>
                </tr>
                <tr>
                    <th class="bg-body text- p-">販売終了</th>
                    <td class="p-">xxxxxx</td>
                </tr>
                <tr>
                    <th class="bg-body text- p-">セット内容</th>
                    <td class="p-">xxxxxx</td>
                </tr>
                <tr>
                    <th class="bg-body text- p-">商品サイズ</th>
                    <td class="p-">xxxxxx</td>
                </tr>
                <tr>
                    <th class="bg-body text- p-">商品素材</th>
                    <td class="p-">xxxxxx</td>
                </tr>
                <tr>
                    <th class="bg-body text- p-">種類数</th>
                    <td class="p-">xxxxxx</td>
                </tr>
                <tr>
                    <th class="bg-body text- p-">対手年齢</th>
                    <td class="p-">xxxxxx</td>
                </tr>
                <tr>
                    <th class="bg-body text- p-">コピーライト</th>
                    <td class="p-">xxxxxx</td>
                </tr>

            </tbody>
        </table>
    </div>




</div>


<!--在庫・価格-->
{{-- <div class="overflow-hidden w-100">
    <div id="discription-price"
    class="row align-items-center justify-content-center flex-column g-3  mb-5"
    style="transform: scale(1.2);"
    >


        <!--価格・在庫-->
        <div class="col">
            <div class="d-flex gap-3 justify-content-center" style="font-size:11px;">

                <!--在庫-->
                <div class=" bg-light border text-dark px-2 rounded-pill">
                    <span class="">残り</span>
                    {{number_format($gacha->remaining_count)}}
                </div>

                <!--待機中-->
                <div class=" bg-warning px-2 rounded-pill">
                    <span class="">待機中</span>
                    {{number_format($gacha->waiting_count)}}
                </div>

            </div>

            <!--価格-->
            <div class="text-center">
                <span class="fs-6">１回</span>
                <span class="fs-4 text-danger">¥</span>
                <span class="fs-1 text-danger"> {{number_format($gacha->price)}}</span>
                <span class="fs-6">(税込)</span>
            </div>

        </div>




        <div class="col">


            <!--ガチャボタン-->
            <div class="d-flex justify-content-center mb-4">
                <div class="">
                    <button class="btn btn-light p-0
                    border-info border-2 mx-auto
                    rounded-pill  rotate-hover
                    d-flex align-items-center justify-content-center
                    "
                    style="width:8rem; height:8rem;"
                    data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{$gacha->id}}"
                    type="submit">

                        <div class="bg-info text-white p-1
                        shadow rounded-pill w-100
                        border border-white border-2
                        d-flex align-items-center justify-content-center gap-2 mx-auto"
                        >
                            <i class="bi bi-arrow-repeat fs-2" style="line-height:.8rem;"></i>
                        </div>


                    </button>


                    <div class="fs-5 fw-bold text-center">ガチャを回す</div>
                </div>
            </div>


            <button class="btn btn-lg btn-info text-white shadow rounded-pill
            d-flex align-items-center justify-content-center gap-2 mx-auto"
            data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{$gacha->id}}"
            type="submit">
                <i class="bi bi-arrow-repeat fs-3" style="line-height:.8rem;"></i>
                ガチャを回す
            </button>


        </div>


    </div>
</div> --}}
