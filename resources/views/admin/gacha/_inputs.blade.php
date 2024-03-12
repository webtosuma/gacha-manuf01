<div class="row">
    <div class="col-md">


        <div class="px-3">

             <!--トップ画像(image)-->
            <label class="d-block mb-4">
                <div class="form-label">トップ画像</div>
                <div class="form-text">*この登録画像は、ガチャのメイン画像として使用されます。</div>

                <read-image-file-component
                img_path="{{ $gacha->image_path }}"
                noimg_path="{{asset('storage/site/image/no_image.jpg')}}"
                style_class="ratio ratio-4x3 rounded-3"
                name="image"
                ></read-image-file-component>

                <!--error message-->
                @if ( $errors->has('image') )
                    <div class="text-danger"> {{$errors->first('image')}} </div>
                @endif
            </label>


            <!--カテゴリー(category_id)-->
            <label class="d-block mb-4">
                <div class="form-label">カテゴリー</div>
                <select class="form-select" name="category_id">
                    <option value="">選択してください</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                        @if($gacha->category_id == $category->id) selected @endif
                        @if(old('category_id')  == $category->id) selected @endif
                        >{{ $category->name }}</option>
                    @endforeach

                </select>
                <!--error message-->
                @if ( $errors->has('category_id') )
                    <div class="text-danger"> {{$errors->first('category_id')}} </div>
                @endif
            </label>

            <!--ガチャ名(name)-->
            <label class="d-block mb-4">
                <div class="form-label">ガチャ名</div>

                <input value="{{old('name', $gacha->name )}}"
                name="name"
                type="text" class="form-control">
                <!--error message-->
                @if ( $errors->has('name') )
                    <div class="text-danger"> {{$errors->first('name')}} </div>
                @endif
            </label>

            <!--1回のガチャに必要なポイント(one_play_point)-->
            <label class="d-block mb-4">
                <div class="form-label">1回のガチャに必要なポイント</div>

                <div class=" col-4">
                    <div class="d-flex align-items-center gap-2">
                        <input value="{{old('one_play_point', $gacha->one_play_point ?? 0 )}}"
                        name="one_play_point"
                        type="number" class="form-control" min="0">
                        <span>pt</span>
                    </div>
                </div>

                <!--error message-->
                @if ( $errors->has('one_play_point') )
                    <div class="text-danger"> {{$errors->first('one_play_point')}} </div>
                @endif
            </label>


        </div>


    </div>
    <div class="col-md-6">

        <!--ガチャの種類(type)-->
        <div class="d-block mb-4">
            <div class="form-label">ガチャの種類</div>

            <div class="card p-2 mx-2">
                <div class="form-text">ガチャの種類を選択してください。</div>
                <div class="d-flex flex-column gap-3 ps-3">
                    @foreach ($gacha->types() as $value => $lable)
                        <label class="form-check">
                            <input name="type" value="{{$value}}"
                            @if( $value == $gacha->type ) checked @endif
                            class="form-check-input" type="radio">
                            <div class="form-check-div">{{ $lable }}</div>
                        </label>
                    @endforeach
                </div>

                <!--error message-->
                @if ( $errors->has('type') )
                    <div class="text-danger"> {{$errors->first('type')}} </div>
                @endif
            </div>
        </div>


        <!--会員ランクの指定(user_rank_id)-->
        <div class="d-block mb-4">
            <div class="form-label">会員ランクの指定</div>


            <div class="px-2">
                <select class="form-select" name="user_rank_id">

                    <option value=""
                    @if( $gacha->user_rank_id === null ) selected @endif
                    >{{ '全ての会員' }}</option>


                    @foreach ($user_ranks as $id => $user_rank)
                        <option value="{{$id}}" value="1"
                        @if( $gacha->user_rank_id !=='' && $gacha->user_rank_id === $id  ) selected @endif
                        >{{ $user_rank['label'] }}</option>
                    @endforeach

                </select>
            </div>


            <!--error message-->
            @if ( $errors->has('user_rank_id') )
                <div class="text-danger"> {{$errors->first('user_rank_id')}} </div>
            @endif
        </div>



        <!--残数メーター表示設定(is_meter)-->
        <div class="d-block mb-4">
            <div class="form-label">残数メーター表示設定</div>

            <div class="card p-2 mx-2">

                <div class="d-flex flex-column gap-3 ps-4">
                    <div class="form-text">残数メーターを表示させますか？</div>
                    <label class="form-check">
                        <input name="is_meter" value="1"
                        {{ $gacha->is_meter==true ? 'checked' : '' }}
                        class="form-check-input" type="radio">
                        <div class="form-check-div">表示させる</div>
                    </label>
                    <label class="form-check">
                        <input name="is_meter" value="0"
                        {{ $gacha->is_meter==false ? 'checked' : '' }}
                        class="form-check-input" type="radio">
                        <div class="form-check-div">表示させない</div>
                    </label>
                </div>

                <!--error message-->
                @if ( $errors->has('is_meter') )
                    <div class="text-danger"> {{$errors->first('is_meter')}} </div>
                @endif

            </div>
        </div>


        <!--スライド設定(is_slide-->
        <div class="d-block mb-4">
            <div class="form-label">スライド設定</div>

            <div class="card p-2 mx-2">

                <div class="d-flex flex-column gap-3 ps-4">
                    <div class="form-text">トップページのスライドに表示させますか？</div>
                    <label class="form-check">
                        <input name="is_slide" value="1"
                        {{ $gacha->is_slide==true ? 'checked' : '' }}
                        class="form-check-input" type="radio">
                        <div class="form-check-div">表示させる</div>
                    </label>
                    <label class="form-check">
                        <input name="is_slide" value="0"
                        {{ $gacha->is_slide==false ? 'checked' : '' }}
                        class="form-check-input" type="radio">
                        <div class="form-check-div">表示させない</div>
                    </label>
                </div>

                <!--error message-->
                @if ( $errors->has('is_slide') )
                    <div class="text-danger"> {{$errors->first('is_slide')}} </div>
                @endif

            </div>
        </div>



        <div class="col-md-6 my-5">
            @if (!$gacha->id)
            <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
            @else
            <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
            @endif
        </div>


    </div>
</div>
