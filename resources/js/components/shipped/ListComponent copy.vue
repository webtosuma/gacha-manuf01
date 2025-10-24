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
                            style="border-radius:.5rem 0 0 .5rem;">
                                発送待ち
                            </label>

                            <input v-model="inputs.state_id"
                            value="21"
                            type="radio" class="btn-check" id="btn-21" autocomplete="off">
                            <label class="btn btn-lg btn-outline-primary w-50" for="btn-21">
                                発送済み
                                <span v-if="unread_count>0"
                                class="badge rounded-pill bg-warning">{{unread_count}}</span>
                            </label>

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


                <li v-for="(user_shipped, key) in user_shipped" :key="key"
                class="col-12 col-lg-12 mb-3 ">
                    <a :href="user_shipped.r_show"
                    class="card card-body bg-white text-dark">

                        <div class="row g-3 align-items-center">
                            <div class="col">

                                <!--購入日・発送日-->
                                <div v-if="user_shipped.state_id==11">
                                    {{ user_shipped.done_at_format }}

                                    <span  class="badge text-danger">発送待ち</span>
                                </div>
                                <div v-if="user_shipped.state_id==21">

                                    {{ user_shipped.shipment_at_format }}

                                    <span class="badge text-success">発送済み</span>
                                    <span v-if="!user_shipped.shipment_read" class="badge rounded-pill bg-warning">未読</span>
                                </div>


                                <!--宛名-->
                                <div class="fs-5 text-primary">{{ user_shipped.address.name+' 様' }}</div>

                                <!--住所-->
                                <div class="form-text">
                                    <span>{{ '〒'+user_shipped.address.postal_code }}</span>
                                    <br>
                                    <span>{{ user_shipped.address.todohuken }}</span>
                                    <span>{{ user_shipped.address.shikuchoson }}</span>
                                    <span>{{ user_shipped.address.number }}</span>
                                </div>

                            </div>
                            <!--商品数-->
                            <div class="col-auto fs-5 text-end" style="width:5rem;">
                                <span>{{ user_shipped.sumItemsCount }}点</span>
                            </div>
                            <!--画像-->
                            <div class="col-auto d-none d-md-block" style="width:6rem;">
                                <ratio-image-component
                                :url="user_shipped.store_keeps[0].store_item.image_paths[0]"
                                style_class="ratio ratio-1x1 w-100 rounded"
                                ></ratio-image-component>
                            </div>
                            <div class="col-auto fs-3">
                                <i class="bi bi-chevron-right"></i>
                            </div>

                        </div>

                    </a>
                </li>




                <li v-if="!loading && user_shipped.length==0"
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

    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const nextPageUrl = ref('');  /* 次のデータの読み込みURL */

    /* 発送履歴 */
    const user_shipped = ref('');    //

    /* 合計件数 */
    const total_count = ref(0);    //

    /* 未読数 */
    const unread_count = ref(0);

    /* 入力値 */
    const inputs = ref({
        _token : props.token,
        state_id: 11,//発送状態
        month:    '',
        // order:  '',
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

            const paginate = response.data['user_shipped'];
            user_shipped.value =
            route === props.r_api_list ? paginate.data : [...user_shipped.value, ...paginate.data];

            /* 発送状態選択 */
            states.value = response.data.states || states.value;

            /* 年月選択肢(初回の読み込み時のみ) */
            months.value = response.data.months || months.value;

            /* 合計件数 */
            total_count.value  = response.data.total_count || total_count.value;

            /* 未読数 */
            unread_count.value = response.data.unread_count || unread_count.value;

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




</script>
<style scoped>
.btn-check:checked + .btn, :not(.btn-check) + .btn:active, .btn:first-child:active, .btn.active, .btn.show {
    color: white;
}
</style>
