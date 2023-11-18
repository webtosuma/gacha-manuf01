@extends('layouts.900_simple_base')


<!----- title ----->
@section('title','お問い合わせ')

<!----- meta ----->
@section('meta')
@endsection


<!----- style ----->
@section('style')
@endsection


<!----- script ----->
@section('script')
@endsection


<!----- contents ----->
@section('contents')
<h2 class="text-secondary fw-bold mb-5 border-start border-primary border-5 ps-2">
    お問い合わせ
</h2>

<section id="app" class="mb-5">
    <div class="my-3">

        <div class="fs-2 fw-bold text-center text-danger">
            ※まだ送信は完了していません。
        </div>

    </div>
    <div class="mb-3">

        <!-- ご入力内容 -->
        <div class="card w-100 mb-5">
            <h5 class="card-title p-3 border-bottom border-light">ご入力内容の確認をお願いします。</h5>


            <div class="card-body">

                <div class="row mb-3">
                    <p class="col-sm-3"><strong>お問い合わせの種類：</strong></p>
                    <p class="col-sm-9">{{ $inputs['contact_type'] }}</p>
                </div>

                <div class="row mb-3">
                    <p class="col-sm-3"><strong>氏名：</strong></p>
                    <p class="col-sm-9">{{ $inputs['name'] }}</p>
                </div>

                <div class="row mb-3">
                    <p class="col-sm-3"><strong>会社名：</strong></p>
                    <p class="col-sm-9">{{ $inputs['company'] ? $inputs['company'] : '※未入力' }}</p>
                </div>

                <div class="row mb-3">
                    <p class="col-sm-3"><strong>メールアドレス：</strong></p>
                    <p class="col-sm-9">{{ $inputs['email'] }}</p>
                </div>

                <div class="row mb-3">
                    <p class="col-sm-3"><strong>電話番号：</strong></p>
                    <p class="col-sm-9">{{ $inputs['tell'] }}</p>
                </div>

                <div class="row mb-3">
                    <p class="col-sm-3"><strong>お問い合わせ内容：</strong></p>
                    <p class="col-sm-9">{!! nl2br( e( $inputs['body'] ) ) !!}</p>
                </div>

            </div>
        </div>



        <!-- 送信ボタン -->
        <div class="form_group mb-5">
            <div class="col-sm-8 mb-3 mx-auto">
                <form action="{{route('contact.completion')}}" method="POST">
                    @csrf
                    @foreach ($inputs as $name => $value)
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                    @endforeach

                    <disabled-button-component
                    text="確定"style_class="btn btn-primary btn-arrow btn-lg text-white fs-5 w-100 "
                    ></disabled-button-component>

                </form>
            </div>
            <div class="col-sm-8 mb-3 mx-auto">
                <!-- <a href="input.php" class="btn btn btn-outline-secondary btn-lg fs-5 w-100">戻る</a> -->
                <button class="btn btn-outline-secondary btn-arrow btn-lg fs-5 w-100" type="submit"
                onClick="history.back();"
                >戻る</button>
            </div>
        </div>




    </div>
</section>

@endsection
