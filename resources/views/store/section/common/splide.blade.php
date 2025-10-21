<div class="splide__track">
    <ul class="splide__list">
        @foreach ($slides as $si => $slide)

            <li class="splide__slide px-1 ">
                <a href="{{ $slide['href'] }}">


                    <!--image-->
                    @if( $slide['type'] == 'info' )

                        <div style="z-index:1;">
                            <ratio-image-component
                            style_class="ratio {{config('app.gacha_card_ratio')}} "
                            url="{{ $slide['image'] }}"
                            ></ratio-image-component>
                        </div>

                    @else
                        @php
                        $splide_img_bool = config('app.gacha_card_ratio')=='ratio-3x4' ? true : false;
                        $splide_img_w = $splide_img_bool ? 'w-50' : 'w-75';
                        $splide_img_w = config('app.gacha_card_ratio')==config('store.item_ratio') ? 'w-100' : $splide_img_w;
                        @endphp
                        <div class="h-100 d-flex align-items-center justify-content-center" style="z-index:1;">
                            <ratio-image-component
                            style_class="ratio {{config('store.item_ratio')}} {{$splide_img_w}} mx-auto"
                            url="{{ $slide['image'] }}"
                            ></ratio-image-component>
                        </div>
                    @endif


                </a>
            </li>

        @endforeach
    </ul>
</div>
