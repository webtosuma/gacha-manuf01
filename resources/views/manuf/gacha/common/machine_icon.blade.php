<!--head-->
<div class="position-relative">

    <!--new-->
    @if( isset( $machine_text ) )
        <div class="position-absolute top-0 start-0 w-100
        px-3 text-start">
            <span class="bg-danger text-white px-2 rounded-pill"
            style="font-size:11px;"
            >{{ 'NEW' }}</span>
        </div>
    @endif

    <img src="{{$gacha_title->img_path_card_head}}" alt="" class="d-block w-100">

</div>


<!--image-->
<div class="mx-auto overflow-hidden" style="width:88%;">
    <ratio-image-component
    url="{{$gacha_title->image_samune_path}}"
    style_class="{{ $gacha_title->ratio.' ratio bg-body border'}}"
    bg_size="contain"
    ></ratio-image-component>
</div>


<!--body-->
<div class="position-relative">

    <img src="{{$gacha_title->img_path_card_body}}" alt="" class="d-block w-100">

    {{-- @if( isset( $machine_text ) )
        <!--タイトル-->
        <h6 class="
        position-absolute bottom-0 start-0 w-100
        text-truncate form-text fw-bold px-2
        " >{{ $gacha_title->name }}</h6>
    @endif --}}

</div>


@if( isset( $machine_text ) )

    <!--発送時期-->
    <div class="{{ $gacha_title->estimated_shipping_label_style }} w-100"
    >{{ $gacha_title->estimated_shipping_label }}</div>

@endif
