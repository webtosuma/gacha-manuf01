<!--サブスクプラン-->
{{-- @if($gacha->subscription_id)
    <div class="text-center fw-bold">『{{ $gacha->subscription->sub_label }}』専用</div>
@endif --}}



{{-- <div class="d-flex align-items-end justify-content-start gap-2 px-2 py-1">

    @if( $gacha->new_label && $gacha->is_type_label_text )
        <!--新着-->
        <span
        class="bg-danger text-white px-2 rounded-pill"
        style="font-size:12px;"
        >{{ 'NEW!!' }}</span>
    @endif

    @if( $gacha->user_rank_label && $gacha->is_type_label_text )
        <!--会員ランク限定-->
        <span class="bg-info border text-light px-2 rounded-pill"
        style="font-size:11px;"
        >{{ $gacha->user_rank_label }}</span>
    @endif

    @if( $gacha->type_label && $gacha->is_type_label_text )
        <!--限定ガチャラベル-->
        <span class="bg-body border text-dark px-2 rounded-pill"
        style="font-size:11px;"
        >{{ $gacha->type_label }}</span>
    @endif

</div> --}}



{{-- <u-manuf-gacha-metter
sm_card         ="{{$gacha->sm_card}}"
new_label_path  ="{{$gacha->new_label_path}}"
bg_color        =""
gacha_type      ="{{$gacha->type}}"
sponsor_ad      ="{{$gacha->sponsor_ad}}"
gacha_play_point="{{$gacha->one_play_point}}"
is_meter        ="{{$gacha->is_meter}}"
remaining_ratio ="{{$gacha->remaining_ratio}}"
remaining_count ="{{$gacha->remaining_count}}"
max_count       ="{{$gacha->max_count}}"
img_path_point="{{$gacha->img_path_point}}"
type_n_remaining_count_label="{{$gacha->type_n_remaining_count_label}}"
></u-manuf-gacha-metter> --}}
