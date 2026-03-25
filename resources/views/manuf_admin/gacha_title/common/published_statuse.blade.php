<!--公開ステータス-->
<div class="">

    @switch($gacha_title->published_status)
        @case(1)
            <span class="badge rounded-pill bg-success">公開中</span>
            @break
        @case(2)
            <span class="badge rounded-pill bg-warning">予約中</span>
            @break
        @default
            <span class="badge rounded-pill bg-secondary">停止中</span>
            @break

    @endswitch


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
