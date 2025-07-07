<template>
    <div class="row gy-3">


        <!--side menu-->
        <div class="col-12 col-lg-auto">
            <div class="position-sticky ps-2" style="top: 6rem; ">

                <loading-cover-component :loading="loading" />

                <div class="row flex-md-column g-2 mb-2">
                    <!--キーワード検索-->
                    <div class="col-12 col-md input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text"
                        v-model="inputs.keyword"
                        class="form-control"
                        placeholder="商品名">

                        <span
                        @click="resetKeyword()"
                        class="btn btn-light border"><i class="bi bi-x"></i></span>
                    </div>
                    <!--購入年月-->
                    <div class="col">
                        <select
                        v-model="inputs.month"
                        class="form-select">
                            <option value="">購入年月</option>

                            <option v-for="( month, key ) in months" :key="key"
                            :value="month.date_stanp"
                            >{{month.format}}</option>
                        </select>
                    </div>
                    <!--並び替え-->
                    <div class="col">
                        <select
                        v-model="inputs.order"
                        class="form-select">
                            <option value="">並び替え</option>

                            <option v-for="( order, key ) in orders" :key="key"
                            :value="order.key"
                            >{{order.label}}</option>
                        </select>
                    </div>
                </div>


            </div>
        </div>


        <!--商品一覧-->
        <div class="col ">
            <ul class="row g-2 p-0" style="list-style:none;">


                <li v-for="(purchased, key) in purchaseds" :key="key"
                class="col-12 col-lg-6 ">
                    <div class="card card-body rounded-4 text-dark">
                        <!--購入日-->
                        <div class="">{{ purchased.done_at_format }}</div>

                        <div class="row gx-3 gy-2 gy-0 ">
                            <div class="col-3">

                                <u-store-item-image
                                :ration        ="purchased.store_item.ration"
                                :image_path    ="purchased.store_item.image_paths[0]"
                                :is_prize      ="purchased.store_item.prize?1:0"
                                />


                            </div>
                            <div class="col">
                                <div class="d-flex flex-column justify-content-between h-100">
                                    <div class="">
                                        <!--商品名-->
                                        <div class="">
                                            {{ purchased.done_store_item_name }}
                                        </div>
                                        <!--カテゴリー-->
                                        <div class="form-text">
                                            {{ purchased.store_item.category.name }}
                                        </div>
                                        <div class="">
                                            購入数：{{ purchased.count }}
                                        </div>
                                        <div class="fs-5 mt-3">
                                            合計：¥{{ purchased.done_sum_price.toLocaleString() }}
                                        </div>


                                    </div>

                                    <!--button group-->
                                    <div class="row g-2 mt-3">
                                        <div class="col-auto">
                                            <a :href="purchased.store_item.r_show"
                                            class="btn btn-outline-primary rounded-pill w-100">再度購入</a>
                                        </div>
                                        <div class="col-auto">
                                            <a :href="purchased.store_item.r_show"
                                            class="btn btn-light border rounded-pill w-100">商品を表示</a>
                                        </div>
                                        <div class="col-auto">
                                            <a :href="purchased.store_history.r_show"
                                            class="btn btn-light border rounded-pill w-100">発送状況</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>




                <li v-if="!loading && purchaseds.length==0"
                class="py-3">*購入した商品はありません。</li>

            </ul>
            <div v-show="nextPageUrl" class="mt-3">
                <a @click.prevent="getData( nextPageUrl )"
                class="btn btn-light border"
                href="">もっと読み込む</a>
            </div>

        </div>


    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:       { type: String, default: '' },
        r_api_list:  { type: String, default: '' },

    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const messages    = ref([]);  /* ポップアップメッセージ */
    const nextPageUrl = ref('');  /* 次のデータの読み込みURL */

    /* 購入済み商品 */
    const purchaseds = ref('');    //

    /* 入力値 */
    const inputs = ref({
        _token : props.token,
        keyword: '',
        month: '',
        order: '',
    });


    /* 選択枝 */
    const months = ref([]);/* 購入年月選択 */
    const orders = ref([]);/* 並び替え選択 */


    /* 監視 */
    watch(() => inputs.value.keyword, () => getData());
    watch(() => inputs.value.month,   () => getData());
    watch(() => inputs.value.order,   () => getData());


    /* 初回データ取得 */
    onMounted(() => { getData(); });



    /* データ取得 */
    const getData = (route = props.r_api_list) => {

        loading.value = true;/* 読み込み */

        axios.post(route, inputs.value)
        .then(response => {
            // console.log( response.data );

            const paginate = response.data['purchaseds'];
            purchaseds.value =
            route === props.r_api_list ? paginate.data : [...purchaseds.value, ...paginate.data];

            /* 年月選択肢(初回の読み込み時のみ) */
            months.value = response.data.months || months.value;

            /* 並び替え選択肢(初回の読み込み時のみ) */
            orders.value = response.data.orders || orders.value;

            loading.value = false;/* 読み込み */

            /* 次のデータの読み込み */
            // const { current_page, last_page, next_page_url } = paginate;
            // if (current_page !== last_page) {
            //     getData(next_page_url);
            // }

            /* 次のデータURLの保存 */
            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;


        })
        .catch(error => {
            console.error(error.response?.data);
            console.log( error );
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        });

    };


    /* キーワードのリセット */
    const resetKeyword = () => {
        inputs.value.keyword = '';
        getData();
    };



</script>
