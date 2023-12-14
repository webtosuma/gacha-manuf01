<template>
    <div class="">
        <section class="p-3">
            <div class="cardd card-body bg-lightt">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3">
                        <div class="">
                            <span>合計口数：</span>
                            <number-comma-component :number="gacha.max_count" />
                        </div>
                        <div class="">
                            <span>合計ポイント：</span>
                            <number-comma-component :number="gacha.total_point" />
                            <span>pt</span>
                        </div>
                    </div>
                    <div class="">
                        <disabled-button style_class="btn btn-warning text-white w-100 shadow"
                        btn_text="更新する" />
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="row g-1">

                <div class="col-auto">
                    <div class="d-flex flex-column py-3">

                        <button v-for="( discription, key ) in discriptions" :key="key"
                        @click="changeActive( discription.gacha_rank_id )"
                        type="button"
                        class="btn btn-link text-decoration-none position-relative border-bottom">
                            <h6 class="fw-bold mb-0">{{ discription.rank_label }}</h6>
                            <div class="text-dark text-end">
                                <span>口数：</span>
                                <number-comma-component :number="discription.g_prizes_max_count" />
                            </div>
                            <div class="text-dark text-end">
                                <span>(</span>
                                <number-comma-component :number="discription.g_prizes_ratio" />
                                <span>%)</span>
                            </div>
                            <div class="text-secondary text-end">
                                <number-comma-component :number="discription.total_point" />
                                <span>pt</span>
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

                            :gacha_rank_id="discription.gacha_rank_id"
                            />

                        </div>
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

            gacha: {},   // ガチャ情報
            discriptions: [],/* ガチャランク */

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
                    this.gacha = json.data.gacha;

                    this.discriptions = json.data.discriptions;
                    this.active_gr_id = json.data.discriptions[0].gacha_rank_id;
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
