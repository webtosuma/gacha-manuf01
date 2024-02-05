
@php
/* NEW ラベル */
$published_at = $gacha->published_at ? $gacha->published_at->toDateTimeString() : '';
$new_start_at = now()->subday(7)->toDateTimeString();//減算
$bool = $new_start_at < $published_at;
@endphp

@if($bool)
    <div class="d-inline-block bg-warninggg border border-warning border-3
     p-0 px-2 text-warning fw-bold"
    style="transform: skew(-15deg); font-size:.8rem;"
    >NEW</div>
@endif
