<form action="{{ route('admin.text.meta.update') }}" method="POST" novalidate
enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
    @csrf
    @method('PATCH')


    <!--タイトル(meta_title)-->
    <label class="d-block mb-4">
        <div class="form-label">
            タイトル
            <span class="text-danger">＊</span>
            <span class="form-text">140文字以内</span>
        </div>

        <!--error message-->
        @if ( $errors->has('default_meta_title') )
            <div class="text-danger"> {{$errors->first('default_meta_title')}} </div>
        @endif

        <encodedーinputtext-component
        id="meta_title" name="meta_title"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('meta_title') ) : $text_bodys['meta_title'] }}"
        maxlength="140"
        placeholder="タイトルを入力してください。"
        ></encodedーinputtext-component>

    </label>



    <!--サイト説明文(meta_discription)-->
    <label class="d-block mb-4">
        <div class="form-label">
            サイト説明文
            <span class="text-danger">＊</span>
            <span class="form-text">140文字以内</span>
        </div>
        <div class="form-text">＊改行や空白文字は、更新後反映されません。</div>

        <!--error message-->
        @if ( $errors->has('default_meta_discription') )
            <div class="text-danger"> {{$errors->first('default_meta_discription')}} </div>
        @endif

        <encodedーtextarea-component
        name="meta_discription" id="meta_discription"
        style_class="form-control"
        rows="6"
        maxlength="140"
        placeholder="サイト説明文を入力してください。"
        default_body="{{ $errors->all() ? urldecode( old('meta_discription') ) : $text_bodys['meta_discription'] }}"
        ></encodedーtextarea-component>

    </label>



    <!--キーワード(meta_keyword)-->
    <label class="d-block mb-4">
        <div class="form-label">
            キーワード
            <span class="text-danger">＊</span>
            <span class="form-text">140文字以内</span>
        </div>
        <div class="form-text">＊キーワードは、「 , 」区切りで入力してください。</div>

        <!--error message-->
        @if ( $errors->has('default_meta_keyword') )
            <div class="text-danger"> {{$errors->first('default_meta_keyword')}} </div>
        @endif

        <encodedーinputtext-component
        id="meta_keyword" name="meta_keyword"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('meta_keyword') ) : $text_bodys['meta_keyword'] }}"
        maxlength="140"
        placeholder="例)キーワード1,キーワード2,キーワード3"
        ></encodedーinputtext-component>

    </label>



    <div class="col-md-6 mx-auto my-5">
        <disabled-button style_class="btn btn-lg btn-warning text-white w-100 shadow"
        btn_text="更新する"></disabled-button>
    </div>

</form>
