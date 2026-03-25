@extends('manuf_admin.layouts.gacha_title')


@section('title',$gacha_title->name)


@section('meta') @php
$active_key = 'gacha_title.show';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection



@section('style')
 @include('manuf.gacha.common.css')
@endsection



@section('script')
 @include('manuf.gacha.common.js')
     <script>
        "use strict";
        /**
         * ==========================================
         *  スライダー(splide)　JS
         * ==========================================
        */
        document.addEventListener( 'DOMContentLoaded', function() {


            /* PC */
            var splidePc = new Splide( '#splide_mobile', {

                type     : 'loop',
                padding: '50px',
                focus  : 'center',
                perPage : 3, //3
                autoplay: true,
                pagination : false,

            } );
            splidePc.mount();

        } ) ;

    </script>

@endsection



@section('content')


    {{-- パンくずリスト --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
            >{{ 'Top' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title') }}"
            >{{ 'ガチャタイトル一覧' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$gacha_title->name}}</li>
        </ol>
    </nav>


    <!--マシーン詳細　offcanvace-->
    @foreach ($gacha_title->machines as $machine)

        <div class="offcanvas offcanvas-start  "
        tabindex="-1" id="oc_prizes{{ $machine->id }}" aria-labelledby="oc_prizes{{ $machine->id }}Label"
        style="max-width:90vw;"
        >
            <div class="offcanvas-header">

                <h5 class="
                offcanvas-title
                border-start border-info border-5 ps-1 fs-4 fw-bold
                "
                id="oc_prizes{{ $machine->id }}Label"
                >ガチャマシン09{{ $machine->id }}</h5>


                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">

                <div class="row g-1 pt-2 text-center mb-3" style="font-size:14px;">

                    <!--在庫-->
                    <div class="col">
                        <div class=" bg-dark border text-white px-2 rounded w-100 ">
                            <span class="">残り</span>
                            {{number_format($machine->remaining_count)}}
                        </div>
                    </div>

                    <!--待機中-->
                    <div class="col">
                        <div class=" bg-warning px-2 rounded w-100 ">
                            <span class="">待機中</span>
                            {{number_format($machine->waiting_count)}}
                        </div>
                    </div>
                </div>


                <!-- prize -->
                <div  class="border rounded oberflow-hidden mb-3">
                    @php $examples= [
                        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095777/melotabi_mejirushi_1.jpg',
                        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095784/melotabi_mejirushi_2.jpg',
                        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095791/melotabi_mejirushi_3.jpg',
                        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095807/melotabi_mejirushi_4.jpg',
                        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095814/melotabi_mejirushi_5.jpg',
                    ]; @endphp
                    <table class="table border text-dark m-0 rounded overflow-hidden" style="font-size:12px">
                        <tbody>
                            @foreach ($examples as $key => $url)
                            <tr>
                                <th class="" style="width:4rem;">
                                    <div class="ratio ratio-1x1 border rounded bg-white"
                                    style="
                                    background: no-repeat center center / contain;
                                    background-image: url({{$url}});
                                    " ></div>
                                </th>
                                <td class="">
                                    <div class="fw-bold">商品名syouhinmei</div>
                                    xxxxxx
                                </td>
                                <td style="width:5rem;">
                                    <div class="text-center" style="font-size:11px;">当選率</div>
                                    <div class="fs-5 text-center">25.0%</div>
                                </td>
                                <td style="width:5rem;">
                                    <div class="text-center" style="font-size:11px;">残数</div>
                                    <div class="fs-5 text-center">3個</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="mt-3">
                    <button class="btn btn-sm btn-light border rounded-pill w-100"
                    data-bs-dismiss="offcanvas" aria-label="Close"
                    >閉じる</button>
                </div>
            </div>
        </div>

    @endforeach


    <div class="row mx-0 g-0 g-md-3" style="min-height:90vh;">


        <!--flex-c2-1 -->
        <div class="col order-2">
            <div class="mx-auto" style="max-width:600px;">



                <h5 class="fw-bold">プレビュー表示</h5>


                <div class="border rounded-4 p-3">

                    @include('manuf.gacha.common.title_discription.index')

                </div>




            </div>
        </div>
        <!--flex-c2-2 -->
        <aside class="col-12 col-lg-4 pe-0  order-1 order-lg-2">
            <div class="position-sticky ps-2 pb-3 border-bottom mb-3" style="top: 0rem; ">
                <div class="p-3 bg-body rounded-4 mb-4">
                    <div class="row">
                        <div class="col">

                            <!--編集ボタン-->
                            <a href="{{route('admin.gacha_title.edit',$gacha_title)}}"
                            class="btn btn-light border"
                            ><i class="bi bi-pencil-fill me-2"></i>編集</a>

                        </div>
                        <div class="col-auto">

                            <!--削除ボタン-->
                            <a href="#"
                            class="btn btn-light border"
                            data-bs-toggle="modal"
                            data-bs-target="{{'#deleteModal'.'delete'}}"
                            ><i class="bi bi-trash"></i></a>


                        </div>
                    </div>


                </div>
                <div class="p-3 bg-body rounded-4 mb-4">


                    <!--公開ステータス-->
                    <div class="mb-2">
                        @include('manuf_admin.gacha_title.common.published_statuse')
                    </div>


                    <!--合計販売数-->
                    <div class="mb-2">
                        <div class="px-2">
                            <span class="form-text fw-bold">販売期間:</span>

                            <div class="form-text">
                                {{ $gacha_title['sales_start_at']
                                ?  $gacha_title['sales_start_at']->format('Y/m/d H:i')
                                : '----/--/-- --:--' }}
                                <span>~</span>
                                {{ $gacha_title['sales_end_at']
                                ? $gacha_title['sales_end_at']->format('Y/m/d H:i')
                                : '----/--/-- --:--' }}
                            </div>
                        </div>
                    </div>


                    <!--合計販売数-->
                    <div class="mb-2">
                        <div class="px-2 fw-bold form-text">
                            合計販売数:
                            <span class="fs-6">99</span>
                        </div>
                    </div>

                    <!--合計口数-->
                    <div class="mb-2">
                        <div class="px-2 fw-bold form-text">
                            合計口数:
                            <span class="fs-6">999</span>
                        </div>
                    </div>


                    <!--筐体数-->
                    <div class="mb-2">
                        <div class="px-2 fw-bold form-text">
                            公開中 筐体数:
                            <span class="fs-5">999</span>
                        </div>
                    </div>


                </div>


            </div>
        </aside>


    </div>

    <!--削除モーダル-->
    <form  action="{{route('admin.gacha_title.destroy',$gacha_title)}}" method="post">
        @csrf
        @method('DELETE')

        <delete-modal-component
        index_key="delete"
        icon="bi-trash"
        func_btn_type="submit"
        button_class="invisible">
            <div>
                <span class="fw-bold">このタイトルを削除します。
                <br />よろしいですか？
            </div>
        </delete-modal-component>
    </form>


@endsection
