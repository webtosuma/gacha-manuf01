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

                @if($user){{--個人--}}
                <li class="breadcrumb-item"><a href="{{ route('admin.user.show', $user) }}"
                    >{{ $user->name }}</a></li>
                @endif

                <li class="breadcrumb-item"><a href="{{ route('admin.user.point_history', $user?$user->id:0 ) }}"
                    >{{ 'ポイント履歴' }}</a></li>


                <li class="breadcrumb-item active" aria-current="page">ポイント履歴削除</li>
            </ol>
        </nav>


        <h2 class="my-5 py-3 border-bottom">以下の履歴を削除します</h2>
        {{-- {{$point_histories->count()}} --}}



        <ul class="list-group list-group-flush">
            @php $confirm = true; @endphp
            @include('admin.user.point_history._types')
        </ul>



        <div class="fs-2 text-center mt-5 my-3">よろしいですか？</div>

        <form action="{{route('admin.user.point_history.destroy', $user?$user->id:0 )}}" method="POST">
            @csrf
            @method('DELETE')


            @foreach ($point_histories as $point_history)
                <input type="hidden" name="point_history_ids[]" value="{{$point_history->id}}">
            @endforeach


            <div class="row g-3 justify-content-center">
                <div class="col-12 col-md-4">
                    <button class="btn btn-lg btn-danger text-white w-100 " type="submit">削除する</button>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('admin.user.point_history', $user?$user->id:0 ) }}"
                    class="btn btn-lg btn-light border w-100 " type="submit">やめる</a>
                </div>
            </div>
        </form>
    </div>
@endsection
