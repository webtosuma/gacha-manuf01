<template>
    <div class="row g-1">
        <div class="col">

            <!-- {{ prize_ids }} -->
            <div class="card bg-white overflow-auto" style="height: 90vh">

                <div v-if="is_special_rank" class="bg-danger-subtle p-2 form-text m-0">
                    *特殊な商品の数量は、更新時に自動算出されます。<br>
                    *特殊な商品の登録は1種類までです。
                </div>

                <div v-if="test"
                class="p-2">prize ids:{{ prize_ids }}</div>


                <table class="table">
                    <thead><tr>
                        <th colspan="5">{{ rank_label }}</th>
                        <td class="text-center" style="width:6rem;">口数</td>
                        <td></td>
                    </tr></thead>

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
                    <!--登録ずみガチャ商品-->
                    <tbody v-for="(g_prize, key) in g_prizes" :key="key">
                        <tr  v-show=" g_prize.show ">

                            <td scope="row" style="width:3rem;">
                                <!-- 画像 -->
                                <ratio-image-component
                                style_class="ratio ratio-3x4 rounded-3"
                                :url=" g_prize.prize.image_path " />
                            </td>
                            <td>{{ g_prize.prize.code }}</td>
                            <td>{{ g_prize.prize.name }}</td>
                            <td>{{ g_prize.prize.rank.name }}</td>
                            <td>{{ g_prize.prize.point }} pt</td>
                            <td>
                                <input v-model="g_prize.max_count"
                                :name="'gri'+gacha_rank_id+'-gacha_prize_counts[]'"
                                :disabled="is_special_rank"
                                type="number"
                                class="form-control form-control-sm text-end">

                                <input v-if="is_special_rank"
                                v-model="g_prize.max_count"
                                :name="'gri'+gacha_rank_id+'-gacha_prize_counts[]'"
                                type="hidden" value="1"
                                >
                            </td>
                            <td class=""  style="width:2rem;">

                                <!-- 削除 -->
                                <input :value="g_prize.id"
                                :name="'gri'+gacha_rank_id+'-delete_gacha_prize_ids[]'"
                                type="checkbox" class="btn-check"
                                :id="'delete_gasha_prizes'+gacha_rank_id+'-'+g_prize.id"
                                autocomplete="off">

                                <label @click="removePrizeIds( g_prize )"
                                class="btn btn-sm border text-danger"
                                :for="'delete_gasha_prizes'+gacha_rank_id+'-'+g_prize.id"
                                ><i class="bi bi-trash3"></i></label>
                            </td>
                        </tr>



                    </tbody>
                    <!--商品の登録情報はありません-->
                    <tbody v-if="!loading && prize_ids.length==0 && prizes.length==0">
                        <tr>
                            <td colspan="8" class="text-center text-secondary border-0 py-5">
                                *商品の登録情報はありません。
                            </td>
                        </tr>
                    </tbody>
                    <!--新規登録商品-->
                    <tbody>
                        <tr v-for="(prize, p_key) in prizes" :key="p_key">
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

                            <td class="bg-success-subtle"  style="width:6rem;">

                                <!--disabledのときのprize count-->
                                <input v-if="is_special_rank"
                                type="hidden" value="1"
                                :name="'gri'+gacha_rank_id+'-new_prize_counts[]'"
                                >
                                <!--prize count-->
                                <input type="number" value="1"
                                :name="'gri'+gacha_rank_id+'-new_prize_counts[]'"
                                :disabled="is_special_rank"
                                class="form-control form-control-sm text-end" min="0">

                                <!--prize ID-->
                                <input type="hidden" :value="prize.id"
                                :name="'gri'+gacha_rank_id+'-new_prize_ids[]'"
                                >
                            </td>

                            <td class="bg-success-subtle"  style="width:2rem;">
                                <!-- 削除 -->
                                <button @click="removeGachaPrize(prize.id)"
                                class="btn btn-sm border text-danger" type="button"
                                ><i class="bi bi-trash3"></i></button>
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
            :gacha_rank_id="gacha_rank_id"

            :token="token"
            :category_id="category_id"
            :r_api_prize="r_api_prize"

            :is_special_rank="is_special_rank"
            :test="test"
            />

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

            rank_label:{ type: String,  default: '', },
            r_api_ranks_gacha_prizes:{ type: String,  default: '', },//ガチャ商品

            gacha_rank_id:{ type: [String,Number],  default: '', },
        },
        data() { return {

            g_prizes: [],/* 登録ずみガチャ商品 */

            prize_ids: [],//右を非表示にするID
            prizes:  [],  /* 新規登録　商品 */

            is_special_rank: false,
            loading: false,
            test: false,

        } },
        mounted() {

            this.getData();

        },
        methods: {

            /** ガチャ商品 */
            getData() {

                this.loading = true;//読み込み中

                // パラメーター
                const route = this.r_api_ranks_gacha_prizes;
                axios.post( route , { _token : this.token, } )
                .then(json => {
                    // console.log(json.data);

                    this.g_prizes = json.data;
                    this.g_prizes.forEach( g_prize => {
                        this.prize_ids.push(g_prize.prize.id);
                        g_prize['show'] = true; // ガチャ商品の表示カラム
                    });

                    this.isSpecialGachaRank();/** 特殊商品が否かの保存 */

                    this.loading = false;//読み込み中
                    console.log(this.g_prizes)
                })
                .catch(error => {
                    // alert('通信エラーが発生しました。')
                    // console.log( error.response.data );

                });

            },


            /** 新しいガチャ商品の種類を追加 */
            addGachaPrize(prize_ids) {
                this.prize_ids = [...this.prize_ids, ...prize_ids];

                // 既に登録ずみの商品IDを除去
                this.prize_ids = this.filterPrizeIds();

                this.getPrizeData();/* 新規商品データ取得 */
            },


            /** 新しいガチャ商品の種類を削除 */
            removeGachaPrize(id) {
                // prize_ids配列から該当IDを削除
                const array = this.prize_ids.filter( p_id => {
                    return id != p_id;
                } );
                this.prize_ids = array;

                /* 新規商品データ取得 */
                const filtterArray = this.filterPrizeIds();

                if( filtterArray.length>0 ){

                    this.getPrizeData();
                }else{
                    this.prizes = [];
                }
            },


            /** 登録ずみ商品を削除 */
            removePrizeIds( g_prize ){

                // 商品ID配列の更新
                const new_prize_ids = this.prize_ids.filter( prize_id => {
                    return prize_id != g_prize.prize.id;
                } );
                this.prize_ids = new_prize_ids;

                // ガチャ商品の非表示
                g_prize.show = false;
                console.log( this.g_prizes );
            },


            /** 既に登録ずみの商品IDを除去 */
            filterPrizeIds(){
                // 既に登録ずみのID配列
                const created_prize_ids = this.g_prizes.map( g_prize => {
                    return g_prize.prize.id;
                } );

                return this.prize_ids.filter( id => {
                    return !created_prize_ids.includes(id);
                } );

            },

            /** 新規商品データ取得 */
            getPrizeData() {

                this.loading = true;//読み込み中

                // パラメーター
                const inputs = {
                    _token : this.token,
                    ids: this.prize_ids,
                };

                const route = this.r_api_prize;
                axios.post( route , inputs )
                .then(json => {
                    // console.log(json.data);

                    // this.prizes = json.data;
                    this.prizes = json.data.prizes;
                    this.loading = false;//読み込み中
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    // console.log( error.response.data );

                });

            },

            /** 特殊商品が否かの保存 */
            isSpecialGachaRank() {
                const array = ['10','310','320'];
                this.is_special_rank = array.includes(this.gacha_rank_id);
            },
        }
    }
</script>


