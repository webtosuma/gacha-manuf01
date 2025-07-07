<template>
    <div class="">

        <div class="position-relative">
            <input
            v-model="inputs.keyword"
            name="keyword"
            type="text"
            class="form-control form-control-lg px-5" placeholder="商品名"
            >

            <div class="position-absolute top-50 start-0 translate-middle-y ps-3 fs-5">
                <i class="bi bi-search"></i>
            </div>

            <button @click="resetKeyword()"
            type="button"
            class="btn position-absolute top-50 end-0 translate-middle-y pe-3">
                <i class="bi bi-x"></i>
            </button>
        </div>

        <!--ユーザーの検索履歴-->
        <div v-if="user_search_histories.length"
        class="row g-2 gx-4 my-3">
            <h6 class="col-12 m-0">履歴</h6>

            <div v-for="(search_history, key) in user_search_histories" :key="key"
            :class="col">

                <div class="row align-items-center">
                    <div class="col">
                        <button @click="setKeyword( search_history.keyword  )"
                        type="button"
                        class="btn btn-sm borderrr rounded-pill position-relative text-start w-100  px-5">

                            <div class="fs-5 text-break">{{ search_history.keyword }}</div>

                            <div class="position-absolute top-50 start-0 translate-middle-y fs-5 ms-3">
                                <i class="bi bi-search"></i>
                            </div>

                        </button>
                    </div>
                    <div class="col-auto">
                        <button @click="destroyKeyword(search_history.r_api_destroy)" style="z-index: 3;"
                        type="button"
                        class="btn btn-sm"><i class="bi bi-trash"></i></button>
                    </div>
                </div>

            </div>
        </div>

        <!--全体の検索履歴-->
        <div v-if="search_histories.length"
        class="row g-2 gx-4 my-3">
            <h6 class="col-12 m-0">候補</h6>

            <div v-for="(search_history, key) in search_histories" :key="key"
            :class="col">
                <button @click="setKeyword( search_history.keyword  )"
                type="button"
                class="btn btn-sm borderrr rounded-pill position-relative text-start w-100  px-5">

                    <div class="fs-5 text-break">{{ search_history.keyword }}</div>

                    <div class="position-absolute top-50 start-0 translate-middle-y fs-5 ms-3">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>

                </button>
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
        keyword:     { type: String, default: '' },
        col: { type: String, default: 'col-12 col-md-6' },//カラムのサイズ
    });


    /* データの状態 */
    const loading = ref(true); /* 読み込み中 */

    const search_histories = ref([]);  /* 全体の検索履歴 */

    const user_search_histories = ref([]);  /* ユーザーの検索履歴 */

    const inputs   = ref({
        _token:  props.token,
        keyword: props.keyword,
    });


    /* 監視 */
    watch(() => inputs.value.keyword, () => getData());


    /* 初回データ取得 */
    onMounted(() => { getData(); });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {
        try {

            const response      = await axios.post(route, inputs.value);
            search_histories.value      = response.data['search_histories'];
            user_search_histories.value = response.data['user_search_histories'];

        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };


    /* キーワードの削除 */
    const destroyKeyword = async (route) => {
        try {

            const response = await axios.post(route, {...inputs.value, _method: 'delete'});
            getData();

        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };

    /* キーワードのセット */
    const setKeyword = (keyword) => {
        inputs.value.keyword = keyword;
    };

    /* キーワードのリセット */
    const resetKeyword = () => {
        inputs.value.keyword = '';
    };

</script>
