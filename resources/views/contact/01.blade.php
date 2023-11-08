@extends('layouts.900_simple_base')


<!----- title ----->
@section('title','お問い合わせ')

<!----- meta ----->
@section('meta')
@endsection


<!----- style ----->
@section('style')
<link href="{{ asset('css/worker_info.css') }}" rel="stylesheet">
@endsection


<!----- script ----->
@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


<!----- contents ----->
@section('contents')
<h2 class="text-secondary fw-bold mb-5 border-start border-primary border-5 ps-2">
    お問い合わせ
</h2>


<section id=""  class="mb-5">

    <p class="text-secondary mb-4">
        ご不明な点は、下記フォームよりお問い合わせください。<br>
        お問合せ内容の確認後、担当者よりご連絡致します。
    </p>

    <form action="{{route('contact.confirmation')}}" onsubmit="stopOnbeforeunload()" method="POST" class="fs-5">
        @csrf
        <div class="mb-3">
            <label for="input_contact_type" class="form-label fw-bold">
                お問い合わせの種類 <span class="badge bg-danger">必須</span>
            </label>
            <select class="form-select" name="contact_type" id="input_contact_type" required>
                <option value="">選択してください。</option>

                @foreach ($contact_types as $contact_type)

                    <option value="{{$contact_type['value_text']}}"
                        @if($type_code === $contact_type['code']) selected @endif
                    >
                        {{ $contact_type['value_text'] }}

                    </option>

                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="input_name" class="form-label fw-bold">
            氏名
            </label><span class="badge bg-danger ms-1">必須</span>
            <input type="text" name="name" required
            class="form-control" id="input_name" aria-describedby="input_name" placeholder="例) 山田　太郎"
            >
        </div>
        <div class="mb-3">
            <label for="input_name" class="form-label fw-bold">
            会社名
            </label>
            <input type="text" name="company"
            class="form-control" id="input_name" aria-describedby="input_name" placeholder="※法人の場合のみ、入力をお願いします。"
            >
        </div>
        <div class="mb-3">
            <label for="input_email" class="form-label fw-bold">
                メールアドレス(半角英数)
            </label><span class="badge bg-danger ms-1">必須</span>
            <input type="email"  name="email" required
            class="form-control" id="input_email" aria-describedby="input_email"
            placeholder="例) yamada@mail.co.jp"
            >
        </div>
        <div class="mb-3">
            <label for="input_tell" class="form-label fw-bold">
                電話番号(半角数字)
            </label><span class="badge bg-danger ms-1">必須</span>
            <input type="tel" name="tell" required
            class="form-control" id="input_tell" aria-describedby="input_tell"
            placeholder="例) 09012345678"
            pattern="\d{10,11}"
            >
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold" for="input_body">
                お問い合わせ内容
            </label><span class="badge bg-danger ms-1">必須</span>
            <textarea type="text" name="body" class="form-control" placeholder="ご自由にご記入ください"
            id="input_body" style="height:10rem;" required
            ></textarea>
        </div>



        <div class="card border-light  mt-5">
            <div class="card-body text-md-center">
                <h5 class="card-title fw-bold  text-center">個人情報の取り扱いについて</h5>
                <p class="card-text">
                    <a href="javascript:void(0)" onclick="window.open('{{route('privacy_policy')}}')"
                    >プライバシーポリシー</a>をご確認ください。<br>
                    同意いただけた場合のみ「同意する」にチェックを入れ、確認画面へお進みください。
                </p>
                <div class="mt-3 form-check  text-center">
                    <div class="d-inline-block">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="agree" required>
                        <label class="form-check-label" for="exampleCheck1">同意する</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form_group my-5">
            <div class="col-sm-8 mb-3 mx-auto">
                <button class="btn btn-primary btn-arrow btn-lg text-white w-100" type="submit">入力内容の確認</button>
            </div>
        </div>


    </form>

</section>

@endsection
