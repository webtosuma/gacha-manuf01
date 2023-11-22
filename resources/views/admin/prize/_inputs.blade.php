<div class="row">
    <div class="col-md">


        <div class="col-6 mx-auto">
            <!--商品画像(image)-->
            <label class="d-block mb-4">
                <div class="form-label">商品画像</div>

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
            <div class="form-label">カテゴリー</div>
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


        <!--商品コード(code)-->
        <label class="d-block mb-4">
            <div class="form-label">商品コード</div>

            <input value="{{old('code', $prize->code )}}"
            name="code"
            type="text" class="form-control">
            <!--error message-->
            @if ( $errors->has('code') )
                <div class="text-danger"> {{$errors->first('code')}} </div>
            @endif
        </label>

        <!--商品名(name)-->
        <label class="d-block mb-4">
            <div class="form-label">商品名</div>

            <input value="{{old('name', $prize->name )}}"
            name="name"
            type="text" class="form-control" >
            {{-- <div class="form-text">ユーザーには表示されない、管理用の名前です。</div> --}}
            <!--error message-->
            @if ( $errors->has('name') )
                <div class="text-danger"> {{$errors->first('name')}} </div>
            @endif
        </label>


        <!--ランク(rank_id)-->
        <label class="d-block mb-4">
            <div class="form-label">評価ランク</div>
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
        <label class="d-block mb-4 col-4">
            <div class="form-label">交換ポイント</div>

            <input value="{{old('point', $prize->point )}}"
            name="point"
            type="number" class="form-control" min="0">
            <!--error message-->
            @if ( $errors->has('point') )
                <div class="text-danger"> {{$errors->first('point')}} </div>
            @endif
        </label>


        <div class="col-md-6 my-5">
            @if (!$prize->id)
            <disabled-button style_class="btn btn-primary text-white w-100" btn_text="登録する"></button>
            @else
            <disabled-button style_class="btn btn-warning text-white w-100" btn_text="更新する"></button>
            @endif
        </div>


    </div>
</div>
