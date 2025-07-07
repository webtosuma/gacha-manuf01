<div>

    <!-- お届け先と利用ポイント -->
    <section class="my-4">
        <div class="mb-2">発送コード：{{ $store_history->code}}</div>
        <!--購入日-->
        <div class="mb-2">{{ $store_history->done_at_format }}</div>
        <!--発送日-->
        <div class="mb-2">{{ $store_history->shipment_at_format }}</div>
    </section>



    <section class="my-4">
        <h5>お届け先住所</h5>

        <div class="card card-body text-dark">
            <input type="hidden" name="user_address_id" value="{{ $store_history->address->id }}">
            <div class="fw-bold">
                {{ $store_history->address->name }} 様
            </div>
            <div class="fw-bold">
                <span>{{ '〒'.$store_history->address->postal_code }}</span>
                <span>{{ $store_history->address->todohuken }}</span>
                <span>{{ $store_history->address->shikuchoson }}</span>
                <span>{{ $store_history->address->number }}</span>
            </div>
            <h5 class="mt-3">ご連絡先電話番号</h5>
            <div class="">{{ $store_history->address->tell }}</div>
        </div>
    </section>



    <section class="my-4">
        <h5>ご注文商品</h5>

        <div class="card card-body text-dark">


            @foreach ($store_history->store_keeps as $store_keep)
                <div class="row">
                    <div class="col-3 col-md-2 pe-2">
                        <u-store-item-image
                        ration        ="{{$store_keep->store_item->ration}}"
                        image_path    ="{{$store_keep->store_item->image_paths[0]}}"
                        is_prize      ="{{$store_keep->store_item->prize?1:0}}"
                        />
                    </div>
                    <div class="col" style="font-size:14px;">
                        <div class="h6">
                            {{ $store_keep->done_store_item_name }}
                        </div>
                        <div>{{ $store_keep->store_item->category->name }}</div>
                        <!-- 数量 -->
                        <span class="fs-3">{{ $store_keep->count }}</span>点
                    </div>
                    <div class="col-auto text-end">
                        <!-- 注文時の合計価格 -->
                        <div class="fs-5">
                            ¥{{ number_format( $store_keep->done_sum_price  ) }}
                        </div>
                        @if( $store_keep->done_sum_points_redemption )
                            <!-- 注文時の合計還元ポイント -->
                            <div class="text-danger" style="font-size:11px;">
                                {{ number_format( $store_keep->done_sum_points_redemption ) }}pt還元
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="d-flex gap-3 justify-content-end align-items-center">
                <span>合計</span>
                <div>
                    <span class="fs-3">{{ number_format( $store_history->sumItemsCount() ) }}</span>点
                </div>
                <div>
                    <span class="fs-3">¥{{ number_format( $store_history->sumItemsPrice() ) }}</span>
                </div>
            </div>

        </div>
    </section>



    <section class="my-4">
        <h5>支払済み金額</h5>

            <div class="card card-body text-dark text-end">

                <div class="row">
                    <div class="col-auto">
                        商品小計（税込）
                    </div>
                    <div class="col text-end">
                        ¥{{ number_format( $store_history->sumItemsPrice() ) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-auto">
                        発送料・手数料：
                    </div>
                    <div class="col text-end">
                        ¥{{ number_format( $store_history->shipped_price ) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-auto">
                        利用ポイント：
                    </div>
                    <div class="col text-end">
                        ¥{{ number_format( $store_history->use_point ) }}
                    </div>
                </div>
                <!--ご請求金額（税込)-->
                <div class="row">
                    <div class="col-auto fs-5">
                        ご請求金額（税込）：
                    </div>
                    <div class="col text-end fs-3">
                        ¥{{ number_format( $store_history->totalItemsPrice() ) }}
                    </div>
                </div>
                <div class="row border-top text-danger pt-2">
                    <div class="col-auto">
                        還元ポイント：
                    </div>
                    <div class="col text-end">
                        {{ number_format( $store_history->redemption_point ) }}pt
                    </div>
                </div>

            </div>
    </section>

</div>
