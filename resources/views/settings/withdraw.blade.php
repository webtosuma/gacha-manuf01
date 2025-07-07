@extends('layouts.sub_toggl')

<!----- title ----->
@section('title','退会の手続き')


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('settings') }}">会員情報設定</a></li>
          <li class="breadcrumb-item active" aria-current="page">退会の手続き</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3>退会の手続き</h3>

    <form action="{{ route('auth.destroy') }}" method="post">
        @csrf
        @method('delete')


        <div class="card card-body border-danger bg-white text-dark">

            <div class="mb-3">
                <h6 class="fw-bold">一度退会すると、あなたのアカウントに関する情報がすべて失われます。</h6>

                <ul class="bg-body rounded-4 py-3">
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
            </div>


            <!--サブスク-->
            @if ( env('SUBSCRIPTION',false) )
                <div class="mb-3">
                    <h6 class="fw-bold  text-danger">サブスクプランのキャンセル申請はお済みですか？</h6>

                    <ul class="bg-danger-subtle rounded-4 py-3">
                        <li>
                            サブスクプランのキャンセル申請が行われていない場合、退会後も請求が自動更新されます。
                        </li>
                        <li>
                            必ず、<strong class="text-danger">サブスクプランの解約を行なった後に</strong>退会の手続きを行ってください。
                        </li>
                        <li>
                            <a href="{{ route('point_sail.customer_portal') }}"
                            class="btn btn-dark text-white rounded-pill btn-sm"
                            >利用中のサブスクプランの確認と、キャンセル申請はこちら</a>
                        </li>
                    </ul>
                </div>
            @endif


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
