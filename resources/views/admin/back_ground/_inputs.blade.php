
<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>
<div class="row gx-5 justify-content-">


    <!--トップページ背景画像(bg_top)-->
    <div class="col-6 col-md-4">
        <label class="d-block mb-4">
            <div class="form-label">トップページ背景画像</div>

            <read-image-file-component
            img_path="{{ asset('storage/'.$bg_top) }}"
            noimg_path=""
            style_class="ratio ratio-1x1 rounded-3 border"
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
            style_class="ratio ratio-1x1 rounded-3 border"
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
            style_class="ratio ratio-1x1 rounded-3 border"
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


    <!--ECページ背景画像(bg_ec)-->
    @if( config('store.admin') )
    <div class="col-6 col-md-4">
        <label class="d-block mb-4">
            <div class="form-label">ECページ背景画像</div>

            <read-image-file-component
            img_path="{{ asset('storage/'.$bg_ec) }}"
            noimg_path=""
            style_class="ratio ratio-1x1 rounded-3 border"
            name="bg_ec"
            ></read-image-file-component>

            <!--error message-->
            @if ( $errors->has('bg_ec') )
                <div class="text-danger"> {{$errors->first('bg_ec')}} </div>
            @endif

            <div class="mt-3 bg-body rounded p-2 form-text">
                トップページの「全てのガチャ一覧」の背景に利用する画像です
            </div>
        </label>
    </div>
    @endif

    <!--イベントページ背景画像(bg_event)-->
    @if( config('app.event_gacha') )
    <div class="col-6 col-md-4">
        <label class="d-block mb-4">
            <div class="form-label">イベントページ背景画像</div>

            <read-image-file-component
            img_path="{{ asset('storage/'.$bg_event) }}"
            noimg_path=""
            style_class="ratio ratio-1x1 rounded-3 border"
            name="bg_event"
            ></read-image-file-component>

            <!--error message-->
            @if ( $errors->has('bg_event') )
                <div class="text-danger"> {{$errors->first('bg_event')}} </div>
            @endif

            <div class="mt-3 bg-body rounded p-2 form-text">
                トップページの「全てのガチャ一覧」の背景に利用する画像です
            </div>
        </label>
    </div>
    @endif



    <div class="col-12  my-5">
        <div class="col-md-6 mx-auto">
            <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
        </div>
    </div>

</div>
