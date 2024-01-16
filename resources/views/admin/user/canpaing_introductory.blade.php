@extends('admin.layouts.app')


@section('title','キャンペーン紹介一覧')


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

                <li class="breadcrumb-item active" aria-current="page">キャンペーン紹介一覧</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">キャンペーン紹介一覧</h2>

        <section class="card card-body bg-white my-3 overflow-auto">
            <table class="table bg-white my-3">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th></th>
                        <th scope="col">紹介者</th>
                        <th scope="col">メールアドレス</th>
                        <th scope="col">紹介pt付与回数</th>
                        <th></th>
                        <th scope="col">ご友人</th>
                        <th scope="col">メールアドレス</th>
                        <th scope="col">購入回数</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recruiters as $recruiter)
                        @foreach ($recruiter->friends as $friend)
                            <tr>
                                <!--紹介者-->
                                @php
                                $canpaing_recruiter_count = \App\Models\PointHistory::where('user_id',$recruiter->id)
                                ->where('reason_id',31)->get()->count();
                                @endphp
                                <td>{{$recruiter->id}}</td>
                                <td>{{$recruiter->name}}</td>
                                <td>{{$recruiter->email}}</td>
                                <td>{{$canpaing_recruiter_count.'回'}}</td>
                                <!--ご友人-->
                                @php
                                $canpaing_friend_count = \App\Models\PointHistory::where('user_id',$friend->id)
                                ->where('reason_id',32)->get()->count();
                                @endphp
                                <td>{{$friend->id}}</td>
                                <td>{{$friend->name}}</td>
                                <td>{{$friend->email}}</td>
                                <td>{{$friend->point_sail_histories->count()}}</td>

                                <td>
                                    <!--pt購入履歴があるのに、キャンペーン付与がない(NG)-->

                                    @if ( $canpaing_friend_count )
                                        <span>{{$canpaing_friend_count.'回'}}</span>
                                    @else
                                        <span class="text-danger">{{$canpaing_friend_count.'回'}}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary border-0 py-5">
                                *登録ユーザーはいません
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
@endsection
