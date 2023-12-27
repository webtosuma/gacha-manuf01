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

    <div class="mx-auto" style="max-width:900px;">
        <div class="list-group bg-white">

            <a href="{{ route('settings.acount') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >{{ 'アカウント設定' }}
                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            <a href="{{ route('password.request') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >{{ 'パスワード変更' }}
                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            <a href="{{ route('settings.credit_card') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >{{ 'クレジット情報設定' }}
                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            <a href="{{ route('settings.shipped_address') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >{{ '商品発送先の住所設定' }}
                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            {{-- <a href="{{ route('settings.email_reception') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >{{ 'メール受信設定' }}
                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a> --}}

            {{-- <a href="{{ route('settings.withdraw') }}"
            class="list-group-item list-group-item-action btn-arrow fs-51 fw-bold text-secondary py-3 position-relative"
            >{{ '退会の手続き' }}
                <div class="position-absolute top-50 end-0 translate-middle-y p-3 text-"
                ><i class="bi bi-chevron-right"></i></div>
            </a> --}}

        </div>
    </div>

</div>
@endsection
