@extends('layouts.movie')

<!--title-->
@section('title','取得中...')


@section('meta') @endsection


@section('style')
<style>
    /* 読み込み中カバー */
    .modal-backdrop { --bs-backdrop-opacity: 1 !important; }
</style>
@endsection



@section('content')

    @php
    $params = [
        'category_code'=>$user_gacha_history->gacha->category->code_name,
        'user_gacha_history'=>$user_gacha_history,
    ];
    @endphp

    <u-movie-play-youtube
    token="{{ csrf_token() }}"
    movie_path_mobile="{{ $movie_path['youtube'] }}"
    r_action="{{ route('gacha.result', $params )}}"
    rank_up="{{ $rank_up ? 1 : 0}}"
    ></u-movie-play-youtube>

@endsection
