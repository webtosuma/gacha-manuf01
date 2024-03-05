{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','メール受信設定')


@section('meta')
    @php $header_back_btn = true; @endphp
@endsection


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('settings') }}">会員情報設定</a></li>
          <li class="breadcrumb-item active" aria-current="page">メール受信設定</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block ">メール受信設定</h3>

    <div class="mx-auto mt-5" style="max-width:900px;">
        <form method="post" action="{{ route('settings.email_reception.update' ) }}" novalidate>
            @csrf
            @method('PATCH')
            <div class="card card-body bg-white border-0 row g-2">


                <!-- メール連絡受取り設定 -->
                <div class="col-12">
                    <label class="form-check-label fw-bold" for="get_email">メール連絡受取り設定</label>

                    <div class="d-flex align-items-end mb-3">
                        <div style="width:7rem;">受け取らない</div>
                        <div class="form-check form-switch ms-3">
                            <input class="form-check-input fs-3" type="checkbox" name="get_email" id="get_email"
                            {{ Auth::user()->get_email ? 'checked' : ''}}
                            >
                        </div>
                        <div class="">受け取る</div>
                    </div>
                </div>
                <div class="">
                    <a href="https://note.com/cardfesta/n/ne1fe05bfbe31" target="_blank">メールが届かない場合</a>はこちら
                </div>
            </div>


            <div class="my-5">
                <div class="col-md-6 mx-auto my-3">
                    <disabled-button style_class="btn btn-lg btn-warning text-white rounded-pill w-100"
                    btn_text="更新する"></button>
                </div>
                <div class="col-md-6 mx-auto my-3">
                    <a href="{{ route('settings') }}"
                    class="btn btn-lg btn-light border rounded-pill w-100"
                    >会員情報設定に戻る</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
