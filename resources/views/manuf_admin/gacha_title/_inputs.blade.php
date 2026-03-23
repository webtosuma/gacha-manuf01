<div >
    <div class="form-text mb-4">
        <span class="text-danger">＊</span>入力必須
    </div>



    <!--タイトル名(name・default_name)-->
    <label id="name" class="d-block mb-5">
        <div class="form-label fw-bold">
            タイトル名
            <span class="text-danger">＊</span>
        </div>

        <encodedーinputtext-component
        id="name" name="name"
        style_class="form-control form-control-lg"
        default_body="{{ $errors->all() ? urldecode( old('name') ) : $gacha_title->name }}"
        ></encodedーinputtext-component>


        <!--error message(default_name)-->
        @if ( $errors->has('default_name') )
            <div class="text-danger"> {{$errors->first('default_name')}} </div>
        @endif
    </label>



    <!--カテゴリー(category_id)-->
    <label id="category_id" class="d-block mb-4">
        <div class="form-label fw-bold">
            カテゴリー
            <span class="text-danger">＊</span>
        </div>
        <select class="form-select" name="category_id">
            <option value="">選択してください</option>

            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                @if($gacha_title->category_id == $category->id) selected @endif
                @if(old('category_id')  == $category->id) selected @endif
                >{{ $category->name }}</option>
            @endforeach

        </select>
        <!--error message-->
        @if ( $errors->has('category_id') )
            <div class="text-danger"> {{$errors->first('category_id')}} </div>
        @endif
    </label>



    <!--価格(税込み)(price)-->
    <label id="price" class="d-block mb-4">
        <div class="form-label fw-bold">
            価格(税込み)
            <span class="text-danger">＊</span>
        </div>

        <div class="">
            <div class="d-flex align-items-end gap-2">
                <input value="{{old('price', $gacha_title->price ?? 0 )}}"
                name="price"
                style="width:8rem;"
                type="number" class="form-control form-control-lg text-end" min="0">
                <span>円(税込み)</span>
            </div>
        </div>

        <!--error message-->
        @if ( $errors->has('price') )
            <div class="text-danger"> {{$errors->first('price')}} </div>
        @endif
    </label>



    <!--サムネ画像(image_samune)-->
    <label id="image_samune" class="d-block mb-4">
        <div class="form-label fw-bold">
            サムネ画像
            <span class="text-danger">＊</span>
        </div>
        <div class="form-text">*この登録画像は、ガチャのメイン画像として使用されます。</div>

        <div class="col-md-8">
            <read-image-file-100k-component
            img_path="{{ $gacha_title->image_samune_path }}"
            noimg_path="{{asset('storage/site/image/no_image.jpg')}}"
            style_class="ratio {{config('app.gacha_card_ratio')}} rounded-3 border bg-body"
            name="image_samune"
            bg_size="contain"
            ></read-image-file-100k-component>
        </div>

        <!--error message-->
        @if ( $errors->has('image_samune') )
            <div class="text-danger"> {{$errors->first('image_samune')}} </div>
        @endif
    </label>



    <!--説明文(description default_description')-->
    <label id="description" class="d-block mb-4">
        <div class="form-label fw-bold">
            説明文
        </div>

        <encodedーtextarea-component
        name="description" id="description"
        style_class="form-control" rows="6"
        placeholder="ガチャタイトルの説明文を入力してください。"
        default_body="{{ $errors->all() ? urldecode( old('description') ) : $gacha_title->description_text }}"
        ></encodedーtextarea-component>


        <!--error message-->
        @if ( $errors->has('default_description') )
            <div class="text-danger"> {{$errors->first('default_description')}} </div>
        @endif
    </label>



    <!--セット内容(set_contents default_set_contents)-->
    <label id="set_contents" class="d-block mb-4">
        <div class="form-label fw-bold">
            セット内容
        </div>

        <encodedーtextarea-component
        name="set_contents" id="set_contents"
        style_class="form-control" rows="6"
        placeholder="セット内容を入力してください。"
        default_body="{{ $errors->all() ? urldecode( old('set_contents') ) : $gacha_title->set_contents_text }}"
        ></encodedーtextarea-component>


        <!--error message-->
        @if ( $errors->has('default_set_contents') )
            <div class="text-danger"> {{$errors->first('default_set_contents')}} </div>
        @endif
    </label>



    <!--商品サイズ(prize_size・default_prize_size)-->
    <label id="prize_size" class="d-block mb-4">
        <div class="form-label fw-bold">
            商品サイズ
        </div>

        <encodedーinputtext-component
        id="prize_size" name="prize_size"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('prize_size') ) : $gacha_title->prize_size }}"
        ></encodedーinputtext-component>


        <!--error message(default_prize_size)-->
        @if ( $errors->has('default_prize_size') )
            <div class="text-danger"> {{$errors->first('default_prize_size')}} </div>
        @endif
    </label>



    <!--商品素材(prize_materials・default_prize_materials)-->
    <label id="prize_materials" class="d-block mb-4">
        <div class="form-label fw-bold">
            商品素材
        </div>

        <encodedーinputtext-component
        id="prize_materials" name="prize_materials"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('prize_materials') ) : $gacha_title->prize_materials }}"
        ></encodedーinputtext-component>


        <!--error message(default_prize_materials)-->
        @if ( $errors->has('default_prize_materials') )
            <div class="text-danger"> {{$errors->first('default_prize_materials')}} </div>
        @endif
    </label>



    <!--対象年齢(age_range・default_age_range)-->
    <label id="age_range" class="d-block mb-4">
        <div class="form-label fw-bold">
            対象年齢
        </div>

        <encodedーinputtext-component
        id="age_range" name="age_range"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('age_range') ) : $gacha_title->age_range }}"
        ></encodedーinputtext-component>


        <!--error message(default_age_range)-->
        @if ( $errors->has('default_age_range') )
            <div class="text-danger"> {{$errors->first('default_age_range')}} </div>
        @endif
    </label>



    <!--コピーライト(copy_right・default_copy_right)-->
    <label id="copy_right" class="d-block mb-4">
        <div class="form-label fw-bold">
            コピーライト
        </div>

        <encodedーinputtext-component
        id="copy_right" name="copy_right"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('copy_right') ) : $gacha_title->copy_right }}"
        ></encodedーinputtext-component>


        <!--error message(default_copy_right)-->
        @if ( $errors->has('default_copy_right') )
            <div class="text-danger"> {{$errors->first('default_copy_right')}} </div>
        @endif
    </label>



</div>
