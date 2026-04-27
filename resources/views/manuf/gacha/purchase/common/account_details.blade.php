<section>
    <h5 class="fw-bold">お支払い金額</h5>

    <div class="card card-body border">

        <div class="d-flex justify-content-between">
            <div class="">商品小計(税込)</div>

            <div class="fw-bold">
                <span class="fs-6">¥{{number_format( $sub_total_fee )}}</span>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <div class="">発送料・手数料</div>

            <div class="fw-bold">
                <span class="fs-6">¥{{number_format( $shipped_fee )}}</span>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <div class="">お支払い合計(税込)</div>

            <div class="fw-bold">
                <span class="fs-3">¥{{number_format( $total_fee )}}</span>
            </div>
        </div>

    </div>

</section>