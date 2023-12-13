<template>
    <div>

        <!--カード一覧-->
        <div class="row justify-content-center g-3 gy-4 mb-4" style="min-height: 50vh;" >

            <div v-if="loading"
            class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div v-for="(userPrize, key) in userPrizes" :key="key"
            class="col-3 col-md-3">
                <div class="d-flex align-items-center justify-content-center h-100">


                    <label class="w-100">

                        <!--商品ランク表示-->
                        <div class="text-center">
                            <span class="fw-bold fs-5"
                            >{{ userPrize.prize.rank.name }}</span>
                        </div>

                        <div class="position-relative">
                            <!--チェックボックス-->
                            <div class="position-absolute top-0 start-0 translate-middle" style="z-index:100">
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

                        <!--商品ランク表示-->
                        <!-- <div class="bg-dark text-primary fw-bold text-center mt-1 px-1 rounded">
                            {{ userPrize.prize.rank.name }}
                        </div> -->
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
                <div class="form-check mb-3">
                    <input v-model="allCheck" @change="changeAll()"
                    class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        全て選択
                    </label>
                </div>

                <div class="form-check mb-3">
                    <span class="fs-1 fw-bold">
                        <number-comma-component :number="totalPoint" />
                    </span>pt
                </div>
            </div>
            <div class="col-md-8 mx-auto">
                <button type="submit"
                class="btn btn-warning rounded-pill w-100" :disabled="disabled"
                >選択した商品をポイント交換する</button>
            </div>
            <p class="text-white form-text m-0 mt-3">
                *選択されなかった商品は、「取得した商品一覧」に移動されます。
            </p>
        </div>


    </div>
</template>
<script>
    import axios from 'axios'

    export default {
        props: {
            token:{ type: String,  default: '', },
            r_use_gacha_history_show:{ type: String,  default: '', },
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

                const route = this.r_use_gacha_history_show;
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
