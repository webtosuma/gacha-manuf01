@extends('admin.layouts.app')


@section('title','登録ユーザー')


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
                <li class="breadcrumb-item active" aria-current="page">登録ユーザー</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">登録ユーザー</h2>


        <section class="card card-body bg-white my-3 overflow-auto" style="height: 60vh;">
            <table class="table bg-white " style="min-width: 600px; font-size: 16px;">
                <table class="table bg-white my-3">
                    <!--ヘッド（並べ替えボタン）-->
                    <thead>
                        <tr class="bg-white">
                            <th scope="col">アカウント名</th>
                            <th scope="col">メールアドレス</th>
                            <th class="text-center" scope="col"
                            >保有ポイント</th>
                            <th class="text-center" scope="col"
                            >保有商品数</th>
                            <th scope="col">会員登録日</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-center">
                                    <number-comma-component number="{{ $user->point }}"></number-comma-component>
                                </td>
                                <td class="text-center">
                                    <number-comma-component number="{{ $user->u_prizes->count() }}"></number-comma-component>
                                </td>
                                <td>{{ $user->created_at->format('Y年m月d日') }}</td>
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


            </table>
        </section>
    </div>
@endsection
