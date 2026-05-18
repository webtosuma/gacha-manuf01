<!-- お届け先 -->
@include('manuf.gacha.purchase.common.address')


<!--会計明細-->
@include('manuf.gacha.purchase.common.account_details')


<!--購入内容-->
<section class="my-4">
    <h5 class="fw-bold">購入内容</h5>

    @foreach ($history->items as $item)
        @php
            $machine = $item->machine;
        @endphp

        @include('manuf.gacha.purchase.common.title_card')

    @endforeach

</section>


<!--発送商品-->
<section class="my-4">

    <h5 class="fw-bold">発送商品</h5>

    <div class="card card-body bg-white">

        @foreach ($shipped_prizes as $shipped_prize)
            <div class="row p-3">
                <div class="col-3 col-md-2 p-0 pe-2">
                    <div class="">
                        <ratio-image-component
                        style_class="ratio ratio-3x4 rounded border"
                        url="{{ $shipped_prize->image_path }}" />
                    </div>
                </div>
                <div class="col">
                    <div class="p-">
                        <h6 classs="form-text">{{ $shipped_prize->name }}</h6>
                        <div class="form-text">{{ $shipped_prize->code }}</div>    
                    </div>
                </div>
                <div class="col-auto text-end">
                    <span class="p-3 fs-3">{{ $shipped_prize->count }}</span>点
                </div>
            </div>
        @endforeach


        @foreach ($user_prizes as $user_prize)
            <input type="hidden" name="user_prize_ids[]" value="{{ $user_prize->id }}">
        @endforeach

        <div class="text-end">
            <span class="me-3">合計</span>
            <span class="fs-3">{{ $user_prizes->count() }}</span>点
        </div>
    </div>

</section>
