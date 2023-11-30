<div class="row">
    <div class="col">


        <div class="px-3 col-8 mx-auto">
             <!--アカウント画像(image)-->
            <label class="d-block mb-4">
                <div class="form-label">アカウント画像</div>

                <read-image-file-component
                img_path="{{ Auth::user()->image_path }}"
                noimg_path="{{asset('storage/site/image/user_no_image.png')}}"
                style_class="ratio ratio-1x1 rounded-pill border"
                name="image"
                ></read-image-file-component>

                <!--error message-->
                @if ( $errors->has('image') )
                    <div class="text-danger"> {{$errors->first('image')}} </div>
                @endif
            </label>
        </div>


    </div>
    <div class="col-md-8">

        <!--アカウント名(name)-->
        <label class="d-block mb-4">
            <div class="form-label">アカウント名</div>

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
            <div class="form-label">メールアドレス</div>

            <input value="{{old('email', Auth::user()->email )}}"
            name="email"
            type="text" class="form-control">
            <!--error message-->
            @if ( $errors->has('email') )
                <div class="text-danger"> {{$errors->first('email')}} </div>
            @endif
        </label>

    </div>
</div>
