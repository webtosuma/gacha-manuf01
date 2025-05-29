<!--利用回数-->
@if ($coupon->count>0)
    <div class="my-2">
        @switch($coupon->user_type)
            @case('all_user')<!--先着-->
                <div>
                    <div class="d-flex align-items-center form-text">
                        <span>先着で</span>
                        <span>{{ $coupon->count }}回</span>
                        <span>までご利用できます。</span>
                    </div>
                    @if ($coupon->remaining_count)
                        <div class="border px-2 d-flex align-items-center">
                            <span >先着あと</span>
                            <div class="fw-bold fs-5 m-0 px-2">{{$coupon->remaining_count}}</div>
                            <span >名</span>
                        </div>
                    @else
                        <div class="border px-2 text-center text-danger">終了しました。</div>
                    @endif
                </div>
                @break


            @case('user')<!--おひとり様-->
                <div>
                    <div class="d-flex align-items-end form-text">
                        <span>おひとり様</span>
                        <span>{{ $coupon->count }}回</span>
                        <span>までご利用できます。</span>
                    </div>
                    @if ($coupon->remaining_count)
                        <div class="border px-2 d-flex align-items-end">
                            <span>あと</span>
                            <div class="fw-bold fs-5 m-0 px-2">{{$coupon->remaining_count}}</div>
                            <span>回</span>
                        </div>
                    @else
                        <div class="border px-2 text-center text-danger">終了しました。</div>
                    @endif
                </div>
                @break
            <!---->
        @endswitch
    </div>
@else
    <div>何回でも利用可能</div>
@endif
