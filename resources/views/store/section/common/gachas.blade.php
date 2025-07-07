<div class="px-md-5">
    <div class="row mb-3 gy-3">
        <!--nomal gacha card-->
        @foreach ($gachas as $num => $gacha)
            <div class="col-6 col-md-3">


                {{-- @include('gacha.section.nomal_gacha_card_sm_a') --}}
                @include('store.section.common.nomal_gacha_card_sm_a')


            </div>
        @endforeach


        <div class="col-6 col-md-3">
            <a href="{{route('gacha_category')}}"  target="_blank">


                <!--image-->
                <div class="h-100
                d-flex align-items-center justify-content-center
                overflow-hidden bg-body rounded-4
                ">
                    もっと見る
                </div>


            </a>
        </div>

    </div>
</div>
