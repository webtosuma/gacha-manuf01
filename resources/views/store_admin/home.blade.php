@extends('store_admin.layouts.app')


@section('title','Admin TOP')


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">{{ 'Top' }}</li>
            </ol>
        </nav>


        <section class="row g-0 mt-5">

            <div class="col-12"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.user')}}">
                <div class="fw-bold">登録ユーザー</div>
                {{-- <div class="fs-3"><number-comma-component number="{{ $users->count() }}"></number-comma-component></div> --}}
                <div class="fs-3"><number-comma-component number="{{ $users_count }}"></number-comma-component></div>
            </a></div>


            <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.gacha')}}">
                <div class="fw-bold">公開中ガチャ</div>

                <div class="fs-3"><number-comma-component number="{{ $gachas->total() }}"></number-comma-component></div>
            </a></div>

            <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.point_history')}}">
                <div class="fw-bold">月間売上</div>
                <div class="fs-3"><number-comma-component number="{{ $sales }}"></number-comma-component></div>
            </a></div>


            <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.shipped')}}">
                <div class="fw-bold">発送待ち</div>

                <div class="fs-3">
                    @if ($waiting_shippeds_count)
                    <span class="text-warning">
                        <number-comma-component number="{{ $waiting_shippeds_count }}"></number-comma-component>
                    </span>
                    @else{{ '0' }}@endif
                </div>
            </a></div>

        </section>


        {{-- <section class="row g-0 mt-5">

            <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.gacha')}}">
                <div class="fw-bold">販売中アイテム</div>

            </a></div>

            <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.point_history')}}">
                <div class="fw-bold">月間売上</div>
                <div class="fs-3"><number-comma-component number="{{ $sales }}"></number-comma-component></div>
            </a></div>

            <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.user')}}">
                <div class="fw-bold">登録ユーザー</div>
                <div class="fs-3"><number-comma-component number="{{ $users_count }}"></number-comma-component></div>
            </a></div>

            <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.shipped')}}">
                <div class="fw-bold">発送待ち</div>

                <div class="fs-3">
                    @if ($waiting_shippeds_count)
                    <span class="text-warning">
                        <number-comma-component number="{{ $waiting_shippeds_count }}"></number-comma-component>
                    </span>
                    @else{{ '0' }}@endif
                </div>
            </a></div>

        </section> --}}


    </div>
@endsection
