<div class="text-start">
    <div class="badge d-inline-block bg-success text-white px-2 mb-2">チケット交換</div>
    <h3 class="fs-6 fw-bold m-0">{{$store->prize->name}}</h3>

    <!--ポイント表示-->
    <div class="d-inline-block border px-3 bg-whitee text-center mt-1 px-1 rounded-pill">
        <number-comma-component number="{{$store->point_count}}"></number-comma-component>pt
    </div>


    <div class="mt-3  @if($store->count==0) text-danger @endif">在庫数：<span class="fs-">{{$store->count}}</span></div>

    <div class="d-flex gap-2 align-items-center mt-">
        <img src="{{asset('storage/site/image/ticket/success.png')}}"
        alt="チケット" class="d-block"  style=" width:24px; height:24px;">
        <i class="bi bi-x"></i>
        <div class="text-success">
            <span class="fs-3">{{$store->ticket_count}}</span>枚
        </div>
    </div>
</div>
