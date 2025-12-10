<form action="{{ route('admin.text.user_rank.update') }}" method="POST" novalidate
enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
    @csrf
    @method('PATCH')



    <!--タイトル(user_rank_title)-->
    <label class="d-block col-md-8 mb-5">
        <div class="form-label">
            タイトル
            <span class="text-danger">＊</span>
            <span class="form-text">140文字以内</span>
        </div>

        <!--error message-->
        @if ( $errors->has('default_user_rank_title') )
            <div class="text-danger"> {{$errors->first('default_user_rank_title')}} </div>
        @endif

        <encodedーinputtext-component
        id="user_rank_title" name="user_rank_title"
        style_class="form-control form-control-lg"
        default_body="{{ $errors->all() ? urldecode( old('user_rank_title') ) : $text_bodys['user_rank_title'] }}"
        maxlength="140"
        placeholder="タイトルを入力してください。"
        ></encodedーinputtext-component>

    </label>



    <!--画像01(user_rank_img01)-->
    <label class="d-block col-md-6 mb-4">
        <div class="form-label">画像01</div>

        <div class="">
            <read-image-file-component
            img_path="{{$text_bodys['user_rank_img01']}}"
            noimg_path=""
            style_class="ratio {{config('app.gacha_card_ratio')}} rounded-3 border bg-body"
            name="user_rank_img01"
            ></read-image-file-component>
        </div>

        <!--error message-->
        @if ( $errors->has('user_rank_img01') )
            <div class="text-danger"> {{$errors->first('user_rank_img01')}} </div>
        @endif
    </label>



    <!--説明文01(user_rank_body01)-->
    <label class="d-block mb-4">
        <div class="form-label">
            説明文01
            <span class="text-danger">＊</span>
            <span class="form-text">140文字以内</span>
        </div>

        <!--error message-->
        @if ( $errors->has('default_user_rank_body01') )
            <div class="text-danger"> {{$errors->first('default_user_rank_body01')}} </div>
        @endif

        <encodedーtextarea-component
        name="user_rank_body01" id="user_rank_body01"
        style_class="form-control"
        rows="16"
        placeholder="説明文01を入力してください。"
        default_body="{{ $errors->all() ? urldecode( old('user_rank_body01') ) : $text_bodys['user_rank_body01'] }}"
        ></encodedーtextarea-component>

    </label>



    <!--画像02(user_rank_img02)-->
    <label class="d-block col-md-6 mb-4">
        <div class="form-label">画像02</div>

        <div class="">
            <read-image-file-component
            img_path="{{$text_bodys['user_rank_img02']}}"
            noimg_path=""
            style_class="ratio {{config('app.gacha_card_ratio')}} rounded-3 border bg-body"
            name="user_rank_img02"
            ></read-image-file-component>
        </div>

        <!--error message-->
        @if ( $errors->has('user_rank_img02') )
            <div class="text-danger"> {{$errors->first('user_rank_img02')}} </div>
        @endif
    </label>



    <!--説明文02(user_rank_body02)-->
    <label class="d-block mb-4">
        <div class="form-label">
            説明文02
            <span class="text-danger">＊</span>
            <span class="form-text">140文字以内</span>
        </div>

        <!--error message-->
        @if ( $errors->has('default_user_rank_body02') )
            <div class="text-danger"> {{$errors->first('default_user_rank_body02')}} </div>
        @endif

        <encodedーtextarea-component
        name="user_rank_body02" id="user_rank_body02"
        style_class="form-control"
        rows="16"
        placeholder="説明文02を入力してください。"
        default_body="{{ $errors->all() ? urldecode( old('user_rank_body02') ) : $text_bodys['user_rank_body02'] }}"
        ></encodedーtextarea-component>

    </label>



    <div class="col-md-6 mx-auto my-5">
        <disabled-button style_class="btn btn-lg btn-warning text-white w-100 shadow"
        btn_text="更新する"></disabled-button>
    </div>

</form>

