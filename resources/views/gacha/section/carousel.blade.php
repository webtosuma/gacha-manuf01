<!--カルーセル-->
<section class="overflow-hidden" style="background:rgb(0, 0, 0,.8);">

    <div class="container px-4 bg-dar">
        <div class="mx-auto"  style="max-width: 900px;">
            <div id="carouselIndicators" class="carousel slide" data-bs-ride="carousel">

                <!--image スライド -->
                <div class="carousel-inner">
                    @foreach ($slides as $si => $slide)

                        <a href="{{ $slide['href'] }}" class="carousel-item pb- bg-dark
                        {{ $si==0 ? 'active' : ''}}">

                            <!--image-->
                            @if( $slide['type'] == 'gacha' )

                                @php $gacha = $slide['gacha'];@endphp
                                @include('gacha.common.top_image')

                            @else
                                <div class="ratio ratio-4x3 position-relative">
                                    <div style="z-index:1;">
                                        <ratio-image-component
                                        style_class="ratio ratio-4x3"
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
                    @endforeach
                </div>


                <!--side menu-->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
                </button>


                <!--bottom menu-->
                <div class="carousel-indicators mb-0">
                    @foreach ($slides as $si => $slide)
                        <button type="button" data-bs-target="#carouselIndicators"
                        class="{{ $si==0 ? 'active' : ''}}"
                        data-bs-slide-to="{{$si}}" aria-current="true" aria-label="{{'Slide '.($si+1)}}x"></button>
                    @endforeach
                </div>


            </div>
        </div>
    </div>
</section>
