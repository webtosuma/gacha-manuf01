{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','会員ランク')


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">会員ランク</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block ">会員ランク</h3>



    <ul class="list-group list-group-flush">
        {{-- 会員ランク --}}
        <li class="list-group-item bg-white py-4 fs-">
            <!-- 会員ランク -->
            @if( Auth::user()->now_rank )
            @php $now_rank = Auth::user()->now_rank; @endphp
            <div class="d-flex justify-content-between gap-3">
                <div class="col col-md-3">
                    <div style="font-size:14px;" class="mb-2">会員ランク：</div>

                    <ratio-image-component
                    style_class="ratio ratio-16x9 overflow-hidden
                    position-relative shiny"
                    url="{{ $now_rank->image_path }}"
                    ></ratio-image-component>
                </div>
                <div class="col">
                    @include('mypage.user_rank')
                </div>
            </div>
            @endif
        </li>



        <!-- 一覧 -->
        @forelse ($user_rank_histories as $user_rank_history)
            <li class="list-group-item bg-white py-3">
                <div class="row align-items-center ">
                    <div class="col">

                        <div class="form-text">{{$user_rank_history->created_at->format('Y/m/d H:i')}}</div>
                        <div class="fw-bold">{{$user_rank_history->label}}</div>
                        <div class="form-text">pt消費数:{{ number_format( $user_rank_history->pt_count ) }}pt</div>

                    </div>
                    <div class="col-auto" style="width:120px;">
                        <ratio-image-component
                        style_class="ratio ratio-16x9 overflow-hidden
                        position-relative shiny"
                        url="{{ $user_rank_history->image_path }}"
                        ></ratio-image-component>
                    </div>
                </div>
            </li>
        @empty
            <li class="list-group-item bg-white py-3 fw-bold">履歴はありません。</li>
        @endforelse
</ul>

</div>
@endsection
