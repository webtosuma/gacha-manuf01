<template>
    <div class="row g-1">
        <!--追加ボタン-->
        <div class="col-auto" style="height: 90vh">
            <div class="d-flex align-items-center h-100">
                <button @click="sendPrizeId"
                :disabled="!ids.length"
                class="btn btn-light border p-0 fs-3"
                ><i class="bi bi-caret-left"></i></button>
            </div>
        </div>
        <!--商品リスト-->
        <div class="col">

            <!-- {{ parent_prize_ids }}
            {{ ids }} -->


            <div class="card overflow-auto"  style="height: 90vh">

                <!-- <div v-if="is_special_rank" class="bg-danger-subtle p-2 form-text m-0">
                    *特殊な商品の登録は1種類までです。
                </div> -->

                <div v-if="test">
                    <div class="p-2">parent ids:{{ parent_prize_ids }}</div>
                    <div class="p-2">ids:{{ ids }}</div>
                    <div class="p-2">is_special_rank:{{ specialDisabled(0) }}</div>
                </div>


                <div class="p-2">
                    <input @change="changeKeyWord()" v-model="keyWords"
                    type="text" class="form-control form-control mb-1" placeholder="検索：商品名・商品コード名"
                    aria-label="Username" aria-describedby="basic-addon1" />

                    <div class="input-group">
                        <span class="input-group-text">最大・最低pt</span>
                        <input @change="getData()"
                        v-model="inputs.max_point"
                        type="number" class="form-control"
                        placeholder="最大pt" style="width:6rem;">
                        <input @change="getData()"
                        v-model="inputs.min_point"
                        type="number" class="form-control"
                        placeholder="最低pt" style="width:6rem;">
                    </div>
                </div>

                <table class="table">
                    <!--ヘッド（並べ替えボタン）-->
                    <thead>
                        <tr class="">
                            <th style="width:1rem;"><!--チェックボックス-->
                                <!-- <input v-model="allCheck" @change="changeAll()"
                                class="form-check-input" type="checkbox"> -->
                            </th>

                            <th scope="col"></th>

                            <th scope="col"><a
                            @click.prevent="changeOrder( 'order_code' )"
                            href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                <!-- <span>商品コード</span> -->
                                <i v-if="inputs['order_code']!='desc'" class="bi bi-caret-up-fill"></i>
                                <i v-if="inputs['order_code']!='asc'"  class="bi bi-caret-down-fill"></i>
                            </a></th>

                            <th scope="col"><a
                            @click.prevent="changeOrder( 'order_name' )"
                            href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                <!-- <span>商品名</span> -->
                                <i v-if="inputs['order_name']!='desc'" class="bi bi-caret-up-fill"></i>
                                <i v-if="inputs['order_name']!='asc'"  class="bi bi-caret-down-fill"></i>
                            </a></th>

                            <th scope="col"><div class="row align-items-end g-0">
                                <!-- <span>評価ランク</span> -->
                                <div class="col">
                                    <select @change="getData(false)"
                                    v-model="inputs.where_rank_id"
                                    class="form-select form-select-sm fw-bold" aria-label="Default select example">
                                        <!-- <option value="">評価ランク</option> -->
                                        <option v-for="(prize_rank, key) in selects.prize_ranks" :key="key"
                                        :value="prize_rank.id">{{ prize_rank.name }}</option>
                                    </select>
                                </div>
                                <!-- <div class="col-auto">
                                    <a @click.prevent="changeOrder( 'order_rank_id' )"
                                    href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                        <i v-if="inputs['order_rank_id']!='desc'" class="bi bi-caret-up-fill"></i>
                                        <i v-if="inputs['order_rank_id']!='asc'"  class="bi bi-caret-down-fill"></i>
                                    </a>
                                </div> -->
                            </div></th>

                            <th scope="col"><a
                            @click.prevent="changeOrder( 'order_point' )"
                            href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                <!-- <span>交換ポイント</span> -->
                                <i v-if="inputs['order_point']!='desc'" class="bi bi-caret-up-fill"></i>
                                <i v-if="inputs['order_point']!='asc'"  class="bi bi-caret-down-fill"></i>
                            </a></th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="(prize, key) in prizes" :key="key">
                            <td>
                                <input v-model="ids"
                                @change="changeChildren()"
                                class="form-check-input" type="checkbox" :value=" prize.id ">
                            </td>
                            <td scope="row" style="width:3rem;">
                                <!--画像-->
                                <ratio-image-component
                                style_class="ratio ratio-3x4 rounded-3"
                                :url=" prize.image_path " />
                            </td>
                            <td>{{ prize.code }}</td>
                            <td>{{ prize.name }}</td>
                            <td>{{ prize.rank.name }}</td>
                            <td>{{ prize.point }} pt</td>
                        </tr>

                        <tr v-if="!loading && prizes.length==0">
                            <td colspan="8" class="text-center text-secondary border-0 py-5">
                                *商品の登録情報はありません。
                            </td>
                        </tr>

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

                    </tbody>
                </table>


            </div>
        </div>


    </div>
