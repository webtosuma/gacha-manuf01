@switch( $gacha->type )
    @case('one_time')
        <!-- 1回限定 -->
        @include('gacha.common.play_buttons_one_time')
        @break


    @case('only_oneday')
        <!-- 一日一回限定 -->
        @include('gacha.common.play_buttons_only_oneday')
        @break

    @case('only_new_user')
        <!-- 新規会員限定 -->
        @include('gacha.common.play_buttons_only_new_user')
        @break



    @default
        {{-- 通常ボタン --}}
        @include('gacha.common.play_buttons_nomal')
        @break

    {{----}}
@endswitch
