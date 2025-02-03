@if($gacha->is_show_timezone)<!-- (時間帯限定)表示可能か否か -->

    @switch( $gacha->type )
        @case('one_chance')
            <!-- ワンチャンス限定 -->
            @include('gacha.common.play_buttons_one_chance')
            @break


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
            @if( ! $gacha->sponsor_ad )
                @include('gacha.common.play_buttons_nomal')

            {{-- 広告ボタン --}}
            @else
                @include('gacha.common.play_buttons_sponsor_ad')
            @endif


            @break
        {{----}}
    @endswitch

@else
    <div class="py-3 text-center text-secondary">公開時間までお待ちください。</div>
@endif

