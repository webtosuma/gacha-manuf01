@switch( $gacha->type )
    @case('one_time')
        <!-- 1回限定 -->
        @include('gacha.common.play_buttons_one_time')
        @break


    @case('only_oneday')
        <!-- 一日一回限定 -->
        @include('gacha.common.play_buttons_only_oneday')
        @break


    @default
        {{-- 通常ボタン --}}
        @include('gacha.common.play_buttons_nomal')
        @break

    {{----}}
@endswitch
