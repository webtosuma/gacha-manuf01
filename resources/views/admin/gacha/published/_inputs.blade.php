<div class="px-3 mb-5">

    <!-- 公開 -->
    <label class="card card-body mb-3 disabled"
    @if ( $gacha_restriction ) style="opacity: .5;" @endif
    >
        <div class="form-check w-100">
            <input name="is_published" value="1" type="radio" class="form-check-input"
            @if ( $gacha_restriction ) disabled @endif
            {{ $gacha->is_published ? 'checked' : ''}}
            >
            <h5 class="mb-0">公開</h5>
        </div>
        <ul class="form-text">
            <li>ガチャを公開します。</li>
            <li>公開後は「基本情報」と「商品登録」の編集ができなくなりますので、ご注意ください。</li>
            <li>「演出動画」と「詳細説明」の編集は、引き続き可能です。</li>
        </ul>
        @if( $gacha->is_published ) <div class="form-text">
            {{\Carbon\Carbon::parse($gacha->published_at)->format('公開日：Y年m月d日 H:i')}}
        </div> @endif
    </label>


    <!-- 公開予約 -->
    <label class="card card-body mb-3"
    @if ( $gacha_restriction ) style="opacity: .5;" @endif
    >
        <div class="form-check w-100">
            <input name="is_published" value="2" type="radio" class="form-check-input"
            @if ( $gacha_restriction ) disabled @endif
            {{ !$gacha->is_published && $gacha->published_at ? 'checked' : ''}}
            >
            <h5 class="mb-0">公開予約</h5>
        </div>
        <ul class="form-text">
            <li>ガチャを公開予約します。</li>
            <li>「公開予約日時」を設定すると、指定日時にガチャを自動公開することができます。</li>
        </ul>

        <div class="input-group mb-3">
            <span class="input-group-text" >公開予約日時</span>
            <input name="published_at"
            type="datetime-local" class="form-control"
            value="{{ $gacha->published_at ? $gacha->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\T00:00') }}"
            min="{{ now()->format('Y-m-d\T00:00') }}">
        </div>
    </label>


    <!-- 非公開 -->
    <label for="publishedType3" class="card card-body mb-3">
        <div class="form-check w-100">
            <input name="is_published" value="0"
            type="radio" id="publishedType3" class="form-check-input"
            {{ !$gacha->published_at ? 'checked' : ''}}
            >
            <h5>非公開</h5>
        </div>
        <ul class="form-text">
            <li>ガチャを非公開します。</li>
            <li>非公開後はガチャ一覧にガチャが表示されなくなります。</li>
            <li>商品の残数はそのままです。</li>
        </ul>
    </label>
</div>
