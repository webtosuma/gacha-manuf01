@extends('layouts.app')

<!----- title ----->
@section('title','発送申請・確認')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user_prize') }}">取得した商品</a></li>
                <li class="breadcrumb-item active" aria-current="page">発送申請</li>
            </ol>
        </nav>
    </div>


    <div class="container py-4 mb-5">
        <h3 class="mb-">発送申請・確認</h3>

        <form action="{{ route('shipped.appli.comp') }}" method="POST">
            @csrf
            @method('POST')

            <div class="mx-auto mt-5" style="max-width:900px;">

                <h5 class="text-primary text-center">
                    *内容をご確認の上、発送申請を確定させてください。
                </h5>

                <!-- お届け先と利用ポイント -->
                <section class="my-4">
                    @include('shipped.common.confirm_list')
                </section>


                <section class="my-5">
                    <div class="col-md-8 mx-auto my-3">

                        <disabled-button
                        style_class="btn btn-lg btn-primary text-white w-100"
                        btn_text="発送申請を確定する"></disabled-button>

                    </div>
                    <div class="col-md-8 mx-auto my-3">
                        <a onclick="history.back(); return false;" href="#"
                        class="btn btn-lg btn-light border w-100"
                        >お届け先を変更する</a>
                    </div>
                </section>
            </div>

        </form>
    </div>

@endsection
