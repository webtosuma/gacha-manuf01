<template>
    <div class="container overflow-hidden" style="min-height:50vh;">

        <!-- <div class="bg-white">
            {{ inputs }}
        </div> -->



        <!--絞り込み-->
        <div class="row g-2 align-items-center justify-content-end mb-3">
            <div class="col col-lg-auto">
                <select
                v-model="inputs.search_key"
                @change="getData()"
                class="form-select form-select-sm rounded-pill border border-2">

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
                :class="search_style_class" >
                    {{ inputs.card_size=='sm' ?'大きく表示':'小さく表示'}}
                </button>

            </div>
        </div>

        <!--読み込み中-->
        <div v-if="loading"
        class="row overflow-hidden g-3 g-md-5 mx-0 pb-4 gy-4"
        data-aos="fade-in"
        >
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
                        <div class="rounded-pill p-2 shadow" style="min-height:3rem;">
                        </div>
                    </div>

                    <div class="col text-center">
                        <div class="rounded-pill p-2 shadow" style="min-height:3rem;">
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <!--読み込み完了-->
        <div v-else>


            <div class="row overflow-hidden g-3 g-md-5 mx-0 pb-4 gy-y" >
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

<script>
    import axios from 'axios';
    // import { get } from 'lodash';
    export default {
        props: {
            token:         { type: String,  default: '', },
            category_code: { type: String,  default: '', },//カテゴリーcode
            search_key:    { type: String,  default: 'desc_crated', },//検索キーワード
            card_size:     { type: String,  default: '', },
            order:         { type: String,  default: '', },//並び順
            sm_card:       { type: [String,Number,Boolean],  default: 0, },//カードの表示サイズ
            r_api_gacha_list:{ type: String,  default: '', },
            is_desc_popularity:{ type: [String,Number,Boolean],  default: 0, },//人気順か否か
        },
        data() { return {

            loading: true,

            gachas:[],//ガチャ
            countdown_gachas:[],//カウントダウンガチャ
            searchs:    [],//検索キーワード

            /* 入力値の初期設定 */
            inputs: {
                _token:        this.token,
                category_code: this.category_code,
                search_key:    this.search_key,//検索キーワード
                card_size:     this.card_size,
            },

            localStorageKey:   'u.gacha.list.index.inputs',//ローカルストレージキー

            search_style_class: " btn btn-sm btn-light border-0 px-2 py-",
            /*列の指定*/
            list_col_class:    '',//表示 class
            list_sm_col_class: 'col-6  col-md-4 col-lg-3',//小さく表示 class
            list_md_col_class: 'col-12 col-md-6 col-lg-4',//大きく表示 class


            // LSInputs: {},


        } },
        mounted() {

            /* カードサイズの変更 */
            this.setInitialData();

            /* データ取得 */
            this.getData();

        },
        methods:{


            /* データ取得 */
            getData :function(route = this.r_api_gacha_list){

                /* データの初期化 */
                if( route == this.r_api_gacha_list ){
                    this.loading = true;
                    this.gachas = [];
                }

                /* ローカルストレージ保存 */
                localStorage.setItem( this.localStorageKey, JSON.stringify(this.inputs) );


                const params = this.inputs;
                axios.post( route, params )
                .then(json => {

                    //ページネーションデータ
                    const paginate = json.data.gachas;

                    // 商品情報の登録（新規登録・ページネーション追加）
                    this.gachas = route == this.r_api_gacha_list ? paginate.data
                    : [ ...this.gachas, ...paginate.data];

                    //
                    this.searchs = json.data.searchs;

                    /* 読み込み完了 */
                    this.loading = false;

                    /* 次のデータの読み込み */
                    const current_page = paginate.current_page;//表示中ページ
                    const last_page    = paginate.last_page;   //最終ページ
                    if( current_page != last_page ){
                        const nextPageUrl = paginate.next_page_url;     //URLの更新
                        this.getData( nextPageUrl );
                    }

                })
                .catch(error => {
                    console.log( error.response.data );
                    if ( confirm("通信エラーが発生しました。再読み込みを行いますか？") ) {
                        location.reload();
                    }
                    // alert('通信エラーが発生しました。')


                });

            },


            /* カードサイズの変更 */
            changeCardSize: function(){
                this.inputs.card_size = this.inputs.card_size.length ? '' : 'sm' ;
                this.list_col_class = this.inputs.card_size=='sm' ? this.list_sm_col_class : this.list_md_col_class;
                this.getData();
            },


            /* 初期データのセット */
            setInitialData : function(){

                /* ローカルストレージ取得 */
                const storedData = localStorage.getItem( this.localStorageKey )
                const storage_input = storedData ? JSON.parse(storedData) : {};

                /* 入力値の初期設定 */
                this.inputs = {
                    _token:        this.token,
                    category_code: this.category_code,
                    search_key:    this.search_key  || ( storage_input.search_key  || 'desc_created'),//検索キーワード
                    card_size:     this.card_size   || ( storage_input.card_size  || ''),
                };

                /* カードサイズの変更 */
                const card_size = this.inputs.card_size;
                this.list_col_class =  card_size=='sm' ? this.list_sm_col_class : this.list_md_col_class;
            },
        },

    }
</script>
