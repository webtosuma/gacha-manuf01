<template>
    <div class="container px-0 overflow-hidden" style="min-height:50vh;">

        <!--絞り込み-->
        <div  v-if="!reading_data" class="row g-2 align-items-center justify-content-end mb-3   px-1">
            <div class="col col-lg-auto">
                <select
                v-model="inputs.search_key"
                @change="getData()"
                style="box-shadow:none;"
                class="form-select rounded-pill border border-2">

                    <option v-for="( search, key ) in searchs" :key="key"
                    :value="search.key"
                    >{{ search.label }}</option>

                </select>
            </div>
            <div class="col-auto">
                <button
                @click="changeCardSize()"
                type="button"
                style="font-size:11px;"
                class="btn btn-light border-0" >
                    {{ inputs.card_size=='sm' ?'大きく表示':'小さく表示'}}
                </button>

            </div>
        </div>
        <div v-else class="row g-2 align-items-center justify-content-end mb-3   px-1">
            <div class=" col col-lg-auto">
                <div class="px-5 py-2 bg-white rounded-pill">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!--読み込み中-->
        <div v-if="loading"
        class="row overflow-hidden gy-5  gx-md-5 mx-0 gx-3">
            <div v-for="(num, key) in [1,2,3,4,5,6]" :key="key"
            class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow bg-transparent
                text-dark text-center overflow-hidden text-decoration-none
                position-relative shiny
                hover_animeee"
                style="border-radius:1rem;">
                    <div class="ratio ratio-4x3 bg-body d-flex align-items-center justify-content-center">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="row align-items-center justify-content-center mt-3">
                    <div class="col text-center">
                        <div class="bg-body rounded-pill p-2 shadow" style="min-height:3rem;">
                        </div>
                    </div>

                    <div class="col text-center">
                        <div class="bg-body rounded-pill p-2 shadow" style="min-height:3rem;">
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <!--読み込み完了-->
        <div v-else>


            <div class="row overflow-hidden gy-5  gx-md-5 mx-1 pb-4 gy-y"
            :class="inputs.card_size=='sm'?' gx-3 ':' gx-0 '"
            >
                <div v-for="(gacha, key) in gachas" :key="key"
                :class="list_col_class" >

                    <!--人気順位-->
                    <div v-if="inputs.search_key=='desc_popularity'"
                    :class="{'invisible': gacha.is_sold_out}"
                    class="text-center text-white  mb-1">
                        <div class="bg-dark d-inline-block px-2">
                            第<span class="fs-3 px-1">{{ key+1 }}</span>位
                        </div>
                    </div>


                    <u-gacha-card
                    data-aos="zoom-inin"
                    :gacha="gacha"
                    :sm_card="inputs.card_size=='sm'?1:0"
                    />



                </div>


                <div v-if="gachas.length<1"
                class="col-12 text-secondary bg-light-subtle
                p-3 fs-5 rounded-3 shadow
                ">*該当するガチャがありません。</div>

            </div>


        </div>
    </div>
</template>

<script setup>
    import { ref, reactive, onMounted } from 'vue';
    import axios from 'axios';

    const props = defineProps({
        token:              { type: String, default: '' },
        category_code:      { type: String, default: '' }, // カテゴリーcode
        search_key:         { type: String, default: 'desc_crated' }, // 検索キーワード
        card_size:          { type: String, default: '' },
        order:              { type: String, default: '' }, // 並び順
        r_api_gacha_list:   { type: String, default: '' },
        sm_card:            { type: [String, Number, Boolean], default: 0 }, // カードの表示サイズ
        // is_desc_popularity: { type: [String, Number, Boolean], default: 0 }, // 人気順か否か
    });

    const loading      = ref(true);
    const reading_data = ref(true);
    const gachas       = ref([]); // ガチャ
    const searchs      = ref([]); // 検索キーワード

    /* 入力値 */
    const inputs = reactive({
        _token: props.token,
        category_code: props.category_code,
        search_key: props.search_key,
        card_size: props.card_size,
    });

    const list_col_class = ref('');
    const list_sm_col_class = ref('col-6 col-md-4 col-lg-3'); // 小さく表示 class
    const list_md_col_class = ref('col-12 col-md-6 col-lg-4'); // 大きく表示 class
    // const localStorageKey = ref('u.gacha.list.index.inputs'); // ローカルストレージキー



    // 初期データ設定＆データ取得
    onMounted(() => {

        setInitialData();
        getData();

    });



    /* データ取得 */
    const getData = async (route = props.r_api_gacha_list) => {
        if (route === props.r_api_gacha_list) {
            loading.value      = true;
            reading_data.value = true
            gachas.value       = [];
        }

        /* ローカルストレージ保存 */
        // localStorage.setItem( localStorageKey.value , JSON.stringify( inputs.value ));

        try {
            const response = await axios.post(route, inputs);
            const paginate = response.data.gachas;

            gachas.value =
            route === props.r_api_gacha_list ? paginate.data : [...gachas.value, ...paginate.data];

            searchs.value = response.data.searchs;
            loading.value = false;

            // 次のデータの読み込み
            const { current_page, last_page, next_page_url } = paginate;
            if (current_page !== last_page) {
                getData(next_page_url);
            }
            else{
                reading_data.value = false;
            }


        } catch (error) {

            console.error(error.response?.data);
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
            location.reload();
            }

        }
    };



    /* カードサイズの変更 */
    const changeCardSize = () => {

        inputs.card_size = inputs.card_size.length ? '' : 'sm';
        list_col_class.value = inputs.card_size === 'sm' ? list_sm_col_class : list_md_col_class;
        getData();

    };



    /* 初期データのセット */
    const setInitialData = () => {

        // const storedData = localStorage.getItem( localStorageKey.value ) || null ;
        // const storageInput = storedData._token ? JSON.parse(storedData) : {};

        // Object.assign( inputs.value , {
        //     _token: props.token,
        //     category_code: props.category_code,
        //     search_key: props.search_key || storageInput.search_key || 'desc_created',
        //     card_size: props.card_size || storageInput.card_size || '',
        // });

        list_col_class.value = inputs.card_size === 'sm' ? list_sm_col_class : list_md_col_class;
    };



</script>
