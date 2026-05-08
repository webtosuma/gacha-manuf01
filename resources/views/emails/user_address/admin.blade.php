<section>


    <div>発送受付中の発送住所が更新されました。</div>
    <div>
        詳細については、管理者ページよりご確認ください。
    </div>
    <div>
        <a href="{{ route('admin.shipped') }}">{{ route('admin.shipped') }}</a>
    </div>


    <br><br>


    <div>更新日時：{{$user_address->updated_at->format('Y/m/d H:i')}}</div>
    <div>アカウント：{{'ID:'.$user->id}} {{$user->name}}</div>
    <div>発送待ち数：{{$user_address->shipped_waiting_count}}</div>


    <br><br>


    <div>---------- 発送住所 ----------</div>



        @include('shipped.common.user_address')


    {{-- <div>氏名：{{$name}}</div>
    <br>
    <div>メールアドレス：{{$email}}</div>
    <br>
    <div>電話番号：{{$tell}}</div>
    <br>
    <div>
        お問い合わせ内容：<br>
        {!! str_replace(["\r\n","\r","\n"],"<br>", e( $body ) )!!}

    </div> --}}
    <br>
    <div>---------------------------------------</div>


</section>
