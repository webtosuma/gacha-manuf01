{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','アカウント設定')


@section('meta')
    @php $header_back_btn = true; @endphp
@endsection


@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('settings') }}">会員情報設定</a></li>
          <li class="breadcrumb-item active" aria-current="page">アカウント設定</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block ">アカウント設定</h3>

    <div class="mx-auto my-3" style="max-width:900px;">


        <form action="{{ route('settings.acount.update') }}" method="POST"
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
            @csrf
            @method('PATCH')


                @include('settings.acount._inputs')



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
        </section>


        {{-- <div class="col-md-6 my-5">
            <disabled-button style_class="btn btn-warning text-white w-100" btn_text="更新する"></button>
        </div> --}}



    </div>
</div>
@endsection
