@extends('layouts.sub_toggl')

<!----- title ----->
@section('title','商品発送先の住所設定')


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
          <li class="breadcrumb-item"><a href="{{ route('settings.user_address') }}">商品発送先の住所設定</a></li>
          <li class="breadcrumb-item active" aria-current="page">住所修正</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block ">住所編集</h3>

        <form id="edit-form"
        action="{{ route('settings.user_address.update',$user_address) }}" method="POST"
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
            @csrf
            @method('PATCH')

            <input type="hidden" name="previous_url" value="{{$previous_url}}">


            <div style="min-height:80vh;">

                <u-edit-user-address-container
                token="{{ csrf_token() }}"
                r_api_show=  "{{route('api.use_address.show',$user_address)}}"
                r_api_update="{{route('api.use_address.update',$user_address)}}"
                use_size    ="{{config('app.address_use_size')}}"
                default_email="{{Auth::user()->email}}"
                ></u-edit-user-address-container>

            </div>


            <!--非表示-->
            <button id='submit-button' type="submit" class="d-none">送信</button>



        </form>

</div>
@endsection
