@foreach ($gacha->discriptions as $num => $discription)

    <div class="mx-3 py-2 border-bottom">
        <button class="btn w-100 text-start" type="button"
        data-bs-toggle="collapse" data-bs-target="#collapse{{$discription->id}}" aria-expanded="false" aria-controls="collapse{{$discription->id}}">
            <div class="row">
                <div class="col dropdown-toggle">{{ $discription->rank_label }}</div>


                <div class="col-auto">登録動画数：{{ $discription->movies->count() }}</div>
                <div class="col-auto">
                    @if ( $discription->g_prizes->sum('max_count') > 0 )
                        <span class="badge rounded-pill bg-success">商品登録あり</span>
                    @else
                        <span class="badge rounded-pill bg-secondary">商品登録なし</span>
                    @endif

                </div>
            </div>
        </button>
        <div class="collapse mb-3  @if(!$num) showww @endif" id="collapse{{$discription->id}}">
            <div class="card card-body overflow-auto bg-body" style="">
                <div class="form-text">動画選択</div>
                @foreach ($movies as $movie)

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
                            @include('admin.movie.pc_movie_modal')
                        </div>
                        <div class="col-auto">
                            <!-- モバイル用 -->
                            @include('admin.movie.mobile_movie_modal')
                        </div>
                    </div>

                @endforeach
            </div>
        </div>
    </div>

@endforeach
