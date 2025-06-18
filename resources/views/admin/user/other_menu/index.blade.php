@extends('admin.layouts.app')


@section('title','登録ユーザー|その他メニュー')


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
                <li class="breadcrumb-item active" aria-current="page">{{ 'その他メニュー' }}</li>

            </ol>
        </nav>



        <a href="{{route('admin.user')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <h2 class="mb-5 py-3 border-bottom">その他メニュー</h2>


        <div class="row">

            @if(
                true
                // config('app.user_prize_deadline_date')
                // or config('app.user_point_deadline_date')
            )
            <section class="col-md-4">
                <div class="card card-body">
                    <h5 class="mb-3 text-center">
                        @if ( config('app.user_prize_deadline_date') )
                            <div class="">期限切れユーザー商品のポイント交換</div>
                        @endif
                        @if ( config('app.user_point_deadline_date') )
                            <div class="">期限切れユーザーポイントのリセット</div>
                        @endif

                    </h5>

                    <a-user-deadlin-prize-change 
                    token     ="{{ csrf_token() }}"
                    r_redirect="{{ route('admin.api.user.user_prize.deadline.comp_change_point') }}"
                    r_api_deadline_prize="{{ route('admin.api.user.user_prize.deadline.change_point') }}"
                    r_api_deadline_point="{{ route('admin.api.user.point_history.deadline.point_reset') }}"
                    ></a-user-deadlin-prize-change>


                </div>
            </section>
        @endif


            {{-- @if( config('app.user_prize_deadline_date') )
                <section class="col-md-4">
                    <div class="card card-body">
                        <h5 class="mb-3 text-center">期限切れユーザー商品のポイント交換</h5>

                        <a-user-deadlin-prize-change
                        token     ="{{ csrf_token() }}"
                        r_api_post    ="{{ route('admin.api.user.user_prize.deadline.change_point') }}"
                        r_redirect="{{ route('admin.api.user.user_prize.deadline.comp_change_point') }}"
                        ></a-user-deadlin-prize-change>


                    </div>
                </section>
            @endif --}}


            {{-- @if( config('app.user_point_deadline_date') )
                <section class="col-md-4">
                    <div class="card card-body">
                        <h5 class="mb-3 text-center">期限切れユーザーポイントのリセット</h5>

                        <a-user-deadlin-point-reset
                        token     ="{{ csrf_token() }}"
                        r_api_post    ="{{ route('admin.api.user.point_history.deadline.point_reset') }}"
                        r_redirect="{{ route('admin.api.user.point_history.deadline.comp_point_reset') }}"
                        ></a-user-deadlin-point-reset>


                    </div>
                </section>
            @endif --}}

        </div>


        {{-- <a-user-deadlin-point-reset></a-user-deadlin-point-reset> --}}

        {{-- <a-user-deadlin-prize-change></a-user-deadlin-prize-change> --}}

    </div>
@endsection
