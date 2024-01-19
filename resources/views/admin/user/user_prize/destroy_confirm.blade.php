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

                <li class="breadcrumb-item"><a href="{{ route('admin.user.user_prize',$user->id) }}"
                    >{{ 'ユーザー取得商品一覧' }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">ユーザー取得商品削除</li>
            </ol>
        </nav>


        <h2 class="my-5 py-3 border-bottom">以下の{{$user_prizes->count()}}件の取得商の記録を削除します</h2>



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




        <div class="fs-2 text-center mb-5">よろしいですか？</div>

        <form action="{{route('admin.user.user_prize.destroy',$user)}}" method="POST">
            @csrf
            @method('DELETE')
            @foreach ($user_prizes as $user_prize)
                <input type="hidden" name="user_prize_ids[]" value="{{$user_prize->id}}">
            @endforeach

            <div class="col-8 mx-auto">
                <button class="btn btn-lg btn-danger text-white w-100 " type="submit">削除する</button>
            </div>
        </form>
    </div>
@endsection