</template>
<script>
    import axios from 'axios'

    export default {
        props: {
            token:{ type: String,  default: '', },
            category_id:   { type: [String,Number],  default: '', },
            gacha_rank_id: { type: [String,Number],  default: '', },

            r_api_prize:   { type: String,  default: '', },   //商品

            parent_prize_ids:{ type: Array,  default: [], },   //親が持つ商品ID

            is_special_rank:{ type: Boolean,  default: false, },//特殊商品が否か
            test:           { type: Boolean,  default: false, },//特殊商品が否か
        },
        data() { return {


            prizes:  [],/* 商品 */
            inputs: {
                key_words: '',
                category_id: 1,
                order_code: '',
                order_name: '',
                order_rank_id: '',
                order_point: '',
                order_updated_at: '',
                not_ids: [],
                where_rank_id: '',

                max_point: null,
                min_point: null,
            },

            selects: {
                prize_ranks: {},
            },

            keyWords: '',
            ids: [],/*チェックボックスのID*/


            loading:  true,
            allCheck: false,/*全てチェック*/
            disabled: true,

        } },
        watch: {
            parent_prize_ids:{
                handler(){
                    this.getData();/* データ取得 */
                }, deep: true
            }
        },
        mounted() {

            this.inputs._token = this.token; //token保存
            this.inputs.category_id = this.category_id; //カテゴリーID
            this.inputs.where_rank_id = this.setWhereRankId();//選択中の商品ランク

            this.getData();/* データ取得 */

        },
        methods: {

            /* ガチャランクに応じた商品ランクIDを返す */
            setWhereRankId() {
                let id = null;
                switch (this.gacha_rank_id) {
                    case '100': id = 1; break;
                    case '200': id = 2; break;
                    case '300': id = 3; break;
                    case '400': id = 4; break;
                    case '500': id = 5; break;
                    case '600': id = 6; break;

                    default:  id = 1; break;
                }
                return id;
            },



            /* 商品データ取得 */
            getData(inputs_not=false) {

                this.loading = true;//読み込み中

                if(inputs_not){
                    this.inputs.not_ids = [ ...this.parent_prize_ids, ...this.ids ];//親が持つデータは除く
                }else{
                    //rank絞り込みの処理
                    this.ids=[];
                }

                this.getPagenateData();

                return;
            },


            getPagenateData( route = this.r_api_prize ) {
                axios.post( route , this.inputs )
                .then(json => {
                    // console.log(json.data);

                    //ページネーションデータ
                    const paginate = json.data.prizes;

                    // 商品情報の登録（新規登録・ページネーション追加）
                    this.prizes = route == this.r_api_prize ? paginate.data
                    : [ ...this.prizes, ...paginate.data];

                    // ランクの登録
                    this.selects.prize_ranks = json.data.prize_ranks;

                    this.loading = false;//読み込み中



                    /* 次のデータの読み込み */
                    const current_page = paginate.current_page;//表示中ページ
                    const last_page    = paginate.last_page;   //最終ページ
                    if( current_page != last_page ){
                        const nextPageUrl = paginate.next_page_url;     //URLの更新
                        this.getPagenateData( nextPageUrl );
                    }
                })
                .catch(error => {
                    // alert('通信エラーが発生しました。')
                    // console.log( error.response.data );

                });
            },




            /** キーワード検索 */
            changeKeyWord() {
                this.inputs.key_words = this.keyWords;
                this.getData(); /* データ取得 */
            },


            /** 並び替え */
            changeOrder(key) {
                const order = this.inputs[key];

                switch (order) {
                    case '':    this.inputs[key]='asc';  break;
                    case 'asc': this.inputs[key]='desc';  break;
                    default:    this.inputs[key]='';  break;
                }

                this.getData(); /* データ取得 */
            },


            /** 全て選択をクリック */
            changeAll(){
                const ids = this.prizes.map( value => { return value.id; } );
                this.ids  = this.allCheck ? ids : [];
            },

            /** 子チェックをクリック */
            changeChildren(){
                const ids = this.prizes.map( value => { return value.id; } );
                this.allCheck = this.ids.length == ids.length;
            },


            /** 日付データをテクスト変換  */
            formatDate(inputString) {
                const date = new Date(inputString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1し、2桁にパディング
                const day = String(date.getDate()).padStart(2, '0'); // 日も2桁にパディング

                return `${year}/${month}/${day}`;
            },



            /** 選択した商品IDを送信 */
            sendPrizeId() {
                this.$emit('send-prize-id',this.ids);//選択した商品IDを送信

                this.getData();/* データ取得 */
                this.ids = [];//チェックボックスのリセット
            },


            /** 特殊な商品のdisabled */
            specialDisabled(id=1) {
                return (
                    this.is_special_rank
                    && this.ids.length>0
                    && !this.ids.includes(id)
                ) || (
                    this.is_special_rank
                    && this.parent_prize_ids.length>0
                )
                ;
            },
        },

    };
</script>
