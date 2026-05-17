<div class="card card-body bg-white">
    <div class="row">
        <div class="col">

            <div class="row g-3">
                <div class="col-4 col-lg-2 text-center">
            
                    <ratio-image-component
                    url="{{$gacha_title->image_samune_path}}"
                    style_class="{{$gacha_title->ratio.' ratio bg-body'}}"
                    bg_size="contain"
                    ></ratio-image-component>
            
            
                </div>
                <div class="col ">
            
            
                    <div class="">
            
                        <!--discription head-->
                        @include('manuf.gacha.common.title_discription.title_name')
            
            
                        <div class="d-flex gap-2 align-items-center mb-1">
                            <h6 class="fw-bold m-0">ガチャマシーン</h6>
        
                            <!--限定ガチャラベル-->
                            @if ( $machine->gacha->type_label && $machine->gacha->is_type_label_text)
                                <span class="bg-info text-white px-2 rounded"
                                style="font-size:11px;"
                                >{{ $machine->gacha->type_label }}</span>
                            @endif
        
                        </div>

                        <div class="card p-1 mb-4 ">{{$machine->name}}</div>
                            
            
                    </div>
            
            
            
                </div>
            </div>
            
        </div>
        <div class="col-3 text-end fw-bold">
            <div class="">
                1回/税込¥{{number_format(
                    isset($gacha_title_price) ? $gacha_title_price : $machine->gacha_title->price
                )}}
            </div>
            @if( isset($play_count) )
                <div class="">
                    {{number_format($play_count)}}点
                </div>
                <div class="mt-4">
                    商品小計
                    <span class="fs-3">¥{{number_format( $sub_total_fee )}}</span>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- <div class="row g-3">
    <div class="col-4 col-lg-2 text-center">

        <ratio-image-component
        url="{{$gacha_title->image_samune_path}}"
        style_class="{{$gacha_title->ratio.' ratio bg-body'}}"
        bg_size="contain"
        ></ratio-image-component>


    </div>
    <div class="col ">


        <div class="">

            <!--discription head-->
            @include('manuf.gacha.common.title_discription.title_name')


            
                <div class="d-flex gap-2 align-items-center mb-1">
                    <h6 class="fw-bold m-0">ガチャマシーン</h6>

                    <!--限定ガチャラベル-->
                    @if ( $machine->gacha->type_label && $machine->gacha->is_type_label_text)
                        <span class="bg-info text-white px-2 rounded"
                        style="font-size:11px;"
                        >{{ $machine->gacha->type_label }}</span>
                    @endif

                </div>
                
            <div class="card p-1 mb-4 ">{{$machine->name}}</div>

            <!--price-->
            @include('manuf.gacha.common.title_discription.price')


        </div>



    </div>
</div> --}}
