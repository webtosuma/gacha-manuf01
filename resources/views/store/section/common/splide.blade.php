<div class="splide__track">
    <ul class="splide__list">
        @foreach ($slides as $si => $slide)

            <li class="splide__slide px-1 ">
                <a href="{{ $slide['href'] }}">


                    <!--image-->
                    <div class="ratio {{config('store.item_ratio')}}  position-relative
                    overflow-hidden rounded-
                    ">
                        <div style="z-index:1;">
                            <ratio-image-component
                            style_class="ratio {{config('store.item_ratio')}} "
                            url="{{ $slide['image'] }}"
                            ></ratio-image-component>
                        </div>
                    </div>


                </a>
            </li>

        @endforeach
    </ul>
</div>
