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


        <!-- 紹介したご友人 -->
        @if($friends->count() > 0)
            <section class="card card-body bg-white my-5 overflow-auto">
                <h5 class="mb-5 fw-bold">
                    紹介したご友人(<number-comma-component number="{{ $friends_count }}"></number-comma-component>)
                </h5>
                <!-- ページネーション -->
                {{-- <div class="d-flex justify-content-start mt-3">
                    {{ $friends->links('vendor.pagination.bootstrap-4',['elements' => 8]) }}
                </div> --}}

                <table class="table bg-white my-3">
                    <!--ヘッド（並べ替えボタン）-->
                    <thead>
                        <tr class="bg-white">
                            <th scope="col">ご友人</th>
                            <th scope="col">メールアドレス</th>
                            <th scope="col">購入回数</th>
                            <th scope="col">友人pt付与回数</th>
                            <th scope="col">登録日時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($friends as $f_key => $friend)
                            <tr>
                                <!--ご友人-->
                                @php
                                $canpaing_friend_count = \App\Models\PointHistory::where('user_id',$friend->id)
                                ->where('reason_id',32)->get()->count();
                                @endphp
                                <td>
                                    {{$friend->id}}：
                                    <a href="{{route('admin.user.show',$friend)}}">
                                        {{ mb_strlen($friend->name) <= 14 ? $friend->name : mb_substr($friend->name,0,14).'...' }}
                                    </a>
                                </td>
                                <td>{{$friend->email}}</td>
                                <td>{{$friend->point_sail_histories->count()}}</td>

                                <td>
                                    <!--pt購入履歴があるのに、キャンペーン付与がない(NG)-->

                                    @if ( $canpaing_friend_count )
                                        <span>{{$canpaing_friend_count.'回'}}</span>
                                    @else
                                        <span class="text-danger">{{$canpaing_friend_count.'回'}}</span>
                                    @endif
                                </td>
                                <td>{{$friend->created_at->format('Y年m月d日 H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- ページネーション -->
                <div class="d-flex justify-content-start mt-3">
                    {{ $friends->links('vendor.pagination.bootstrap-4',['elements' => 8]) }}
                </div>
            </section>
        @endif


        <!-- 退会処理 -->
        <section class="card card-body mb-5">
            <div class="d-flex justify-content-between">
                <h5 class="m-0 fw-bold">退会処理</h5>
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
