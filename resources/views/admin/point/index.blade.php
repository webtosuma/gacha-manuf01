@extends('admin.layouts.app')


@section('title','ポイント管理')


@section('meta') @php
$active_key = 'point';
@endphp @endsection



<!----- script ----->
@section('script')
{{-- 折れ線グラフ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1"></script>
<script>
  window.onload = function () {
    let context = document.querySelector("#fukuoka_temperature_chart").getContext('2d')
    new Chart(context, {
        type: 'line',
        data: {
            labels: ['01', '02', '03', '04', '05', '06', '07',],
            datasets: [
                {
                    label: "過去7日間",
                    data: [10,11,13,9,12,16,17,],
                    borderColor:     '#00b8e6',
                    backgroundColor: '#00b8e6',
                },
                {
                    label: "前の期間",
                    data: [17,10,11,13,9,12,16,],
                },

            ],
        },
        options: {
            responsive: false,
            scales: {
                y: { min: 0, max: 30, }
            },
        }
    })
  }
</script>
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ポイント管理</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">ポイント管理</h2>


        <!-- グラフ -->
        <div class="carddd">
            <div class="card-body">
                <div class="row g-2">
                    <!-- グラフタイトル -->
                    {{-- <div class="col-12 col-md-8 p-0 pe-md-2">
                        <select class="form-select bg-white fw-bold">
                            <option value="1">Web広告の企画営業・AP◎年間休127日◎平均月収45万円</option>
                            <option value="2">【法人営業】年間休日123日以上／業界未経験OK／待遇充実</option>
                        </select>
                    </div> --}}
                    <div class="col-6 col-md-4 p-0">
                        <select class="form-select" style="font-size:11px;">
                            <option value="1">過去7日間</option>
                            <option value="2">過去14日間</option>
                            <option value="3">今月</option>
                            <option value="3">全ての期間</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 col-sm-3 p-0">
                        <btn class="btn list-group-item-action text-secondary">
                            <div style="font-size:11px;">送信数</div>
                            <div class="fw-bold d-md-none">4000</div>
                            <h5 class="fw-bold d-none d-md-block">4000</h5>
                        </btn>
                    </div>
                    <div class="col-4 col-sm-3 p-0">
                        <btn class="btn list-group-item-action text-secondary">
                            <div style="font-size:11px;">開封数</div>
                            <div class="fw-bold d-md-none">100</div>
                            <h5 class="fw-bold d-none d-md-block">100</h5>
                        </btn>
                    </div>
                    <div class="col-4 col-sm-3 p-0">
                        <btn class="btn list-group-item-action text-secondary">
                            <div style="font-size:11px;">開封率</div>
                            <div class="fw-bold d-md-none">25</div>
                            <h5 class="fw-bold d-none d-md-block">25</h5>
                        </btn>
                    </div>
                </div>
            </div>

            <canvas id="fukuoka_temperature_chart" class="w-100" height="300"></canvas>

        </div>



    </div>
@endsection
