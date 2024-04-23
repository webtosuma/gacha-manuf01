<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>
<div class="row">
    <div class="col-md">



        <!--スポンサー名(user_name)-->
        <label class="d-block mb-4">
            <div class="form-label">
                スポンサー名
                <span class="text-danger">＊</span>
            </div>

            <div class="input-group mb-3">
                <input  value="{{old('user_name', $user->name )}}"
                name="user_name"
                placeholder="スポンサー名を入力してください。"
                type="text" class="form-control">
                {{-- <span class="input-group-text">円</span> --}}
            </div>

            <!--error message-->
            @if ( $errors->has('user_name') )
                <div class="text-danger"> {{$errors->first('user_name')}} </div>
            @endif
        </label>



        <!--メールアドレス(user_email)-->
        <label class="d-block mb-4">
            <div class="form-label">
                メールアドレス
                <span class="text-danger">＊</span>
            </div>

            <div class="input-group mb-3">
                <input  value="{{old('user_email', $user->email )}}"
                name="user_email"
                placeholder="example@mail.co.jp"
                type="text" class="form-control">
                {{-- <span class="input-group-text">円</span> --}}
            </div>

            <!--error message-->
            @if ( $errors->has('user_email') )
                <div class="text-danger"> {{$errors->first('user_email')}} </div>
            @endif
        </label>


        <!--電話番号(user_address_tell)-->
        <label class="d-block mb-4">
            <div class="form-label">
                電話番号
                <span class="text-danger">＊</span>
            </div>

            <div class="input-group mb-3">
                <input  value="{{old('user_address_tell', $user_address->tell )}}"
                name="user_address_tell"
                placeholder="012011112222"
                type="text" class="form-control">
                {{-- <span class="input-group-text">円</span> --}}
            </div>

            <!--error message-->
            @if ( $errors->has('user_address_tell') )
                <div class="text-danger"> {{$errors->first('user_address_tell')}} </div>
            @endif
        </label>


    </div>
    <div class="col-md-6">

        <div class="col-md-6 my-5 mx-auto">
            @if (!$sponsor->id)
            <disabled-button style_class="btn btn-dark text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
            @else
            <disabled-button style_class="btn btn-dark text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
            @endif
        </div>

    </div>
</div>
