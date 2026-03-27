
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
