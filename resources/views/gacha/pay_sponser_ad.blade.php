@extends('layouts.movie')

<!--title-->
@section('title','広告-'.$sponsor_ad->title)


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
    movie_path_mobile="{{ $sponsor_ad->movie_path }}"
    r_action  ="{{ route('gacha.result', $params )}}"
    r_redirect="{{ route('gacha.sponsor_ad.redirect',$sponsor_ad) }}"
    redirect_text="{{'このサイトを見る'}}"
    rank_up="{{ $rank_up ? 1 : 0}}"
    max_time="15"
    ></u-movie-play>

    {{-- rank_up="{{ isset($rank_up)&&$rank_up ? 1 : 0}}" --}}


@endsection
