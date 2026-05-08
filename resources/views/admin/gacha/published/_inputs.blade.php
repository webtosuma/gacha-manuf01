<div class="px-3 mb-5">

    <!--公開ステータス-->
    @include('admin.gacha.common.published_statuse')


    <!-- 公開予約 -->
    <div class="card card-body mb-3 position-relative overflow-hidden">

        <!--公開制限-->
        @if( $gacha_restriction )
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-white"
            style="z-index:1; opacity: .5;"></div>
        @endif


        <h5 class="mb-0">公開予約</h5>

        <ul class="form-text">
            <li>ガチャを公開予約します。</li>
            <li>「公開予約日時」を設定すると、指定日時にガチャを自動公開にすることができます。</li>
            <li>「停止予約日時」を設定すると、指定日時にガチャを自動非公開にすることができます。</li>
        </ul>

        <!-- 公開予約日時 published_at -->
        <div class="input-group mb-3">
            <span class="input-group-text" >公開 予約日時</span>
            <input name="published_at"
            type="datetime-local" class="form-control"
            value="{{old( 'published_at',
            $gacha['published_at'] ? $gacha['published_at']->format('Y-m-d\TH:i') : null )}}"
            min="{{ now()->format('Y-m-d\T00:00') }}">
        </div>


        <!-- 停止予約日時 end_published_at -->
        <div class="input-group mb-3">
            <span class="input-group-text" >停止 予約日時</span>
            <input
            name="end_published_at"
            type="datetime-local" class="form-control"
            value="{{old( 'end_published_at',
            $gacha['end_published_at'] ? $gacha['end_published_at']->format('Y-m-d\TH:i') : null )}}"
            min="{{ now()->format('Y-m-d\T00:00') }}">
        </div>



        <div class="col-12 col-md-6 mt-4">
            <disabled-button style_class="btn btn-warning text-white w-100 shadow"
            btn_text="予約日時を更新"></disabled-button>
        </div>

    </div>


</div>
