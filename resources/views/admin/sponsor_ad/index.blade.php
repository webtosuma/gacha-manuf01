@extends('admin.layouts.app')


@section('title','広告管理')


@section('meta') @php
$active_key = 'sponsor_ad';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">広告管理</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">広告管理</h2>


        <div class="d-flex gap-3 mb-3">
            <a href="{{ route('admin.sponsor.create') }}"　admin.sopnsor_ad.create
            class="btn btn-dark text-white shadow">
            <i class="bi bi-plus-lg"></i>
            {{'スポンサー新規登録'}}
            </a>

            <a href="{{ route('admin.sponsor_ad.create') }}"　admin.sopnsor_ad.create
            class="btn btn-primary text-white shadow">
            <i class="bi bi-plus-lg"></i>
            {{'広告新規登録'}}
            </a>
        </div>


        <section class="card card-body bg-white mb-3 overflow-auto my-3">
            <table class="table bg-white my-3 text-center">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th scope="col" class="text-start">広告タイトル</th>
                        <th scope="col">スポンサー名</th>
                        <th scope="col">ガチャ</th>
                        <th scope="col">動画再生数</th>
                        <th scope="col">サイトアクセス数</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sponsor_ads as $sponsor_ad)
                        <tr>
                            <td class="text-start">{{$sponsor_ad->title}}</td>
                            <td>{{$sponsor_ad->sponsor->name}}</td>
                            <td><a href="{{route('admin.gacha.show',$sponsor_ad->gacha)}}">{{$sponsor_ad->gacha->name}}</a></td>
                            <td></td>
                            <td>{{number_format($sponsor_ad->sponsor->access_count)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
@endsection
