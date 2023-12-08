<template>
    <div class="">
        <section>
            <div class="row g-1">
                <div class="col-auto">
                    <div class="d-flex flex-column gap- p- ">
                        hogehoge


                        <!-- @foreach ($gacha->discriptions as $discription)
                            <a href="" class="btn btn-link text-decoration-none">
                                <div class="d-flex justify-content-between gap-3">
                                    <span>{{ $discription->rank_label }}</span>

                                    <span class="text-secondary">100(10%)</span>
                                </div>
                            </a>
                        @endforeach -->
                    </div>
                </div>
                <div class="col">
                    <div class="card overflow-auto" style="height: 60vh">


                        <div class="p-2">{{ prize_ids }}</div>


                        <table class="table">
                            <thead><tr>
                                <th colspan="5"></th>
                                <td class="text-center" style="width:6rem;">口数</td>
                            </tr></thead>
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
                                    <td class="bg-success-subtle" scope="row" style="width:3rem;">
                                        <!-- 画像 -->
                                        <ratio-image-component
                                        style_class="ratio ratio-3x4 rounded-3"
                                        :url=" prize.image_path " />
                                    </td>
                                    <td class="bg-success-subtle">{{ prize.code }}</td>
                                    <td class="bg-success-subtle">{{ prize.name }}</td>
                                    <td class="bg-success-subtle">{{ prize.rank.name }}</td>
                                    <td class="bg-success-subtle">{{ prize.point }} pt</td>
                                    <td class="bg-success-subtle">
                                        <input type="number"
                                        class="form-control form-control-sm text-end" min="0">
                                    </td>
                                </tr>

                                <tr v-if="!loading && prizes.length==0">
                                    <td colspan="8" class="text-center text-secondary border-0 py-5">
                                        *商品の新規追加情報はありません。
                                    </td>
                                </tr>

                            </tbody>
                        </table>



                    </div>
                </div>
                <div class="col">

                    <!--商品リスト-->
                    <a-gachaprize-prize-list
                    @send-prize-id="addGachaPrize"
                    :parent_prize_ids="prize_ids"

                    :token="token"
                    :category_id="category_id"
                    :r_api_prize="r_api_prize"
                    :r_api_category="r_api_category"
                    />

                </div>

            </div>
        </section>
        <section class="mt-3">
            <div class="card card-body bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3">
                        <div class="">合計口数：100</div>
                        <div class="">合計交換ポイント：10000pt</div>
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
            r_api_category:{ type: String,  default: '', },//ガチャ カテゴリー


        },
        data() { return {

            prize_ids: [],
            prizes:  [],/* 商品 */
            loading:  false,


        } },
        mounted() {

        },
        methods: {

            /** ガチャ商品の種類を追加 */
            addGachaPrize(prize_ids) {

                this.prize_ids = [...this.prize_ids, ...prize_ids];
                this.getPrizeData();/* 新規商品データ取得 */
            },


            /** 新規商品データ取得 */
            getPrizeData() {

                this.loading = true;//読み込み中

                // パラメーター
                const inputs = {
                    _token : this.token,
                    ids    : this.prize_ids,
                };

                const route = this.r_api_prize;
                axios.post( route , inputs )
                .then(json => {
                    console.log(json.data);

                    this.prizes = json.data;
                    this.loading = false;//読み込み中
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    // console.log( error.response.data );

                });

            },



            /** 新規商品データの削除 */
            deletePlize(id) {

            },
        },

    };
</script>
