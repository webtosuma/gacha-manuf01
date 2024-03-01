{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','チケット交換')


@section('content')
<!--breadcrumb-->
<div class="container mt-">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">チケット交換</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3 class="d-none d-md-block ">チケット交換</h3>


    <a href="{{ route('ticket_sail') }}" class="btn btn-sm btn-outline-success text- rounded-pill shadow"
    >チケットをポイントで購入する</a>

</div>
@endsection
