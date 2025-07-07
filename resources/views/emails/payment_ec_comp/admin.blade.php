<section>

    <div>
        詳細については、管理者ページよりご確認ください。
    </div>
    <div>
        <a href="{{ route('admin.home') }}">{{ route('admin.home') }}</a>
    </div>

    <br><br>

    <div>---------- ご購入内容 ----------</div>
    <br>
    <div>
        ユーザー：{{$user->id}}:{{$user->name}}様
    </div>
    <br>
    <div>
        購入内容：{{ $store_history->product_name }}
    </div>
    <br>
    <div>
        購入点数：{{ number_format( $store_history->sumItemsCount() ) }}点
    </div>
    <br>
    <div>
        購入金額：{{ number_format( $store_history->totalItemsPrice() ) }}円
    </div>
    <br>
    <br>
    <div>---------------------------------------</div>


</section>
