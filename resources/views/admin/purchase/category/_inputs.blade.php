{{-- コード重複許可用 --}}
<input type="hidden" name="gacha_category_id" value="{{$purchase_category->id}}">

<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>
<div class="row">
    <div class="col-md-6">

        <!--カテゴリー名(name)-->
        <label class="d-block mb-4">
            <div class="form-label">
                カテゴリー名
                <span class="text-danger">＊</span>
            </div>

            <input value="{{old('name', $purchase_category->name )}}"
            name="name"
            type="text" class="form-control">

            <!--error message-->
            @if ( $errors->has('name') )
                <div class="text-danger"> {{$errors->first('name')}} </div>
            @endif
        </label>



        <!--公開設定(is_published)-->
        <div class="d-block mb-5">
            <div class="form-label">
                公開設定
                <span class="text-danger">＊</span>
            </div>

            <div class="px-">

                <!-- 公開 -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="1" type="radio" class="form-check-input"
                        {{ $purchase_category->is_published ? 'checked' : ''}}
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
                        {{ !$purchase_category->is_published ? 'checked' : ''}}
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
            @if (!$purchase_category->id)
            <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
            @else
            <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
            @endif
        </div>

    </div>
</div>
