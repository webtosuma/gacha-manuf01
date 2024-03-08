{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title',$store->prize->name)


@section('meta')

    <!--ヘッダーの戻るボタン-->
    @php $header_back_btn = true; @endphp

@endsection


@section('style')
<style>
    .ratio-3x4{ --bs-aspect-ratio: 133.3%; }
</style>
@endsection


@section('content')

    <!--ボトムメニュー-->
    @include('ticket_store.common.bottom_menu')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ticket_store') }}">チケット交換</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$store->prize->name}}</li>
            </ol>
        </nav>
    </div>

    <div class="container py-md-4 pb-5 mb-5">
        <div class="row gy-3">
            <div class="col-12 col-md-4">

                <!--image-->
                <div class="mx-auto">
                    @include('ticket_store.common.prize_image')
                </div>

            </div>
            <div class="col-12 col-md-8">
                <div class="card card-body rounded-3 border-0 shadow-sm bg-white mb-5">

                    <!--discription-->
                    @include('ticket_store.common.prize_discription')

                </div>

                <div class="col-md-10 mx-auto">
                    @if( $store->count > 0)
                        <form action="{{route('ticket_store.post', $store)}}" method="POST">
                            @csrf

                            <select name="item_count"
                            class="form-select bg-white fs-3 shadow-sm mb-3">
                                @for ($num = 1; $num <= $store->count; $num++)
                                    <option value="{{$num}}">{{'数量：'.$num}}</option>
                                @endfor
                            </select>


                            <div class="modal fade" id="exchangeModal" tabindex="-1" aria-labelledby="exchangeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body text-center">
                                            <h5 class="modal-title" id="exchangeModalLabel">
                                                <p>チケットと交換します。<br>よろしいですか？</p>
                                                {{-- <p>商品を<strong class="fs-3">{{ totalPoint }}pt</strong>と交換する</p> --}}
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <button type="button"
                                                    class="btn btn-light border rounded-pill w-100"
                                                    data-bs-dismiss="modal"
                                                    >キャンセル</button>
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit"
                                                    class="btn btn-danger text-white rounded-pill w-100"
                                                    >交換する</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <button class="btn btn-lg btn-danger text-white rounded-pill shadow w-100"
                            type="button"
                            data-bs-toggle="modal" data-bs-target="#exchangeModal"
                            >チケットと交換する</button>
                        </form>
                    @else
                        <div class="fs-3 text-center text-danger mb-3">SOLD OUT</div>
                    @endif


                    <a href="{{ route('ticket_store') }}"
                    class="btn btn-lg btn-light rounded-pill border w-100 mt-3"
                    >チケット交換一覧に戻る</a>

                </div>
            </div>
        </div>
    </div>
@endsection
