<template>
    <div class="">

        <!--loading-->
        <div v-if="loading"
         class="d-flex justify-content-center align-items-center"
        style="height:300px;"
        >
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>


        <!-- recomend list-->
        <div v-else>
            <div class="row g-3">
                <div v-for="(gacha, key) in gachas" :key="key"
                class="col-6">

                    <!--card-->
                    <u-gacha-card
                    :gacha="gacha"
                    sm_card="sm"
                    no_slider="1"
                    />

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:              { type: String, default: '' },
        category_code:      { type: String, default: '' }, // カテゴリーcode
        search_key:         { type: String, default: 'desc_published_at' }, // 検索キーワード
        order:              { type: String, default: '' }, // 並び順
        r_api_gacha_list:   { type: String, default: '' },
    });


    /* データの状態 */
    const loading      = ref(true);
    const gachas       = ref([]); // ガチャ
    const searchs      = ref([]); // 検索キーワード

    /* 入力値 */
    const inputs = ref({
        _token:        props.token,
        category_code: props.category_code,
        search_key:    props.search_key,

    });


    /* 監視 */
    // watch(data, () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        getData();
    });


    /* データ取得 */
    const getData = async (route = props.r_api_gacha_list) => {

        try {
            const response = await axios.post(route, inputs);
            const paginate = response.data.gachas;

            gachas.value =
            route === props.r_api_gacha_list ? paginate.data : [...gachas.value, ...paginate.data];

            searchs.value = response.data.searchs;
            loading.value = false;



        } catch (error) {

            // console.error(error.response?.data);
            // if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
            //     location.reload();
            // }
            loading.value = true;
            alert('データ読み込みに失敗しました。');

        }


    };





</script>
