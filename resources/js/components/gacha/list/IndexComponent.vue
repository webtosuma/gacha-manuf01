<template>
    <div class="container px-0 overflow-hidden" style="min-height:50vh;">

        <loading-cover-component :loading="loading" />


        <!--カスタムボタン　モーダル-->
        <u-gacha-custom-modal
        v-for="( gacha, key ) in gachas" :key="key+'custom-modal'"
        :one_play_point="gacha.one_play_point"
        :token="token"
        :r_action="gacha.r_action"
        :gacha_id="gacha.id"
        :max_count="gacha.remaining_count"
        :max_custom_type_count="gacha.max_custom_type_count"
        >
            <u-gacha-image
            :gacha_name            ="gacha.name"
            :gacha_ratio           ="gacha.ratio"
            :gacha_image_path      ="gacha.image_path"

            :initial_time          ="gacha.i_time"
            :limitted_i_time       ="gacha.limitted_i_time"
            :published_at_format   ="gacha.published_at_format"
            :remaining_count       ="gacha.remaining_count"
            :add_chance_image_path ="gacha.add_chance_image_path"
            :add_chance_count      ="gacha.add_chance_count"
            :have_user_rank        ="gacha.have_user_rank"
            :user_played_count     ="gacha.user_played_count"

            :img_path_one_chance   ="gacha.img_path_one_chance "
            :img_path_one_time     ="gacha.img_path_one_time"
            :img_path_only_oneday  ="gacha.img_path_only_oneday"
            :img_path_only_new_user="gacha.img_path_only_new_user"
            :img_path_user_rank    ="gacha.img_path_user_rank"
            />
        </u-gacha-custom-modal>



        <!--POPUPモーダル-->
        <u-gacha-modal
        v-for="( gacha, key ) in gachas" :key="key+'pupup-modal'"
        :one_play_point="gacha.one_play_point"
        :token="token"
        :r_action="gacha.r_action"
        :is_popup_btn="gacha.is_popup_btn"
        :gacha_id="gacha.id"
        >
            <u-gacha-image
            :gacha_name            ="gacha.name"
            :gacha_ratio           ="gacha.ratio"
            :gacha_image_path      ="gacha.image_path"

            :initial_time          ="gacha.i_time"
            :limitted_i_time       ="gacha.limitted_i_time"
            :published_at_format   ="gacha.published_at_format"
            :remaining_count       ="gacha.remaining_count"
            :add_chance_image_path ="gacha.add_chance_image_path"
            :add_chance_count      ="gacha.add_chance_count"
            :have_user_rank        ="gacha.have_user_rank"
            :user_played_count     ="gacha.user_played_count"

            :img_path_one_chance   ="gacha.img_path_one_chance "
            :img_path_one_time     ="gacha.img_path_one_time"
            :img_path_only_oneday  ="gacha.img_path_only_oneday"
            :img_path_only_new_user="gacha.img_path_only_new_user"
            :img_path_user_rank    ="gacha.img_path_user_rank"
            />
        </u-gacha-modal>



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


                <div v-if="gachas.length<1"  class="col-12 p-3">
                    <div class="text-secondary bg-light-subtle p-3 fs-5 rounded-3 shadow"
                    >*該当するガチャがありません。</div>
                </div>

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
                loading.value      = false;
                reading_data.value = false;
            }


        } catch (error) {

            // console.error(error.response?.data);
            // if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
            //     location.reload();
            // }
            loading.value = true;
            alert('データ読み込みに失敗しました。');

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

        list_col_class.value = inputs.card_size === 'sm' ? list_sm_col_class : list_md_col_class;

    };



</script>
