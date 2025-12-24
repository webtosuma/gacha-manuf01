<template>
    <div >

        <loading-cover-component :loading="loading" />



        <!--header-->
        <div class="">

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
            <div class="row g-2 align-items-center justify-content-between mb-2 px-2"
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
                        <input v-for="(user_shipped_id,key) in inputs.user_shipped_ids" :key="key"
                        type="hidden" name="ids[]" :value="user_shipped_id">

                        <input type="hidden" name="state_id" v-model="inputs.state_id">


                        <button class="btn btn-sm btn-light border"
                        :disabled="!inputs.user_shipped_ids.length"
                        ><i class="bi bi-filetype-csv fs-5"></i>ダウンロード</button>
                    </form>
                </div>
                <!--一括発送処理-->
                <div v-show="inputs.state_id==11 && r_update" class="col-auto">
                    <button type="button" data-bs-toggle="modal"
                    :data-bs-target="'#deleteModal'+'send'"
                    class="btn  btn-light border w-100"
                    :disabled="!inputs.user_shipped_ids.length"
                    >{{ '発送通知' }}</button>
                </div>
                <!--一括発送キャンセル-->
                <div v-show="inputs.state_id==11" class="col-auto">
                    <button type="button" data-bs-toggle="modal"
                    :data-bs-target="'#deleteModal'+'cancell'"
                    class="btn  btn-light border text-danger w-100"
                    :disabled="!inputs.user_shipped_ids.length"
                    >{{ '申請取消し' }}</button>
                </div>

            </div>

        </div>


        <!--一覧-->
        <ul class="row p-0" style="list-style:none;">


            <li v-for="(user_shipped, key) in user_shippeds" :key="key"
            class="col-12 col-lg-12 mb-3 ">
                <div class="card card-body bg-white text-dark">
                    <div class="row g-3 align-items-center">

                        <div class="col-auto">

                            <!--チェックボックス-->
                            <input v-model="inputs.user_shipped_ids"
                            :value="user_shipped.id"
                            class="form-check-input p-2"
                            type="checkbox" >


                        </div>
                        <div class="col-auto">

                            <!--購入日・発送日-->
                            <div v-if="user_shipped.shipment_at_format"
                            >{{ user_shipped.shipment_at_format }}</div>
                            <div v-else
                            >{{ user_shipped.created_at_format }}</div>




                            <!--発送コード-->
                            <div class="">発送コード；{{ user_shipped.code }}</div>

                            <!--発送状況-->
                            <div v-if="user_shipped.state_id==11">

                                <span  class="badge text-danger border border-danger">発送待ち</span>

                            </div>
                            <div v-if="user_shipped.state_id==21">

                                <span class="badge text-success border border-success">発送済み</span>
                                <span v-if="!user_shipped.shipment_read"
                                class="badge rounded-pill bg-warning border border-warning"
                                >ユーザー未読</span>

                            </div>

                        </div>
                        <div class="col text-center">

                            <!--宛名-->
                            <a :href="user_shipped.r_admin_show"
                            class="fs-5 text-primary">{{ user_shipped.user_address.name+' 様' }}</a>

                        </div>
                        <div class="col text-center">

                            <!--ユーザー情報-->
                            <a :href="user_shipped.r_admin_user"
                            >{{ 'ID:'+user_shipped.user.id+' '+user_shipped.user.name }}</a>

                            <div v-if="user_shipped.user.deleted_at" class="text-danger">*退会済み</div>


                        </div>
                        <div class="col d-none d-md-block">
                            <!--住所-->
                            <div class="">
                                <span>{{ '〒'+user_shipped.user_address.postal_code }}</span>
                                <br>
                                <span>{{ user_shipped.user_address.todohuken }}</span>
                                <span>{{ user_shipped.user_address.shikuchoson }}</span>
                                <span>{{ user_shipped.user_address.number }}</span>
                            </div>
                        </div>
                        <!--商品数-->
                        <div class="col-auto fs-5 text-end" style="width:5rem;">
                            <span>{{ user_shipped.user_prizes.length }}点</span>
                        </div>

                    </div>
                </div>
            </li>


            <li v-if="!loading && user_shippeds.length==0"
            class="py-3">*発送の履歴はありません。</li>


            <li>
                <!-- ページネーション -->
                <pagenation-component
                :pagenate="pagenate"
                :data="user_shippeds"
                @cahnge-data="getData"
                />
            </li>


        </ul>


        <!--一括発送通知モーダル-->
        <form v-if="r_update"
         :action="r_update" method="POST">

            <input type="hidden" name="_token" :value="token">
            <input type="hidden" name="_method" value="patch">

            <input v-for="(user_shipped_id,key) in inputs.user_shipped_ids" :key="key"
            type="hidden" name="ids[]" :value="user_shipped_id">

            <delete-modal-component
            index_key="send"
            icon="bi-send"
            color="warning"
            func_btn_type="submit"
            button_class="invisible"
            >
                <div>
                    <h5 class="text-warning">
                        発送する内容にお間違いはありませんか？
                    </h5>
                    <p class="form-text">
                        発送通知後の修正を行うことはできません。<br>
                        発送通知を一括送信してもよろしいですか？
                    </p>
                </div>
            </delete-modal-component>

        </form>


        <!--一括発発送取消しモーダル-->
        <form v-if="r_cancell"
         :action="r_cancell" method="POST">

            <input type="hidden" name="_token" :value="token">
            <input type="hidden" name="_method" value="patch">

            <input v-for="(user_shipped_id,key) in inputs.user_shipped_ids" :key="key"
            type="hidden" name="ids[]" :value="user_shipped_id">

            <delete-modal-component
            index_key="cancell"
            icon="bi-arrow-return-left"
            color="danger"
            func_btn_type="submit"
            button_class="invisible"
            >
                <div>
                    <h5 class="text-danger">
                        発送キャンセルの内容にお間違いはありませんか？
                    </h5>
                    <p class="form-text">
                        発送申請された商品が、ユーザー取得商品に戻されます。<br>
                        発送キャンセルをしてもよろしいですか？
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
        mount_state_id:{ type: String, default: '' },
        r_api_list:  { type: String, default: '' },
        r_csv:       { type: String, default: '' },//[ルーティング]CSV DL ルーティング
        r_update:    { type: String, default: '' },//[ルーティング]一括発送処理
        r_cancell:   { type: String, default: '' },//[ルーティング]一括発送キャンセル
    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const nextPageUrl = ref('');  /* 次のデータの読み込みURL */

    /* 発送履歴 */
    const user_shippeds = ref('');    //

    /* 合計件数 */
    const total_count = ref(0);    //

    /* 入力値 */
    const inputs = ref({
        _token : props.token,
        state_id: 11,//発送状態
        month:    '',
        user_shipped_ids: [], //チェック中ID
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
        get: () => inputs.value.user_shipped_ids.length === user_shippeds.value.length,
        set: (value) => {
            if (value) {
                // すべて選択
                inputs.value.user_shipped_ids = user_shippeds.value.map(user_shipped => user_shipped.id);
            } else {
                // すべて解除
                inputs.value.user_shipped_ids = [];
            }
        }
    });


    /* 監視 */
    watch(() => inputs.value.state_id,  () => getData());
    watch(() => inputs.value.month,     () => getData());
    watch(() => inputs.value.page_count,() => getData());


    /* 初回データ取得 */
    onMounted(() => {
        inputs.value.state_id = props.mount_state_id || inputs.value.state_id;
        getData();
    });


    /* データ取得 */
    const getData = (route = props.r_api_list) => {

        loading.value = true;/* 読み込み */

        axios.post(route, inputs.value)
        .then(response => {
            // console.log( response.data );

            const paginate = response.data['user_shippeds'];
            user_shippeds.value = paginate.data ;

            /* 発送状態選択 */
            states.value = response.data.states || states.value;

            /* 年月選択肢(初回の読み込み時のみ) */
            months.value = response.data.months || months.value;

            /* 合計件数 */
            total_count.value = response.data.total_count===null ? total_count.value : response.data.total_count ;
            console.log(response.data);

            loading.value = false;/* 読み込み */


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




    /* すべてチェック */
    const toggleAllChecks = () => {
        if (allChecked.value) {
            inputs.value.user_shipped_ids = [];
        } else {
            inputs.value.user_shipped_ids = user_shippeds.value.map(user_shipped => user_shipped.id);
        }
    };


</script>
<style scoped>
.btn-check:checked + .btn, :not(.btn-check) + .btn:active, .btn:first-child:active, .btn.active, .btn.show {
    color: white;
}
</style>
