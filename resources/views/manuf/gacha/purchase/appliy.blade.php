@extends('manuf.layouts.app')

<!--title-->
@section('title',$gacha_title->name)


<!--meta-->
@section('meta')
    @php
    $meta_title = $gacha_title->name;
    $meta_image = $gacha_title->image_samune_path;
    @endphp
@endsection


@section('style') @endsection



@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection




@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manuf') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ $gacha_title->r_show }}">{{$gacha_title->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">購入のお手続き</li>
            </ol>
        </nav>
    </div>



    <div class="container px-0">
        <form action="{{ $gacha_title->r_purchase_confirm }}" method="post"
        novalidate
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()"
        >
            @csrf

            <input type="hidden" 
            name="gacha_key" 
            value="{{$machine->key}}"
            >
        

            <div class="row mx-0 g-4 g-md-3">


                <!--flex-c2-1 -->
                <div class="col-12 col-lg-8">

                    <div class="mx-auto" style="max-width:768px;">


                        <!-- 購入ガチャタイトル -->
                        <section class="mb-4">

                            <h5 class="fw-bold">購入ガチャタイトル</h5>


                            <div class="pe-3 borderxx border-radius bg-bodyxx rounded w-100 overflow-hidden">

                                @include('manuf.gacha.purchase.common.title_card')


                                <div class="mt-3 text-end">
                                    <button class="btn btn-dark border rounded-pill " 
                                    type="button"
                                    >詳細を見る</button>        
                                </div>
                    
                            </div>

                        </section>
                        

                        <!-- 購入数 -->
                        <section class="mb-4">

                            <h5 class="fw-bold">購入数</h5>


                            <select name="play_count"
                            class="form-select form-select-lg" >
                                @for ($i = 01; $i < 20; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>

                        </section>


                        <!-- お届け先 -->
                        <section class="mb-4">

                            <h5 class="fw-bold">お届け先</h5>


                            <u-addressーlist-form
                            token="{{ csrf_token() }}"
                            r_index="{{ route('api.use_address') }}"
                            r_store="{{ route('api.use_address.store') }}"
                            r_destroy="{{ route('api.use_address.destroy') }}"
                            show_check="1"
                            use_size="{{config('app.address_use_size')}}"
                            ></u-addressーlist-form>

                        </section>


                        <!--発送料金 -->
                        <section class="mb-4">

                            <h5 class="fw-bold">発送料金</h5>


                           <div class="card card-body border text-end">
                                {{number_format($shipped_fee)}}円
                           </div>

                        </section>
    
    
                    </div>

                    
                </div>




                <!--flex-c2-2 -->
                <aside class="col">
                    <div class="position-sticky" style="top: 2rem; ">


                        <!--btn-->
                        <section class="my-5">
                            <div class="row g-3 mb-5">

                                <div class="col-12">
                                    <button 
                                    class="btn btn-lg btn-warning w-100 rounded-pill"
                                    type="submit">購入内容の確認</button>
                                </div>
        
                                <div class="col-12">
                                    <a href="{{ $gacha_title->r_show }}" 
                                    class="btn btn-light border w-100 rounded-pill"
                                    >別のガチャを選択する</a>
                                </div>
                            
                            </div>
                        </section>


                        

                        <!--note-->
                        @include('manuf.gacha.common.title_discription.note')



                    </div>
                </aside>

                
            </div>


        </form>
    </div>





@endsection
