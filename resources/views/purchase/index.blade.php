@extends('layouts.purchase')


<!----- title ----->
@section('title','買取表')


@section('content')


    <form action="{{route('purchase.appraisal')}}" method="post">
        @csrf

        <u-purchase-list
        token="{{ csrf_token() }}"
        r_api_list    ="{{ route('purchase.api') }}"
        category_id   ="{{ $category_id }}"
        ></u-purchase-list>

    </form>


@endsection
