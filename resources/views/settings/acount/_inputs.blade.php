<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>

<div class="row">
    <div class="col">


            <!--アカウント画像(image)-->
        <label class="d-block mb-4">
            <div class="form-label">アカウント画像</div>

            <div class="p-3 col-8 mx-auto">
                <read-image-file-component
                img_path="{{ Auth::user()->image_path }}"
                noimg_path="{{asset('storage/site/image/user_no_image.png')}}"
                style_class="ratio ratio-1x1 rounded-pill border"
                name="image"
                ></read-image-file-component>
            </div>

            <!--error message-->
            @if ( $errors->has('image') )
                <div class="text-danger"> {{$errors->first('image')}} </div>
            @endif
        </label>


    </div>
    <div class="col-md-8">
        <!--アカウント名(name)-->
        <label class="d-block mb-4">
            <div class="form-label">
                アカウント名
                <span class="text-danger">＊</span>
            </div>

            <input value="{{old('name', Auth::user()->name )}}"
            name="name"
            type="text" class="form-control">
            <!--error message-->
            @if ( $errors->has('name') )
                <div class="text-danger"> {{$errors->first('name')}} </div>
            @endif
        </label>

        <!--メールアドレス(email)-->
        <label class="d-block mb-4">
            <div class="form-label">
                メールアドレス
                <span class="text-danger">＊</span>
            </div>

            <input value="{{old('email', Auth::user()->email )}}"
            name="email"
            type="text" class="form-control">
            <!--error message-->
            @if ( $errors->has('email') )
                <div class="text-danger"> {{$errors->first('email')}} </div>
            @endif
        </label>


        <!--X(旧twitter)ID(twitter_id)-->
        <label class="d-block mb-4">
            <div class="form-label">X(旧twitter)ID</div>

            <input value="{{old('twitter_id', Auth::user()->twitter_id )}}"
            name="twitter_id"
            placeholder="@○○○○○"
            type="text" class="form-control">
            <!--error message-->
            @if ( $errors->has('twitter_id') )
                <div class="text-danger"> {{$errors->first('twitter_id')}} </div>
            @endif
        </label>

    </div>
</div>
