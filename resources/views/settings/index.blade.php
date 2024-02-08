@extends('layouts.app')

<!----- title ----->
@section('title','会員情報設定')


@section('content')
<!--breadcrumb-->
<div class="container mt-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">会員情報設定</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3>会員情報設定</h3>

    <div class="mx-auto mt-4" style="max-width:900px;">
        <div class="list-group bg-white">

            <a href="{{ route('settings.acount') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >
                <div class="row align-items-center">
                    <div class="col-auto fs-3 text-primary">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="col">{{ 'アカウント設定' }}</div>
                </div>

                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            <a href="{{ route('password.request') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >
                <div class="row align-items-center">
                    <div class="col-auto fs-3 text-success">
                        <i class="bi bi-key"></i>
                    </div>
                    <div class="col">{{ 'パスワード変更' }}</div>
                </div>

                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            {{-- <a href="{{ route('settings.credit_card') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >
                <div class="row align-items-center">
                    <div class="col-auto fs-3 text-warning">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="col">{{ 'クレジット情報設定' }}</div>
                </div>

                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a> --}}

            <a href="{{ route('settings.shipped_address') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >
                <div class="row align-items-center">
                    <div class="col-auto fs-3 text-info">
                        <i class="bi bi-pin-map"></i>
                    </div>
                    <div class="col">{{ '商品発送先の住所設定' }}</div>
                </div>

                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            <a href="{{ route('settings.email_reception') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >
                <div class="row align-items-center">
                    <div class="col-auto fs-3 text-warning">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="col">{{ 'メール受信設定' }}</div>
                </div>
                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            {{-- <a href="{{ route('settings.withdraw') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >

                <div class="row align-items-center">
                    <div class="col-auto fs-3 text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <div class="col">{{ '退会の手続き' }}</div>
                </div>

                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a> --}}

        </div>
    </div>

</div>
@endsection
