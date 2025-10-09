@extends('admin.layouts.event')

<!--title-->
@section('title','「'.$gacha->name.'」の結果')


@section('meta')
    @php
        $meta_title = '「'.$gacha->name.'」の結果';
        $meta_image = $bg_image;
    @endphp
@endsection


@section('style')
    <style>
        main{ padding-top: 0rem; }
        #bgWindow{
            background-image: url({{ $bg_image }});
        }
        /* body{
            background-image: url({{ $bg_image }});
        } */


        /* アイコンを下にスライドさせるアニメーション */
        .slide-updown {
        display: inline-block;
        animation: slideDown 2s ease-in-out infinite alternate;
        }

        @keyframes slideDown {
        from { transform: translateY(-16px); }
        to   { transform: translateY(0px); } /* 8px下に移動（調整可） */
        }

        /* 視覚負荷軽減対応 */
        @media (prefers-reduced-motion: reduce){
        .slide-updown { animation: none; }
        }

    </style>
@endsection



@section('content')
    <section id="result" style="padding-top:0rem; min-height: 80vh;">
        <div class="container px-3 py-4"  style="max-width:500px;">


            <h2 class="p- mb-4 fs-6">
                <div class="rounded-3 p-3 text-light position-relative" style="background: rgb(0, 0, 0, .7);">

                    <div class="mb-2" style="font-size:10px;">
                        <div class="">{{$user_gacha_history->created_at->format('Y/m/d H:i')}}</div>
                    </div>


                    <div class="mb-3" style="font-size:.8rem;">
                        <div class="fs-5 text-center">{{$page_title}}</div>
                    </div>

                </div>

            </h2>


            <!--商品発送フォーム-->
            @php $category_code = $user_gacha_history->gacha->category->code_name; @endphp
            <form action="{{ route( 'event.gacha.exchange_points', compact('category_code','user_gacha_history') ) }}" method="POST">
                @csrf
                @method('PATCH')


                <!--カード一覧-->
                <u-gacha-result-form
                show_change_btn="0"

                token="{{ csrf_token() }}"
                r_api_use_gacha_history_show="{{ route('api.use_gacha_history.show',$user_gacha_history) }}"
                r_gacha_category="{{ route('gacha_category',$gacha->category_code_name) }}"
                r_redirect="{{route('user_prize.exchange_points')}}"
                r_user_prize    ="{{route('user_prize')}}"

                no_exchange_point="{{ 1 }}"
                change_ticket    ="{{ 0 }}"
                ></u-gacha-result-form>


                {{-- <button type="button"
                data-bs-toggle="modal" data-bs-target="{{'#'.'changeModal'}}"
                class="btn btn-sm border btn-light text-danger">すべて削除</button> --}}

                <!--ポイント交換モーダル-->
                <div class="modal fade"
                id="{{'changeModal'}}" tabindex="-1"
                aria-labelledby="{{'changeModal'.'Label'}}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body fs-5">
                                商品を受け取り済みにします。<br>
                                よろしいですか？
                            </div>
                            <div class="modal-footer">
                                <div class="col">
                                    <button type="button" class="btn border w-100"
                                    data-bs-dismiss="modal">キャンセル</button>
                                </div>
                                <div class="col">
                                    <button
                                    type="submit"
                                    class="btn btn-success text-white w-100"
                                    >実行</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>



        </div>
    </section>
    <section class="mb-5">
        @if( env('SHARE_BTNS') )
            <div class="mb-5 py-">
                <h5 class="text-center fs-5 fw-bold mb-3">ガチャ結果を送る</h5>
                @php
                $sns_url  = route('gacha.result_history',$user_gacha_history->key);
                $sns_text = $page_title
                @endphp
                @include('includes.sns_btn')
            </div>
        @endif


        <!-- その他のガチャ情報 -->
        {{-- <div class="container px- pb-5"  style="max-width:500px;">

            @include('gacha.common.result_gachas')

        </div> --}}

    </section>


    <!--ボトムメニュー-->
    <div class="position-fixed bottom-0 end-0 w-100 py-3 text-white"
    style="z-index:50; background:rgb(0, 0, 0, .7);">
        <div class="container mx-auto" style="max-width:600px;">

            <!--play_buttons-->
            <div class="row gy-3">
                <div class="col-12">
                    <button
                    data-bs-toggle="modal" data-bs-target="{{'#'.'changeModal'}}"
                    class="btn btn-lg btn-primary text-white rounded-pill w-100"
                    >商品を受け取り済みにする</button>
                </div>
                <div class="col">
                    <a href="{{route('event.gacha',$gacha->category->code_name)}}"
                    class="btn btn-dark rounded-pill w-100"
                    >一覧</a>
                </div>
                <div class="col">
                    <a href="{{ $gacha->r_event_show }}" class="btn btn-dark rounded-pill w-100"
                    >詳細</a>
                </div>
            </div>


        </div>
    </div>


@endsection
