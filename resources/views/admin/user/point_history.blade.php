@extends('admin.layouts.app')


@section('title','ポイント履歴')


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

                <li class="breadcrumb-item active" aria-current="page">ポイント履歴</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">ポイント履歴</h2>



        {{-- <form action="" class="col-md-4">
            <div class="input-group mb-3">
                <span class="input-group-text">取得商品ID</span>
                <input type="number" class="form-control" value="1" name="user_prize_id">
                <button class="btn btn-outline-secondary" type="submit">以降を削除</button>
            </div>
        </form> --}}

        <!-- 削除 -->
        @if( $user )
        <form action="{{route('admin.user.point_history.destroy_confirm',$user)}}" class="col-md-4" method="post">
            @csrf

            <div class="input-group mb-3">
                <span class="input-group-text">ポイント履歴ID</span>
                <input type="number" class="form-control" name="point_history_id" value="1" >
                <button class="btn btn-outline-secondary" type="submit">以降を削除</button>
            </div>
            <div class="form-text">*購入分除く</div>
        </form>
        @endif




        <ul class="list-group list-group-flush">
            @include('admin.user.._types')
        </ul>
    </div>
@endsection
