{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','商品発送先の住所設定')


@section('meta')
    @php $header_back_btn = true; @endphp
@endsection


@section('content')
<!--breadcrumb-->
<div class="container mt-">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('settings') }}">会員情報設定</a></li>
          <li class="breadcrumb-item active" aria-current="page">商品発送先の住所設定</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3 class="d-none d-md-block ">商品発送先の住所設定</h3>

    <div class="mx-auto" style="max-width:900px;">

        <!-- お届け先 -->
        <section class="my-4">
            <u-addressーlist-form
            token="{{ csrf_token() }}"
            r_index="{{ route('api.use_address') }}"
            r_store="{{ route('api.use_address.store') }}"
            r_destroy="{{ route('api.use_address.destroy') }}"
            show_check="0"
            ></u-addressーlist-form>
        </section>



        <div class="my-5">
            <div class="col-md-6 mx-auto my-3">
                <a href="{{ route('settings') }}"
                class="btn btn-lg btn-light border rounded-pill w-100"
                >会員情報設定に戻る</a>
            </div>
        </div>

    </div>
</div>
@endsection
