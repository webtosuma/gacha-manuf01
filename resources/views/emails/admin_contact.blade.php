<section>


    <div>お客様よりお問い合わせを受け付けました。</div>
    <div>
        詳細については、管理者ページよりご確認ください。
    </div>
    <div>
        <a href="{{ url('/admin/contact') }}">{{ url('/admin/contact') }}</a>
    </div>

    <br><br>

    <div>---------- お問い合わせ内容 ----------</div>
    <br>
    <div>氏名：{{$name}}</div>
    <br>
    <div>メールアドレス：{{$email}}</div>
    <br>
    <div>電話番号：{{$tell}}</div>
    <br>
    <div>
        お問い合わせ内容：<br>
        {!! str_replace(["\r\n","\r","\n"],"<br>", e( $body ) )!!}

    </div>
    <br>
    <div>---------------------------------------</div>


</section>
