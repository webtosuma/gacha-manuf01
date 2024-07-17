@extends('layouts.sub')

<!----- title ----->
@section('title',number_format($point_sail->value).'ポイント購入')


@section('meta')
    @php $header_back_btn = true; @endphp
@endsection


@section('content')


    <div class="container py-4 mb-5">

        <section class="d-none d-md-block   px-3">
            <h3 class="mt-3 mb-5">ポイント購入手続き</h3>

            <a href="{{route('point_sail')}}" class="btn border btn-light rounded-pill">←戻る</a>
        </section>

        <section class="p-3 mb-5">
            <div class="row bg-white rounded-4 mx-0 g-0">
                <!-- left contents -->
                <div class="col">
                    <div class="d-flex h-100 align-items- justify-content-center ">

                        <div class="py-5">
                            <h5 class="fw-bold text-center">購入ポイント数と金額を<br>ご確認ください。</h5>
                            <div class="row gy-3 my-3 mx-auto" style="max-width:400px;">
                                <div class="col-12 col-md-6">支払い金額(税込)</div>
                                <div class="col-12 col-md-6 border-bottom text-end fw-bold fs-5">
                                    {{ number_format($point_sail->price).'円' }}
                                </div>
                                <div class="col-12 col-md-6">ポイント数</div>
                                <div class="col-12 col-md-6 border-bottom text-end fw-bold">
                                    {{ number_format($point_sail->value + $reduction_point) }}
                                    ポイント
                                </div>
                                <div class="col-12 col-md-6">支払い方法</div>
                                <div class="col-12 col-md-6 border-bottom text-end fw-bold">クレジットカード</div>
                            </div>
                            <div class="text-danger text-center">
                                @if (session('error-message'))
                                    <!--エラーメッセージ-->
                                    <p>{{ session('error-message') }}</p>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
                <!-- light contents -->
                <div class="col-12 col-lg" >
                    <div class="d-flex h-100 align-items- justify-content-center ">

                        <div class="py-5">
                            <!-- 登録済みクレジットカード一覧 -->
                            @if(count($card_list))
                            <form action="{{route( 'point_sail.process', $point_sail )}}" method="POST">
                                @csrf
                                <h6 class="fw-bold">お支払いカードを選択してください。</h6>



                                @foreach ($card_list as $card)
                                    <div class="card ps-2 bg-white  mb-2">
                                        <label class="form-check d-flex align-items-center">

                                            <input class="form-check-input" type="radio"
                                            name="card_id"
                                            value="{{$card['id']}}"
                                            @if( $card['default_flag'] ) checked @endif
                                            >

                                            <div class="form-check-label col ps-3">
                                                    <div class="form-text">{{ $card['card_no'] }}</div>
                                                    <div class="form-text">{{ $card['holder_name'] }}</div>
                                                {{-- </div> --}}
                                            </div>
                                        </label>
                                    </div>
                                @endforeach


                                <disabled-button
                                style_class="btn btn-primary text-white w-100 my-3"
                                btn_text="選択したカードで支払う"></disabled-button>

                                </button>
                                <hr />

                            </form>
                            @endif



                            <!-- クレジットカード新規登録ボタン　等 -->
                            <div class="col-12 col-md- mx-auto">

                                <a href="{{route('point_sail.create_card',$point_sail)}}"
                                class="btn btn-light rounded- border w-100 mb-3"
                                >支払いカードの新規登録</a>

                                <a href="{{route('point_sail')}}"
                                class="btn btn-light rounded- border w-100 mb-3"
                                >購入ポイントの変更</a>

                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection
