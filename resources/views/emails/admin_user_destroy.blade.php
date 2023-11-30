<section>


    <div>ユーザーの退会を受け付けました。</div>
    <div>
        詳細については、管理者ページよりご確認ください。
    </div>
    <div>
        <a href="{{ route('admin.contact'); }}">{{ route('admin.contact' ); }}</a>
    </div>

    <br><br>

    <div>---------- 退会人材情報 ----------</div>
    <br>
    <div>氏名：{{$name}}</div>
    <br>
    <div>メールアドレス：{{$email}}</div>
    <br>
    <div>
        退会理由など：<br>
        {!! str_replace(["\r\n","\r","\n"],"<br>", e( $body ) )!!}

    </div>
    <br>
    <div>---------------------------------------</div>


</section>
