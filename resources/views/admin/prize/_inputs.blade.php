<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
   <input type="hidden" name="prize_id" value="{{$prize->id}}">


</div>
<div class="row">
    <div class="col-md">


        <div class="col-6 mx-auto">

            <!--商品画像(image)-->
            <label class="d-block mb-4">
                <div class="form-label">
                    商品画像<span class="text-danger">＊</span>
                </div>

                <read-image-file-component
                img_path="{{ $prize->image_path }}"
                noimg_path="{{asset('storage/site/image/no_image.jpg')}}"
                style_class="ratio ratio-3x4 rounded-3 shadow"
                name="image"
                ></read-image-file-component>

                <!--error message-->
                @if ( $errors->has('image') )
                    <div class="text-danger"> {{$errors->first('image')}} </div>
                @endif
            </label>


        </div>


    </div>
    <div class="col-md-6">
        <!--カテゴリー(category_id)-->
        <label class="d-block mb-4">
            <div class="form-label">
                カテゴリー<span class="text-danger">＊</span>
            </div>
            <select class="form-select" name="category_id">
                <option value="">選択してください</option>

                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                    @if($prize->category_id == $category->id) selected @endif
                    @if(old('category_id')  == $category->id) selected @endif
                    >{{ $category->name }}</option>
                @endforeach

            </select>
            <!--error message-->
            @if ( $errors->has('category_id') )
                <div class="text-danger"> {{$errors->first('category_id')}} </div>
            @endif
        </label>


        <!--商品名(name)-->
        <label class="d-block mb-4">
            <div class="form-label">
                商品名<span class="text-danger">＊</span>
            </div>

            <encodedーinputtext-component
            id="name" name="name"
            style_class="form-control"
            default_body="{{ $errors->all() ? urldecode( old('name') ) : $prize->name }}"
            ></encodedーinputtext-component>


            <div class="form-text">*絵文字を使用することはできません</div>
            <!--error message-->
            @if ( $errors->has('name') )
                <div class="text-danger"> {{$errors->first('name')}} </div>
            @endif
        </label>


        <!--商品コード(code)-->
        <label class="d-block mb-4">
            <div class="form-label">
                商品コード<span class="text-danger">＊</span>
            </div>

            <input value="{{old('code', $prize->code )}}"
            name="code"
            type="text" class="form-control">

            <div class="form-text">
                <div>*既に登録積みの商品コードを登録することはできません。</div>
                <div>
                    <div>*利用可能な文字</div>
                    <ul>
                        <li>英数字：a〜z、A〜Z、0〜9</li>
                        <li>記号：-（ハイフン）、_（アンダースコア）、*、+、.（ドット）、,（カンマ）、!、?、#、$、%、&、~、|、^、@、;、:、(、)、[、]、{、}、/</li>
                        <li>半角スペース</li>
                    </ul>
                </div>
            </div>
            <!--error message-->
            @if ( $errors->has('code') )
                <div class="text-danger"> {{$errors->first('code')}} </div>
            @endif
        </label>


        <!--ランク(rank_id)-->
        <label class="d-block mb-4">
            <div class="form-label">
                評価ランク<span class="text-danger">＊</span>
            </div>

            <select class="form-select" name="rank_id">
                <option value="">選択してください</option>

                @foreach ($ranks as $rank)
                    <option value="{{ $rank->id }}"
                    @if($prize->rank_id == $rank->id) selected @endif
                    @if(old('rank_id')  == $rank->id) selected @endif
                    >{{ $rank->name }}</option>
                @endforeach

            </select>
            <!--error message-->
            @if ( $errors->has('rank_id') )
                <div class="text-danger"> {{$errors->first('rank_id')}} </div>
            @endif
        </label>


        <!--交換ポイント(point)-->
        <label class="d-block mb-4">
            <div class="form-label">
                交換ポイント<span class="text-danger">＊</span>
            </div>

            <div class="col-4">
                <input value="{{old('point', $prize->point )}}"
                name="point"
                type="number" class="form-control" min="0">
            </div>

            <!--error message-->
            @if ( $errors->has('point') )
                <div class="text-danger"> {{$errors->first('point')}} </div>
            @endif
        </label>



        <!--説明文(discription)-->
        <label class="d-block mb-4">
            <div class="form-label">
                説明文
                {{-- <span class="text-danger">＊</span> --}}
            </div>

            <encodedーtextarea-component
            name="discription" id="discription"
            style_class="form-control" rows="6"
            placeholder="商品の説明文を入力してください。"
            default_body="{{ $errors->all() ? urldecode( old('discription') ) : $prize->discription_text }}"
            ></encodedーtextarea-component>


            <!--error message-->
            @if ( $errors->has('discription') )
                <div class="text-danger"> {{$errors->first('discription')}} </div>
            @endif
        </label>


        <div class="col-md-6 my-5">
            @if (!$prize->id)
            <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
            @else
            <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
            @endif
        </div>

    </div>
</div>
