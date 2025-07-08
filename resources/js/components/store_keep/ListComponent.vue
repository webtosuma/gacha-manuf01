<template>
    <div class="">
        <loading-cover-component :loading="loading" />


        <!-- トーストポップアップ -->
        <div id="toast_container" class="position-fixed bottom-0 end-0 p-2" style="z-index:10;">
            <div v-for="(message, key) in messages" :key="key"
            class="toast fade show mb-1 fade-in-message" role="alert" aria-live="assertive" aria-atomic="true" >
                <div class="toast-header bg-dark text-white">
                    <strong class="me-auto">{{ message }}</strong>
                    <button type="button" class="btn px-1 py-0 text-white fs-5" data-bs-dismiss="toast"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
        </div>
        <div class="row mx-0 g-4">


            <!--flex-c1-->
            <aside class="col-12 col-lg-3 order-lg-2">
                <div class="position-sticky py-3 ps-2" style="top: 6rem; ">

                    <label  class="form-check d-flex aligin-items-center mb-3">
                        <input v-model="allChecked" class="form-check-input p-2" type="checkbox"
                        @click="toggleAllChecks">
                        <div class="form-check-label mt-1 ms-1">すべて選択</div>
                    </label>

                    <div class="border-bottom pb-3 text-end mb-3">
                        <!--小計-->
                        <div class="">
                            <div class="text-start">小計（税込）</div>
                            <div class="text-end"><span class="fs-1"
                            >{{ calcs.sum_items_price.toLocaleString() }}</span>円</div>
                        </div>
                        <!--取得予定ポイント-->
                        <div class="d-flex justify-content-between">
                            <span>送料：</span>
                            <span>{{ calcs.shipped_price.toLocaleString() }}円</span>
                        </div>
                        <!--取得予定ポイント-->
                        <div class="d-flex justify-content-between">
                            <span>還元ポイント：</span>
                            <span class="text-danger">{{ calcs.sum_items_points_redemption.toLocaleString() }}pt</span>

                        </div>
                    </div>

                    <button class="btn btn-lg btn-primary text-white rounded-pill w-100 mb-3" type="submit"
                    :disabled="store_keep_ids.length==0"
                    >ご購入手続き</button>

                </div>
            </aside>
            <!--flex-c2-->
            <div class="col order-lg-1">

                <div class="row g-3">
                    <div v-for="(store_keep, key) in store_keeps" :key="key"
                    class="col-12 col-md-6">

                        <div class="bg-light p-3 rounded-4 overflow-hiddenn border border-3  position-relative h-100"
                        :class=" store_keep_ids.includes(store_keep.id) ?'border-primary':'border-light'">

                            <!--チェックボックス-->
                            <div class="position-absolute top-0 start-0 translate-middle" style="z-index:5">
                                <input
                                v-model="store_keep_ids"
                                :value="store_keep.id"
                                name="store_keep_ids[]"
                                class="form-check-input p-3 rounded-circle"
                                type="checkbox" >
                            </div>

                            <div class="row g-3">
                                <div class="col">
                                    <u-store-item-image
                                    :ration        ="store_keep.store_item.ration"
                                    :image_path    ="store_keep.store_item.image_paths[0]"
                                    :new_label_path="store_keep.store_item.new_label_path"
                                    :is_sold_out   ="store_keep.store_item.is_sold_out"
                                    :is_prize      ="store_keep.store_item.prize?1:0"
                                    />
                                </div>
                                <div class="col-6">

                                    <!--詳細情報-->
                                    <div class="py-md-3" style="font-size:11px;">
                                        <div class="">
                                            {{ store_keep.store_item.name }}
                                        </div>
                                        <div class="">
                                            {{ store_keep.store_item.category.name }}
                                        </div>
                                        <div class="fs-5">
                                            ¥{{ store_keep.store_item.price.toLocaleString() }}
                                        </div>
                                        <div v-if="store_keep.store_item.points_redemption" class="text-danger">
                                            {{ store_keep.store_item.points_redemption.toLocaleString() }}pt還元
                                        </div>
                                    </div>


                                    <!--数量変更-->
                                    <div class="my-3">
                                        <div class="input-group rounded-pill">
                                            <!-- - -->
                                            <button type="button" class="form-control"
                                            @click="storeKeepUpdate( key, -1 )"
                                            :disabled=" store_keep.count <= 1"
                                            ><i class="bi bi-dash"></i></button>

                                            <div class="form-control text-center" style="flex:2;">{{ store_keep.count }}</div>

                                            <!-- + -->
                                            <button type="button" class="form-control"
                                            @click="storeKeepUpdate( key, 1 )"
                                            :disabled=" store_keep.count >= store_keep.store_item.count"
                                            ><i class="bi bi-plus"></i></button>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 mt-3">

                                        <div class="col">
                                            <a :href="store_keep.store_item.r_show"
                                            class="btn btn-sm btn-dark text-white rounded-pill  w-100">詳細</a>
                                        </div>

                                        <!--削除モーダル-->
                                        <delete-modal-component
                                        :index_key="'delete'+store_keep.id"
                                        icon="bi-trash"
                                        button_text="削除"
                                        func_btn_type="button"
                                        @parent-func="storeKeepDestroy( key )"
                                        button_class="btn btn-sm border rounded-pill px-3">
                                            <div>
                                                <span class="fw-bold">『{{store_keep.store_item.name}}』</span>を<br />
                                                買い物カートから削除します。<br />
                                                よろしいですか？
                                            </div>
                                        </delete-modal-component>


                                    </div>
                                </div>
                                <div class="col-12">



                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div v-if="store_keeps.length<1" class="py-4 card card-body text-dark border-0">
                    *保存中の商品はありません。
                </div>

            </div>

        </div>
    </div>
