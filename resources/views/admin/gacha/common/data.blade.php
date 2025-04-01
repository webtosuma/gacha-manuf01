<div class="p-3 bg-light rounded-3 mb-3">
    <div class="mb-">
        <h5 class="border-bottom ">{{ $gacha->name }}</h5>

        <div class="card-body">
            <h6 class="">
                <div class="d-flex align-items-center gap-2">
                    @include('includes.point_icon')
                    <div class="">
                        1回×
                        <span class="fs-3">
                            <number-comma-component number="{{ $gacha->one_play_point }}"></number-comma-component>
                        </span>pt
                    </div>
                </div>
            </h6>
            <p class="card-text m-0">
                残り
                <number-comma-component number="{{ $gacha->remaining_count }}"></number-comma-component>
                /
                <number-comma-component number="{{ $gacha->max_count }}"></number-comma-component>
            </p>
            <div class="progress mb-3">
                @php
                $ratio = $gacha->remaining_ratio;
                $bg_color = $ratio>70 ? 'bg-primary' : ( $ratio>40 ? 'bg-warning' : 'bg-danger' );
                $style_class = 'progress-bar progress-bar-striped '.$bg_color
                @endphp
                <div class="{{ $style_class }}" role="progressbar"
                style="width: {{$ratio.'%'}}" aria-valuenow="{{ $ratio }}" aria-valuemin="0" aria-valuemax="{{ $ratio }}"></div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-1">
            <!--広告-->
            @if($gacha->sponsor_ad)
                <div class="border px-3 rounded-pill">
                    広告
                    <span>{{'×'.$gacha->sponsor_ads->count()}}</span>
                </div>
            @endif
            <!--ガチャの種類-->
            <span class="border px-3 rounded-pill">{{ $gacha->types()[$gacha->type] }}</span>
            <!--サブスクガチャの種類-->
            @if( $gacha->subscription_id )
                <span class="border px-3 rounded-pill">{{ $gacha->subscription->sub_label }}</span>
            @endif
            <!--ランクの指定-->
            @if( env('NEW_TICKET_SISTEM',false) )
                <span class="border px-3 rounded-pill">{{ $gacha->user_rank_id!==null ? $gacha->user_rank->label : '全ての' }}会員</span>
            @endif
            <!--時間帯-->
            <span class="border px-3 rounded-pill">{{ $gacha->min_time.'〜'.$gacha->max_time }}</span>
        </div>
    </div>
</div>


{{-- <div class="p-3 bg-light rounded-3 mb-3">
    @if ( $gacha->is_published )
        <div class="text-success border-bottom">公開中</div>
    @else
        <div class="text-danger border-bottom">非公開</div>
        <div class="">{{
        $gacha->published_at ?
        '公開予定日：'.\Carbon\Carbon::parse($gacha->published_at)->format('Y年m月d日')
        : ''
        }}</div>
    @endif
    <div class="mt-3">
        <a href="{{ route('admin.gacha.published', $gacha) }}"
        class="btn btn-sm btn-light border">公開設定</a>
    </div>
</div> --}}


<div class="p-3 bg-light rounded-3 mb-3">
    @php
    $total_play_point = $gacha->one_play_point * $gacha->max_count; //合計売上ポイント
    $profit = $total_play_point - $gacha->total_point;//利益
    $ratio  = $total_play_point? $profit/$total_play_point*100 : 0;//利益率
    @endphp
    <div class="row border-bottom mb-3">
        <div class="col">{{ '予定売上：' }}</div>
        <div class="col text-end">
            <span class="fs-4">
                <number-comma-component
                number="{{ $total_play_point }}"></number-comma-component>
            </span>
            <span>pt</span>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col">{{ '交換予定ポイント：' }}</div>
        <div class="col text-end">
            <span class="fs-">
                <number-comma-component
                number="{{ $gacha->total_point }}"></number-comma-component>
            </span>
            <span>pt</span>
        </div>
    </div>
    <div class="row">
        <div class="col">{{ '予定利益' }}</div>
        <div class="col text-end">
            <span class="fs-">
                <number-comma-component
                number="{{ $profit }}"></number-comma-component>
            </span>
            <span>pt</span>
        </div>
    </div>
    <div class="row">
        <div class="col text-end">
            <span>{{ round( $ratio, 1) .'%' }}</span>
        </div>
    </div> --}}
</div>


<div class="mb-3">
    @include('admin.gacha.common.play_buttons_nomal')
</div>
