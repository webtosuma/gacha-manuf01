<template>
    <div>

        <!--カード一覧-->
        <div class="row justify-content-center align-items-center g-3 gy-4 mb-4"
        style="min-height: 50vh;" >

            <div v-if="loading"
            class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div v-if="userPrizes.length==0"
            class="text-center fs-5">*表示できる商品はありません</div>
            <div v-for="(userPrize, key) in userPrizes" :key="key"
            class="col-3 col-md-3">
                <div class="d-flex align-items-center justify-content-center h-100">


                    <label class="w-100">

                        <!--商品ランク表示-->
                        <!-- <div class="text-center">
                            <span class="fw-bold fs-5"
                            >{{ userPrize.prize.rank.name }}</span>
                        </div> -->

                        <div class="position-relative">
                            <!--チェックボックス-->
                            <div class="position-absolute top-0 start-0 translate-middle" style="z-index:3">
                                <input v-model="ids" @change="changeChildren()"
                                class="form-check-input float-xl-none m-0 rounded-pill"
                                style="width:2em; height:2em;"
                                type="checkbox" name="user_prize_ids[]" :value="userPrize.id">
                            </div>

                            <!--カード画像-->
                            <ratio-image-component
                            style_class="ratio ratio-3x4 rounded-3"
                            :url="userPrize.prize.image_path"
                            ></ratio-image-component>
                        </div>

                        <!--ポイント表示-->
                        <div class="bg-white text-center mt-1 px-1 rounded-pill">
                            <number-comma-component :number="userPrize.prize.point" />pt
                        </div>

                    </label>




                </div>
            </div>

        </div>


        <div class="rounded-3 p-3" style="background: rgb(0, 0, 0, .7);">
            <div class="d-flex justify-content-between align-items-start text-white">
                <div class="form-check mb-">
                    <input v-model="allCheck" @change="changeAll()"
                    class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        全て選択
                    </label>
                </div>

                <div class="form-check mb-">
                    <span class="fs-1 fw-bold">
                        <number-comma-component :number="totalPoint" />
                    </span>pt
                </div>
            </div>
            <p class="text-white form-text m-0 mb-3">
                *選択されなかった商品は、「取得した商品一覧」に移動します。
            </p>
            <div class="col-md-8 mx-auto">
                <button type="button"
                data-bs-toggle="modal" data-bs-target="#exchangeModal"
                class="btn btn-warning rounded-pill w-100" :disabled="disabled"
                >選択した商品をポイント交換する</button>
            </div>
            <div class="col-md-8 mx-auto mt-2">
                <a :href="r_gacha_category"
                class="btn text-danger rounded-pill w-100" :disabled="disabled"
                >SKIP</a>
            </div>
        </div>




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
                                <button type="submit"
                                class="btn p-md-33 btn-warning text-white rounded-pill w-100"
                                >交換する</button>
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
            r_api_use_gacha_history_show:{ type: String,  default: '', },
            r_gacha_category:{ type: String,  default: '', },
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

                const route = this.r_api_use_gacha_history_show;
                axios.post( route )
                .then(json => {
                    console.log(json.data);
                    this.userPrizes = json.data;
                    this.loading = false;//読み込み中
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );

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
                    if( this.ids.some( id => id === userPrize.id) ){
                        this.totalPoint += userPrize.prize.point;
                    }
                } );

                this.disabled = this.totalPoint==0;
            },

        },

    };
</script>
