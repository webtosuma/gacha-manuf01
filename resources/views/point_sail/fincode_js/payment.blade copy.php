{{-- @extends('layouts.app') --}}
{{-- @extends('layouts.sub') --}}
@extends('layouts.payment')

<!----- title ----->
@section('title',number_format($point_sail->value).'ポイント購入')


@section('script')
<script src="https://js.test.fincode.jp/v1/fincode.js"></script>
@endsection


@section('content')



    <div class="py-4 mb-5">

        <section class="px-3">
            <h3 class="mt-3 mb-5">ポイント購入手続き</h3>

            <a href="{{route('point_sail')}}" class="btn border btn-light rounded-pill">←戻る</a>
        </section>

        <section class="bg- p-3 mb-5">
            <div class="row">
                <!-- left contents -->
                <div class="col-12 col-md">
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
                                    {{ number_format($point_sail->value) }}
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
                <div class="col-12 col-md p-0" style="min-height:60vh;">


                    <u-fincode-payment-form></u-fincode-payment-form>


                </div>
            </div>
        </section>
    </div>


@endsection
