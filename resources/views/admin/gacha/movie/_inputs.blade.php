@foreach ($gacha->discriptions as $num => $discription)
    @if ( $discription->gacha_rank_id<900)

        <div class="mx-3 py-2 border-bottom">
            <button class="btn w-100 text-start" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapse{{$discription->id}}" aria-expanded="false" aria-controls="collapse{{$discription->id}}">
                <div class="row">
                    <div class="col dropdown-toggle">{{ $discription->rank_label }}</div>


                    <div class="col-auto">登録動画数：{{ $discription->movies->count() }}</div>
                    <div class="col-auto">
                        @if ( $discription->total_count_format )
                            <span class="badge rounded-pill bg-success">商品登録あり</span>
                        @else
                            <span class="badge rounded-pill bg-secondary">商品登録なし</span>
                        @endif

                    </div>
                </div>
            </button>
            <div class="collapse mb-3
            @if($discription->total_count_format) show @endif "
            id="collapse{{$discription->id}}">
                <div class="card card-body overflow-auto bg-body" style="">
                    <div class="form-text">動画選択</div>
                    <div class="row">
                        @foreach ($movies as $movie)
                        <div class="col-3">
                            <label class="form-check">
                                <input name="{{'gri'.$discription->gacha_rank_id.'-movie_ids[]'}}"
                                class="form-check-input" type="checkbox" value="{{ $movie->id }}"
                                @if( in_array( $movie->id, $discription->movies->pluck('id')->toArray() ) ) checked @endif
                                >
                                <div>{{ $movie->name }}</div>
                            </label>
                        </div>
                        @endforeach
                    </div>


                    {{-- @foreach ($movies as $movie)
                        <div class="row py-2 border-bottom align-items-center">
                            <div class="col">
                                <label class="form-check">
                                    <input name="{{'gri'.$discription->gacha_rank_id.'-movie_ids[]'}}"
                                    class="form-check-input" type="checkbox" value="{{ $movie->id }}"
                                    @if( in_array( $movie->id, $discription->movies->pluck('id')->toArray() ) ) checked @endif
                                    >
                                    <div>{{ $movie->name }}</div>
                                </label>
                            </div>
                            <div class="col-auto">
                                <!-- PC用 -->
                                <movie-modal-component
                                id   ="{{$movie->id.'-pc'}}"
                                title="{{ $movie->name.'（PC）' }}"
                                src  ="{{ $movie->pc }}"
                                btn_label="PC用動画再生"
                                max_width="800px"
                                ></movie-modal-component>
                            </div>
                            <div class="col-auto">
                                <!-- モバイル用 -->
                                <movie-modal-component
                                id   ="{{$movie->id.'-mobile'}}"
                                title="{{ $movie->name.'（モバイル）' }}"
                                src  ="{{ $movie->mobile }}"
                                btn_label="モバイル用動画再生"
                                max_width="400px"
                                ></movie-modal-component>
                            </div>
                        </div>
                    @endforeach --}}
                </div>
            </div>
        </div>

    @endif
@endforeach
