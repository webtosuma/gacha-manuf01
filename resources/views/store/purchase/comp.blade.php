@extends('store.layouts.sub')

<!----- title ----->
@section('title','ご注文完了')


@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('store.keep') }}">買い物カート</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{'ご注文完了'}}</li>
            </ol>
        </nav>
    </div>
    <div class="container py-md-4 pb-5 mb-5 ">
        <div class="mx-auto" style="max-width:900px;">


            <h3 class="text-success pb-5 mb-5">ご注文の手続きが完了しました。</h3>


            @include('store.shipped.show_body')


            <section class="my-5">
                <div class="col-md-8 mx-auto my-3">
                    <a href="{{route('store.shipped')}}"
                    class="btn btn-lg btn-light border rounded-pill w-100"
                    >発送履歴を確認する</a>
                </div>
            </section>


            <!--注意事項-->
            @include('store.includes.notes')

        </div>
    </div>

@endsection
