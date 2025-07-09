@extends('store.layouts.sub')

<!----- title ----->
@section('title','注文のお手続き')


<!----- script ----->
@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('store.keep') }}">買い物カート</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{'注文のお手続き'}}</li>
            </ol>
        </nav>
    </div>
    <div class="container py-md-4 pb-5 mb-5 ">


        {{-- <form action="{{ route('store.purchase.confirm.post') }}" method="post"> --}}
        <form action="{{ route('store.purchase.stripe.checkout') }}" method="post"
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
            @csrf

            <!--すぐに購入の商品ページURL-->
            <input type="hidden" name="r_buynow_item" value="{{$r_buynow_item}}">

            <u-store-purchase-appli
            token="{{ csrf_token() }}"
            r_index="{{ route('api.use_address') }}"
            r_store="{{ route('api.use_address.store') }}"
            r_destroy="{{ route('api.use_address.destroy') }}"

            r_api_list="{{ route('store.keep.api') }}"
            ids       ="{{ implode(',',$store_keep_ids) }}"

            user_point="{{auth()->user()->point}}"
            >

                <!--注意事項-->
                <div class="card px-3 border-0 bg-white rounded-4 text-dark">
                    @include('store.includes.notes')
                </div>


            </u-store-purchase-appli>

        </form>
    </div>

@endsection
