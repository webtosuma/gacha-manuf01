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


            <h6 class="fw-bold m-0">ガチャマシーン</h6>
            <div class="card p-1 mb-4 ">{{$machine->name}}</div>

            <!--price-->
            @include('manuf.gacha.common.title_discription.price')


        </div>



    </div>
</div>
