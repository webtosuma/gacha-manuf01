<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>
<div class="row justify-content-center px-3 g-5">
    <div class="col-lg-6">

        <!--タイトル(title)-->
        <label class="d-block mb-4">
            <div class="form-label">
                タイトル
                <span class="text-danger">＊</span>
            </div>

            {{-- <input value="{{old('title', $infomation->title )}}"
            name="title"
            type="text" class="form-control"> --}}

            <encodedーinputtext-component
            id="title" name="title"
            style_class="form-control"
            default_body="{{ $errors->all() ? urldecode( old('title') ) : $infomation->title }}"
            ></encodedーinputtext-component>

            <!--error message-->
            @if ( $errors->has('title') )
                <div class="text-danger"> {{$errors->first('title')}} </div>
            @endif
        </label>


        <!--イメージ画像(image)-->
        <label class="d-block col-md-12 mx-auto mb-4">
            <div class="form-label">イメージ画像</div>

            <div class="px-3">
                <read-image-file-component
                img_path="{{ $infomation->image_path }}"
                noimg_path="{{asset('storage/site/image/no_image.jpg')}}"
                style_class="ratio {{config('app.gacha_card_ratio')}} rounded-3"
                name="image"
                ></read-image-file-component>
            </div>

            <!--error message-->
            @if ( $errors->has('image') )
                <div class="text-danger"> {{$errors->first('image')}} </div>
            @endif
        </label>


        <!--本文(body)-->
        <label class="d-block mb-4">
            <div class="form-label">
                本文
                <span class="text-danger">＊</span>
            </div>

            {{-- <textarea name="body"
            class="form-control" style="height:10rem;"
            placeholder="お知らせ本文を入力してください。"
            >{{ $infomation->body }}</textarea> --}}

            <encodedーtextarea-component
            name="body" id="body"
            style_class="form-control" rows="6"
            placeholder="お知らせ本文を入力してください。"
            default_body="{{ $errors->all() ? urldecode( old('body') ) : $infomation->body_text }}"
            ></encodedーtextarea-component>


            <!--error message-->
            @if ( $errors->has('body') )
                <div class="text-danger"> {{$errors->first('body')}} </div>
            @endif
        </label>



    </div>
    <div class="col-lg-4">


        <!--公開設定(is_published)-->
        <div class="d-block mb-5">
            <div class="form-label">
                公開設定
                <span class="text-danger">＊</span>
            </div>

            <div class="px-4">
                <!-- 公開 -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="1" type="radio" class="form-check-input"
                        {{ $infomation->is_published ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">公開</h6>

                    </div>
                    <ul class="form-text m-0">
                        <li>お知らせを公開します。</li>
                    </ul>
                    @if( $infomation->is_published ) <div class="form-text">
                        {{\Carbon\Carbon::parse($infomation->published_at)->format('公開日：Y年m月d日 H:i')}}
                    </div> @endif
                </label>


                <!-- 公開予約 -->
                <label class="card p-2 mb-3">
                    <div class="form-check ">
                        <input name="is_published" value="2" type="radio" class="form-check-input"
                        {{ !$infomation->is_published && $infomation->published_at ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">公開予約</h6>
                    </div>
                    <ul class="form-text m-0 pe-3">
                        <li>お知らせを公開予約します。</li>
                        <li>「公開予約日時」を設定すると、指定日時にお知らせを自動公開することができます。</li>
                    </ul>

                    <div class="input-group mb-3 px-3">
                        <span class="input-group-text" >公開予約日時</span>
                        <input name="published_at"
                        type="datetime-local" class="form-control"
                        value="{{ $infomation->published_at ? $infomation->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\T00:00') }}"
                        min="{{ now()->format('Y-m-d\T00:00') }}">

                    </div>
                </label>


                <!-- 非公開 -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="0"
                        type="radio" id="publishedType3" class="form-check-input"
                        {{ !$infomation->published_at ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">非公開</h6>
                    </div>
                    <ul class="form-text m-0">
                        <li>お知らせを非公開します。</li>
                        <li>非公開時はお知らせ一覧にお知らせが表示されなくなります。</li>
                    </ul>
                </label>
            </div>
        </div>


        <!--スライド設定(is_slide-->
        <div class="d-block mb-5">
            <div class="form-label">
                スライド設定
                <span class="text-danger">＊</span>
            </div>

            <div class="card p-2 mx-4">

                <div class="d-flex flex-column gap-3 ps-4">
                    <div class="form-text">トップページのスライドに表示させますか？</div>
                    <label class="form-check">
                        <input name="is_slide" value="1"
                        {{ $infomation->is_slide==true ? 'checked' : '' }}
                        class="form-check-input" type="radio">
                        <div class="form-check-div">表示させる</div>
                    </label>
                    <label class="form-check">
                        <input name="is_slide" value="0"
                        {{ $infomation->is_slide==false ? 'checked' : '' }}
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




    </div>
    <div class="col-12">
        <div class="col-md-6 mx-auto my-5">
            @if (!$infomation->id)
            <disabled-button style_class="btn btn-lg btn-primary text-white w-100 shadow" btn_text="登録する"></disabled-button>
            @else
            <disabled-button style_class="btn btn-lg btn-warning text-white w-100 shadow" btn_text="更新する"></disabled-button>
            @endif
        </div>
    </div>
</div>
