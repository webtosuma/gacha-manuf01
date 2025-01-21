<div class="mx-3 py-2 border-bottom row text-end">
    <div class="col-3"></div>
    <div class="col">口数</div>
    <div class="col">当選率</div>
    <div class="col">平均PT</div>
    <div class="col">残数</div>
</div>
@foreach ($gacha->discriptions as $num => $discription)
    {{-- @if ( $discription->g_prizes->sum('max_count') > 0 ) --}}
    @if (  $discription->g_prizes->count() > 0 )
        <div class="mx-3 py-2 border-bottom">

            <!-- ランク情報 -->
            <button class="btn w-100 text-start" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapse{{$discription->id}}" aria-expanded="false" aria-controls="collapse{{$discription->id}}">
                <div class="row align-items-center text-end">

                    <div class="col-3 dropdown-toggle text-start">
                        <!--ランク表示-->
                        <span class="fs-5">{{ $discription->rank_label }}</span>
                    </div>


                    <div class="col">
                        <!--口数-->
                        {{ $discription->total_count_format }}
                    </div>
                    <div class="col">
                        <!--当選率-->
                        {{  $discription->winning_ratio_format }}
                    </div>
                    <div class="col">
                        <!--平均PT-->
                        {{ $discription->average_point_format }}
                    </div>
                    <div class="col">
                        <!--残数-->
                        {{ $discription->remaining_count_format }}
                    </div>

                </div>
            </button>

            <!-- 登録商品 -->
            <div class="collapse my-3
            @if($discription->gacha_rank_id>300 && $discription->gacha_rank_id<400 && $discription->g_prizes->count()>0 ) showww @endif
            "
            id="collapse{{$discription->id}}">

            @if( $discription->gacha_rank_id>300 && $discription->gacha_rank_id<400 && $discription->g_prizes->count()>0 )
                <div class="rounded bg-light p-2 mb-2">
                    当選番号：{{ $discription->hit_nums }}
                </div>
            @endif

                <div class="card card-body overflow-auto bg-body border-0" style="">
                    @foreach ($discription->g_prizes as $g_prize)
                        <div class="row mb-2 text-end">
                            <div class="col-3">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <ratio-image-component
                                        style_class="ratio ratio-3x4 rounded-3 bg-danger"
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
                                <!--口数-->
                                {{ $g_prize->total_count_format }}
                            </div>
                            <div class="col">
                                <!--当選率-->
                                {{  $g_prize->winning_ratio_format }}
                            </div>
                            <div class="col">
                                <!--平均PT-->
                                <number-comma-component
                                number="{{ $g_prize->prize->point.'pt' }}"></number-comma-component>
                            </div>
                            <div class="col">
                                <!--残数-->
                                {{ $g_prize->remaining_count_format }}
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach
