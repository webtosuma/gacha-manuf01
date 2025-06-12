<template>
    <div class="text-dark">

        <!--ボトムメニュー-->
        <div v-if="bottom_menu == 'true'"
        class="position-fixed bottom-0 end-0 w-100 pb-3 bg-white border"
        style="border-radius: 1rem 1rem 0 0; z-index:50;">
            <div class="container" style="max-width:900px;">

                <div class="d-flex justify-content-between align-items-center px-3">
                    <label class="form-check" style="cursor:pointer;">
                        <input v-model="allCheck" @change="changeAll()"
                        class="form-check-input" type="checkbox">
                        <span class="form-check-label fs-5">
                            全て選択
                        </span>
                    </label>
                    <div class="form-check mb-">
                        <span class="fs-1 fw-bold">
                            <number-comma-component :number=" totalPoint ? '+'+totalPoint : 0 " />
                        </span>pt
                    </div>
                </div>


                <div class="row g-2">
                    <div class="col-6">

                        <!-- 選択した商品の発送申請 r_shipped_appli -->
                        <form :action="r_shipped_appli" method="post">
                            <input type="hidden" name="_token" :value="token">

                            <input v-for="(id, key) in ids" :key="key"
                            type="hidden" name="user_prize_ids[]" :value="id">

                            <button type="submit" :disabled="disabled"
                            class="btn py-md-3 btn-light border rounded-pill w-100"
                            >発送申請</button>
                        </form>

                    </div>
                    <div class="col-6">

                        <!--選択した商品をポイント交換 r_exchange_points -->
                        <button type="button" :disabled="disabled"
                        data-bs-toggle="modal" data-bs-target="#exchangeModal"
                        class="btn py-md-3 btn-warning text-white rounded-pill w-100"
                        >ポイント交換</button>

                    </div>
                </div>

            </div>
        </div>


        <!--Headー-->
        <div class="row align-items-center gy-2">
            <div class="col-12 position-relative">
                <input v-model="search_key"
                @change="getData()"
                type="text" class="form-control rounded-pill" placeholder="商品名検索">

                <button @click="resetSearchKey"
                class="btn position-absolute top-50 translate-middle-y"
                style="right:1rem;">×</button>
            </div>
            <div class="col-12 col-lg">

                <select v-model="category_id"
                @change="getData()"
                class="form-select form-select-sm" >
                    <option v-for="(category,key) in categories" :key="key" :value="category.id">{{ category.name }}</option>
                </select>


            </div>
            <div class="col-12 col-md">
                <div class="d-flex gap-1">
                    <button v-for="(select_order,key) in select_orders" :key="key"
                    @click="changeOrder( select_order.value )"
                    class="btn btn-sm border rounded-pill"
                    :class=" order==select_order.value ? 'disabled btn-primary text-white' : 'btn-light' "
                    style="opacity:1;"
                    >{{ select_order.lable }}</button>
                </div>
            </div>
            <div class="col-auto">
                取得商品数：
                <span class="fs-1 fw-bold">
                    <!-- <number-comma-component :number="userPrizes.length" /> -->
                    <number-comma-component :number="total" />
                </span>
            </div>
        </div>


        <!--商品一覧-->
        <ul class="row px-3 bg-white text-dark rounded-3 mx-2 gy-3 mt-0" style="list-style:none;">

            <!--読み込み中-->
            <li v-if="loading"
            class="list-group-item bg-white text-dark py-5 fs-5 text-secondary">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </li>

            <li v-for="(userPrize, key) in userPrizes" :key="key"
            class="col-12 col-sm-6 col-lg-4">
                <!-- <label class="d-block " style="cursor:pointer;"> -->
                    <div class="row" v-if="userPrize.prize">
                        <div class="col-4 px-0 pe-3 position-relative">


                            <!--チェックボックス-->
                            <div v-if="bottom_menu == 'true'"
                            class="position-absolute top-0 start-0 translate-middle" style="z-index:5">

                                <input @change="changeChildren()"
                                v-model="ids" :value="userPrize.id"
                                class="form-check-input float-xl-none m-0 rounded-pill"
                                style="width:2em; height:2em;"
                                type="checkbox" name="user_prize_ids[]" >

                            </div>

                            <ratio-image-component
                            style_class="ratio ratio-3x4 rounded-3"
                            :url=" userPrize.prize.image_path " />


                        </div>
                        <div class="col-8 p-0">
                            <div class="form-text">取得日：{{ formatDate(userPrize.created_at) }}</div>
                            <h6 classs="fw-bold">{{ userPrize.prize.name }}</h6>

                            <div class="mt- px-3 text-center border rounded-pill d-inline-block">
                                <number-comma-component :number=" userPrize.point " />{{ 'pt' }}
                            </div>
                            <div class="form-text text-danger">{{ userPrize.deadline_text }}</div>

                            <div class="">


                                <!--商品説明モーダル-->
                                <button v-if="userPrize.prize.discription_text"
                                class="btn btn-sm btn-dark rounded-pill"
                                type="button"
                                data-bs-toggle="modal"
                                :data-bs-target="'#PrizeDiscriptionModal'+userPrize.id"
                                ><i class="bi bi-search me-2"></i>商品説明</button>

                                <u-prize-discription
                                v-if="userPrize.prize.discription_text"
                                :id         ="userPrize.id"
                                :name       ="userPrize.prize.name"
                                :image_path ="userPrize.prize.image_path"
                                :discription="userPrize.prize.discription_text"
                                size       ="2rem"
                                :src_icon   ="userPrize.prize.discription_icon_path"
                                no_btn     ="1"
                                ></u-prize-discription>


                            </div>
                        </div>
                    </div>
                    <div v-else class="py-5">
                        <!--商品情報が削除されたとき-->
                        *商品情報が削除されました
                    </div>


                <!-- </label> -->
            </li>

            <li v-if="!loading && userPrizes.length==0"
            class="py-3">*取得した商品はありません。</li>

        </ul>


        <!-- ポイント交換Modal -->
        <div class="modal fade" id="exchangeModal" tabindex="-1" aria-labelledby="exchangeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h5 class="modal-title" id="exchangeModalLabel">
                            <p>ポイント交換しますか？</p>
                            <p>商品を<strong class="fs-3"><number-comma-component :number="totalPoint" />pt</strong>と交換する</p>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button"
                                class="btn p-md-33 btn-light border rounded-pill w-100"
                                data-bs-dismiss="modal"
                                >キャンセル</button>
                            </div>
                            <div class="col-6">

                                <form :action="r_exchange_points" method="post">
                                    <input type="hidden" name="_token" :value="token">
                                    <input type="hidden" name="_method" value="patch">

                                    <input v-for="(id, key) in ids" :key="key"
                                    type="hidden" name="user_prize_ids[]" :value="id">

                                    <button type="submit"
                                    class="btn p-md-33 btn-warning text-white rounded-pill w-100"
                                    >交換する</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    import axios from 'axios';

    export default {
        props: {
            token:{ type: String,  default: '', },
            user_id:{ type: [String,Number],  default: '', },
            bottom_menu:{ type: String,  default: 'true', },

            r_api_user_prize:{ type: String,  default: '', },
            r_api_user_prize:{ type: String,  default: '', },//データ取得ルート
            r_exchange_points:{ type: String,  default: '', },//ポイント交換ルート
            r_shipped_appli:  { type: String,  default: '', },//発送申請ルート
        },
        data() { return {

            loading: true,

            categories:[],//ガチャ カテゴリー
            userPrizes: [],/* ユーザー取得商品 */
            total: 0,/* ユーザー取得商品数 */

            ids: [],/*チェックボックスのID*/

            allCheck: false,/*全てチェック*/

            totalPoint: 0,/*チェック中のユーザー商品の合計ポイント*/

            disabled: true,

            category_id: 0,//カテゴリーID
            search_key: '',//検索キーワード
            order: 'desc_created',//並び順

            select_orders :[
                { lable: '新しい順',      value:'desc_created', },
                { lable: '古い順',        value:'asc_created', },
                { lable: '高ポイント順', value:'desc_point', },
                { lable: '低ポイント順', value:'asc_point', },
            ],
        } },
        mounted() {

            /* データ取得 */
            this.getData();

        },
        methods:{


            /* データ取得 */
            getData :function(route = this.r_api_user_prize){

                const params = {
                    _token:     this.token,
                    user_id:    this.user_id,
                    search_key: this.search_key,//検索キーワード
                    order:      this.order,     //並び順
                    category_id:this.category_id,
                };


                axios.post( route, params )
                .then(json => {
                    console.log(json.data);

                    // // カテゴリー
                    this.categories = json.data.categories;
                    this.categories[0].id = 0;//すべて　id:p0

                    //ページネーションデータ
                    const paginate = json.data.user_prizes;

                    // 商品情報の登録（新規登録・ページネーション追加）
                    this.userPrizes = route == this.r_api_user_prize ? paginate.data
                    : [ ...this.userPrizes, ...paginate.data];

                    this.total = paginate.total;

                    this.loading = false;//読み込み中
                    this.ids = [];//チェックボックスのリセット
                    this.allCheck = false;
                    this.totalPoint = 0; //ポイント合計値のリセット

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


            /* 並び順の変更 */
            changeOrder :function( value ){
                this.order = value;
                this.getData();
            },


            /* 検索キーワードのリセット */
            resetSearchKey :function(){
                this.search_key = '';
                this.getData();
            },


            /** 全て選択をクリック */
            changeAll: function(){
                const ids = this.userPrizes.map( value => { return value.id; } );
                this.ids  = this.allCheck ? ids : [];

                this.calcTotalPoint(); //ポイント合計値の計算
            },

            /** 子チェックをクリック */
            changeChildren: function(){
                const ids = this.userPrizes.map( value => { return value.id; } );
                this.allCheck = this.ids.length == ids.length;

                this.calcTotalPoint(); //ポイント合計値の計算
            },

            /** ポイント合計値の計算 */
            calcTotalPoint: function(){
                this.totalPoint = 0;

                this.userPrizes.forEach( userPrize => {
                    if( this.ids.some( id => id === userPrize.id) && userPrize.prize ){
                        this.totalPoint += userPrize.point;
                    }
                } );

                this.disabled = this.totalPoint==0;
            },


            /** 日付データをテクスト変換  */
            formatDate: function(inputString) {
                const date = new Date(inputString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1し、2桁にパディング
                const day = String(date.getDate()).padStart(2, '0'); // 日も2桁にパディング

                return `${year}/${month}/${day}`;
            },


            /** アクティブなカテゴリーのセット */
            // setActiveCategory( category_id ) {

            //     this.search_key=''; //キーワードのリセット

            //     this.category_id = category_id;//アクティブなカテゴリーIDのセット
            //     this.getData(); /* データ取得 */
            // },




        },

    };
</script>
