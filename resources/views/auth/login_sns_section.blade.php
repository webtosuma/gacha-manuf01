@php
$login_sns_noet =  <<<__
SNSでのログインについて

LINE、Google、Facebook、TwitterおよびYahoo!JAPANに登録している情報と本サイトの会員登録情報を連携することにより、
各サイトIDを使ってログインすることができます。
ログイン画面にある、「他サイトIDでログイン」ボタンより手続きを行ってください。
なお、ログイン時の認証画面にて許可をいただいた場合のみ、各サイトの登録情報を取得し連携します。
取得情報は以下の項目となります。

■X：名前・メールアドレス
■LINE：名前
■Google：名前・メールアドレス
■Yahoo：名前・メールアドレス
__;
@endphp




<div class="border rounded bg- white text-center p-4 w-100">
    <h5>外部IDでログイン</h5>

    <!--Xログイン-->
    @if(config('services.twitter.client_id'))
        <a class="btn d-flex align-items-center border text-white w-100 mb-3 p-0"
        style="text-decoration:none; background:#000000;"
        href="{{route('login.x')}}">
            <div class="col-auto"><img src="{{asset('storage/site/image/sns-login-icon/x.png')}}"
            alt="Xロゴ"
            class="d-block" style="height:2.4rem;"></div>
            <div class="col">Xでログイン</div>
        </a>
    @endif

    <!--LINEログイン-->
    @if(config('services.line.client_id'))
        <a class="btn d-flex align-items-center border text-white w-100 mb-3 p-0"
        style="text-decoration:none; background:#06C755;"
        href="{{route('login.line')}}">
            <div class="col-auto"><img src="{{asset('storage/site/image/sns-login-icon/line.png')}}"
            alt="LINEロゴ"
            class="d-block" style="height:2.4rem;"></div>
            <div class="col">LINEでログイン</div>
        </a>
    @endif

    <!--Facebookログイン-->
    {{-- <a class="btn d-flex align-items-center border text-white w-100 mb-3 p-0"
    style="text-decoration:none; background:#0966ff;"
    href="">
        <div class="col-auto"><img src="{{asset('storage/site/image/sns-login-icon/facebook.png')}}"
        alt="Facebookロゴ"
        class="d-block" style="height:2.4rem;"></div>
        <div class="col">Facebookでログイン</div>
    </a> --}}

    <!--Yahooログイン-->
    @if(config('services.yahoo.client_id'))
        <a class="btn d-flex align-items-center border text-white w-100 mb-3 p-0"
        style="text-decoration:none; background:#ff0033;"
        href="{{route('login.yahoo')}}">
            <div class="col-auto"><img src="{{asset('storage/site/image/sns-login-icon/yahoo.png')}}"
            alt="Yahooロゴ"
            class="d-block" style="height:2.4rem;"></div>
            <div class="col">Yahooでログイン</div>
        </a>
    @endif

    <!--Googleログイン-->
    @if(config('services.google.client_id'))
        <a class="btn d-flex align-items-center border bg-white w-100 mb-3 p-0"
        style="text-decoration:none;"
        href="{{route('login.google')}}">
            <div class="col-auto"><img src="{{asset('storage/site/image/sns-login-icon/google.png')}}"
            alt="Googleロゴ"
            class="d-block" style="height:2.4rem;"></div>
            <div class="col">Googleでログイン</div>
        </a>
    @endif

    <div class="alert alert-secondary text-start border-0 form-text" role="alert">
        {!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $login_sns_noet ) )!!}
    </div>
</div>
