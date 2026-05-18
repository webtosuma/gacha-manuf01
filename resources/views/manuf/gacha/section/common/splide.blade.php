{{-- {{config('app.gacha_card_ratio')}} --}}

<div class="splide__track">
    <ul class="splide__list">
        @foreach ($slides as $si => $slide)

            <li class="splide__slide px-2">
                <a href="{{ $slide['href'] }}">


                    <!--image-->
                    <div class="ratio {{config('app.info_ratio')}} position-relative
                    overflow-hidden rounded-4
                    "
                    >
                        <!--image-->
                        @if( $slide['type'] == 'gacha' )

                            <div class="rounded-4 overflow-hidden">
                                @php $gacha = $slide['gacha'];@endphp
                                @include('gacha.common.top_image')
                            </div>

                        @else
                            <div style="z-index:1;">
                                <ratio-image-component
                                style_class="ratio {{config('app.info_ratio')}} bg-whiteXX"
                                url="{{ $slide['image'] }}"
                                bg_size="contain"
                                ></ratio-image-component>
                            </div>
                        @endif
                    </div>


                </a>
            </li>

        @endforeach

    </ul>
</div>
