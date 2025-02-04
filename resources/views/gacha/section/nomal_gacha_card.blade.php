<!--nomal gacha card-->
@forelse ($gachas as $gacha)
    <div class="col-12 col-md-6 col-lg-4  ">
        <div
        class="card border-secondary border-0 shadow bg-transparent
        text-dark text-center overflow-hidden text-decoration-none
        position-relative shiny
        hover_anime" style="border-radius:1rem;">

            <!--image-->
            <a href="{{$gacha->route}}" class="d-block">
                @include('gacha.common.top_image')
            </a>

            <!-- スライダー -->
            @if ( $gacha->slide_imgs )
                <div id="{{'splide_gacha'.$gacha->id}}" class="splide_gacha splide bg-white">
                    @include('gacha.common.splide')
                </div>
            @endif

            <!--metter-->
            <a href="{{$gacha->route}}" class="d-block text-dark bg-white">
                @include('gacha.common.metter')
            </a>

            <!--play_buttons-->
            @if ( env('GACHA_CARD_PRIZE_SLIDE',false) )
                <div class="p-2 pt-0 bg- " style="background: rgb(0, 0, 0, .3)">
                    @include('gacha.common.play_buttons')
                </div>
            @endif

        </div>


        @if ( !env('GACHA_CARD_PRIZE_SLIDE',false) )
            <!--play_buttons-->
            @include('gacha.common.play_buttons')
        @endif



    </div>
@empty
    <div class="col-12 text-secondary bg-light-subtle
    p-3 fs-5 rounded-3 shadow
        ">
        *該当するガチャがありません。
    </div>
@endforelse
