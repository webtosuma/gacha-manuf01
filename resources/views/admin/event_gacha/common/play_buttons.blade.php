<!-- 説明文 -->
<div class="d-flex justify-content-center pt-3">
    <div class="text-start">

        <replace-text-component
        text="{{$gacha->resume_text}}"
        replace_link="0"
        ></replace-text-component>


    </div>
</div>


<div class="row align-items-center ">
    <div class="col-auto">
        <a href="{{route('event.gacha',$gacha->category->code_name)}}"
        class="btn btn-sm text-light fs-5 rounded-pill w-100 mt-2"
        ><i class="bi bi-chevron-left"></i>一覧</a>
    </div>
    <div class="col">


        <e-gacha-play-buttons
        r_action="{{ $gacha->r_event_play }}"
        r_costom=""
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

        ></e-gacha-play-buttons>


    </div>
</div>
