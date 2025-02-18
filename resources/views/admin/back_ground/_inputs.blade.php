
<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>
<div class="row gx-5 justify-content-center">


    <!--トップページ背景画像(bg_top)-->
    <div class="col-6 col-md-4">
        <label class="d-block mb-4">
            <div class="form-label">トップページ背景画像</div>

            <read-image-file-component
            img_path="{{ asset('storage/'.$bg_top) }}"
            noimg_path=""
            style_class="ratio ratio-3x4 rounded-3 border"
            name="bg_top"
            ></read-image-file-component>

            <!--error message-->
            @if ( $errors->has('bg_top') )
                <div class="text-danger"> {{$errors->first('bg_top')}} </div>
            @endif

            <div class="mt-3 bg-body rounded p-2 form-text">
                トップページの「全てのガチャ一覧」の背景に利用する画像です
            </div>
        </label>
    </div>


    <!--サブページ背景画像(bg_sub)-->
    <div class="col-6 col-md-4">
        <label class="d-block mb-4">
            <div class="form-label">サブページ背景画像</div>

            <read-image-file-component
            img_path="{{ asset('storage/'.$bg_sub) }}"
            noimg_path=""
            style_class="ratio ratio-3x4 rounded-3 border"
            name="bg_sub"
            ></read-image-file-component>

            <!--error message-->
            @if ( $errors->has('bg_sub') )
                <div class="text-danger"> {{$errors->first('bg_sub')}} </div>
            @endif

            <div class="mt-3 bg-body rounded p-2 form-text">
                「取得商品一覧」、「履歴」、「利用規約」などの背景に利用する画像です。
            </div>
        </label>
    </div>


    <!--ガチャ結果背景画像(bg_result)-->
    <div class="col-6 col-md-4">
        <label class="d-block mb-4">
            <div class="form-label">ガチャ結果背景画像</div>

            <read-image-file-component
            img_path="{{ asset('storage/'.$bg_result) }}"
            noimg_path=""
            style_class="ratio ratio-3x4 rounded-3 border"
            name="bg_result"
            ></read-image-file-component>

            <!--error message-->
            @if ( $errors->has('bg_result') )
                <div class="text-danger"> {{$errors->first('bg_result')}} </div>
            @endif

            <div class="mt-3 bg-body rounded p-2 form-text">
                「ガチャ結果」の背景に利用する画像です。
            </div>
        </label>
    </div>


    <div class="col-12 col-md-6  my-5">
        <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
    </div>

</div>
