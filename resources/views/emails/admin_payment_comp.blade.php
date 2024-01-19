<section>

    <div>
        詳細については、管理者ページよりご確認ください。
    </div>
    <div>
        <a href="{{ route('admin.point_history') }}">{{ route('admin.point_history') }}</a>
    </div>

    <br><br>

    <div>---------- ご購入内容 ----------</div>
    <br>
    <div>
        ユーザー：{{$user->id}}:{{$user->name}}様
    </div>
    <br>
    <div>
        購入金額：{{$point_sail->price}}円
    </div>
    <br>
    <div>
        購入ポイント：{{$point_sail->value}}pt
    </div>
    <br>
    <br>
    <div>---------------------------------------</div>


</section>
