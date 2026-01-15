<!--カスタムボタン　モーダル-->
@php $params = ['category_code'=>$gacha->category_code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp

<u-gacha-custom-modal
one_play_point="{{$gacha->one_play_point}}"
token    ="{{csrf_token()}}"
r_action ="{{ route('admin.gacha.play', $params) }}"
gacha_id ="{{$gacha->id}}"
max_count="{{$gacha->remaining_count}}"
max_custom_type_count="{{$gacha->max_custom_type_count}}"
>

    @include('gacha.common.top_image')

</u-gacha-custom-modal>


