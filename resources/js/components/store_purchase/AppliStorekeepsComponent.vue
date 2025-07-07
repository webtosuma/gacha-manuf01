<template>
    <div class="">


        <div v-for="(store_keep, key) in store_keeps" :key="key">

            <!--ID-->
            <input type="hidden" name="store_keep_ids[]" :value="store_keep.id">


            <div class="row mx-0">
                <div class="col-3 col-md-2 p-0 pe-2">
                    <u-store-item-image
                    :ration        ="store_keep.store_item.ration"
                    :image_path    ="store_keep.store_item.image_paths[0]"
                    :new_label_path="store_keep.store_item.new_label_path"
                    :is_sold_out   ="store_keep.store_item.is_sold_out"
                    :is_prize      ="store_keep.store_item.prize?1:0"
                    />
                </div>
                <div class="col" style="font-size:11px;">
                    <div class="h6">
                        {{ store_keep.store_item.name }}
                    </div>
                    <div class="">
                        {{ store_keep.store_item.category.name }}
                    </div>
                    <div class="fs-">
                        ¥{{ store_keep.store_item.price.toLocaleString() }}
                    </div>
                    <div v-if="store_keep.store_item.points_redemption" class="text-danger">
                        {{ store_keep.store_item.points_redemption.toLocaleString() }}pt還元
                    </div>
                </div>
                <div class="col-auto text-end">
                    <span class="fs-5">{{ store_keep.count }}</span>点
                    <!-- sum_price -->
                    <div class="fs-5">
                        ¥{{ ( store_keep.sum_price  ).toLocaleString() }}
                    </div>
                    <!-- sum_points_redemption -->
                    <div v-if="store_keep.sum_points_redemption" class="text-danger">
                        {{ ( store_keep.sum_points_redemption ).toLocaleString() }}pt還元
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-3 justify-content-end align-items-center">
            <span>合計</span>
            <div>
                <span class="fs-5">{{ sum_items_count.toLocaleString() }}</span>点
            </div>
            <div>
                <span class="fs-3">¥{{ sum_items_price.toLocaleString() }}</span>
            </div>
        </div>



    </div>
</template>

<script setup>
    import { ref, computed, watch, onMounted } from 'vue';


    const props = defineProps({
        store_keeps:    { type: [Object,Array], default: [] },
        sum_items_price:{ type: Number, default: [] },
        sum_items_count:{ type: Number, default: [] },
    });


</script>
