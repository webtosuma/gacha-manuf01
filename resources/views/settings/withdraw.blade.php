@extends('layouts.app')

<!----- title ----->
@section('title','退会の手続き')


@section('content')
<!--breadcrumb-->
<div class="container mt-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('settings') }}">会員情報設定</a></li>
          <li class="breadcrumb-item active" aria-current="page">退会の手続き</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3>退会の手続き</h3>

    <form action="{{ route('auth.destroy') }}" method="post">
        @csrf
        @method('delete')


        <div class="card card-body border-danger bg-white">

            一度退会すると、あなたのアカウントに関する情報がすべて失われます。
            <ul class="my-3">
                <li>
                    登録中のメールアドレスの再登録ができなくなります。
                </li>
                <li>
                    これまで取得されたポイントの情報は削除され、利用ができなくなります。
                </li>
                <li>
                    これまで取得された商品の情報は削除されます。
                </li>
            </ul>




            <div class="mb-3">
                <label class="form-label fw-bold" for="input_body">
                    退会の理由・アンケート
                </label><span class="badge bg-danger ms-1">必須</span>

                <textarea name="body" id="input_body"
                class="form-control
                @if($errors->has('body')) border-warning @endif
                "
                placeholder="退会の理由・ご意見をご入力下さい"
                style="height:10rem;"
                ></textarea>

                <!--error message-->
                @if ( $errors->has('body') )
                    <div class="text-warning"> *{{$errors->first('body')}} </div>
                @endif
            </div>


            <h5 class="text-danger">本当に退会しますか？</h5>

        </div>


        <div class="my-5">
            <div class="col-md-6 mx-auto my-3">

                <disabled-button
                style_class="btn btn-lg btn-danger text-white rounded-pill w-100"
                btn_text="退会する"></disabled-button>

            </div>
            <div class="col-md-6 mx-auto my-3">
                <a href="{{ route('settings') }}"
                class="btn btn-lg btn-light border rounded-pill w-100"
                >会員情報設定に戻る</a>
            </div>

        </div>
    </form>
</div>
@endsection
