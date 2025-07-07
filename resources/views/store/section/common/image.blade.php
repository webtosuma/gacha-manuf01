<u-store-item-image
ration        ="{{$store_item->ration}}"
image_path    ="{{$store_item->image_paths ? $store_item->image_paths[0] : ''}}"
new_label_path="{{$store_item->new_label_path}}"
is_sold_out   ="{{$store_item->is_sold_out}}"
is_prize      ="{{$store_item->prize?1:0}}"
></u-store-item-image>
