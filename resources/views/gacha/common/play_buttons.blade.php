<!--BTN-->
<u-gacha-play-buttons
r_action="{{ $gacha->r_action }}"
r_costom="{{ $gacha->r_costom }}"
r_prize_history="{{ $gacha->r_prize_history }}"

one_play_point             ="{{$gacha->one_play_point   }}"
is_disabled_oneplay_btn    ="{{$gacha->is_disabled_oneplay_btn}}"
is_disabled_tenplay_btn    ="{{$gacha->is_disabled_tenplay_btn}}"
is_disabled_hundredplay_btn="{{$gacha->is_disabled_hundredplay_btn}}"
is_disabled_custom_btn     ="{{$gacha->is_disabled_custom_btn }}"

i_time              ="{{$gacha->initial_time}}"
limitted_i_time     ="{{$gacha->initial_timezone}}"
sub_auth_user       ="{{$gacha->sub_auth_user       ? 1 : null }}"
dont_auth_user_rank ="{{$gacha->dont_auth_user_rank ? 1 : null }}"

gacha_id    ="{{ $gacha->id }}"
is_popup_btn="{{$gacha->is_popup_btn}}"


btn_style_one_play_active      ="{{$gacha->btn_styles['one_play'    ]['active']}}"
btn_style_one_play_soldout     ="{{$gacha->btn_styles['one_play'    ]['soldout']}}"
btn_style_ten_play_active      ="{{$gacha->btn_styles['ten_play'    ]['active']}}"
btn_style_ten_play_soldout     ="{{$gacha->btn_styles['ten_play'    ]['soldout']}}"
btn_style_hundred_play_active  ="{{$gacha->btn_styles['hundred_play']['active']}}"
btn_style_hundred_play_soldout ="{{$gacha->btn_styles['hundred_play']['soldout']}}"
btn_style_coustom_active       ="{{$gacha->btn_styles['coustom'     ]['active']}}"
btn_style_coustom_soldout      ="{{$gacha->btn_styles['coustom'     ]['soldout']}}"

></u-gacha-play-buttons>

