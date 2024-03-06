@if(Auth::check())
    <div class="position-fixed bottom-0 end-0 w-100 py-3 text-white"
    style="z-index:50; background:rgb(0, 0, 0, .9);">
        <div class="container mx-auto">

            <div class="d-flex align-items-center gap-2">
                <div class="col">所持チケット：</div>
                <div class="col-auto pe-2">
                    {{-- <div  style="font-size:14px;">所持チケット：</div> --}}
                    <div class="d-flex align-items-center gap-2">
                        <div class="col-auto">
                            <img src="{{asset('storage/site/image/ticket/success.png')}}"
                            alt="チケット" class="d-block mx-auto"  style=" width:2rem; height:2rem;">
                        </div>
                        <div class="col">
                            <span>×</span>
                            <span class="fs-2 fw-bold">
                                <number-comma-component number="{{ Auth::user()->ticket }}"></number-comma-component>
                            </span>
                            <span>枚</span>
                        </div>
                    </div>
                </div>


                {{-- <div class="col-auto">
                    <a href="{{ route('ticket_sail') }}"
                    class="d-block btn py-1 py-2 border border-success text-success rounded-pill shadow w-100">
                        <div class="d-flex gap-2 align-items-center">
                            <div class="">チケット購入</div>
                        </div>
                    </a>
                </div> --}}


                {{-- <div class="col-auto">
                    <a href="{{ route('ticket_store') }}"
                    class="d-block btn py-1 btn-danger text-white rounded-pill shadow w-100">
                        <div class="d-flex gap-2 align-items-center">
                            <i class="bi bi-cart4 fs-5"></i>
                            <div class="">商品購入<span class="badge rounded-pill bg-light text-dark ms-1"
                            >{{100}}</span></div>
                        </div>
                    </a>
                </div> --}}


            </div>



        </div>
    </div>
@endif
