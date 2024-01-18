@extends('admin.layouts.app')


@section('title','紹介キャンペーン一覧')


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

                <li class="breadcrumb-item active" aria-current="page">紹介キャンペーン一覧</li>
            </ol>
        </nav>



        <h2 class="mt-5 py-3 border-bottom">紹介キャンペーン一覧</h2>


        <a href="{{route('admin.user')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>




        <!-- ページネーション -->
        <div class="d-flex justify-content-start align-items-center gap-3 mt-3">
            <span class="mb-3">紹介者情報</span>
            {{ $recruiters->links('vendor.pagination.bootstrap-4') }}
        </div>


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
                    @php $old_recruiter = null; /* 一列前の紹介者 */ @endphp
                    @forelse ($recruiters as $recruiter)
                        @foreach ($recruiter->friends as $f_key => $friend)
                            <tr>
                                <!--紹介者-->
                                @if( $f_key==0 )
                                    @php
                                    $canpaing_recruiter_count = \App\Models\PointHistory::where('user_id',$recruiter->id)
                                    ->where('reason_id',31)->get()->count();
                                    @endphp
                                    <td >{{$recruiter->id}}</td>
                                    <td>{{$recruiter->name}}</td>
                                    <td>{{$recruiter->email}}</td>
                                    <td>{{$canpaing_recruiter_count.'回'}}</td>
                                @else
                                    <td colspan="4" class="text-secondary text-center">*同上</td>
                                @endif
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

                        @php $old_recruiter = $recruiter; /* 一列前の紹介者 */ @endphp
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


        <!-- ページネーション -->
        <div class="d-flex justify-content-start align-items-center gap-3 mt-3">
            <span class="mb-3">紹介者情報</span>
            {{ $recruiters->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection
