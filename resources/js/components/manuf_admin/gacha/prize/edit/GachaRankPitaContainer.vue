<template>
    <div class="row g-1">
        <div class="col">


            <!-- {{ prize_ids }}{{ new_prizes_ids }} -->




            <div class="card bg-white overflow-auto" style="height: 90vh">

                <div v-if="is_special_rank" class="bg-danger-subtle p-2 form-text m-0">
                    <span v-if="gacha_rank_id>=360 && gacha_rank_id<370"
                    >*当選は、個人の利用数に対して判定されます。</span>
                    <span v-else>*当選は、ガチャの総口数に対して判定されます。</span>
                    <br>

                    *{{rank_label}}の商品が当選する「当選するプレイ数」を入力してください。<br>
                    *{{rank_label}}目商品の当選予定数は、全体の商品数量に含まれません。<br>
                    *{{rank_label}}目当選時には、ランダムで他の登録商品の当選数が１削除されます。<br>
                    *「当選プレイ数」が重複した場合、{{rank_label}}目当選時にはランダムで{{rank_label}}目商品が付与されます。
                </div>

                <div v-if="test"
                class="p-2">prize ids:{{ prize_ids }}</div>


                <table class="table">
                    <thead><tr>
                        <th colspan="5">{{ rank_label }}</th>
                        <th colspan="2">当選プレイ数</th>

                        <td></td>
                    </tr></thead>

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
                            <td style="width:6rem;" class="position-relative">

                                <!--special_counts(キリ番が当選する間隔[更新])-->
                                <input
                                type="number"
                                v-model.number="g_prize.special_count"
                                :name="'gri'+gacha_rank_id+'-special_counts[]'"
                                :disabled="false"
                                class="form-control form-control-sm text-end" min="1">

                                <!--input err-->
                                <div v-if="g_prize.input_err"
                                class="position-absolute top-0 end-0 translate-middle px-2">
                                    <div class="badge text-bg-danger text-white shadow">{{ g_prize.input_err }}</div>
                                </div>


                            </td>

                            <td class=""  style="width:2rem;">

                                <!-- 削除 -->
                                <input :value="g_prize.id"
                                :name="'gri'+gacha_rank_id+'-delete_gacha_prize_ids[]'"
                                type="checkbox" class="btn-check"
                                :id="'delete_gasha_prizes'+gacha_rank_id+'-'+g_prize.id"
                                autocomplete="off">

                                <label v-if="true"
                                @click="removeGachaPrizeIds( g_prize )"
                                class="btn btn-sm border text-danger"
                                :for="'delete_gasha_prizes'+gacha_rank_id+'-'+g_prize.id"
                                ><i class="bi bi-trash3"></i></label>

                                <!-- @click="removeGachaPrizeIds( g_prize )" -->
                            </td>
                        </tr>



                    </tbody>
                    <!--商品の登録情報はありません-->
                    <tbody v-if="!loading && prize_ids.length==0 && new_prizes.length==0">
                        <tr>
                            <td colspan="8" class="text-center text-secondary border-0 py-5">
                                *商品の登録情報はありません。
                            </td>
                        </tr>
                    </tbody>
                    <!--新規登録商品-->
                    <tbody>
                        <tr v-for="(prize, p_key) in new_prizes" :key="p_key" >
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

                            <td class="bg-success-subtle position-relative"  style="width:6rem;">

                                <!--new_special_counts(キリ番が当選する間隔[新規])-->
                                <input
                                type="number"
                                value="1"
                                v-model.number="prize.special_count"
                                :name="'gri'+gacha_rank_id+'-new_special_counts[]'"
                                :disabled="false"
                                class="form-control form-control-sm text-end" min="1">

                                <!--prize ID-->
                                <input type="hidden" :value="prize.id"
                                :name="'gri'+gacha_rank_id+'-new_prize_ids[]'"
                                >

                                <!--input err-->
                                <div v-if="prize.input_err"
                                class="position-absolute top-0 end-0 translate-middle px-2">
                                    <div class="badge text-bg-danger text-white shadow">{{ prize.input_err }}</div>
                                </div>

                            </td>

                            <td class="bg-success-subtle"  style="width:2rem;">
                                <!-- 削除 -->
                                <button @click="removeGachaPrize(p_key)"
                                class="btn btn-sm border text-danger" type="button"
                                ><i class="bi bi-trash3"></i></button>


                            </td>
                        </tr>
                    </tbody>
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

                </table>
            </div>

        </div>
        <div class="col">

            <!--商品リスト-->
            <a-gachaprize-prize-list-pita
            @send-prize-id="addGachaPrize"
            :parent_prize_ids="prize_ids"
            :gacha_rank_id="gacha_rank_id"
            :token="token"
            :category_id="category_id"
            :r_api_prize="r_api_prize"

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
            category_id:              { type: [String,Number],  default: '', },
            r_api_prize:              { type: String,  default: '', },   //商品
            rank_label:               { type: String,  default: '', },
            r_api_ranks_gacha_prizes: { type: String,  default: '', },//ガチャ商品
            gacha_rank_id:            { type: [String,Number],  default: '', },
            delete_gacha_prize_ids:   { type: [Array,Object],  default: [], },
            is_special_rank:          { type: Boolean,  default: true, },
        },
        data() { return {

            g_prizes: [],/* 登録ずみガチャ商品 */

            prize_ids: [],//右を非表示にするID

            new_prizes:    [],  /* 新規登録　商品 */
            new_prizes_ids:[],  /* 新規登録　商品ID */

            // is_special_rank: false,
            loading: false,
            test: false,

        } },
        watch: {
            // g_prizes: {
            //     deep: true,
            //     handler() {
            //         // special_count のみ抽出（未入力・nullは除外）
            //         const counts = this.g_prizes
            //             .map(p => p.special_count)
            //             .filter(v => v !== null && v !== '' && v !== undefined);

            //         // 重複している値を抽出
            //         const duplicatedValues = counts.filter(
            //             (v, i, arr) => arr.indexOf(v) !== i
            //         );

            //         // 各 g_prize にエラーフラグを設定
            //         this.g_prizes.forEach(p => {
            //             p.input_err =
            //                 p.special_count &&
            //                 duplicatedValues.includes(p.special_count);
            //         });
            //     }
            // }
            g_prizes: {
                deep: true,
                handler() {
                    this.checkSpecialCount();
                }
            },
            new_prizes: {
                deep: true,
                handler() {
                    this.checkSpecialCount();
                }
            },
        },
        mounted() {

            this.getData();

        },
        methods: {

            /** 重複防止チェック */
            checkSpecialCount() {
                const allPrizes = [
                    ...this.g_prizes,
                    ...this.new_prizes
                ];

                // 数値（1以上）のみ抽出
                const validCounts = allPrizes
                    .map(p => p.special_count)
                    .filter(v => typeof v === 'number' && v >= 1);

                // 重複値抽出
                const duplicatedValues = validCounts.filter(
                    (v, i, arr) => arr.indexOf(v) !== i
                );

                allPrizes.forEach(p => {
                    // 初期化
                    p.input_err = null;

                    // 未入力 or 0
                    if (
                        p.special_count === null ||
                        p.special_count === undefined ||
                        p.special_count === '' ||
                        p.special_count === 0
                    ) {
                        p.input_err = '1以上の数値入力が必要です';
                        return;
                    }

                    // 重複チェック
                    if (duplicatedValues.includes(p.special_count)) {
                        p.input_err = '他の商品と同じ値は設定できません';
                    }
                });
            },

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

                    // this.isSpecialGachaRank();/** 特殊商品が否かの保存 */

                    this.loading = false;//読み込み中
                    // console.log(this.g_prizes)
                })
                .catch(error => {
                    // alert('通信エラーが発生しました。')
                    // console.log( error.response.data );

                });

            },


            /** 新しいガチャ商品の種類を追加 */
            addGachaPrize(prize_ids) {
                // 右を非表示にするID
                this.prize_ids = [...this.prize_ids, ...prize_ids];

                //  新規登録　商品ID
                this.new_prizes_ids = [...this.new_prizes_ids, ...prize_ids];

                // this.getPrizeData();/* 新規商品データ取得 */
                this.getPrizeData( this.r_api_prize, prize_ids );/* 新規商品データ取得 */

            },


            /** 新しいガチャ商品の種類を削除 */
            removeGachaPrize(n) {
                this.new_prizes_ids.splice(n, 1);
                this.new_prizes.splice(n, 1);
            },


            /** 登録ずみ商品を削除 */
            removeGachaPrizeIds( g_prize ){

                // 商品ID配列の更新
                const new_prize_ids = this.prize_ids.filter( prize_id => {
                    return prize_id != g_prize.prize.id;
                } );
                this.prize_ids = new_prize_ids;

                // ガチャ商品の非表示
                g_prize.show = false;
            },




            /** 新規商品データ取得 */
            getPrizeData( route, prize_ids ) {

                this.loading = true;//読み込み中

                // パラメーター
                const inputs = {
                    _token : this.token,
                    ids: prize_ids
                };

                axios.post( route , inputs )
                .then(json => {

                    //ページネーションデータ
                    const paginate = json.data.prizes;

                    //
                    paginate.data.forEach(prize => {
                        prize.special_count = 1;
                    });

                    // 商品情報の登録（新規登録・ページネーション追加）
                    this.new_prizes = [ ...this.new_prizes, ...paginate.data];

                    this.loading = false;//読み込み中


                    /* 次のデータの読み込み */
                    const current_page = paginate.current_page;//表示中ページ
                    const last_page    = paginate.last_page;   //最終ページ
                    if( current_page != last_page ){
                        const nextPageUrl = paginate.next_page_url;     //URLの更新
                        this.getPrizeData( nextPageUrl, prize_ids );
                    }
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );

                });

            },

        }
    }
</script>
<style scoped>
/* 吹き出し本体 */
.bubble-error {
  display: block;
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  margin-bottom: 6px;

  background: #dc3545; /* Bootstrap danger */
  color: #fff;
  padding: 6px 10px;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  white-space: nowrap;
  z-index: 10;
}

/* 吹き出しの三角 */
.bubble-error::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border-width: 6px;
  border-style: solid;
  border-color: #dc3545 transparent transparent transparent;
}
</style>

