    <!-- countdown gacha card -->
    @foreach ($countdown_gachas as $gacha)
        @if( $gacha->initial_time )
            <div class="col-12 col-md-6 col-lg-4  ">
                <div class="card border-secondary border-0 shadow bg-white
                text-dark text-center overflow-hidden text-decoration-none
                hover_anime position-relative" style="border-radius:1rem;">

                    <u-countdown-gacha
                    initial_time="{{$gacha->initial_time}}"
                    ></u-countdown-gacha>

                    <div class="position-relative">
                        <!--loading-->
                        <div class="ratio ratio-4x3">
                            <div class="bg-dark d-flex align-items-center justify-content-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <!--gacha image-->
                        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden"
                        style="z-index:0; -ms-filter: blur(6px); filter: blur(6px); ">
                            <ratio-image-component
                            url="{{ $gacha->image_path }}" style_class="ratio ratio-4x3"
                            ></ratio-image-component>
                        </div>
                    </div>


                    <div class="card-body bg-dark" style="height:4.3rem;"></div>
                </div>
            </div>
        @endif
    @endforeach
