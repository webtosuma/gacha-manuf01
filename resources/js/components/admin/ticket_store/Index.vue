<template>
    <div class="">

        <!--カテゴリー-->
        <section class="mb-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a @click="setActiveCategory( '' )"
                    :class="{'active disabled': inputs.category_id == ''}" class="nav-link" href="#"
                    >{{ 'すべて' }}</a>

                </li>
                <li v-for="(category, key) in categories" :key="key"
                class="nav-item">
                    <a @click="setActiveCategory( category.id )"
                    :class="{'active disabled': inputs.category_id == category.id}" class="nav-link" href="#"
                    >{{ category.name }}</a>

                </li>
            </ul>
        </section>



        <section class="mb-2">
            <div class="row">
                <div class="col-auto me-" v-if="edit">
                    <create-store-modal
                    @create-store="getDataReset()"
                    :category_id="inputs.category_id "
                    :r_api_prize="r_api_prize"
                    :r_api_create="r_api_create"
                    />
                </div>

                <div class="col col-lg-4">
                    <input @change="getData()" v-model="inputs.key_words"
                    type="text" class="form-control" placeholder="検索：商品名・商品コード名"
                    aria-label="Username" aria-describedby="basic-addon1" />
                </div>

                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text">最大・最低チケット枚数</span>
                        <input @change="getData()"
                        v-model="inputs.max_ticket"
                        type="number" class="form-control"
                        placeholder="最大チケット" style="width:6rem;">
                        <input @change="getData()"
                        v-model="inputs.min_ticket"
                        type="number" class="form-control"
                        placeholder="最低チケット" style="width:6rem;">
                    </div>
                </div>

                <div class="col-auto" v-if="!edit">
                    <button @click="toggleEdit()"
                    class="btn btn-outline-warning" type="button"
                    ><i class="bi bi-pencil-fill me-2"></i>一括編集</button>
                </div>
                <div class="col-auto" v-if="edit">
                    <button @click="toggleEdit()"
                    class="btn btn-warning" type="button"
                    ><i class="bi bi-pencil-fill me-2"></i>一括編集 終了</button>
                </div>
            </div>
        </section>




        <!--テーブル-->
        <section class="card card-body bg-white my-3 overflow-auto"
        :class="{'border-warning border-3':edit}"
        style="height: 90vh;">
            <table class="table bg-white " style="min-width: 600px; font-size: 16px;">


                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th scope="col"><a
                        @click.prevent="changeOrder( 'order_published_at' )"
                        href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                            <span>公開</span>
                            <i v-if="inputs['order_published_at']!='asc'" class="bi bi-caret-up-fill"></i>
                            <i v-if="inputs['order_published_at']!='desc'"  class="bi bi-caret-down-fill"></i>
                        </a></th>

                        <th scope="col"></th>
                        <th scope="col">商品名</th>
                        <!-- <th scope="col">交換チケット</th> -->
                        <th scope="col"><a
                        @click.prevent="changeOrder( 'order_ticket_count' )"
                        href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                            <span>交換チケット</span>
                            <i v-if="inputs['order_ticket_count']!='asc'" class="bi bi-caret-up-fill"></i>
                            <i v-if="inputs['order_ticket_count']!='desc'"  class="bi bi-caret-down-fill"></i>
                        </a></th>

                        <th scope="col"><a
                        @click.prevent="changeOrder( 'order_point_count' )"
                        href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                            <span>交換ポイント</span>
                            <i v-if="inputs['order_point_count']!='asc'" class="bi bi-caret-up-fill"></i>
                            <i v-if="inputs['order_point_count']!='desc'"  class="bi bi-caret-down-fill"></i>
                        </a></th>

                        <th scope="col"><a
                        @click.prevent="changeOrder( 'order_count' )"
                        href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                            <span>在庫</span>
                            <i v-if="inputs['order_count']!='asc'" class="bi bi-caret-up-fill"></i>
                            <i v-if="inputs['order_count']!='desc'"  class="bi bi-caret-down-fill"></i>
                        </a></th>

                        <th v-if="edit"><!--*削除*--></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(store, key) in stores" :key="key">
                        <!--公開-->
                        <td style="width:6.8rem;">
                            <span v-if="store.is_published"
                            class="badge rounded-pill bg-success">{{ '公開中' }}</span>
                            <span v-else
                            class="badge rounded-pill bg-danger">{{ '非公開' }}</span>


                            <div v-if="edit"
                            class="form-check form-switch">
                                <input v-model="store.is_published"
                                @change="update(store)"
                                class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" >
                            </div>
                            <div v-else
                            class="form-text">{{ formatDate(store.published_at) }}</div>

                        </td>
                        <!--画像-->
                        <td style="width:4rem;">
                            <ratio-image-component
                            style_class="ratio ratio-3x4 rounded w-100"
                            :url=" store.prize.image_path " />
                        </td>
                        <!--商品名-->
                        <td>
                            <div class="">{{ store.prize.name }}</div>
                            <div class="form-text">{{ store.prize.code }}</div>
                        </td>
                        <!--交換チケット-->
                        <td>
                            <div class="row g-1 align-items-center">
                                <div class="col"  v-if="edit">
                                    <input v-model="store.ticket_count"
                                    @change="update(store)"
                                    type="number" class="form-control" min="0">
                                </div>
                                <div class="col-auto" v-else>{{ store.ticket_count }}</div>
                                <div class="col-auto">枚</div>
                            </div>
                        </td>
                        <!--交換ポイント-->
                        <td>
                            <div class="row g-1 align-items-center">
                                <div class="col" v-if="edit">
                                    <input v-model="store.point_count"
                                    @change="update(store)"
                                    type="number" class="form-control" min="0">
                                </div>
                                <div class="col-auto" v-else>{{ store.point_count }}</div>
                                <div class="col-auto">pt</div>
                            </div>
                        </td>

                        <!--在庫数-->
                        <td>
                            <div class="">
                                <input v-if="edit"
                                v-model="store.count"
                                @change="update(store)"
                                type="number" class="form-control" min="0">

                                <span v-else>{{ store.count }}</span>
                            </div>
                            <div v-if="!store.count" class="badge bg-danger">SOLD OUT</div>
                        </td>
                        <!--削除ボタン-->
                        <td v-if="edit">
                            <delete-modal-component
                            @parent-func="destory( store )"
                            icon="bi-trash"
                            :index_key="'delete'+store.id"
                            func_btn_type="button"
                            button_class="btn btn-sm btn-light border ">
                                <div>
                                    『<span class="fw-bold">{{ store.prize.name }}</span>』を削除します。
                                    <br />よろしいですか？
                                </div>
                            </delete-modal-component>
                        </td>
                    </tr>
                </tbody>




            </table>
        </section>





    </div>
