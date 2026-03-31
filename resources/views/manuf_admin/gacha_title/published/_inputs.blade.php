<div >



    <!-- 公開期間 published_start_at published_end_at　-->
    <div class="card card-body mb-4 position-relative overflow-hidden">

        <h6 class="fw-bold">公開期間</h6>

        <ul class="form-text">
            <li>公開期間内は、サイト内に公開されます。</li>
            <li>公開期間内は、「売り切れ」になっても、サイト内に公開されます。</li>
        </ul>


        <!-- 公開開始 日時 published_start_at -->
        <div class="input-group mb-3">
            <span class="input-group-text" >公開開始 日時</span>
            <input name="published_start_at"
            type="datetime-local" class="form-control"
            value="{{old( 'published_start_at',
            $gacha_title['published_start_at'] ? $gacha_title['published_start_at']->format('Y-m-d\TH:i') : null )}}"
            min="{{ now()->format('Y-m-d\T00:00') }}">
        </div>


        <!-- 公開終了 時 published_end_at -->
        <div class="input-group mb-3">
            <span class="input-group-text" >公開終了 日時</span>
            <input
            name="published_end_at"
            type="datetime-local" class="form-control"
            value="{{old( 'published_end_at',
            $gacha_title['published_end_at'] ? $gacha_title['published_end_at']->format('Y-m-d\TH:i') : null )}}"
            >
        </div>


    </div>



    <!-- 販売期間 sales_start_at sales_end_at -->
    <div class="card card-body mb-4 position-relative overflow-hidden">

        <h6 class="fw-bold">販売期間</h6>

        <ul class="form-text">
            <li>販売期間内は、タイトルの筐体が販売されます。</li>
            <li>公開期間内は、販売期間が終了しても、サイト内に公開されます。</li>
        </ul>


        <!-- 販売開始 日時 sales_start_at -->
        <div class="input-group mb-3">
            <span class="input-group-text" >販売開始 日時</span>
            <input name="sales_start_at"
            type="datetime-local" class="form-control"
            value="{{old( 'sales_start_at',
            $gacha_title['sales_start_at'] ? $gacha_title['sales_start_at']->format('Y-m-d\TH:i') : null )}}"
            >
        </div>


        <!-- 販売終了 日時 sales_end_at -->
        <div class="input-group mb-3">
            <span class="input-group-text" >販売終了 日時</span>
            <input
            name="sales_end_at"
            type="datetime-local" class="form-control"
            value="{{old( 'sales_end_at',
            $gacha_title['sales_end_at'] ? $gacha_title['sales_end_at']->format('Y-m-d\TH:i') : null )}}"
            >
        </div>


    </div>



    <!-- 発送予定 estimated_shipping_at -->
    <div class="card card-body mb-4 position-relative overflow-hidden">

        <h6 class="fw-bold">発送予定</h6>

        <ul class="form-text">
            <li>発送の予定日時を設定してください。</li>
        </ul>


        <!-- 発送予定 日時 estimated_shipping_at -->
        <div class="input-group mb-3">
            <span class="input-group-text" >発送予定 日時</span>
            <input name="estimated_shipping_at"
            type="datetime-local" class="form-control"
            value="{{old( 'estimated_shipping_at',
            $gacha_title['estimated_shipping_at'] ? $gacha_title['estimated_shipping_at']->format('Y-m-d\TH:i') : null )}}"
            >
        </div>


    </div>



</div>