</template>

<script setup>
    import { ref, computed, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:       { type: String, default: '' },
        r_api_list:  { type: String, default: '' },

    });


    /* データの状態 */
    const loading     = ref(true);  /* 読み込み中 */

    /* ポップアップメッセージ */
    const messages = ref([]);

    const store_keeps = ref('');    //

    const store_keep_ids = ref([]);

    /* 合計計算値 */
    const calcs = ref({
        // 送料
        shipped_price               : 0,
        // 購入するカート商品の合計点数
        sum_items_count             : 0,

        // 購入するカート商品の還元ポイント
        sum_items_points_redemption : 0,

        // 購入するカート商品の[小計]
        sum_items_price             : 0,

        // 購入するカート商品の[請求金額]
        total_items_price           : 0,
    });


    /* [コンピューティッド]すべてチェック */
    const allChecked = computed({
        get: () => store_keep_ids.value.length === store_keeps.value.length,
        set: (value) => {
            if (value) {
                // すべて選択
                store_keep_ids.value = store_keeps.value.map(store_keep => store_keep.id);
            } else {
                // すべて解除
                store_keep_ids.value = [];
            }
        }
    });


    /* 監視 */
    watch(store_keep_ids, () => getData());


    /* 初回データ取得 */
    onMounted(() => { getData(); });


    /* データ取得 */
    const getData = (route = props.r_api_list) => {

        loading.value = true;/* 読み込み */

        const inputs = {
            _token: props.token,
            store_keep_ids: store_keep_ids.value, //選択中のID
        };

        axios.post(route, inputs)
        .then(response => {
            // console.log( response.data );

            const paginate = response.data['store_keeps'];
            store_keeps.value =
            route === props.r_api_list ? paginate.data : [...store_keeps.value, ...paginate.data];

            /*合計計算値の保存*/
            calcs.value = response.data['calcs'] ;


            loading.value = false;/* 読み込み */

            // 次のデータの読み込み
            const { current_page, last_page, next_page_url } = paginate;
            if (current_page !== last_page) {
                getData(next_page_url);
            }


        })
        .catch(error => {
            console.error(error.response?.data);
            console.log( error );
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        });

    };



    /**
     * 数量変更
     *
     * @param Integer key //store_keeps　キー
     * @param Integer add //加算・減算数
     * @return Void
     */
    const storeKeepUpdate = ( key, add ) => {

        // 加算・減算
        store_keeps.value[key].count += add;

        // 通信
        const route = store_keeps.value[key].r_api_update;
        const inputs = {
            _token:  props.token,
            _method: 'PATCH',
            count:   store_keeps.value[key].count,
        };

        axios.post(route, inputs)
        .then(response => {

            getData();

        })
        .catch(error => {
            console.error(error.response?.data);
            console.log( error );
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        });

    }


    /**
     * 削除
     *
     * @param Integer key //store_keeps　キー
     * @return Void
     */
    const storeKeepDestroy = ( key ) => {

        // 通信
        const route = store_keeps.value[key].r_api_destroy;
        const inputs = {
            _token:  props.token,
            _method: 'DELETE',
        };

        axios.post(route, inputs)
        .then(response => {

            store_keep_ids.value=[];//選択中チェックボックスのリセット
            messages.value.push('商品を買い物カートから削除しました。');
            getData();
        })
        .catch(error => {
            console.error(error.response?.data);
            console.log( error );
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        });

    }

    /* すべてチェック */
    const toggleAllChecks = () => {
        if (allChecked.value) {
            store_keep_ids.value = [];
        } else {
            store_keep_ids.value = store_keeps.value.map(store_keep => store_keep.id);
        }
    };



</script>
<style scoped>
    @keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px); /* 50px下から */
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
    }

    .fade-in-message {
        opacity: 0; /* 初期状態を透明に */
        animation: fadeInUp .8s ease-out forwards;
    }
</style>
