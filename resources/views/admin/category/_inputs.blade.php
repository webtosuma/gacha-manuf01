{{-- コード重複許可用 --}}
<input type="hidden" name="gacha_category_id" value="{{$gacha_category->id}}">

<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>
<div class="row">
    <div class="col-md-4">


        <div class="col-8 mx-auto">
            <!--背景画像(bg_image)-->
            <label class="d-block mb-4">
                <div class="form-label">背景画像</div>

                <read-image-file-component
                img_path="{{ $gacha_category->bg_image_path }}"
                noimg_path="{{$gacha_category->noImage()}}"
                style_class="ratio ratio-3x4 rounded-3 border"
                name="bg_image"
                ></read-image-file-component>

                <!--error message-->
                @if ( $errors->has('bg_image') )
                    <div class="text-danger"> {{$errors->first('bg_image')}} </div>
                @endif
            </label>
        </div>


    </div>
    <div class="col-md-6">

        <!--カテゴリー名(name)-->
        <label class="d-block mb-4">
            <div class="form-label">
                カテゴリー名
                <span class="text-danger">＊</span>
            </div>

            <input value="{{old('name', $gacha_category->name )}}"
            name="name"
            type="text" class="form-control">

            <!--error message-->
            @if ( $errors->has('name') )
                <div class="text-danger"> {{$errors->first('name')}} </div>
            @endif
        </label>

        <!--コード(code_name)-->
        <label class="d-block mb-4">
            <div class="form-label">
                コード
                <span class="text-danger">＊</span>
            </div>
            <div class="form-text">
                半角小文字英字・数字・記号「-」「_」のみ入力可
            </div>

            <input value="{{old('code_name', $gacha_category->code_name )}}"
            name="code_name"
            type="text" class="form-control">

            <!--error message-->
            @if ( $errors->has('code_name') )
                <div class="text-danger"> {{$errors->first('code_name')}} </div>
            @endif
        </label>


        <!--公開設定(is_published)-->
        <div class="d-block mb-5">
            <div class="form-label">
                公開設定
                <span class="text-danger">＊</span>
            </div>

            <div class="px-">
                @if( env('LIMIT_GACHA_COUNT') )
                    <!--カテゴリー数制限あり-->
                    <div class="alert alert-success border-0" role="alert">
                        <h6 class="fw-bold text-success">カテゴリー数数の制限あり</h6>
                        <span class="fw-bold">公開</span>できるカテゴリー数は、合わせて<span class="fw-bold">{{$limit}}件</span>以内です。(「すべて」は含まれません。)
                    </div>
                @endif


                <!-- 公開 -->
                <label class="card p-2 mb-3"
                @if ( $restriction ) style="opacity: .5;" @endif
                >
                    <div class="form-check">
                        <input name="is_published" value="1" type="radio" class="form-check-input"
                        @if ( $restriction ) disabled @endif
                        {{ $gacha_category->is_published ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">公開</h6>

                    </div>
                    <ul class="form-text m-0">
                        <li>カテゴリーを公開します。</li>
                    </ul>
                </label>


                <!-- 非公開 -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="0"
                        type="radio" id="publishedType3" class="form-check-input"
                        {{ !$gacha_category->is_published ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">非公開</h6>
                    </div>
                    <ul class="form-text m-0">
                        <li>カテゴリーを非公開にします。</li>
                        <li>非公開時はカテゴリー一覧に表示されなくなります。</li>
                    </ul>
                </label>
            </div>


        </div>


        <div class="col-md-6 my-5">
            @if (!$gacha_category->id)
            <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
            @else
            <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
            @endif
        </div>

    </div>
</div>
