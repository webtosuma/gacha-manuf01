<!--nomal gacha card-->
@forelse ($gachas as $gacha)
    <div class="col-6 col-md-4 col-lg-3  ">


        @include('gacha.section.nomal_gacha_card_sm_a')


    </div>
@empty
    <div class="col-12 text-secondary bg-light-subtle
    p-3 fs-5 rounded-3 shadow
        ">
        *該当するガチャがありません。
    </div>
@endforelse
