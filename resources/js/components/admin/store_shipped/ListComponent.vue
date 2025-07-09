<template>
    <div class="row gy-3">


        <!--head menu-->
        <div class="col-12">
            <div class="position-sticky ps-2" style="top: 6rem; ">

                <loading-cover-component :loading="loading" />

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
                    <!--CSVダウンロード-->
                    <div class="col-auto">
                        <form :action="r_csv" method="get">

                            <!--入力値-->
                            <input v-for="(value, key) in inputs" :key="key"
                            type="hidden" :name="key" :value="value">

                            <!--注文履歴ID-->
                            <input v-for="( store_history, key) in store_histories" :key="key"
                            type="hidden" name="ids[]" :value="store_history.id">


                            <button type="submit" class="btn border w-100 py-0"
                            ><i class="bi bi-filetype-csv fs-4"></i>ダウンロード</button>
                        </form>
                    </div>
                    <!--合計数-->
                    <div class="col text-end">
                        <span>合計</span>
                        <span class="px-2 fs-1">{{ total_count }}</span>
                        <span>件</span>
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
        r_csv:       { type: String, default: '' },//CSV DL ルーティング
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
    });


    /* 選択枝 */
    const states = ref([]);/* 発送状態選択 */
    const months = ref([]);/* 購入年月選択 */
    // const orders = ref([]);/* 並び替え選択 */


    /* 監視 */
    watch(() => inputs.value.state_id, () => getData());
    watch(() => inputs.value.month,    () => getData());
    // watch(() => inputs.value.order,    () => getData());


    /* 初回データ取得 */
    onMounted(() => { getData(); });



    /* データ取得 */
    const getData = (route = props.r_api_list) => {

        loading.value = true;/* 読み込み */

        axios.post(route, inputs.value)
        .then(response => {
            // console.log( response.data );

            const paginate = response.data['store_histories'];
            store_histories.value =
            route === props.r_api_list ? paginate.data : [...store_histories.value, ...paginate.data];

            /* 発送状態選択 */
            states.value = response.data.states || states.value;

            /* 年月選択肢(初回の読み込み時のみ) */
            months.value = response.data.months || months.value;

            /* 合計件数 */
            total_count.value =response.data.total_count || total_count.value;

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
<style scoped>
.btn-check:checked + .btn, :not(.btn-check) + .btn:active, .btn:first-child:active, .btn.active, .btn.show {
    color: white;
}
</style>
