<div class="p-3 border-0 border-radius rounded-4
h- mb-3 "
style="background:rgba(255, 255, 255, 1);">

    <!--discription head-->
    <div id="discription-head" class="mb-3">

        <!--badge link-->
        <div class="d-flex gap-2 mb-2">
            {{-- @if($gacha_title->new_label) --}}
                <!--NEW-->
                <div
                class="py-0 text-white bg-danger px-2 rounded-pill"
                style="font-size:11px;">NEW</div>
            {{-- @endif --}}

            <!--カテゴリー-->
            <a href="{{route('manuf.search',[ 'category_code_name'=>$gacha_title->category->code_name ])}}"
            class="btn btn-sm py-0 bg-white border text-secondary rounded-pill"
            style="font-size:11px;"
            >{{$gacha_title->category->name}}</a>


        </div>


        <!--商品名-->
        <div class="">
            <h5 class="fs-5 fw-bold mb-0">{{$gacha_title->name}}</h5>
        </div>


    </div>


    <!--image slide-->
    @include('manuf.gacha.common.title_discription.image_slide')



    <!--discription_table-->
    @include('manuf.gacha.common.title_discription.discription_table')

        <!--machines-->
    @include('manuf.gacha.common.title_discription.machines')


    <!--title_prize_table-->
    @include('manuf.gacha.common.title_discription.title_prize_table')


    <!--title_images-->
    @include('manuf.gacha.common.title_discription.title_images')


    <!--note-->
    @include('manuf.gacha.common.title_discription.note')




</div>


