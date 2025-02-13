        <a href="{{$gacha->route}}"
        class="card border-secondary border-0 shadow bg-white
        text-dark text-center overflow-hidden text-decoration-none
        position-relative shiny
        hover_anime" style="border-radius:1rem;">


            <!--image-->
            @include('gacha.common.top_image')

            <!--metter-->
            <u-gacha-metter
            sm_card         ="1"
            new_label_path  ="{{$gacha->new_label_path}}"
            bg_color        =""
            gacha_type      ="{{$gacha->type}}"
            sponsor_ad      ="{{$gacha->sponsor_ad}}"
            gacha_play_point="{{$gacha->one_play_point}}"
            is_meter        ="{{$gacha->is_meter}}"
            remaining_ratio ="{{$gacha->remaining_ratio}}"
            remaining_count ="{{$gacha->remaining_count}}"
            max_count       ="{{$gacha->max_count}}"
            ></u-gacha-metter>

            {{-- @php
            $bg_color = '';
            $bg_color = $gacha->type=='only_new_user' ? 'bg-success text-white' : $bg_color;//新機械委員限定
            $bg_color = isset($metter_bg_color) ? $metter_bg_color : $bg_color;
            @endphp
            <div class="card-body pt-0 pb-0 {{$bg_color}}">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <div class="p-1">
                        @include('includes.point_icon')
                    </div>

                    <span class="fw-bold fs-5">
                        <number-comma-component number="{{ $gacha->one_play_point }}"></number-comma-component>pt
                    </span>
                </div>

                @if($gacha->is_meter)
                    <div class="progress" style="height:.5rem;">
                        @php
                        $ratio = $gacha->remaining_ratio;
                        $bg_color = $ratio>70 ? 'bg-primary' : ( $ratio>40 ? 'bg-warning' : 'bg-danger' );
                        $style_class = 'progress-bar progress-bar-striped '.$bg_color
                        @endphp
                        <div class="{{ $style_class }} text-light" role="progressbar"
                        style="width: {{$ratio.'%'}}" aria-valuenow="{{ $ratio }}" aria-valuemin="0" aria-valuemax="{{ $ratio }}"
                        ></div>
                    </div>

                    <p class="text-center m-0" style="font-size:.8rem;">
                        残り
                        <number-comma-component number="{{ $gacha->remaining_count }}"></number-comma-component>
                        /
                        <number-comma-component number="{{ $gacha->max_count }}"></number-comma-component>
                    </p>
                @endif
            </div> --}}

        </a>
