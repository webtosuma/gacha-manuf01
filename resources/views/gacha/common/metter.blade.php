<!--metter-->
@php
$bg_color = '';
$bg_color = $gacha->type=='only_new_user' ? 'bg-success-subtle' : $bg_color;//新機械委員限定
$bg_color = isset($metter_bg_color) ? $metter_bg_color : $bg_color;
@endphp
<div class="card-body py-0 {{$bg_color}}">
    <div class="row align-items-center justify-content-between">
        <div class="col">
            @include('gacha.common.new_label')
        </div>


        <div class="col-auto">
            <div class="d-flex align-items-center justify-content-center gap-2 fs-6">
                    @include('includes.point_icon')

                <div class="">
                    1回×
                    <span class="fs-4">
                        <number-comma-component number="{{ $gacha->one_play_point }}"></number-comma-component>
                    </span>pt
                </div>
            </div>
        </div>


        <div class="col">
            {{-- @include('gacha.common.type_label') --}}
            <!-- 広告ガチャ -->
            @if ($gacha->sponsor_ad)
                <div class="px-1 border form-text fw-bold"
                style="background: rgb(255 255 255 / 70%);">広告</div>
            @endif

        </div>
    </div>


    @if($gacha->is_meter)
    <!-- ノーマル -->
    <div class="">
        <div class="progress">
            @php
            $ratio = $gacha->remaining_ratio;
            $bg_color = $ratio>70 ? 'bg-primary' : ( $ratio>40 ? 'bg-warning' : 'bg-danger' );
            $style_class = 'progress-bar progress-bar-striped '.$bg_color
            @endphp
            <div class="{{ $style_class }}" role="progressbar"
            style="width: {{$ratio.'%'}}" aria-valuenow="{{ $ratio }}" aria-valuemin="0" aria-valuemax="{{ $ratio }}"></div>
        </div>
        <p class="text-center m-0" style="font-size:.8rem;">
            残り
            <number-comma-component number="{{ $gacha->remaining_count }}"></number-comma-component>
            /
            <number-comma-component number="{{ $gacha->max_count }}"></number-comma-component>
        </p>
    </div>


    {{-- <!-- 1回限定 -->
    @elseif ($gacha->type=='one_time')
    <div class="">
        <div style="line-height:2rem">
            ＊お一人さま一回限定で利用でます
        </div>
    </div> --}}

    {{-- <!-- 1日限定 -->
    @elseif ($gacha->type=='only_oneday')
    <div class="">
        <div style="line-height:2rem">
            ＊１日１回で利用できます。（0時切り替わり）
        </div>
    </div> --}}

    <!-- 新規会委員限定 -->
    @elseif ($gacha->type=='only_new_user')
        <div class="text-center" style="line-height:2rem">
            ＊一週間限定・一回限定で利用できます
        </div>


    <!-- メーター表示なし -->
    @else
        <div style="height:2rem"><!--  --></div>
    @endif


</div>
