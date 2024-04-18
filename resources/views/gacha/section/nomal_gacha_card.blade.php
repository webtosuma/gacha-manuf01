<!--nomal gacha card-->
@forelse ($gachas as $gacha)
    <div class="col-12 col-md-6 col-lg-4  ">


        <a href="{{$gacha->route}}"
        class="card border-secondary border-0 shadow bg-white
        text-dark text-center overflow-hidden text-decoration-none
        position-relative shiny
        hover_anime" style="border-radius:1rem;">

            <!--image-->
            @include('gacha.common.top_image')

            <!--metter-->
            @include('gacha.common.metter')

        </a>


        <!--play_buttons-->
        @include('gacha.common.play_buttons')


    </div>
@empty
    <div class="col-12 text-secondary bg-light-subtle
    p-3 fs-5 rounded-3 shadow
        ">
        *該当するガチャがありません。
    </div>
@endforelse
