<template>
    <div class="">

        <!-- {{ inputs.category_id }} -->

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


        <!--操作ボタン-->
        <section class="mb-2">
            <div class="row g-3 ">
                <div class="col-auto">
                    <a :href="r_create+'?gacha_category_id='+inputs.category_id"
                    class="btn btn-lg btn-primary text-white px-4 shadow"
                    >+ 商品の新規登録</a>
                </div>
                <div class="col-4">
                    <input @change="changeKeyWord()" v-model="keyWords"
                    type="text" class="form-control form-control-lg" placeholder="検索：商品名・商品コード名"
                    aria-label="Username" aria-describedby="basic-addon1" />
                </div>
            </div>
        </section>

        <!--テーブル-->
        <section class="card card-body bg-white my-3 overflow-auto" style="height: 60vh;">
            <table class="table bg-white " style="min-width: 600px; font-size: 16px;">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th style="width:1rem;"><!--チェックボックス-->
                            <input v-model="allCheck" @change="changeAll()"
                            class="form-check-input" type="checkbox">
                        </th>

                        <th scope="col" style="width:4rem;">画像</th>

                        <th scope="col"><a
                        @click.prevent="changeOrder( 'order_code' )"
                        href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                            <span>商品コード</span>
                            <i v-if="inputs['order_code']!='desc'" class="bi bi-caret-up-fill"></i>
                            <i v-if="inputs['order_code']!='asc'"  class="bi bi-caret-down-fill"></i>
                        </a></th>

                        <th scope="col"><a
                        @click.prevent="changeOrder( 'order_name' )"
                        href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                            <span>商品名</span>
                            <i v-if="inputs['order_name']!='desc'" class="bi bi-caret-up-fill"></i>
                            <i v-if="inputs['order_name']!='asc'"  class="bi bi-caret-down-fill"></i>
                        </a></th>

                        <th scope="col"><a
                        @click.prevent="changeOrder( 'order_rank_id' )"
                        href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                            <span>評価ランク</span>
                            <i v-if="inputs['order_rank_id']!='desc'" class="bi bi-caret-up-fill"></i>
                            <i v-if="inputs['order_rank_id']!='asc'"  class="bi bi-caret-down-fill"></i>
                        </a></th>

                        <th scope="col"><a
                        @click.prevent="changeOrder( 'order_point' )"
                        href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                            <span>交換ポイント</span>
                            <i v-if="inputs['order_point']!='desc'" class="bi bi-caret-up-fill"></i>
                            <i v-if="inputs['order_point']!='asc'"  class="bi bi-caret-down-fill"></i>
                        </a></th>

                        <th scope="col"><a
                        @click.prevent="changeOrder( 'updated_at' )"
                        href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                            <span>更新</span>
                            <i v-if="inputs['updated_at']!='desc'" class="bi bi-caret-up-fill"></i>
                            <i v-if="inputs['updated_at']!='asc'"  class="bi bi-caret-down-fill"></i>
                        </a></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!--読み込み中-->
                    <tr v-if="loading">
                        <td colspan="8" class="text-center text-secondary border-0 py-5">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr v-for="(prize, key) in prizes" :key="key">
                        <td>
                            <input v-model="ids" @change="changeChildren()"
                            class="form-check-input" type="checkbox" :value=" prize.id ">
                        </td>
                        <td scope="row">
                            <!--画像-->
                            <div style="width:3rem;">
                                <ratio-image-component
                                style_class="ratio ratio-3x4 rounded-3"
                                :url=" prize.image_path " />
                            </div>
                        </td>
                        <td>{{ prize.code }}</td>
                        <td>{{ prize.name }}</td>
                        <td>{{ prize.rank.name }}</td>
                        <td>{{ prize.point }} pt</td>
                        <td>{{ formatDate( prize.updated_at ) }}</td>
                        <td class="">
                            <div class="d-flex gap-2 justify-content-end h-100">
                                <div class="" style="width:4rem;">
                                    <span v-if="prize.is_used"
                                    class="badge rounded-pill bg-success">{{ '利用中' }}</span>
                                </div>
                                <!--編集-->
                                <a class="btn btn-sm btn-light border "
                                :href="r_edit+'/'+prize.id"><i class="bi bi-pencil-fill"></i></a>

                                <!--削除モーダル-->
                                <delete-modal-component
                                @parent-func="destory(prize.id)"
                                :indexKey="'delete'+prize.id"
                                icon="bi-trash"
                                :button_class=" prize.is_used ? 'disabled btn btn-sm btn-secondary border' :'btn btn-sm btn-light border' ">
                                    <div>この商品を削除します。<br />よろしいですか？</div>
                                    <div class="form-text">商品コード：{{ prize.code }}</div>
                                    <div class="form-text">商品名：{{ prize.name }}</div>
                                </delete-modal-component>

                            </div>
                        </td>
                    </tr>

                    <tr v-if="!loading && prizes.length==0">
                        <td colspan="8" class="text-center text-secondary border-0 py-5">
                            *商品の登録情報はありません。
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
        props: {
            token:{ type: String,  default: '', },
            r_api_prize:{ type: String,  default: '', },   //商品
            r_api_category:{ type: String,  default: '', },//ガチャ カテゴリー
            r_api_destroy: { type: String,  default: '', },//削除
            r_create:{ type: String,  default: '', },
            r_edit:{ type: String,  default: '', },
            category_id:{ type: String,  default: '', },
        },
        data() { return {

            loading: true,

            categories:[],//ガチャ カテゴリー
            prizes:  [],/* 商品 */

            inputs: {
                key_words: '',
                category_id: '',
                order_code: '',
                order_name: '',
                order_rank_id: '',
                order_point: '',
                updated_at: '',
            },

            keyWords: '',

            ids: [],/*チェックボックスのID*/

            allCheck: false,/*全てチェック*/

            disabled: true,
        } },
        mounted() {

            this.inputs._token = this.token; //token保存
            this.inputs.category_id = this.category_id ;
            this.getCategoryData();/* データ取得 */

        },
        methods:{

            /* カテゴリー　データ取得 */
            getCategoryData() {
                const route = this.r_api_category;
                axios.post( route , this.inputs )
                .then(json => {
                    // console.log(json.data);
                    this.categories = json.data;

                    /** アクティブなカテゴリーのセット *//* 商品データ取得 */
                    // this.setActiveCategory( this.categories[0] );
                    this.setActiveCategory( this.category_id );

                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );

                });
            },


            /* 商品データ取得 */
            getData() {

                this.loading = true;//読み込み中

                const route = this.r_api_prize;
                axios.post( route , this.inputs )
                .then(json => {
                    // console.log(json.data);

                    this.prizes = json.data;
                    this.loading = false;//読み込み中
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    // console.log( error.response.data );

                });
            },


            /** 削除 */
            destory(id) {
                // console.log(id);
                const route = this.r_api_destroy+'/'+id;
                axios.delete( route , {_token: this.token} )
                .then(json => {

                    console.log(json.data);

                    this.getData(); /* データ取得 */
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );

                });

            },


            /** キーワード検索 */
            changeKeyWord() {
                this.inputs.key_words = this.keyWords;
                this.getData(); /* データ取得 */
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
                    case '':    this.inputs[key]='asc';  break;
                    case 'asc': this.inputs[key]='desc';  break;
                    default:    this.inputs[key]='';  break;
                }

                this.getData(); /* データ取得 */
            },


            /** 全て選択をクリック */
            changeAll(){
                const ids = this.prizes.map( value => { return value.id; } );
                this.ids  = this.allCheck ? ids : [];
            },

            /** 子チェックをクリック */
            changeChildren(){
                const ids = this.prizes.map( value => { return value.id; } );
                this.allCheck = this.ids.length == ids.length;
            },


            /** 日付データをテクスト変換  */
            formatDate(inputString) {
                const date = new Date(inputString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1し、2桁にパディング
                const day = String(date.getDate()).padStart(2, '0'); // 日も2桁にパディング

                return `${year}/${month}/${day}`;
            },


        },

    };
</script>
<style scoped>
    th,td{ background-color: #fff; }
</style>
