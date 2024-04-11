<h5 class="fw-bold text-center mb-3">関連ガチャ</h5>

<div class="row mb-3 gy-3">
    <!--nomal gacha card-->
    @forelse ($gachas as $num => $gacha)
        @if($num<6)
            <div class="col-6">


                @include('gacha.section.nomal_gacha_card_sm_a')


            </div>
        @endif
    @empty
        <div class="col-12 text-secondary bg-light-subtle
        p-3 fs-5 rounded-3 shadow
            ">
            *該当するガチャがありません。
        </div>
    @endforelse
</div>

<div class="text-center">
    <a href="{{route('gacha_category',$category_code)}}" class="btn btn-light shadow rounded-pill fw- col-6"
    >もっと見る</a>
</div>
