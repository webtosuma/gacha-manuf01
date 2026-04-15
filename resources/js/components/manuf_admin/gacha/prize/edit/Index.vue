<template>
    <div class="">
        <!-- {{ delete_gacha_prize_ids }} -->

        <input v-for="(delete_gacha_prize_id, key) in delete_gacha_prize_ids" :key="key"
        :value="delete_gacha_prize_id"
        name="delete_gacha_prize_ids[]"
        type="text" >


        <loading-cover-component :loading="loading" />


        <div v-for="( discription, key ) in discriptions" :key="key"
        class="my-3">

            <div
            v-if="['901','903', '173','273','373',  '310','320','330',  '361','362','363'].includes(discription.gacha_rank_id)"
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
            v-else-if="['310','361','901'].includes(discription.gacha_rank_id)"
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
            v-else-if="['173','273','373', '330','363','903',].includes(discription.gacha_rank_id)"
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
            <a-machine-prize-gacharank-container
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

            loading:  true,

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
