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
        [氏　名]　{{ $worker->name.'('.$worker->kana_name.')' }}
    </div>
    <div>
        [性　別]　{{ $worker->gender }}
    </div>
    <div>
        [年　齢]　{{ $worker->age.'歳' }}
    </div>
    <div>
        [メールアドレス]　{{ $worker->email }}
    </div>
    <div>
        [電話番号]　{{ $worker->tell }}
    </div>
    <div>---------------------------------------</div>


</section>