</template>
<script>
    import axios from 'axios'

    export default {
        components: {
            'create-store-modal': require('./CreateStoreModal.vue').default,//編集コンポネント
        },

        props: {
            token:{ type: String,  default: '', },
            r_api_store:{ type: String,  default: '', },   //交換商品
            r_api_category:{ type: String,  default: '', },//ガチャ カテゴリー
            r_api_prize:{ type: String,  default: '', },   //商品

            r_api_create: { type: String,  default: '', },//新規作成
            r_api_update: { type: String,  default: '', },//更新
            r_api_destroy: { type: String,  default: '', },//削除

            category_id:{ type: [String,Number],  default: '', },
        },
        data() { return {

            loading: true,

            categories:[],//ガチャ カテゴリー
            stores:  [],/* 交換用商品 */

            inputs: {},

            reset_inputs: {
                key_words: '',
                category_id: '',

                order_ticket_count: '',
                order_published_at: '',
                order_point_count:  '',
                order_count: '',
                max_ticket: null,
                min_ticket: null,
            },

            edit: false,
            // edit: true,

        } },
        mounted() {

            this.inputs = {...this.reset_inputs}; //入力値のリセット
            this.inputs._token = this.token; //token保存
            this.inputs.category_id = this.category_id ;
            this.getCategoryData();/* データ取得 */

        },
        methods:{

            /* 商品データ取得 */
            getData(route = this.r_api_store) {

                this.loading = true;//読み込み中

                axios.post( route , {_token: this.token, ...this.inputs} )
                .then(json => {
                    // console.log(json.data);

                    //ページネーションデータ
                    const paginate = json.data.stores;

                    // 商品情報の登録（新規登録・ページネーション追加）
                    this.stores = route == this.r_api_store ? paginate.data
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
                this.inputs.category_id = this.category_id ;
                this.getData();
            },



            /** データ更新 */
            update( store ){
                console.log(store);

                const route = this.r_api_update+'/'+store.id;
                axios.patch( route , {_token: this.token, ...store} )
                .then(json => {
                    // console.log(json.data);
                    this.getData(); /* データ取得 */
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );
                });

            },



            /** データ削除 */
            destory( store ) {

                const route = this.r_api_destroy+'/'+store.id;
                axios.delete( route , {_token: this.token, ...store} )
                .then(json => {
                    // console.log(json.data);
                    this.getData(); /* データ取得 */
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );
                });
            },



            /** カテゴリー　データ取得 */
            getCategoryData() {
                const route = this.r_api_category;
                axios.post( route , this.inputs )
                .then(json => {
                    // console.log(json.data);
                    this.categories = json.data;

                    /** アクティブなカテゴリーのセット *//* 商品データ取得 */
                    this.setActiveCategory( this.category_id );
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );

                });
            },




            /** アクティブなカテゴリーのセット */
            setActiveCategory( category_id ) {

                this.inputs.key_words=''; //キーワードのリセット
                this.keyWords='',

                this.inputs.category_id = category_id;//アクティブなカテゴリーIDのセット
                this.getData(); /* データ取得 */
            },



            /** 並び替え */
            changeOrder(key) {
                const order = this.inputs[key];

                switch (order) {
                    case '':    this.inputs[key]='desc';  break;
                    case 'desc': this.inputs[key]='asc';  break;
                    default:    this.inputs[key]='';  break;
                }

                this.getData(); /* データ取得 */
            },


            /** 日付データをテクスト変換  */
            formatDate(inputString) {

                if( !inputString ){ return ''; }

                const date = new Date(inputString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1し、2桁にパディング
                const day = String(date.getDate()).padStart(2, '0'); // 日も2桁にパディング

                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');

                return `${year}/${month}/${day} ${hours}:${minutes}`;
                return `${year}/${month}/${day} ${hours}:${minutes}:${seconds}`;

            },


            /** 編集モード切り替え */
            toggleEdit(){
                this.edit = !this.edit;
            },

        },
    };
</script>
<style scoped>
    /* th,td{ background-color: #fff; } */
</style>
