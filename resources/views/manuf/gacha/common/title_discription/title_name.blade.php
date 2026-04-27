<div id="discription-head" class="mb-3">

    <!--badge link-->
    <div class="d-flex gap-2 mb-2">

        @if($gacha_title->is_new)
            <!--NEW-->
            <div
            class="py-0 text-white bg-danger px-2 rounded-pill"
            style="font-size:11px;">NEW</div>
        @endif

        <!--カテゴリー-->
        <a href="{{ $gacha_title->r_category }}"
        class="btn btn-sm py-0 bg-white border text-secondary rounded-pill"
        style="font-size:11px;"
        target="_blank"
        >{{$gacha_title->category->name}}</a>


    </div>


    <!--商品名-->
    <div class="">
        <h5 class="fs-5 fw-bold mb-0">{{$gacha_title->name}}</h5>
    </div>


</div>
