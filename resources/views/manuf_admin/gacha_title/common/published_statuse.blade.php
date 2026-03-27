<!--公開ステータス-->
<div class="">

    @include('manuf_admin.gacha_title.common.published_statuse_badge')


    <div class="px-2 mt-1 form-text">
        {{ $gacha_title['published_start_at'] && ($gacha_title->published_status>0)
        ?  $gacha_title['published_start_at']->format('Y/m/d H:i')
        : '----/--/-- --:--' }}
        <span>~</span>
        {{ $gacha_title['published_end_at']
        ? $gacha_title['published_end_at']->format('Y/m/d H:i')
        : '----/--/-- --:--' }}
    </div>
</div>
