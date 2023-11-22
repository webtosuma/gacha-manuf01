<div class="row">
    <div class="col-md">


        <div class="px-3">
             <!--トップ画像(image)-->
            <label class="d-block mb-4">
                <div class="form-label">トップ画像</div>

                <read-image-file-component
                img_path="{{ $gacha->image_path }}"
                noimg_path="{{asset('storage/site/image/no_image.jpg')}}"
                style_class="ratio ratio-16x9 rounded-3"
                name="image"
                ></read-image-file-component>
                <div class="form-text">*この登録画像は、ガチャのメイン画像として使用されます。</div>

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
                <input value="{{old('one_play_point', $gacha->one_play_point )}}"
                name="one_play_point"
                type="number" class="form-control" min="0">
            </div>

            <!--error message-->
            @if ( $errors->has('one_play_point') )
                <div class="text-danger"> {{$errors->first('one_play_point')}} </div>
            @endif
        </label>


        <div class="col-md-6 my-5">
            @if (!$gacha->id)
            <disabled-button style_class="btn btn-primary text-white w-100" btn_text="登録する"></button>
            @else
            <disabled-button style_class="btn btn-warning text-white w-100" btn_text="更新する"></button>
            @endif
        </div>


    </div>
</div>
