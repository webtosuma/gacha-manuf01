<div class="splide__track pe-">
    <ul class="splide__list">
        @foreach ($store_items as $si => $store_item)

            <li class="splide__slide px-1 ">
                <a href="{{ $store_item->r_show }}">


                    <!--image-->
                    <div class="ratio {{$store_item->ration}}  position-relative
                    overflow-hidden bg-body rounded-
                    ">
                        <div style="z-index:1;">

                            @include('store.section.common.image')

                        </div>
                    </div>


                </a>
            </li>

        @endforeach


        <li class="splide__slide px-1 ">
            <a href="{{ $line_r_more }}">


                <!--image-->
                <div class="ratio {{$store_item->ration}}
                d-flex align-items-center justify-content-center
                overflow-hidden bg-body rounded-
                ">
                    もっと見る
                </div>


            </a>
        </li>
    </ul>
</div>
