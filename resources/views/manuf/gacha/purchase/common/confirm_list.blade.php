<!-- お届け先 -->
@include('manuf.gacha.purchase.common.address')



<!-- 購入ガチャタイトル -->
<section class="mb-4">

    <h5 class="fw-bold">購入ガチャタイトル</h5>

    @include('manuf.gacha.purchase.common.title_card')

</section>



<!-- 発送料金 -->
<section class="mb-4">

    <h5 class="fw-bold">発送料金</h5>

    <div class="card card-body bg-white">

        <div class="text-end fw-bold">
            <span class="fs-6">¥{{number_format( $shipped_fee )}}</span>
        </div>

    </div>

</section>
