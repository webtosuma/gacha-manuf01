<template>
    <div class="container overflow-hidden" style="min-height:50vh;">



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
        <div v-else
        class="row overflow-hidden g-3 g-md-5 mx-0 pb-4 gy-4"
        data-aos="zoom-in"
        >
            <div v-for="(gacha, key) in gachas" :key="key"
            :class="list_col_class" >


                <!--人気順位-->
                <div v-if="is_desc_popularity==1"
                :class="{'invisible': gacha.is_sold_out}"
                class="text-center text-info  mb-1">
                    <div class="bg-dark d-inline-block px-2">
                        第<span class="fs-3 px-1">{{ key+1 }}</span>位
                    </div>
                </div>


                <u-gacha-card
                :gacha="gacha"
                :sm_card="sm_card"
                />

            </div>


            <div v-if="gachas.length<1"
            class="col-12 text-secondary bg-light-subtle
            p-3 fs-5 rounded-3 shadow
            ">*該当するガチャがありません。</div>

        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        props: {

            token:         { type: String,  default: '', },
            category_code: { type: String,  default: '', },//カテゴリーcode
            search_key:    { type: String,  default: '', },//検索キーワード
            order:         { type: String,  default: '', },//並び順
            sm_card:       { type: [String,Number,Boolean],  default: 0, },//カードの表示サイズ
            r_api_gacha_list:{ type: String,  default: '', },
            is_desc_popularity:{ type: [String,Number,Boolean],  default: 0, },//人気順か否か

        },
        data() { return {

            loading: true,

            gachas:[],//ガチャ

            countdown_gachas:[],//カウントダウンガチャ

            /*列の指定*/
            list_col_class:    'col-12 col-md-6 col-lg-4',//大きく表示 class
            list_sm_col_class: 'col-6  col-md-4 col-lg-3',//小さく表示 class


        } },
        mounted() {

            /* カードの表示サイズ対応 */
            this.list_col_class = this.sm_card!=0 ? this.list_sm_col_class : this.list_col_class;

            /* データ取得 */
            this.getData();

        },
        methods:{


            /* データ取得 */
            getData :function(route = this.r_api_gacha_list){

                const params = {
                    _token:        this.token,
                    category_code: this.category_code,
                    search_key:    this.search_key,//検索キーワード
                };


                axios.post( route, params )
                .then(json => {

                    //ページネーションデータ
                    const paginate = json.data.gachas;

                    // 商品情報の登録（新規登録・ページネーション追加）
                    this.gachas = route == this.r_api_gacha_list ? paginate.data
                    : [ ...this.gachas, ...paginate.data];

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
        },

    }
</script>
