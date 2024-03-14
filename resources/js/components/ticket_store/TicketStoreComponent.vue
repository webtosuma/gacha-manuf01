<template>
    <div class="m-">


        <!--Headー-->
        <div class="row align-items-center gy-1 mb-3">
            <div class="col-12">
                <div class="d-flex gap-1">
                    <button v-for="(category,key) in categories" :key="key"
                    @click="setActiveCategory( category.id )"
                    :class=" inputs.category_id==category.id ? 'disabled btn-primary' : '' "
                    class="btn btn- border rounded-pill col col-md-auto" style="opacity:1;"
                    >{{ category.name }}</button>
                </div>
            </div>
            <div class="col-12 col-lg position-relative">
                <input v-model="inputs.key_words"
                @change="getData()"
                type="text" class="form-control rounded-pill" placeholder="商品名検索">

                <button @click="resetSearchKey"
                class="btn position-absolute top-50 translate-middle-y"
                style="right:1rem;">×</button>
            </div>
            <div class="col-12 col-md">
                <div class="d-flex gap-1">
                    <button v-for="(select_order,key) in select_orders" :key="key"
                    @click="changeOrder( select_order.value )"
                    :class=" inputs.order==select_order.value ? 'disabled btn-primary' : '' "
                    class="btn btn-sm border rounded-pill"
                    style="opacity:1;"
                    >{{ select_order.lable }}</button>
                </div>
            </div>
            <div class="col-12">
                該当商品数：
                <span class="fs-1 fw-bold">
                    <number-comma-component :number="stores.length" />
                </span>
            </div>
        </div>


        <!--body-->
        <div class="row gx-2 gy-4">



            <div v-for="(store, key) in stores" :key="key"
            class="col-4 col-md-3 col-lg-2">
                <a :href="r_api_show+'/'+store.id" class="d-block text-dark btn border-0 p-0">

                    <!--image-->
                    <div class="position-relative">

                        <!-- @include('ticket_store.common.prize_image') -->
                        <div class="position-relative pt-0">
                            <!--prize image-->
                            <div class="ratio ratio-3x4"
                            style="z-index:0;">
                                <ratio-image-component
                                :url="store.prize.image_path"
                                style_class="ratio ratio-3x4 rounded-3"
                                ></ratio-image-component>
                            </div>


                            <div v-if="store.count<1"
                            class="position-absolute top-0 start-0 w-100 h-100"
                            style="z-index:3; background: rgba(0, 0, 0, .8);"
                            ><div class="d-flex align-items-center justify-content-center h-100 fs- text-white"
                            >SOLD OUT</div></div>
                        </div>


                        <!--登録枚数-->
                        <div v-if="store.count>0"
                        class="position-absolute top-0 end-0 p-" style="transform: translate(6px, -6px);">
                            <div class="bg-dark text-white px-1 rounded-pill fw-bold fs-5"
                            >{{'×'+store.count}}</div>
                        </div>

                        <!--チケット枚数-->
                        <div class="position-absolute bottom-0 end-0 p-1 w-100">
                            <div class="d-flex gap-1 align-items-center justify-content-center text-success
                            px-2 rounded" style="font-size:11px; background-color: rgb(0 0 0 / 80%);">
                                <img :src="src_ticket_image"
                                alt="チケット" class="d-block mb-1"  style="width:20px; height:20px;">
                                <i class="bi bi-x"></i>
                                <div class="text-success">
                                    <span class="fs-5">{{store.ticket_count}}</span>枚
                                </div>
                            </div>
                        </div>

                    </div>

                </a>
            </div>


            <div v-if="stores.length<1"
            class="col-12 text-secondary py-5">*交換できる商品はありません</div>


        </div>
    </div>
</template>
<script>
    import axios from 'axios'

    export default {
        props: {
            token:{ type: String, default: '', },
            r_api_list:{ type: String, default: '', },   //交換商品一覧
            r_api_show:{ type: String, default: '', },   //詳細表示
            src_ticket_image:{ type: String,  default: '', },//チケット画像

        },
        data() { return {

            loading: true,

            categories:[],//ガチャ カテゴリー
            stores:  [],/* 交換用商品 */

            inputs: {},

            reset_inputs: {
                key_words: '',
                category_id: null,
                order:'desc_published_at',//並び順
            },


            select_orders :[
                { lable: '新しい順',    value:'desc_published_at', },
                { lable: '古い順',      value:'asc_published_at', },
                { lable: '高チケット順', value:'desc_ticket_count', },
                { lable: '低チケット順', value:'asc_ticket_count', },
            ],

        } },
        mounted() {

            /** デフォルトのデータ取得 */
            this.getDataReset();

        },
        methods:{

            /* 商品データ取得 */
            getData(route = this.r_api_list) {

                this.loading = true;//読み込み中

                axios.post( route , {_token: this.token, ...this.inputs} )
                .then(json => {
                    console.log(json.data);

                    // カテゴリー
                    this.categories = json.data.categories;

                    //ページネーションデータ
                    const paginate = json.data.stores;

                    // 商品情報の登録（新規登録・ページネーション追加）
                    this.stores = route == this.r_api_list ? paginate.data
                    : [ ...this.stores, ...paginate.data];

                    this.loading = false;//読み込み中

                    /* 次のデータの読み込み */
                    const current_page = paginate.current_page;//表示中ページ
                    const last_page    = paginate.last_page;   //最終ページ
                    if( current_page != last_page ){
                        const nextPageUrl = paginate.next_page_url;     //URLの更新
                        this.getData( nextPageUrl );
                    }
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );

                });
            },


            /** デフォルトのデータ取得 */
            getDataReset(){
                this.inputs = {...this.reset_inputs}; //入力値のリセット
                this.inputs._token = this.token; //token保存
                this.getData();
            },


            /** アクティブなカテゴリーのセット */
            setActiveCategory( category_id ) {

                this.inputs.key_words=''; //キーワードのリセット
                this.keyWords='',

                this.inputs.category_id = category_id;//アクティブなカテゴリーIDのセット
                this.getData(); /* データ取得 */
            },


            /* 並び順の変更 */
            changeOrder :function( value ){
                this.inputs.order = value;
                this.getData();
            },


            /* 検索キーワードのリセット */
            resetSearchKey :function(){
                this.inputs.key_words = '';
                this.getData();
            },

        },
    };
</script>
