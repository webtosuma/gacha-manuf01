<template>
    <div class="">

        <loading-cover-component :loading="loading" />



        <div class="row gy-0">

            <!--head menu-->
            <div class="col-12">
                <div class="position-sticky ps-2" style="top: 6rem; ">

                    <!--main header menu-->
                    <div class="row g-2 align-items-center">
                        <!--発送状態-->
                        <div class="col-12 col-md">
                            <div class="input-group mb-3 px-2 overflow-hidden">

                                <input v-model="inputs.state_id"
                                value="11"
                                type="radio" class="btn-check" id="btn-11" autocomplete="off">
                                <label class="btn btn-lg btn-outline-primary w-50" for="btn-11"
                                style="border-radius:.5rem 0 0 .5rem;"
                                >発送待ち</label>

                                <input v-model="inputs.state_id"
                                value="21"
                                type="radio" class="btn-check" id="btn-21" autocomplete="off">
                                <label class="btn btn-lg btn-outline-primary w-50" for="btn-21"
                                >発送済み</label>

                            </div>
                        </div>
                        <!--購入年月-->
                        <div class="col-auto">
                            <select
                            v-model="inputs.month"
                            class="form-select">
                                <option value="">購入年月</option>

                                <option v-for="( month, key ) in months" :key="key"
                                :value="month.date_stanp"
                                >{{month.format}}</option>
                            </select>
                        </div>
                        <!--表示件数-->
                        <div class="col-auto">
                            <select
                            v-model="inputs.page_count"
                            class="form-select">
                                <option v-for="( page_count, key ) in page_counts" :key="key"
                                :value="page_count"
                                >{{page_count+'件表示'}}</option>
                            </select>
                        </div>
                        <!--合計数-->
                        <div class="col text-end">
                            <span>合計</span>
                            <span class="px-2 fs-1">{{ total_count }}</span>
                            <span>件</span>
                        </div>

                    </div>


                    <!--sub header menu-->
                    <div class="row g-3 align-items-center justify-content-between mb-2 px-2"
                    style="min-height:3rem;">
                        <div class="col">
                            <label  class="form-check">
                                <input v-model="allChecked"
                                @click="toggleAllChecks"
                                class="form-check-input p-2" type="checkbox" >
                                <div class="form-check-label">すべて</div>
                            </label>
                        </div>


                        <div class="col-auto">
                            チェックしたものを：
                        </div>
                        <!--csv_DL-->
                        <div class="col-auto">
                            <form :action="r_csv">
                                <input v-for="(store_history_id,key) in inputs.store_history_ids" :key="key"
                                type="hidden" name="ids[]" :value="store_history_id">

                                <button class="btn btn-sm btn-light border"
                                :disabled="!inputs.store_history_ids.length"
                                ><i class="bi bi-filetype-csv fs-5"></i>ダウンロード</button>
                            </form>
                        </div>
                        <!--一括発送処理-->
                        <div v-show="inputs.state_id==11 && r_update" class="col-auto">
                            <button type="button" data-bs-toggle="modal"
                            :data-bs-target="'#deleteModal'+'send'"
                            class="btn  btn-light border text-danger w-100"
                            :disabled="!inputs.store_history_ids.length"
                            >{{ '一括送信通知' }}</button>
                        </div>

                    </div>

                </div>
            </div>






            <!--一覧-->
            <div class="col ">
                <ul class="row p-0" style="list-style:none;">

                    <li v-for="(store_history, key) in store_histories" :key="key"
                    class="col-12 col-lg-12 mb-3 ">
                        <div class="card card-body bg-white text-dark">

                            <div class="row g-3 align-items-center">

                                <div class="col-auto">

                                    <!--チェックボックス-->
                                    <input v-model="inputs.store_history_ids"
                                    :value="store_history.id"
                                    class="form-check-input p-2"
                                    type="checkbox" >


                                </div>
                                <div class="col-auto">

                                    <!--購入日・発送日-->
                                    <div v-if="store_history.state_id==11">
                                        {{ store_history.done_at_format }}

                                        <span  class="badge text-danger">発送待ち</span>
                                    </div>
                                    <div v-if="store_history.state_id==21">

                                        {{ store_history.shipment_at_format }}

                                        <span class="badge text-success">発送済み</span>
                                        <span v-if="!store_history.shipment_read" class="badge rounded-pill bg-warning">未読</span>
                                    </div>

                                    <!--発送コード-->
                                    <div class="">発送コード；{{ store_history.code }}</div>


                                </div>
                                <div class="col text-center">

                                    <!--宛名-->
                                    <a :href="store_history.r_admin_show"
                                    class="fs-5 text-primary">{{ store_history.address.name+' 様' }}</a>

                                </div>
                                <div class="col text-center">

                                    <!--ユーザー情報-->
                                    <a :href="store_history.r_admin_user"
                                    >{{ 'ID:'+store_history.user.id+' '+store_history.user.name }}</a>


                                </div>
                                <div class="col d-none d-md-block">
                                    <!--住所-->
                                    <div class="">
                                        <span>{{ '〒'+store_history.address.postal_code }}</span>
                                        <br>
                                        <span>{{ store_history.address.todohuken }}</span>
                                        <span>{{ store_history.address.shikuchoson }}</span>
                                        <span>{{ store_history.address.number }}</span>
                                    </div>
                                </div>
                                <!--商品数-->
                                <div class="col-auto fs-5 text-end" style="width:5rem;">
                                    <span>{{ store_history.sumItemsCount }}点</span>
                                </div>
                                <!-- <div class="col-auto fs-3">
                                    <i class="bi bi-chevron-right"></i>
                                </div> -->

                            </div>

                        </div>
                    </li>




                    <li v-if="!loading && store_histories.length==0"
                    class="py-3">*発送の履歴はありません。</li>

                </ul>

                <!-- ページネーション -->
                <pagenation-component
                :pagenate="pagenate"
                :data="store_histories"
                @cahnge-data="getData"
                />

            </div>



        </div>


        <!--一括発送通知モーダル-->
        <form v-if="r_update"
         :action="r_update" method="POST">

            <input type="hidden" name="_token" :value="token">
            <input type="hidden" name="_method" value="patch">

            <input v-for="(store_history_id,key) in inputs.store_history_ids" :key="key"
            type="hidden" name="ids[]" :value="store_history_id">

            <delete-modal-component
            index_key="send"
            icon="bi-send"
            color="danger"
            func_btn_type="submit"
            button_class="invisible"
            >
                <div>
                    <h5 class="text-danger">
                        発送する内容にお間違いはありませんか？
                    </h5>
                    <p class="form-text">
                        発送通知後の修正を行うことはできません。<br>
                        発送通知を一括送信してもよろしいですか？
                    </p>
                </div>
            </delete-modal-component>

        </form>



    </div>
