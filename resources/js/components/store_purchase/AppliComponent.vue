<template>
    <div class="row gx-5">


        <!--main-->
        <div class="col">
            <!-- お届け先選択 -->
            <section class="mb-5">
                <h5>お届け先の選択</h5>

                <u-addressーlist-form
                :token="token"
                :r_index="r_index"
                :r_store="r_store"
                :r_destroy="r_destroy"
                @update-address="updateSelectedAddressId"
                ></u-addressーlist-form>

            </section>


            <!-- <slot></slot> -->
            <section class="my-5">
                <h5>利用ポイント</h5>

                <div class="card card-body text-dark">
                    <div class="text- mb-3">
                        所持ポイント：{{ Number(user_point).toLocaleString()}}pt
                    </div>

                    <div class="row g-2 flex-column">
                        <div class="col-auto">
                            利用ポイントを入力
                        </div>
                        <div class="col-md">
                            <input
                            v-model="use_point"
                            name="use_point"
                            type="number"
                            class="form-control text-end"
                            min="0"
                            :max="user_point">
                        </div>
                    </div>
                </div>
            </section>


            <section class="my-5">
                <h5>ご注文商品</h5>

                <div class="card card-body text-dark">
                    <u-store-purchase-appli-storekeeps
                    :store_keeps="store_keeps"
                    :sum_items_price="calcs.sum_items_price"
                    :sum_items_count="calcs.sum_items_count"
                    ></u-store-purchase-appli-storekeeps>
                </div>
            </section>


        </div>
        <!--side-->
        <div class="col-12 col-lg-4">
            <!-- <div class="position-sticky ps-2 " style="top: 5rem; "> -->


                <section >
                    <h5>ご請求金額</h5>

                    <div class="card card-body text-end text-dark">

                        <div class="row">
                            <div class="col-auto">
                                商品小計（税込）
                            </div>
                            <div class="col text-end">
                                ¥{{calcs.sum_items_price.toLocaleString()}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                発送料・手数料：
                            </div>
                            <div class="col text-end">
                                <input type="hidden" name="shipped_price" :value="calcs.shipped_price">

                                ¥{{calcs.shipped_price.toLocaleString()}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                利用ポイント：
                            </div>
                            <div class="col text-end">
                                ¥{{use_point.toLocaleString()}}
                            </div>
                        </div>
                        <!--ご請求金額（税込)-->
                        <div class="row">
                            <div class="col-auto fs-5">
                                ご請求金額（税込）：
                            </div>
                            <div class="col text-end fs-3">
                                ¥{{ (calcs.total_items_price - use_point  ).toLocaleString() }}
                            </div>
                        </div>
                        <div class="row border-top text-danger pt-2">
                            <div class="col-auto">
                                還元ポイント：
                            </div>
                            <div class="col text-end">
                                {{calcs.sum_items_points_redemption.toLocaleString()}}pt
                            </div>
                        </div>

                    </div>
                </section>

                <div class="my-5">
                    <div v-if="selectedAddress==0" class="text-danger">*お届け先住所が選択されていません</div>

                    <button class="btn btn-warning btn-lg rounded-pill w-100 mb-3"
                    type="submit"
                    :disabled="selectedAddress==0"
                    >お支払いに進む</button>

                    <button class="btn btn-light border btn-lg rounded-pill w-100 mb-3"
                    @click="historyBack()"
                    type="button"
                    >注文商品を変更する</button>


                </div>



                <!--追加コンテンツ-->
                <slot></slot>



            <!-- </div> -->
        </div>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:{ type: String,  default: '', },

        /* お届け先一覧 */
        r_index:    { type: [String,Number], default: null },
        r_store:    { type: [String,Number], default: null },//＊新規作成コンポーネントで利用
        r_destroy:  { type: [String,Number], default: null },

        /*カート商品*/
        r_api_list: { type: String, default: '' },
        ids:        { type: String, default: '' },

        /*ユーザーポイント*/
        user_point: { type: [String,Number], default: 0 },
    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */

    /* カート商品 */
    const store_keeps = ref([]);

    /* アドレスの選択 */
    const selectedAddress = ref(0);

    /* 利用ポイント */
    const use_point = ref(0);

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



    /* 監視 */
    watch(use_point, () => {
        const val = Number(use_point.value);

        // 正の整数でない場合は 0 に修正
        if ( val < 0 ) {
            use_point.value = 0;
        }
        else if (
            /[.\-]/.test(val) ||           // . や - を含む
            /^0\d+/.test(val)              // 先頭が0で2桁以上
        ){
            use_point.value = 0;
        }
        // ユーザー所持ポイント以上の値は不可
        else if ( val > props.user_point ) {
            use_point.value = Number(props.user_point);
        }
        else{
            use_point.value = val;
        }

    });

    /* 初回データ取得 */
    onMounted(() => { getData(); });


    /* カート商品データ取得 */
    const getData = (route = props.r_api_list) => {

        loading.value = true;/* 読み込み */

        const inputs = {
            _token: props.token,
            ids   : props.ids.split(","),
            store_keep_ids: props.ids.split(","), //算出用
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



    /** お届け先アドレスの選択変更*/
    const updateSelectedAddressId = ( id )=>{
        selectedAddress.value = id;
    };


    /** 戻るボタン */
    const historyBack = ()=>{
        history.back(); return false;
    }



</script>
