
@php
/* NEW ラベル */
$published_at = $gacha->published_at ? $gacha->published_at->toDateTimeString() : '';
$new_start_at = now()->subday(7)->toDateTimeString();//減算
$bool = $new_start_at < $published_at;
@endphp

@if($bool)
    {{-- <div class="d-inline-block bg-warning border border-warning border-
     p-0 px-2 text-white fw-bold"
    style="transform: skew(-15deg); font-size:.8rem;"
    >NEW</div> --}}

    <div class="d-inline-block"  style="height:1.6rem;">
        <img src="{{  asset( 'storage/site/image/new_icon/5.png' ) }}" class="h-100" alt="">
    </div>
@endif
