{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','発送申請')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user_prize') }}">取得した商品</a></li>
            <li class="breadcrumb-item active" aria-current="page">発送申請</li>
            </ol>
        </nav>
    </div>




    <div class="container py-md-4 mb-5">
        <h3  class="d-none d-md-block ">発送申請</h3>

        <form action="{{ route('shipped.appli.confirm') }}" method="POST">
            @csrf
            @method('POST')

            <u-shipped-form
            token="{{ csrf_token() }}"
            r_index="{{ route('api.use_address') }}"
            r_store="{{ route('api.use_address.store') }}"
            r_destroy="{{ route('api.use_address.destroy') }}"

            u_prize_ids="{{ implode(',',$id_array) }}"
            r_find="{{ route('api.user_prize.find') }}"
            ></u-shipped-form>




        </form>

    </div>
    <div class="container py-4 mb-5">
        <div class="p-3" style="border-radius:1rem; background:rgb(255, 255, 255, .9);">

            <h6 class="border border-danger border-2 p-2 text-danger text-center">
                必ずお読み下さい。
            </h6>


            <!--注意事項-->
            @include('includes.notes')


        </div>
    </div>
@endsection
