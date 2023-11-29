@extends('admin.layouts.app')


@section('title','ガチャ管理')


@section('meta') @php
$active_key = 'gacha';
@endphp @endsection

@section('style')
<style>
    /* ガチャのホバーアニメーション */
    .hover_anime:hover{
        position: relative;
        transform: scale(1.05) translateY(-1rem);

        transition: all .2s;
    }
</style>
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ガチャ管理</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">ガチャ管理</h2>



        <section class="mb-3">
            <ul class="nav nav-tabs">
                @foreach ($categories as $category)
                    @php
                    $active = $category->code_name == $gacha_category->code_name ? 'active' : '';
                    @endphp
                    <li class="nav-item">
                        <a  href="{{ route('admin.gacha',$category->code_name) }}"
                        class="nav-link {{ $active }}"
                        >{{ $category->name }}</a>
                    </li>
                @endforeach
              </ul>
        </section>

        <!--card-->
        <section class="row gy-5 my-3 overflow-hidden">
            <div class="col-12 col-md-4 col-lg-3 ">
                <a href="{{ route('admin.gacha.create',$gacha_category->code_name) }}"
                class="btn btn-primary shadow text-white
                hover_anime w-100 h-100" style="border-radius:1rem;"
                ><div class="d-flex align-items-center justify-content-center h-100 fs-3"
                >新規登録</div></a>


            </div>
            @foreach ($gachas as $gacha)
                <div class="col-12 col-md-4 col-lg-3 ">
                    <a href="{{route('admin.gacha.show',$gacha)}}"
                    class="card border-secondary border-3 shadow bg-white
                    text-dark text-center overflow-hidden text-decoration-none
                    hover_anime" style="border-radius:1rem;">


                        <!--image-->
                        <div class="position-relative">
                            <ratio-image-component
                            url="{{ $gacha->image_path }}" style_class="ratio ratio-4x3"
                            ></ratio-image-component>

                            @if ($gacha->remaining_count==0)
                            <div class="position-absolute top-0 start-0 w-100 h-100"
                            style="z-index:10; background: rgba(0, 0, 0, .7);"
                            ><div class="d-flex align-items-center justify-content-center h-100 fs-1 text-white"
                            >売り切れました</div></div>
                            @endif
                        </div>

                        <!--metter-->
                        <div class="card-body">
                            <h6 class="d-flexxx justify-content-between align-items-end">
                                <div class="d-flex align-items-center gap-2">
                                    @include('includes.point_icon')
                                    <div class="">
                                        1回×
                                        <span class="fs-3">
                                            <number-comma-component number="{{ $gacha->one_play_point }}"></number-comma-component>
                                        </span>pt
                                    </div>
                                </div>
                                <p class="card-text m-0">
                                    残り
                                    <number-comma-component number="{{ $gacha->remaining_count }}"></number-comma-component>
                                    /
                                    <number-comma-component number="{{ $gacha->max_count }}"></number-comma-component>
                                </p>
                            </h6>
                            <div class="progress">
                                @php
                                $ratio = $gacha->remaining_ratio;
                                $bg_color = $ratio>70 ? 'bg-primary' : ( $ratio>40 ? 'bg-warning' : 'bg-danger' );
                                $style_class = 'progress-bar progress-bar-striped '.$bg_color
                                @endphp
                                <div class="{{ $style_class }}" role="progressbar"
                                style="width: {{$ratio.'%'}}" aria-valuenow="{{ $ratio }}" aria-valuemin="0" aria-valuemax="{{ $ratio }}"></div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </section>

        <a href="{{ route('admin.gacha.create') }}" class="btn brn-primariy ">新規登録</a>
    </div>
@endsection
