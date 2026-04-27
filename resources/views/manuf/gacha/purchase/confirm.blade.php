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
            <li class="breadcrumb-item active" aria-current="page">確認</li>
            </ol>
        </nav>
    </div>



    <div class="container px-0">
        <form action="{{ $gacha_title->r_purchase_checkout }}" method="post"
        novalidate
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()"
        >
            @csrf

            <input type="hidden" 
            name="gacha_key" 
            value="{{$machine->key}}"
            >
        

            <div class="row mx-0 g-4 g-md-3 justify-content-center">


                <!--flex-c2-1 -->
                <div class="col-12 col-lg-8">
                    <div class="mx-auto" style="max-width:768px;">
                        
                        <h5 class="fw-bold bg-warning p-2 ">内容をご確認ください。</h5>

                        @include('manuf.gacha.purchase.common.confirm_list')


                    </div>
                </div>




                <!--flex-c2-2 -->
                <aside class="col">
                    <div class="position-sticky" style="top: 2rem; ">


                        <!--会計明細-->
                        @include('manuf.gacha.purchase.common.account_details')

                        <!--btn-->
                        <section class="my-5">
                            <div class="row g-3 mb-5">

                                <div class="col-12">
                                    <button 
                                    class="btn btn-lg btn-warning w-100 rounded-pill"
                                    type="submit">お支払いに進む</button>
                                </div>
        
                                <div class="col-12">
                                    <a href="{{ url()->previous() }}" 
                                    class="btn btn-light border w-100 rounded-pill"
                                    >内容を変更する</a>
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
