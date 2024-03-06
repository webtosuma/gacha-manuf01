<div class="text-start">
    <div class="d-inline-block bg-success text-white px-2 mb-2">チケット交換</div>
    <h3 class="fs-5 fw-bold">{{$store->prize->name}}</h3>
    <div>在庫数：<span class="fs-">{{$store->count}}</span></div>

    <div class="d-flex gap-2 align-items-center">
        <img src="{{asset('storage/site/image/ticket/success.png')}}"
        alt="チケット" class="d-block"  style=" width:36px; height:36px;">
        <div class="text-success">
            <span class="fs-5">{{$store->ticket_count}}</span>枚
        </div>
    </div>
</div>
