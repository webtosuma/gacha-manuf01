<!--公開ステータス-->
<div class="mb-3">

    @switch($gacha->published_status)
        @case(1)
            <span class="badge rounded-pill bg-success fs-6">公開中</span>
            @break
        @case(2)
            <span class="badge rounded-pill bg-warning fs-6">予約中</span>
            @break
        @default
            <span class="badge rounded-pill bg-secondary fs-6">停止中</span>
            @break

    @endswitch


    {{ $gacha['published_at'] && ($gacha->published_status>0)
    ? $gacha['published_at']->format('Y/m/d H:i') : '----/--/-- --:--' }}
</div>
