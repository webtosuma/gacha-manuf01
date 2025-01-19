<div class="mx-3 py-2 border-bottom row text-end">
    <div class="col-3"></div>
    <div class="col">口数</div>
    <div class="col">当選率</div>
    <div class="col">平均PT</div>
    <div class="col">残数</div>
</div>
@foreach ($gacha->discriptions as $num => $discription)
    @if ( $discription->g_prizes->sum('max_count') > 0 )
        <div class="mx-3 py-2 border-bottom">

            <!-- ランク情報 -->
            <button class="btn w-100 text-start" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapse{{$discription->id}}" aria-expanded="false" aria-controls="collapse{{$discription->id}}">
                <div class="row align-items-center text-end">

                    <div class="col-3 dropdown-toggle text-start">
                        <span class="fs-5">{{ $discription->rank_label }}</span>
                    </div>


                    <div class="col">
                        <number-comma-component
                        number="{{ $discription->g_prizes->sum('max_count') }}"></number-comma-component>
                    </div>
                    <div class="col">
                        @php
                        $ratio = $gacha->max_count
                        ? $discription->g_prizes->sum('max_count')/$gacha->max_count*100 :0;
                        @endphp
                        <span>{{ round( $ratio, 2) .'%' }}</span>
                    </div>
                    <div class="col">
                        @php
                        $average = $discription->g_prizes->sum('max_count') ?
                        $discription->total_point / $discription->g_prizes->sum('max_count') : 0;
                        @endphp
                        <number-comma-component
                        number="{{ round( $average ).' pt' }}"></number-comma-component>
                    </div>
                    <div class="col">
                        <number-comma-component
                        number="{{ $discription->g_prizes->sum('remaining_count') }}"></number-comma-component>
                    </div>

                </div>
            </button>

            <!-- 登録商品 -->
            <div class="collapse my-3 showww"
            id="collapse{{$discription->id}}">
                <div class="card card-body overflow-auto bg-body border-0" style="">
                    @foreach ($discription->g_prizes as $g_prize)
                        <div class="row mb-2 text-end">
                            <div class="col-3">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <ratio-image-component
                                        style_class="ratio ratio-3x4 rounded-3 bg-"
                                        url="{{ $g_prize->prize->image_path }}"
                                        ></ratio-image-component>
                                    </div>
                                    <div class="col text-start">
                                        <div class="form-text">{{ $g_prize->prize->code }}</div>
                                        <div class="form-text">{{ $g_prize->prize->name }}</div>
                                        <div class="form-text">{{ $g_prize->prize->rank->name }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <number-comma-component
                                number="{{ $g_prize->max_count }}"></number-comma-component>
                            </div>
                            <div class="col">
                                @php
                                $ratio = $gacha->max_count
                                ? $g_prize->max_count/$gacha->max_count*100 :0;
                                @endphp
                                <span>{{ round( $ratio, 2) .'%' }}</span>
                            </div>
                            <div class="col">
                                <number-comma-component
                                number="{{ $g_prize->prize->point.'pt' }}"></number-comma-component>
                            </div>
                            <div class="col">
                                <number-comma-component
                                number="{{ $g_prize->remaining_count }}"></number-comma-component>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach
