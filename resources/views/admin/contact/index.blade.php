@extends('admin.layouts.app')


@section('title','お問い合わせ')


@section('meta') @php
$active_key = 'contact';
@endphp @endsection




@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">お問い合わせ</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">お問い合わせ</h2>

        <section class="mb-5">
            <contact-list-component
            token="{{ csrf_token() }}"
            r_api_list   ="{{route('api.admin.contact.list')}}"
            r_api_update ="{{route('api.admin.contact.responsed')}}"
            r_api_destroy="{{route('api.admin.contact.destroy')}}"
            r_api_type_create="{{route('api.admin.contact.type_create')}}"
            r_csv_dl="{{route('admin.contact.dl_csv')}}"
            ></contact-list-component>
        </section>

    </div>
@endsection
