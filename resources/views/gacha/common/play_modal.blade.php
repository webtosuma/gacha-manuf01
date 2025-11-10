<!--ポップアップモーダル-->
@php
$params = [ 'category_code'=>$gacha->category_code_name, 'gacha'=>$gacha, 'key'=>$gacha->key ];
@endphp
<u-gacha-modal
one_play_point="{{$gacha->one_play_point}}"
token         ="{{csrf_token()}}"
r_action      ="{{route( 'gacha.play', $params )}}"
is_popup_btn  ="{{$gacha->is_popup_btn}}"
gacha_id      ="{{$gacha->id}}"
>
    @include('gacha.common.top_image')

</u-gacha-modal>
 

<!--カスタムボタン　モーダル-->
<u-gacha-custom-modal
one_play_point="{{$gacha->one_play_point}}"
token    ="{{csrf_token()}}"
r_action ="{{route( 'gacha.play', $params )}}"
gacha_id ="{{$gacha->id}}"
max_count="{{$gacha->remaining_count}}"
max_custom_type_count="{{$gacha->max_custom_type_count}}"
>

    @include('gacha.common.top_image')

</u-gacha-custom-modal>
