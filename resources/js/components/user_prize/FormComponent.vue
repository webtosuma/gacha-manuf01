<template>
    <div class="">

        <!--ボトムメニュー-->
        <div class="position-fixed bottom-0 end-0 w-100 pb-3 bg-white border"
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

        <div class="text-end">
            取得商品数：
            <span class="fs-1 fw-bold">
                <number-comma-component :number="userPrizes.length" />
            </span>
        </div>
        <!--商品一覧-->
        <ul class="row px-3 bg-white rounded-3 mx-2 gy-3 mt-0" style="list-style:none;">

            <!--読み込み中-->
            <li v-if="loading"
            class="list-group-item bg-white py-5 fs-5 text-secondary">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </li>

            <li v-for="(userPrize, key) in userPrizes" :key="key"
            class="col-12 col-sm-6 col-lg-4"><label class="d-block " style="cursor:pointer;">
                <div class="row" v-if="userPrize.prize">
                    <div class="col-4 px-0 pe-3 position-relative">
                        <!--チェックボックス-->
                        <div class="position-absolute top-0 start-0 translate-middle" style="z-index:5">

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
                        <div class="form-text">{{ formatDate(userPrize.created_at) }}</div>
                        <h6 classs="fw-bold">{{ userPrize.prize.name }}</h6>
                        <div class="">{{ userPrize.prize.rank.name }}</div>

                        <div class="mt- px-3 text-center border rounded-pill d-inline-block">
                            <number-comma-component :number=" userPrize.prize.point " />{{ 'pt' }}
                        </div>

                    </div>
                </div>
                <div v-else class="py-5">
                    <!--商品情報が削除されたとき-->
                    *商品情報が削除されました
                </div>
            </label></li>

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
    import axios from 'axios'

    export default {
        props: {
            token:{ type: String,  default: '', },
            r_api_user_prize:{ type: String,  default: '', },
            r_api_user_prize:{ type: String,  default: '', },//データ取得ルート
            r_exchange_points:{ type: String,  default: '', },//ポイント交換ルート
            r_shipped_appli:  { type: String,  default: '', },//発送申請ルート
        },
        data() { return {

            loading: true,

            userPrizes: [],/* ユーザー取得商品 */

            ids: [],/*チェックボックスのID*/

            allCheck: false,/*全てチェック*/

            totalPoint: 0,/*チェック中のユーザー商品の合計ポイント*/

            disabled: true,
        } },
        mounted() {

            /* データ取得 */
            this.getData();

        },
        methods:{

            /* データ取得 */
            getData :function(){

                const route = this.r_api_user_prize;　
                axios.post( route ,{ _token: this.token })
                .then(json => {
                    // console.log(json.data);

                    this.userPrizes = json.data;
                    this.loading = false;//読み込み中
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    // console.log( error.response.data );

                });

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
                        this.totalPoint += userPrize.prize.point;
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

        },

    };
</script>
