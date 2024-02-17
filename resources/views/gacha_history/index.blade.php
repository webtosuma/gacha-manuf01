{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','ガチャ履歴')


@section('content')
<!--breadcrumb-->
<div class="container mt-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">ガチャ履歴</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3 class="d-none d-md-block">ガチャ履歴</h3>
    <div class="list-group list-group-flush">

        <div class="list-group-item bg-white py-4">
            {{-- 所持ポイント --}}
            <div class="d-flex alignitems-center justify-content-between">
                <span>ガチャPLAY数：</span>

                <span class="fs-3 fw-bold ms-3">
                    <number-comma-component
                    number="{{ Auth::user()->gacha_play_count.'回' }}"
                    ></number-comma-component>
                </span>
            </div>
        </div>


        <div class="list-group-item bg-white border-0 py-">
            <!-- ページネーション -->
            <div class="d-flex justify-content-start  mt-3">
                {{ $gacha_histories->links('vendor.pagination.bootstrap-4',['elements' => 8]) }}
            </div>
        </div>


        @foreach ($gacha_histories as $gacha_history)
        <a href="{{route('gacha.result_history',$gacha_history->key)}}"
        class="list-group-item list-group-item-action py-1 border-0 position-relative bg-white"
        ><div class="row">
            <div class="col-4 col-md-2 p-0 position-relative">


                <!--loading-->
                <div class="ratio ratio-4x3">
                    <div class="bg- d-flex align-items-center justify-content-center rounded"
                    style="z-index:0;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>



                <!--gacha image-->
                <div class="position-absolute top-0 start-0 w-100 h-100"
                style="z-index:0;">
                    <ratio-image-component
                    url="{{ $gacha_history->gacha->image_path }}"
                    style_class="ratio ratio-4x3 rounded"
                    ></ratio-image-component>
                </div>


            </div>
            <div class="col">

                <div class="form-text">{{$gacha_history->created_at->format('Y/m/d H:i')}}</div>
                <div class="fw-bold">{{$gacha_history->gacha->name}}</div>
                <div class="">{{$gacha_history->gacha->one_play_point.'pt ×'.$gacha_history->play_count.'回'}}</div>


            </div>
        </div></a>
        @endforeach


        <div class="list-group-item bg-white border-0 py-">
            <!-- ページネーション -->
            <div class="d-flex justify-content-start  mt-3">
                {{ $gacha_histories->links('vendor.pagination.bootstrap-4',['elements' => 8]) }}
            </div>
        </div>
    </div>
</div>
@endsection
