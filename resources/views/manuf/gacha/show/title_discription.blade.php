<div class="p-3 border-0 border-radius rounded-4
h- mb-3 "
style="background:rgba(255, 255, 255, .7);">

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

            <!--ブランド名-->
            {{-- @if( $gacha->brand_name )
                <div class="">
                    <a  href="{{route('manuf.search',[ 'keyword'=>$gacha->brand_name ])}}"
                    >{{$gacha->brand_name}}</a>
                </div>
            @endif --}}

        </div>


        <!--商品名-->
        <div class="mb-2">
            <h5 class="fs-4 mb-0">{{$gacha->name}}</h5>
        </div>


    </div>



    <!--discription resume_text-->
    @if($gacha->resume_text)
        <p id="discription-resume_text" class="border-top  py-3 my-3">

            {!! str_replace(["\r\n","\r","\n"],"<br>", e( $gacha->resume_text ) )!!}<br>

        </p>
    @endif



</div>


<!--在庫・価格-->
<div class="overflow-hidden w-100">
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


            {{-- <button class="btn btn-lg btn-info text-white shadow rounded-pill
            d-flex align-items-center justify-content-center gap-2 mx-auto"
            data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{$gacha->id}}"
            type="submit">
                <i class="bi bi-arrow-repeat fs-3" style="line-height:.8rem;"></i>
                ガチャを回す
            </button> --}}


        </div>


    </div>
</div>
