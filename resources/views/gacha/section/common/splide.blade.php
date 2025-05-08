<div class="splide__track">
    <ul class="splide__list">
        @foreach ($slides as $si => $slide)

            <li class="splide__slide p-2 pb-4 pt-3">
                <a href="{{ $slide['href'] }}">


                    <!--image-->
                    @if( $slide['type'] == 'gacha' )

                        @php $gacha = $slide['gacha'];@endphp
                        @include('gacha.common.top_image')

                    @else
                        <div class="ratio {{config('app.gacha_card_ratio')}}  position-relative">
                            <div style="z-index:1;">
                                <ratio-image-component
                                style_class="ratio {{config('app.gacha_card_ratio')}} "
                                url="{{ $slide['image'] }}"
                                ></ratio-image-component>
                            </div>
                        </div>
                    @endif


                </a>
            </li>

        @endforeach

    </ul>
</div>
