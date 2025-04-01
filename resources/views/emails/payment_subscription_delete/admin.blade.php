<section>

    <div>
        詳細については、管理者ページよりご確認ください。
    </div>
    <div>
        <a href="{{ route('admin.point_history') }}">{{ route('admin.point_history') }}</a>
    </div>

    <br><br>

    <div>---------- サブスクプラン内容 ----------</div>
    <br>
    <div>
        ユーザー：{{$user->id}}:{{$user->name}}様
    </div>
    <br>
    <div>
        サブスクプラン：{{$subscription->sub_label}}
    </div>
    <br>
    <div>
        解約
    </div>
    <br>
    <br>
    <div>---------------------------------------</div>


</section>
