<section>


    <div>人材の会員登録を受付けました。</div>
    <div>
        詳細については、管理者ページよりご確認ください。
    </div>
    <div>
        <a href="{{ route('admin'); }}">{{ route('admin' ); }}</a>
    </div>

    <br><br>

    <div>---------- 登録情報 ----------</div>
    <div>
        [氏　名]　{{ $user->name }}
    </div>
    <div>
        [メールアドレス]　{{ $user->email }}
    </div>
    <div>
        [電話番号]　{{ $user->tell }}
    </div>
    <div>---------------------------------------</div>


</section>