</template>

<script setup>
    import { ref, computed, watch, onMounted, } from 'vue';

    import axios from 'axios';

    const props = defineProps({
        token:       { type: String, default: '' },
        r_api_list:  { type: String, default: '' },
        r_csv:       { type: String, default: '' },//[ルーティング]CSV DL ルーティング
        r_update:    { type: String, default: '' },//[ルーティング]一括発送処理
    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const nextPageUrl = ref('');  /* 次のデータの読み込みURL */

    /* 発送履歴 */
    const store_histories = ref('');    //

    /* 合計件数 */
    const total_count = ref(0);    //

    /* 入力値 */
    const inputs = ref({
        _token : props.token,
        state_id: 11,//発送状態
        month:    '',
        store_history_ids: [], //チェック中ID
        page_count: 20,//表示ページ
    });

    /* ページネーションデータ */
    const pagenate = ref({
        current_page :0,
        links: {}
    });


    /* 選択枝 */
    const states = ref([]);/* 発送状態選択 */
    const months = ref([]);/* 購入年月選択 */
    const page_counts = ref([20,50,100]);
    // const orders = ref([]);/* 並び替え選択 */


    /* [コンピューティッド]すべてチェック */
    const allChecked = computed({
        get: () => inputs.value.store_history_ids.length === store_histories.value.length,
        set: (value) => {
            if (value) {
                // すべて選択
                inputs.value.store_history_ids = store_histories.value.map(store_history => store_history.id);
            } else {
                // すべて解除
                inputs.value.store_history_ids = [];
            }
        }
    });


    /* 監視 */
    watch(() => inputs.value.state_id,  () => getData());
    watch(() => inputs.value.month,     () => getData());
    watch(() => inputs.value.page_count,() => getData());

    // watch(() => inputs.value.order,    () => getData());


    /* 初回データ取得 */
    onMounted(() => { getData(); });

    //users

    /* データ取得 */
    const getData = (route = props.r_api_list) => {

        loading.value = true;/* 読み込み */

        axios.post(route, inputs.value)
        .then(response => {
            // console.log( response.data );

            const paginate = response.data['store_histories'];
            store_histories.value = paginate.data ;

            /* 発送状態選択 */
            states.value = response.data.states || states.value;

            /* 年月選択肢(初回の読み込み時のみ) */
            months.value = response.data.months || months.value;

            /* 合計件数 */
            total_count.value = response.data.total_count===null ? total_count.value : response.data.total_count ;
            console.log(response.data);

            loading.value = false;/* 読み込み */

            /* 次のデータの読み込み */
            // const { current_page, last_page, next_page_url } = paginate;
            // if (current_page !== last_page) {
            //     getData(next_page_url);
            // }

            /* 次のデータURLの保存 */
            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

            pagenate.value.current_page = paginate.current_page;//表示中ページ
            pagenate.value.links = paginate.links;//ページネートURL


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


    /* すべてチェック */
    const toggleAllChecks = () => {
        if (allChecked.value) {
            inputs.value.store_history_ids = [];
        } else {
            inputs.value.store_history_ids = store_histories.value.map(store_history => store_history.id);
        }
    };


</script>
<style scoped>
.btn-check:checked + .btn, :not(.btn-check) + .btn:active, .btn:first-child:active, .btn.active, .btn.show {
    color: white;
}
</style>
