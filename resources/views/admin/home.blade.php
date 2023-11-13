@extends('admin.layouts.app')


@section('title','Admin TOP')


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">{{ 'Top' }}</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">Admin TOP</h2>

    </div>
@endsection
