<template>
    <div class="">
        <!-- {{ delete_gacha_prize_ids }} -->

        <input v-for="(delete_gacha_prize_id, key) in delete_gacha_prize_ids" :key="key"
        :value="delete_gacha_prize_id"
        name="delete_gacha_prize_ids[]"
        type="text" >

        <div class="row g-0">
            <!--flex-c2-->
            <div class="col mb-5">
                <section class="mb-5">
                    <!--head-->
                    <div class="mx-3 py-2 border-bottom row text-end">
                        <div class="col-3"></div>
                        <div class="col">口数</div>
                        <div class="col">当選率</div>
                        <div class="col">平均PT</div>
                    </div>


                    <!--body-->
                    <div v-for="( discription, key ) in discriptions" :key="key"
                    class="mx-3 py-2 border-bottom mb-3">

                        <button class="btn w-100 text-start" type="button"
                        data-bs-toggle="collapse" :data-bs-target="'#collapse'+discription.id"
                        aria-expanded="false" :aria-controls="'collapse'+discription.id">
                            <div class="row align-items-center text-end">


                                <div class="col-3 dropdown-toggle text-start">
                                    <span class="fs-5">{{ discription.rank_label }}</span>
                                </div>

                                <div class="col">
                                    <!--口数-->
                                    {{ discription.total_count_format }}
                                </div>
                                <div class="col">
                                    <!--当選率-->
                                    {{  discription.winning_ratio_format }}
                                </div>
                                <div class="col">
                                    <!--平均PT-->
                                    {{ discription.average_point_format }}
                                </div>

                            </div>
                        </button>

                        <!-- collapse -->
                        <div class="collapse my-3 showww"
                        :id="'collapse'+discription.id"
                        :class="{'showww':discription.gacha_rank_id>300 && discription.gacha_rank_id<400 && discription.gacha_prizes_count>0 }"
                        >
                            <div class="px-3">

                                <div
                                v-if="discription.gacha_rank_id>300 && discription.gacha_rank_id<400 && discription.gacha_prizes_count>0"
                                class="rounded bg-light p-2 mb-2">
                                    当選番号：{{ discription.hit_nums }}
                                </div>

                                <!--スペシャルランク-->
                                <a-gachaprize-gacharank-container
                                v-if="['10'].includes(discription.gacha_rank_id)"
                                @send-delete-gp-id="addDeleteGachaPrizeId"
                                :token="token"
                                :category_id="category_id"
                                :r_api_prize="r_api_prize"
                                :rank_label="discription.rank_label"
                                :r_api_ranks_gacha_prizes="r_api_ranks_gacha_prizes+'/'+discription.id"
                                :gacha_rank_id="discription.gacha_rank_id"
                                :delete_gacha_prize_ids="delete_gacha_prize_ids"
                                :is_special_rank="true"
                                />

                                <!--キリ番-->
                                <a-gachaprize-gacharank-kiri-container
                                v-else-if="['310','361'].includes(discription.gacha_rank_id)"
                                @send-delete-gp-id="addDeleteGachaPrizeId"
                                :token="token"
                                :category_id="category_id"
                                :r_api_prize="r_api_prize"
                                :rank_label="discription.rank_label"
                                :r_api_ranks_gacha_prizes="r_api_ranks_gacha_prizes+'/'+discription.id"
                                :gacha_rank_id="discription.gacha_rank_id"
                                :delete_gacha_prize_ids="delete_gacha_prize_ids"
                                />

                                <!--ゾロ目-->
                                <a-gachaprize-gacharank-zoro-container
                                v-else-if="['320','362'].includes(discription.gacha_rank_id)"
                                @send-delete-gp-id="addDeleteGachaPrizeId"
                                :token="token"
                                :category_id="category_id"
                                :r_api_prize="r_api_prize"
                                :rank_label="discription.rank_label"
                                :r_api_ranks_gacha_prizes="r_api_ranks_gacha_prizes+'/'+discription.id"
                                :gacha_rank_id="discription.gacha_rank_id"
                                :delete_gacha_prize_ids="delete_gacha_prize_ids"
                                />

                                <!--ピタリ賞-->
                                <a-gachaprize-gacharank-pita-container
                                v-else-if="['330','363'].includes(discription.gacha_rank_id)"
                                @send-delete-gp-id="addDeleteGachaPrizeId"
                                :token="token"
                                :category_id="category_id"
                                :r_api_prize="r_api_prize"
                                :rank_label="discription.rank_label"
                                :r_api_ranks_gacha_prizes="r_api_ranks_gacha_prizes+'/'+discription.id"
                                :gacha_rank_id="discription.gacha_rank_id"
                                :delete_gacha_prize_ids="delete_gacha_prize_ids"
                                />

                                <!--スライダー登録-->
                                <a-gachaprize-gacharank-slide-container
                                v-else-if="['1001'].includes(discription.gacha_rank_id)"
                                @send-delete-gp-id="addDeleteGachaPrizeId"
                                :token="token"
                                :category_id="category_id"
                                :r_api_prize="r_api_prize"
                                :rank_label="discription.rank_label"
                                :r_api_ranks_gacha_prizes="r_api_ranks_gacha_prizes+'/'+discription.id"
                                :gacha_rank_id="discription.gacha_rank_id"
                                :delete_gacha_prize_ids="delete_gacha_prize_ids"
                                />



                                <!--通常ランク-->
                                <a-gachaprize-gacharank-container
                                v-else
                                @send-delete-gp-id="addDeleteGachaPrizeId"
                                :token="token"
                                :category_id="category_id"
                                :r_api_prize="r_api_prize"
                                :rank_label="discription.rank_label"
                                :r_api_ranks_gacha_prizes="r_api_ranks_gacha_prizes+'/'+discription.id"
                                :gacha_rank_id="discription.gacha_rank_id"
                                :delete_gacha_prize_ids="delete_gacha_prize_ids"
                                />

                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!--flex-c1-->
            <aside class="col-auto ">
                <div class="position-sticky p-3" style="top: 2rem; ">
                    <div class="">
                        <span>合計口数：</span><br>
                        <span class="fs-3">
                            <number-comma-component :number="gacha.max_count" />
                        </span>
                    </div>
                    <div class="">
                        <span>交換予定ポイント：</span><br>
                        <span class="fs-3">
                            <number-comma-component :number="gacha.total_point" />
                        </span>
                        <span>pt</span>
                    </div>
                    <div class="">
                        <span>予定売上：</span><br>
                        <span class="fs-3">
                            <number-comma-component :number="gacha.total_play_point" />
                        </span>
                        <span>pt</span>
                    </div>

                    <div class="my-3">
                        <disabled-button style_class="btn btn-warning px-5 text-white w-100 shadow"
                        btn_text="更新する" />
                    </div>
                </div>
            </aside>
        </div>
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

            delete_gacha_prize_ids:[],// 削除対象のガチャ商品ID

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

            /** 削除対象のガチャ商品ID　追加 */
            addDeleteGachaPrizeId(id)
            {
                this.delete_gacha_prize_ids = [ ...this.delete_gacha_prize_ids, id ];
            },



            /** アクティブなランクの変更 */
            changeActive(active_gr_id) { this.active_gr_id = active_gr_id; },
        },

    };
</script>
