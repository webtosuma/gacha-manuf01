<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>
<div class="row">
    <div class="col-md">



        <!--広告タイトル(title)-->
        <label class="d-block mb-4">
            <div class="form-label">
                広告タイトル
                <span class="text-danger">＊</span>
            </div>

            <div class="input-group mb-3">
                <input  value="{{old('title', $sponsor_ad->title )}}"
                name="title"
                placeholder="広告のタイトルを入力してください。"
                type="text" class="form-control">
                {{-- <span class="input-group-text">円</span> --}}
            </div>

            <!--error message-->
            @if ( $errors->has('title') )
                <div class="text-danger"> {{$errors->first('title')}} </div>
            @endif
        </label>


        <!--スポンサー選択(sponsor_id)-->
        <label class="d-block mb-4">
            <div class="form-label">
                スポンサー選択
                <span class="text-danger">＊</span>
            </div>

            <div class="input-group mb-3">
                <select class="form-select" name="sponsor_id">
                    <option value="">選択してください</option>

                    @foreach ($selects['sponsors'] as $sponsor)
                        <option value="{{ $sponsor->id }}"
                        @if($sponsor_ad->sponsor_id == $sponsor->id) selected @endif
                        @if(old('sponsor_id')  == $sponsor->id) selected @endif
                        >{{ $sponsor->name }}</option>
                    @endforeach

                </select>
            </div>

            <!--error message-->
            @if ( $errors->has('sponsor_id') )
                <div class="text-danger"> {{$errors->first('sponsor_id')}} </div>
            @endif
        </label>


        <!--ガチャの選択(gacha_id)-->
        <label class="d-block mb-4">
            <div class="form-label">
                ガチャの選択
                <span class="text-danger">＊</span>
            </div>


            <div class="input-group mb-3">
                <select class="form-select" name="gacha_id">
                    <option value="">選択してください</option>

                    @foreach ($selects['categories'] as $category)
                        <optgroup label="{{$category->name}}">


                            @foreach ($category->gachas as $gacha)
                                @if( ! ($gacha->sponsor_ad && $gacha->id!=$sponsor_ad->gacha_id ) )

                                    <!-- 他で使われているガチャの利用不可 -->
                                    <option value="{{ $gacha->id }}"
                                    @if($sponsor_ad->gacha_id == $gacha->id) selected @endif
                                    @if(old('gacha_id')  == $gacha->id) selected @endif
                                    >{{ $gacha->name }}</option>


                                @endif
                            @endforeach


                        </optgroup>
                    @endforeach

                </select>
            </div>

            <!--error message-->
            @if ( $errors->has('gacha_id') )
                <div class="text-danger"> {{$errors->first('gacha_id')}} </div>
            @endif
        </label>


        <!--サイトURL(url)-->
        <label class="d-block mb-4">
            <div class="form-label">
                サイトURL
                {{-- <span class="text-danger">＊</span> --}}
            </div>

            <div class="input-group mb-3">
                <input  value="{{old('url', $sponsor_ad->url )}}"
                name="url"
                placeholder="動画広告からリダイレクトするURLを入力してください。"
                type="text" class="form-control">
                {{-- <span class="input-group-text">円</span> --}}
            </div>

            <!--error message-->
            @if ( $errors->has('url') )
                <div class="text-danger"> {{$errors->first('url')}} </div>
            @endif
        </label>

    </div>
    <div class="col-md-6">


        <!--広告動画(movie)-->
        <label class="d-block mb-3 col-6 mx-auto">
            <div class="form-label">
                広告動画
                <span class="text-danger">＊</span>
            </div>

            <read-movie-file-component
            name="movie"
            video_path="{{ $sponsor_ad->movie_path }}"
            ></read-movie-file-component>

            <!--error message-->
            @if ( $errors->has('movie') )
                <div class="text-danger"> {{$errors->first('movie')}} </div>
            @endif
        </label>


        <div class="col-md-6 my-5 mx-auto">
            @if (!$sponsor_ad->id)
            <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
            @else
            <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
            @endif
        </div>

    </div>
</div>
