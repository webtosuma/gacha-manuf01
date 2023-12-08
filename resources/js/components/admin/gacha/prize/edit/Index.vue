<template>
    <div class="">
        <section>
            <div class="row g-1">

                <div class="col-auto">
                    <div class="d-flex flex-column py-3">

                        <button v-for="( discription, key ) in discriptions" :key="key"
                        @click="changeActive( discription.gacha_rank_id )"
                        class="btn btn-link text-decoration-none position-relative border-bottom">
                            <div class="d-flex flex-column justify-content-between gap-0">
                                <span class="fw-bold">{{ discription.rank_label }}</span>

                                <span class="text-secondary">100(10%)</span>
                            </div>

                            <div v-show=" active_gr_id == discription.gacha_rank_id "
                            class="position-absolute top-50 start-100 translate-middle"
                            style="z-index: 10;"
                            ><i class="bi bi-caret-right-fill text-primary fs-1"></i></div>
                        </button>
                    </div>
                </div>

                <div class="col">
                    <!--ランク別登録商品-->
                    <div v-for="( discription, key ) in discriptions" :key="key">
                        <div v-show="active_gr_id == discription.gacha_rank_id"
                        class="mb-3">

                            <a-gachaprize-gacharank-container
                            :token="token"
                            :category_id="category_id"
                            :r_api_prize="r_api_prize"

                            :rank_label="discription.rank_label"
                            :r_api_ranks_gacha_prizes="r_api_ranks_gacha_prizes+'/'+discription.id"
                            />

                        </div>
                    </div>

                </div>

            </div>
        </section>
        <section class="mt-3">
            <div class="card card-body bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3">
                        <div class="">合計口数：100</div>
                        <div class="">合計ポイント：100pt</div>
                    </div>
                    <div class="">
                        <button class="btn btn-warning text-white">この内容で更新する</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import axios from 'axios'

    export default {
        props: {
            token:{ type: String,  default: '', },
            category_id:{ type: [String,Number],  default: '', },
            r_api_prize:{ type: String,  default: '', },   //商品

            r_api_gacha_ranks:{ type: String,  default: '', },//ガチャランク
            r_api_ranks_gacha_prizes:{ type: String,  default: '', },//ガチャ商品
        },
        data() { return {

            active_gr_id: '',
            discriptions:  [],/* ガチャランク */

            loading:  false,

        } },
        mounted() {

            this.getGachaRanks();// 新規ガチャランクデータ取得
        },
        methods: {

            /** 新規ガチャランクデータ取得 */
            getGachaRanks() {

                this.loading = true;//読み込み中

                // パラメーター
                const route = this.r_api_gacha_ranks;
                axios.post( route , { _token : this.token, } )
                .then(json => {
                    // console.log(json.data);

                    this.discriptions = json.data;
                    this.active_gr_id = json.data[0].gacha_rank_id;
                    this.loading = false;//読み込み中
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    // console.log( error.response.data );

                });

            },



            /** アクティブなランクの変更 */
            changeActive(active_gr_id) { this.active_gr_id = active_gr_id; },
        },

    };
</script>
