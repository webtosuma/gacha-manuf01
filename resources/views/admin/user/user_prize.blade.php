@extends('admin.layouts.app')


@section('title','ユーザー取得商品一覧')


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

                <li class="breadcrumb-item active" aria-current="page">ユーザー取得商品一覧</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">ユーザー取得商品一覧</h2>



        <!-- 削除 -->
        @if( $user )
        <form action="{{route('admin.user.user_prize.destroy_confirm',$user)}}" class="col-md-4" method="post">
            @csrf

            <div class="input-group mb-3">
                <span class="input-group-text">取得日時</span>
                <input type="datetime-local" class="form-control" name="created_at"
                value="{{ now()->format('Y-m-d\T00:00') }}"
                >
                <button class="btn btn-outline-secondary" type="submit">以降を削除</button>
            </div>
        </form>
        @endif


        <section class="card card-body bg-white my-3 overflow-auto">
            <table class="table bg-white my-3">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th></th>
                        <th scope="col">アカウント名</th>
                        <th scope="col">商品コード</th>
                        <th scope="col">商品名</th>
                        <th scope="col">取得日時</th>
                        <th>取得商品ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user_prizes as $user_prize)
                        <tr>
                            <td>{{$user_prize->user->id}}</td>
                            <td>{{$user_prize->user->name}}</td>
                            <td>{{$user_prize->prize->code}}</td>
                            <td>{{$user_prize->prize->name}}</td>
                            <td>{{ $user_prize->created_at->format('Y年m月d日 H:i:s') }}</td>
                            <td>{{$user_prize->id}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary border-0 py-5">
                                *登録ユーザーはいません
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
@endsection
