@extends('admin.layouts.app')


@section('title','文書設定')


@section('meta') @php
$active_key = 'text';
$active_submenu = true;
@endphp @endsection




@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">文書設定</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">文書設定</h2>


        <section class="mb-5">
            <div class="row g-3">
                @foreach ($text_types as $text_type)
                    <div class="col-12 col-md-4">
                        <a href="{{
                            $text_type['multiple']
                            ? route('admin.text.multiple', $text_type['type'])
                            : route('admin.text.edit',     $text_type['type'])
                        }}"
                        class="btn btn-light py-3 border w-100"
                        >{{$text_type['label']}}</a>
                    </div>
                @endforeach
            </div>
        </section>

    </div>
@endsection
