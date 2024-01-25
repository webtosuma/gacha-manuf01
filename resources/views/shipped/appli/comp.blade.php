@extends('layouts.app')

<!----- title ----->
@section('title','発送申請・完了')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user_prize') }}">取得した商品</a></li>
            <li class="breadcrumb-item active" aria-current="page">発送申請</li>
            </ol>
        </nav>
    </div>

    <div class="container py-4 mb-5">
        <h3 class="mb-">発送申請・完了</h3>
        <form action="{{ route('shipped.appli.confirm') }}" method="POST">
            @csrf
            @method('POST')

            <div class="mx-auto" style="max-width:900px;">
                <section class="my-4">
                    <h5>商品の発送申請を受け付けました</h5>
                    <ul class="list-group bg-white">
                        <li class="list-group-item py-3">
                            <div class="">
                                商品発送の進捗は、
                                <a href="{{ route('shipped') }}">発送申請履歴</a>
                                よりご確認ください。
                            </div>

                            <div class="col-md-8 mx-auto my-4">
                                <a href="{{ route('user_prize') }}"
                                class="btn btn-lg btn-light border rounded-pill w-100"
                                >取得した商品一覧に戻る</a>
                            </div>
                        </li>
                        {{-- <li class="list-group-item">

                            <!--注意事項-->
                            @include('includes.notes')

                        </li> --}}
                    </ul>

                </section>
            </div>
        </form>

    </div>
@endsection
