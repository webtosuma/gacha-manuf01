@extends('layouts.app')

<!----- title ----->
@section('title','商品発送先の住所設定')


@section('content')
<!--breadcrumb-->
<div class="container mt-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('settings') }}">会員情報設定</a></li>
          <li class="breadcrumb-item active" aria-current="page">商品発送先の住所設定</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3>商品発送先の住所設定</h3>

    <div class="mx-auto" style="max-width:900px;">
    </div>
</div>
@endsection
