@extends('admin.layouts.app')


@section('title','イント履歴削除')


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

                <li class="breadcrumb-item"><a href="{{ route('admin.user.point_history',$user->id) }}"
                    >{{ 'ポイント履歴' }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">ポイント履歴削除</li>
            </ol>
        </nav>


        <h2 class="my-5 py-3 border-bottom">以下の{{$point_histories->count()}}件の取得商の記録を削除します</h2>



        <ul class="list-group list-group-flush">
            @include('admin.user.._types')
        </ul>



        <div class="fs-2 text-center mb-5">よろしいですか？</div>

        <form action="{{route('admin.user.point_history.destroy',$user)}}" method="POST">
            @csrf
            @method('DELETE')
            @foreach ($point_histories as $point_history)
                <input type="hidden" name="point_history_ids[]" value="{{$point_history->id}}">
            @endforeach

            <div class="col-8 mx-auto">
                <button class="btn btn-lg btn-danger text-white w-100 " type="submit">削除する</button>
            </div>
        </form>
    </div>
@endsection
