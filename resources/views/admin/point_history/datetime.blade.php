@extends('admin.layouts.app')


@section('title','ポイント売上履歴')


@section('meta') @php
$active_key = 'point_history';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>

                <li class="breadcrumb-item"><a href="{{ route('admin.point_history') }}"
                    >{{ 'ポイント売上' }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">履歴</li>
            </ol>
        </nav>



        <h2 class="my- py-3 border-bottom">ポイント売上履歴</h2>


        <a href="{{route('admin.point_history')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <!-- ページネーション -->
        <div class="d-flex justify-content-start mt-3">
            {{ $point_histories->links('vendor.pagination.bootstrap-4') }}
        </div>


        <!--テーブル-->
        <section class="card card-body bg-white overflow-auto">
            <div class="mb-3">ポイント購入顧客情報</div>
            <table class="table bg-white ">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th></th>
                        <th scope="col">アカウント名</th>
                        <th scope="col">メールアドレス</th>
                        <th scope="col">購入ポイント</th>
                        <th scope="col">売上金額</th>

                        <th scope="col">受付日時</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($point_histories as $point_history)
                        <tr>
                            @if($point_history->user)
                                <td>{{ $point_history->user->id }}</td>
                                <td>{{ $point_history->user->name }}</td>
                                <td>{{ $point_history->user->email }}</td>
                            @else
                                <td colspan="3" class="text-seondary">{{ '*退会済み' }}</td>
                            @endif
                            <td>
                                <number-comma-component number="{{ $point_history->value }}"></number-comma-component>pt
                            </td>
                            <td>
                                ¥<number-comma-component number="{{ $point_history->price }}"></number-comma-component>
                            </td>
                            <td>{{ $point_history->created_at->format('Y年m月d日 H:i:s') }}</td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-secondary border-0 py-5">
                                *ポイント購入した顧客情報はありません
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>


        <!-- ページネーション -->
        <div class="d-flex justify-content-start mt-3">
            {{ $point_histories->links('vendor.pagination.bootstrap-4') }}
        </div>

    </div>
@endsection
