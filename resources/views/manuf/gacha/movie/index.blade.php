@extends('layouts.movie')

<!--title-->
@section('title','取得中...')


@section('meta') @endsection


@section('style') @endsection



@section('content')

    <u-movie-play
    token="{{ csrf_token() }}"
    movie_path_mobile="{{ $movie_path['mobile'] }}"
    r_action="{{ route('gacha.result', $item->code )}}"
    rank_up="{{ $rank_up ? 1 : 0}}"
    ></u-movie-play>


@endsection
