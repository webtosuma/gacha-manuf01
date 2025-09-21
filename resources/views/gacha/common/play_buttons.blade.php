@php
$params = [ 'category_code'=>$gacha->category_code_name, 'gacha'=>$gacha, 'key'=>$gacha->key ];
@endphp
<u-gacha-play-buttons
r_action="{{ route( 'gacha.play', $params )         }}"
r_costom="{{ route( 'gacha.custom_count', $params ) }}"
r_prize_history="{{ $gacha->r_prize_history }}"

one_play_point             ="{{$gacha->one_play_point   }}"
is_disabled_oneplay_btn    ="{{$gacha->is_disabled_oneplay_btn}}"
is_disabled_tenplay_btn    ="{{$gacha->is_disabled_tenplay_btn}}"
is_disabled_hundredplay_btn="{{$gacha->is_disabled_hundredplay_btn}}"
is_disabled_custom_btn     ="{{$gacha->is_disabled_custom_btn }}"

i_time                 ="{{$gacha->initial_time}}"
limitted_i_time        ="{{$gacha->initial_timezone}}"
sub_auth_user          ="{{$gacha->sub_auth_user       ? 1 : null }}"
dont_auth_user_rank    ="{{$gacha->dont_auth_user_rank ? 1 : null }}"

></u-gacha-play-buttons>
