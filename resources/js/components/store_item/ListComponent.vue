<template>
    <div >
        <loading-cover-component :loading="loading" />


        <!--絞り込み-->
        <div class="row g-2 align-items-center justify-content-end mb-3   px-1  d-md-none">
            <div class="col-auto">
                <select
                v-model="inputs.order"
                style="box-shadow:none;"
                class="form-select rounded-pill border border-2">

                    <option v-for="( order, key ) in orders" :key="key"
                    :value="order.key"
                    >{{ order.label }}</option>

                </select>
            </div>
        </div>


        <!--list-->
        <div class="row gy-3 ">
            <div v-for="(store_item, key) in store_items" :key="key"
            class="col-12 col-md-4 col-lg-4">


                <!--store_item-->
                <a :href="store_item.r_show" class="d-block text-dark ">
                    <div class="row gx-3 gy-0 ">
                        <div class="col-6 col-md-12">

                            <u-store-item-image
                            :ration        ="store_item.ration"
                            :image_path    ="store_item.image_paths[0]"
                            :new_label_path="store_item.new_label_path"
                            :is_sold_out   ="store_item.is_sold_out"
                            :is_prize      ="store_item.prize?1:0"
                            />


                        </div>
                        <div class="col">
                            <div class="py-md-3">
                                <div class="">
                                    {{ store_item.name }}
                                </div>
                                <div class="">
                                    {{ store_item.category.name }}
                                </div>
                                <div class="fs-5">
                                    ¥{{ store_item.price.toLocaleString() }}
                                </div>
                                <div v-if="store_item.points_redemption" class="text-danger">
                                    {{ store_item.points_redemption.toLocaleString() }}pt還元
                                </div>
                                <div class="form-text">
                                    在庫：{{ store_item.count }}点
                                </div>
                            </div>
                        </div>
                    </div>
                </a>


            </div>
        </div>


        <div v-if="store_items.length<1" class="py-5">＊該当する商品がありません</div>

        <div v-show="nextPageUrl" class="mt-3 col-md-6 mx-auto">
            <a @click.prevent="getData( nextPageUrl )"
            class="btn btn-light border w-100"
            href="">もっと読み込む</a>
        </div>



    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token: { type: String, default: '' },
        r_api_list:   { type: String, default: '' }, // 一覧表示
        category_id:  { type: String, default: '' },
        keyword:      { type: String, default: '' },
        order:        { type: String, default: 'desc_publised' },
    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const store_items   = ref([]); /* ECストアー商品 */

    const categories  = ref([]);   /* カテゴリー一覧 */
    const orders      = ref([]);   /* 並び替え選択肢 */
    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */
    const messages    = ref([]);   /* ポップアップメッセージ */


    const inputs   = ref({
        _token: props.token,

        keyword:     props.keyword,
        category_id: props.category_id,
        order:       props.order,
    });



    /* 監視 */
    watch(() => inputs.value.order, () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        getData();
    });


    /* データ取得 */
    const getData = (route = props.r_api_list) => {

        loading.value = true;/* 読み込み */

        axios.post(route, inputs.value)
        .then(response => {
            // console.log( response.data );

            const paginate = response.data['store_items'];
            store_items.value =
            route === props.r_api_list ? paginate.data : [...store_items.value, ...paginate.data];

            /*カテゴリーデータの保存*/
            categories.value   = response.data['categories'] ;

            /*並び替え選択肢の保存*/
            orders.value       = response.data['orders'] ;

            loading.value = false;/* 読み込み */

            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

            // resetBulc();/* 一括処理パラメーターのリセット */
        })
        .catch(error => {
            console.error(error.response?.data);
            console.log( error );
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        });

    };





</script>
