@extends('admin.layouts.app')


@section('title','ポイント売上')


@section('meta') @php
$active_key = 'point_history';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ポイント売上</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">ポイント売上</h2>


        <section class="mb-5">

            <div class="card mb-3 col-md-6">
                <div class="d-flex">
                    <div class="col">
                        @php
                        $old_month_text = $all_months[count($all_months)-1];
                        $disabled = $previous_month->format('Y-m-01') >= $old_month_text ? '' : 'disabled border-0 text-light';
                        @endphp
                        <a href="{{ route('admin.point_history',$previous_month->format('Y-m-01')) }}"
                        class="btn px-0 list-group-item-action text-center fw-bold text-secondary fs- {{$disabled }}"
                        >＜ {{ $previous_month->format('Y年m月') }}</a>
                    </div>
                    <div class="col">


                        <a-pointhistory-selectmonth
                        r_point_history="{{route('admin.point_history')}}"
                        all_months="{{implode(',',$all_months )}}"
                        selected_month="{{$this_month->format('Y-m-01')}}"
                        ></a-pointhistory-selectmonth>






                    </div>
                    <div class="col">
                        @php
                        $disabled = $next_month->format('Y-m') <= now()->format('Y-m') ? '' : 'disabled border-0 text-light';
                        @endphp
                        <a href="{{ route('admin.point_history',$next_month->format('Y-m-01')) }}"
                        class="btn px-0 list-group-item-action text-center fw-bold text-secondary fs- {{$disabled }}"
                        >{{ $next_month->format('Y年m月') }} ＞</a>
                    </div>
                </div>
            </div>

            <div class="row text-secondary">
                <div class="col col-md-3">
                    <div class="">月間売上</div>
                    <h3 class="fw-bold">
                        <number-comma-component number="{{ $sales }}"></number-comma-component>
                    </h3>
                </div>
                <div class="col col-md-3">
                    <div class="">月間顧客数</div>
                    <h3 class="fw-bold">
                        <number-comma-component number="{{ count($visiters) }}"></number-comma-component>
                    </h3>
                </div>
            </div>

            <!--グラフコンポーネント-->
            <a-pointhistory-chart
            s_labels="{{ $chart['labels'] }}"
            s_data="{{ $chart['data'] }}"
            ></a-pointhistory-chart>

        </section>


        <div class="mb-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a  href="{{route('admin.point_history',['month_text'=>$this_month->format('Y-m-01'),'table'=>'daily_report'])}}"
                    class="nav-link
                    @if($table=='daily_report') active @endif" aria-current="page">{{'月間日別レポート'}}</a>
                </li>
                <li class="nav-item">
                    <a  href="{{route('admin.point_history',['month_text'=>$this_month->format('Y-m-01'),'table'=>'customer_report'])}}"
                    class="nav-link
                    @if($table=='customer_report') active @endif">{{'月間顧客'}}</a>
                </li>
                <li class="nav-item">
                    <a  href="{{route('admin.point_history.datetime')}}"
                    class="nav-link">売上履歴</a>
                </li>
              </ul>
            {{-- <a href="" class="btn border"></a> --}}
        </div>


        <!--テーブル-->
        @include('admin.point_history.table.'.$table)

        {{-- customer_report --}}
    </div>
@endsection
