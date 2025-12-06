<form action="{{ route('admin.text.update',$text->type) }}" method="POST" novalidate
enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
    @csrf
    @method('PATCH')



    <!--本文(body)-->
    <label class="d-block mb-4">
        <div class="form-label">
            本文
            {{-- <span class="text-danger">＊</span> --}}
        </div>

        <!--error message-->
        @if ( $errors->has('body') )
            <div class="text-danger"> {{$errors->first('body')}} </div>
        @endif

        <encodedーtextarea-component
        name="body" id="body"
        style_class="form-control"
        rows="{{$text->textarea_rows}}"
        placeholder="{{$text->label}}の本文を入力してください。"
        default_body="{{ $errors->all() ? urldecode( old('body') ) : $text->body_text }}"
        ></encodedーtextarea-component>

    </label>


    <div class="col-md-6 mx-auto my-5">
        @if (!$text->id)
        <disabled-button style_class="btn btn-lg btn-primary text-white w-100 shadow"
        btn_text="登録する"></disabled-button>
        @else
        <disabled-button style_class="btn btn-lg btn-warning text-white w-100 shadow"
        btn_text="更新する"></disabled-button>
        @endif
    </div>

</form>
