@extends('layouts.movie')

<!--title-->
@section('title','取得中...')


@section('meta') @endsection


@section('style') @endsection



@section('content')

    @php
    $params = [
        'category_code'=>$user_gacha_history->gacha->category->code_name,
        'user_gacha_history'=>$user_gacha_history,
    ];
    @endphp


    <u-movie-play
    token="{{ csrf_token() }}"
    movie_path_mobile="{{ $movie_path['mobile'] }}"
    movie_path_pc="{{ $movie_path['pc'] }}"
    r_action="{{ route('event.gacha.result', $params )}}"
    rank_up="{{ $rank_up ? 1 : 0}}"
    ></u-movie-play>


@endsection



