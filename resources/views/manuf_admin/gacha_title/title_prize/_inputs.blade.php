<div >
    <div class="form-text mb-4">
        <span class="text-danger">＊</span>入力必須
    </div>


    <!--商品画像(image)-->
    <label class="d-block mb-4">
        <div class="form-label fw-bold">
            商品画像
            <span class="text-danger">＊</span>
        </div>

        <div class="form-text">*この登録画像は、ガチャのメイン画像として使用されます。</div>

        <div class="col-md-8">
            <read-image-file-100k-component
            img_path="{{ $title_prize->image_path }}"
            noimg_path="{{asset('storage/site/image/no_image.jpg')}}"
            style_class="ratio {{config('app.gacha_card_ratio')}} rounded-3 border"
            name="image"
            bg_size="contain"
            ></read-image-file-100k-component>
        </div>

        <!--error message-->
        @if ( $errors->has('image') )
            <div class="text-danger"> {{$errors->first('image')}} </div>
        @endif
    </label>



    <!--商品名(name)-->
    <label class="d-block mb-4">
        <div class="form-label fw-bold" >
            商品名<span class="text-danger">＊</span>
        </div>

        <encodedーinputtext-component
        id="name" name="name"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('name') ) : $title_prize->name }}"
        ></encodedーinputtext-component>


        <div class="form-text">*絵文字を使用することはできません</div>
        <!--error message-->
        @if ( $errors->has('name') )
            <div class="text-danger"> {{$errors->first('name')}} </div>
        @endif
    </label>



    <!--商品コード(code)-->
    <label class="d-block mb-4">
        <div class="form-label fw-bold" >
            商品コード<span class="text-danger">＊</span>
        </div>

        <input value="{{old('code', $title_prize->code??$title_prize->new_code )}}"
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
        <div class="form-label fw-bold" >
            評価ランク<span class="text-danger">＊</span>
        </div>


        <select class="form-select" name="rank_id">
            <option value="">選択してください</option>

            @foreach ($ranks as $rank)
                <option value="{{ $rank->id }}"
                @if($title_prize->rank_id == $rank->id) selected @endif
                @if(old('rank_id')  == $rank->id) selected @endif
                >{{ $rank->name }}</option>
            @endforeach

        </select>
        <!--error message-->
        @if ( $errors->has('rank_id') )
            <div class="text-danger"> {{$errors->first('rank_id')}} </div>
        @endif
    </label>



    <!--説明文(discription)-->
    <label class="d-block mb-4">
        <div class="form-label fw-bold" >
            説明文
            {{-- <span class="text-danger">＊</span> --}}
        </div>

        <encodedーtextarea-component
        name="discription" id="discription"
        style_class="form-control" rows="6"
        placeholder="商品の説明文を入力してください。"
        default_body="{{ $errors->all() ? urldecode( old('discription') ) : $title_prize->discription_text }}"
        ></encodedーtextarea-component>


        <!--error message-->
        @if ( $errors->has('discription') )
            <div class="text-danger"> {{$errors->first('discription')}} </div>
        @endif
    </label>



</div>
