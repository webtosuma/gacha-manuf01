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


        <div class="row g-3">

            <!-- main リスト -->
            <div class="col order-lg-2">

                <!--header menu-->
                <div class="">
                    <div class="row g-3 align-items-center justify-content-between mb-2 px-2"  style="min-height:3rem;">
                        <div class="col">
                            <label  class="form-check">
                                <input
                                class="form-check-input p-2" type="checkbox" @click="toggleAllChecks">
                                <div class="form-check-label">すべて</div>
                            </label>
                        </div>

                        <!-- <div class="col">
                            <label  class="form-check">
                                <input v-model="allChecked"
                                class="form-check-input p-2" type="checkbox" @click="toggleAllChecks">
                                <div class="form-check-label">すべて</div>
                            </label>
                        </div>


                        <div v-if="inputs.ids.length" class="col-auto">
                            チェックしたものを：
                        </div> -->


                        <!--一括 対応切替-->
                        <!-- <div v-if="inputs.ids.length" class="col-auto">
                            <div class="input-group">
                                <select v-model="inputs.bulk_update_responsed_value"
                                class="form-select form-select-sm">
                                    <option v-for="(responsed, key) in ['対応済','未対応']" :key="key"
                                    :value="responsed"
                                    >{{ responsed }}</option>
                                </select>
                                <button @click="bulkUpdateResponsed()"
                                class="btn btn-sm btn-light border text-primary">に変更</button>
                            </div>
                        </div> -->
                        <!--一括 フォルダ変更-->
                        <!-- <div v-if="inputs.ids.length" class="col-auto">
                            <div class="input-group">
                                <select v-model="inputs.bulk_update_typetext_value"
                                class="form-select form-select-sm">
                                    <option value="">{{ '受信箱' }}</option>

                                    <option v-for="(type_text, key) in type_texts" :key="key"
                                    :value="type_text"
                                    >{{ type_text }}</option>
                                </select>
                                <button @click="bulkUpdateTypetext"
                                class="btn btn-sm btn-light border text-primary">フォルダに移動</button>
                            </div>
                        </div> -->
                        <!--一括 削除-->
                        <!-- <div v-if="inputs.ids.length " class="col-auto">
                            <button v-if="inputs.type_text=='ゴミ箱'"
                            data-bs-toggle="modal" :data-bs-target="'#deleteContactsModal'"
                            class="btn btn-sm border btn-light text-danger">すべて削除</button>
                        </div> -->
                    </div>
                </div>
                <!--DATA LIST-->
                <div class="list-group ">


                    <div v-if="!surveys.length" class="list-group-item py-5">
                        * アンケートの登録はありません
                    </div>


                    <div v-for=" (survey, index) in surveys " :key="index"
                    class="list-group-item p-0
                    d-flex align-items-center gap-3"
                    >
                        <!--チェックボックス-->
                        <div class="p-2 mb-1" style="width:2rem;">
                            <input v-model="inputs.ids"
                            :value="survey.id"
                            class="form-check-input p-2"
                            type="checkbox" >
                        </div>

                        <!--ガチャ連携中-->
                        <div class="badge text-white bg-success" style="width:4rem;"
                        >{{ 'ガチャ' }}</div>

                        <!--タイトル-->
                        <a href="" class="col">{{ survey.title }}</a>


                        <!--menu-->
                        <div class="dropdown">
                            <button
                            class="btn border btn-light rounded-pill" type="button"
                            :id="'dropdownMenuButton'+survey.id"
                            data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>

                            <ul class="dropdown-menu" :aria-labelledby="'dropdownMenuButton'+survey.id"  style="z-index:100;">

                                <li><a class="dropdown-item"
                                :href="survey.r_admin_edit"
                                >編集する</a></li>

                                <li><a class="dropdown-item"
                                :href=" survey.r_admin_show "
                                >プレビュー</a></li>

                                <li><a class="dropdown-item"
                                :href="survey.r_admin_answer"
                                >集計結果</a></li>

                                <li><form :action="survey.r_admin_copy" method="POST">
                                    <input type="hidden" name="_token" :value="token">
                                    <button type="submit" class="dropdown-item"
                                    >コピーする</button>
                                </form></li>

                                <li><button type="button" data-bs-toggle="modal"
                                :data-bs-target="'#deleteModal'+'delete'+survey.id"
                                class="dropdown-item"
                                >削除する</button></li>
                            </ul>
                        </div>


                        <!--削除モーダル-->
                        <div class="col-auto">
                            <form :action="survey.r_admin_destroy" method="post">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" :value="token">

                                <delete-modal-component
                                :index_key="'delete'+survey.id"
                                icon="bi-trash"
                                func_btn_type="submit"
                                button_class="d-none">
                                    <div>
                                        <span class="fw-bold">『{{survey.title}}』</span>を削除します。
                                        <br />よろしいですか？
                                    </div>
                                </delete-modal-component>
                            </form>
                        </div>


                    </div>
                </div>
                <div v-show="nextPageUrl" class="mt-3">
                    <a @click.prevent="getData( nextPageUrl )"
                    class="btn btn-light border"
                    href="">もっと読み込む</a>
                </div>


            </div>
            <!-- side -->
            <div class="col-12 col-lg-auto order-lg-1">
                <div class="position-sticky" style="top: 2rem; ">



                    <a :href="r_create"
                    class="btn btn-primary text-white mb-2 shadow w-100">
                    <i class="bi bi-plus-lg"></i>
                    {{'新規登録'}}
                    </a>



                </div>
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
    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */
    const messages    = ref([]);  /* ポップアップメッセージ */

    /* アンケート一覧 */
    const surveys = ref([]); //surveys

    const r_create = ref(''); /* [ルーティング]新規登録 */

    const inputs   = ref({
        _token: props.token,
        keyword:   '',
        month:     '',
        type_text: '',//フォルダの種類
        responsed: '絞り込み',//対応状況

        /* 一括処理パラメーター */
        ids: [],
    });


    /* [コンピューティッド]すべてチェック */
    // const allChecked = computed({
    //     get: () => inputs.value.ids.length === surveys.value.length,
    //     set: (value) => {
    //         if (value) {
    //             // すべて選択
    //             inputs.value.ids = surveys.value.map(survey => survey.id);
    //         } else {
    //             // すべて解除
    //             inputs.value.ids = [];
    //         }
    //     }
    // });


    /* 監視 */
    // watch(data, () => getData());


    /* 初回データ取得 */
    onMounted(() => {  getData(); });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {
        try {

            const response = await axios.post(route, inputs.value);
            console.log(response.data);
            const paginate = response.data['surveys'];

            surveys.value =
            route === props.r_api_list ? paginate.data : [...surveys.value, ...paginate.data];
            r_create.value = response.data.r_create;/* [ルーティング]新規登録 */
            loading.value = false;

            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;


        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };



    /* すべてチェック */
    const toggleAllChecks = () => {
        if (allChecked.value) {
            inputs.value.ids = [];
        } else {
            inputs.value.ids = contacts.value.map(contact => contact.id);
        }
    };



    /* 日時変換 */
    const formatAt = (inputString) => {
        const date = new Date(inputString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}/${month}/${day} ${hours}:${minutes}`;
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
