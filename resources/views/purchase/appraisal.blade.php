@extends('layouts.purchase')


<!----- title ----->
@section('title','買取表')


@section('content')


    <h2 class="bg-dark text-center text-white my-3 py-2">買取査定</h2>



    <u-purchase-appraisal
    token     ="{{ csrf_token() }}"
    r_api_list="{{ route('purchase.api') }}"
    props_ids ="{{ $ids }}"
    ></u-purchase-appraisal>



@endsection
