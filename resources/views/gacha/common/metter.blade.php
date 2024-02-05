<!--metter-->
<div class="card-body py-0">
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
        </div>
    </div>



    <div class="@if( !$gacha->is_meter ) invisible @endif">
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
</div>
