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


            <!--期限切れユーザー商品・ユーザーポイント-->
            @if(
                config('app.user_prize_deadline_date')
                ||
                config('app.user_point_deadline_date')
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


            <!--会員ランク更新モーダル-->
            @if(  config('u_rank_ticket.user_rank',false) )
                <section class="col-md-4">
                    <form action="{{ route('admin.user.user_rank_history.all_update') }}" method="post"
                    class="card card-body h-100">

                        <h5 class="mb-3 text-center">会員ランク一括更新</h5>

                        @csrf
                        <delete-modal-component
                        index_key="{{'all_update_user_rank'}}"
                        icon="" color="info"
                        func_btn_type="submit"
                        button_text="会員ランク一括更新"
                        button_class="btn btn-light border rounded-pill w-100">
                            <div>
                                <span class="fw-bold">全てのユーザー</span>の会員ランクを更新します
                                <div class="">よろしいですか？</div>
                            </div>
                        </delete-modal-component>


                    </form>
                </section>
            @endif


        </div>


    </div>
@endsection
