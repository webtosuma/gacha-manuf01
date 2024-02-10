@extends('admin.layouts.app')


@section('title',$user->name.'|登録ユーザー')


@section('meta') @php
$active_key = 'user';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.user') }}"
                >{{ '登録ユーザー' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
            </ol>
        </nav>



        <a href="{{route('admin.user')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>

        <!-- プロフィール -->
        <section class="mb-5">
            <h2 class="mb-3 my- py-3 border-bottom">
                @if ($user->admin)<span class="text-primary">●</span> @endif
                {{ $user->name }}様
            </h2>

            @include('admin.user.common.profile')

        </section>


        <section class="mb-5">
            <div class="row g-2">
                <div class="col-6 col-md">
                    <a href="{{route('admin.user.user_prize',$user->id)}}" class="btn btn-light border py-3 w-100">
                        <h6>商品</h6>
                        <div class="mt-3">
                            <number-comma-component number="{{ $user->u_prizes_count }}"></number-comma-component>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md">
                    <a href="{{route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>21,])}}"
                        class="btn btn-light border py-3 w-100">
                        <h6>ガチャ履歴</h6>
                        <div class="mt-3">
                            <number-comma-component number="{{ $user->gacha_play_count }}"></number-comma-component>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md">
                    <a href="{{route('admin.user.point_history',$user->id)}}" class="btn btn-light border py-3 w-100">
                        <h6>ポイント履歴</h6>
                        <div class="mt-3">
                            <number-comma-component number="{{ $user->point }}"></number-comma-component>pt
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md">
                    <a href="{{route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>11,])}}"
                    class="btn btn-light border py-3 w-100">
                        @php
                        $price = \App\Models\PointHistory::where('user_id',$user->id)
                        ->where('reason_id',11)->get()->sum('price');
                        @endphp

                        <h6>購入履歴</h6>
                        <div class="mt-3">
                            ¥<number-comma-component number="{{ $price }}"></number-comma-component>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md">
                    <a href="{{route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>22,])}}" class="btn btn-light border py-3 w-100">
                        @php
                        $shipped_count = \App\Models\PointHistory::where('user_id',$user->id)
                        ->where('reason_id',22)->get()->count();
                        @endphp

                        <h6>発送申請履歴</h6>
                        <div class="mt-3">
                            <number-comma-component number="{{ $shipped_count }}"></number-comma-component>
                        </div>
                    </a>
                </div>
            </div>
        </section>


        <!-- 退会処理 -->
        <section class="card card-body mb-5">
            <div class="d-flex justify-content-between">
                <h5 class="m-0">退会処理</h5>
                <div class="">
                    <button type="button" data-bs-toggle="modal"
                    data-bs-target="#deleteModal{{'delete'.$user->id}}"
                    class="btn btn-danger text-white"
                    >退会</button>



                    <form action="{{ route('admin.user.destroy', $user) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <delete-modal-component
                        index_key="{{'delete'.$user->id}}"
                        icon="bi-person-dash"
                        func_btn_type="submit"
                        button_class="invisible">
                            <div>
                                <span class="fw-bold">『{{$user->name}}』</span>さんの
                                <br />アカウントを退会処理します。
                                <br />よろしいですか？
                            </div>
                        </delete-modal-component>
                    </form>
                </div>
            </div>
            <div class="border border-danger p-3">
                <h5>注意:以下のことをお伝えください。</h5>
                <ul>
                    <li>
                        登録に使用したメールアドレスで再びアカウントを作成することはできません。
                    </li>
                    <li>
                        退会後は、取得した商品をお渡しすることはできません。
                    </li>
                    <li>
                        ポイントの返金はできません。
                    </li>
                </ul>
            </div>
        </section>
    </div>
@endsection
