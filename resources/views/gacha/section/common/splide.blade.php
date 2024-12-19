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


                            <div class="absolute h-100 w-100 bg-dark d-flex align-items-center justify-content-center"
                            style="z-index:0;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    @endif


                </a>
            </li>

        @endforeach

            {{-- <li class="splide__slide">Slide 01</li>
            <li class="splide__slide">Slide 02</li>
            <li class="splide__slide">Slide 03</li> --}}
    </ul>
</div>
