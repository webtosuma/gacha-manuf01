<div class="splide__track">
    <ul class="splide__list">
        @foreach ($store_item->image_paths as $si => $image_path)


            <li class="splide__slide ">

                <u-store-item-image
                ration        ="{{$store_item->ration}}"
                image_path    ="{{$store_item->image_paths ? $store_item->image_paths[$si] : ''}}"
                new_label_path="{{$store_item->new_label_path}}"
                is_sold_out   ="{{$store_item->is_sold_out}}"
                is_prize      ="{{$store_item->prize?1:0}}"
                ></u-store-item-image>

            </li>


        @endforeach
    </ul>
</div>
