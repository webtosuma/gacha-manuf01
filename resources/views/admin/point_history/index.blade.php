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
                <div class="col-3">
                    <div class="">月間売上</div>
                    <h3 class="fw-bold">
                        <number-comma-component number="{{ $sales }}"></number-comma-component>
                    </h3>
                </div>
                <div class="col-3">
                    <div class="">月間顧客数</div>
                    <h3 class="fw-bold">
                        <number-comma-component number="{{ $visiters->count() }}"></number-comma-component>
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
            <a href="{{route('admin.point_history.datetime')}}" class="btn border">ポイント購入履歴</a>
        </div>

        <!--テーブル-->
        <section class="card card-body bg-white mb-5 overflow-auto">
            <div class="mb-3">ポイント購入顧客情報</div>
            <table class="table bg-white ">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th scope="col" style="width:4rem;">アカウント名</th>
                        <th scope="col" style="width:4rem;">購入ポイント</th>
                        <th scope="col" style="width:4rem;">回数</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visiters as $visiter)
                        <tr>
                            <td>{{ $visiter->name }}</td>
                            <td>
                                <number-comma-component number="{{ $visiter->point_price }}"></number-comma-component>
                            </td>
                            <td>
                                <number-comma-component number="{{ $visiter->count }}"></number-comma-component>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-secondary border-0 py-5">
                                *ポイント購入した顧客情報はありません
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
@endsection
