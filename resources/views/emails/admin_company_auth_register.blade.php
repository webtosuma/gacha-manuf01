<section>


    <div>求人企業の会員登録を受付けました。</div>
    <div>
        詳細については、管理者ページよりご確認ください。
    </div>
    <div>
        <a href="{{ route('admin'); }}">{{ route('admin' ); }}</a>
    </div>

    <br><br>

    <div>---------- 登録情報 ----------</div>
    <div>
        [企業名]　{{ $company->name }}
    </div>
    <div>
        [メールアドレス]　{{ $company->email }}
    </div>
    <div>
        [電話番号]　{{ $company->tell }}
    </div>
    <div>---------------------------------------</div>


</section>
