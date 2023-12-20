<ul class="list-group bg-white">
    <li class="list-group-item p-3">
        <h5>お届け先住所</h5>
        <input type="hidden" name="user_address_id" value="{{ $user_address->id }}">
        <div class="fw-bold">
            {{ $user_address->name }} 様
        </div>
        <div class="fw-bold">
            <span>{{ '〒'.$user_address->postal_code }}</span>
            <span>{{ $user_address->todohuken }}</span>
            <span>{{ $user_address->shikuchoson }}</span>
            <span>{{ $user_address->number }}</span>
        </div>
    </li>
    <li class="list-group-item p-3">
        <h5>利用ポイント</h5>
        <div class="d-flex justify-content-between">
            <span class="form-text">配送料・手数料：</span>
            <span>0pt</span>
        </div>
        <div class="d-flex justify-content-between fs-5 fw-bold">
            <span class="">合計利用ポイント：</span>
            <span class="text-danger">0pt</span>
        </div>
    </li>
    <li class="list-group-item p-3">

        <h5>発送する商品</h5>

        @foreach ($shipped_prizes as $shipped_prize)
            <div class="row p-3">
                <div class="col-3 col-md-2 p-0 pe-2">
                    <div class="">
                        <ratio-image-component
                        style_class="ratio ratio-3x4 rounded-3"
                        url="{{ $shipped_prize->image_path }}" />
                    </div>
                </div>
                <div class="col">
                    <h6 classs="form-text">{{ $shipped_prize->name }}</h6>
                    <div class="form-text">{{ $shipped_prize->code }}</div>
                </div>
                <div class="col-auto text-end">
                    <span class="fs-3">{{ $shipped_prize->count }}</span>点
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

    </li>
</ul>
