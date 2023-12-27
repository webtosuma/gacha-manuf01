
    <form action="{{route('register.post')}}" method="POST"
    novalidate class="w-100"
    enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
        @csrf

        <h2 class="h3 mb-3 fw-bold text-center">会員登録（無料）</h2>


        <!--アカウント名(name)-->
        <label class="d-block py-4">
            <div class="col-form-div text-start">{{ 'アカウント名' }}</div>

            <input value="{{old('name')}}"
            name="name"
            type="text" class="form-control
            {{ $errors->has('name') ? 'is-invalid' : '' }}">

            <!--error message-->
            @if ( $errors->has('name') )
                <div class="text-danger"> {{$errors->first('name')}} </div>
            @endif
        </label>


        <!--メールアドレス名(email)-->
        <label class="d-block mb-4">
            <div class="col-form-div text-start">{{ 'メールアドレス名' }}</div>

            <input value="{{old('email')}}"
            name="email"
            type="text" class="form-control
            {{ $errors->has('email') ? 'is-invalid' : '' }}">

            <!--error message-->
            @if ( $errors->has('email') )
                <div class="text-danger"> {{$errors->first('email')}} </div>
            @endif
        </label>


        <!--パスワード(password)-->
        <label class="d-block mb-4">
            <div class="col-form-div text-start">{{ 'パスワード' }}</div>

            <input value="{{old('password')}}"
            name="password"
            type="password" class="form-control
            {{ $errors->has('password') ? 'is-invalid' : '' }}">

            <!--error message-->
            @if ( $errors->has('password') )
                <div class="text-danger"> {{$errors->first('password')}} </div>
            @endif
        </label>


        <!--確認用パスワード(password_confirmation)-->
        <label class="d-block mb-4">
            <div class="col-form-div text-start">{{ '確認用パスワード' }}</div>

            <input value="{{old('password_confirmation')}}"
            name="password_confirmation"
            type="password" class="form-control
            {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">

            <!--error message-->
            @if ( $errors->has('password_confirmation') )
                <div class="text-danger"> {{$errors->first('password_confirmation')}} </div>
            @endif
        </label>

        <div class="form-text">
            本サービスの
            <a onclick="window.open(`{{route('trems')}}`);" class="btn btn-link p-0">利用規約</a>と
            <a onclick="window.open(`{{route('privacy_policy')}}`);" class="btn btn-link p-0">プライバシーポリシー</a>に
            同意した時のみ、登録を行なってください。登録後は、同意したものとみなされます。

        </div>

        <div class="my-5">
            <div class="col-md-6 mx-auto">

                <disabled-button style_class="btn btn-primary text-white w-100"
                btn_text="登録する"></bdisabled-button>

            </div>
        </div>

    </form>

    <hr class="my-4 w-100">
    <div class="text-center w-100">
        <div class="col-md-6 mx-auto">
            <small class="text-body-secondary">既にアカウントをお持ちの方はこちら</small>
            <a href="{{route('login')}}"
            class="w-100 py-2 mb-2 btn btn-outline-secondary rounded-3"
            >ログイン</a>
        </div>
    </div>

