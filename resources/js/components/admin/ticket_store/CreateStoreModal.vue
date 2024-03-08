<template>
    <div class="">

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary text-white rounded-pill"
        data-bs-toggle="modal" data-bs-target="#createStoreModal"
        >＋ 追加する</button>

        <!-- Modal -->
        <div class="modal fade" id="createStoreModal" tabindex="-1" aria-labelledby="createStoreModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header gap-1 border-0">
                        <!-- <h5 class="modal-title" id="createStoreModalLabel">追加商品の選択</h5> -->
                        <div class="col">
                            <input @change="changeKeyWord()" v-model="keyWords"
                            type="text" class="form-control form-control-lgg" placeholder="検索：商品名・商品コード名"
                            aria-label="Username" aria-describedby="basic-addon1" />
                        </div>
                        <div class="col-auto">
                            <select @change="getData()"
                            v-model="inputs.where_rank_id"
                            class="form-select fw-bold" aria-label="Default select example">
                                <option v-for="(prize_rank, key) in selects.prize_ranks" :key="key"
                                :value="prize_rank.id">{{ prize_rank.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-header pt-0">
                        <div class="col">
                            <span class="text-primary fw-bold">選択商品ID: {{ ids.join(', ') }}</span>
                        </div>
                        <div class="col-auto">
                            <button @click="ids=[]"
                            class="btn btn-sm border">×</button>
                        </div>
                    </div>

                    <!--body-->
                    <div class="modal-body bg-white">
                        <table class="table">


                            <tbody>
                                <tr v-for="(prize, key) in prizes" :key="key">
                                    <td style="width:1rem;">
                                        <input v-model="ids" :value="prize.id"
                                        class="form-check-input" type="checkbox" >
                                        <div class="text-center form-text">{{ prize.id }}</div>
                                    </td>
                                    <td scope="row">
                                        <!--画像-->
                                        <div style="width:3rem;">
                                            <ratio-image-component
                                            style_class="ratio ratio-3x4 rounded"
                                            :url=" prize.image_path " />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="">{{ prize.name }}</div>
                                        <div class="form-text">{{ prize.code }}</div>
                                    </td>
                                    <td>{{ prize.rank.name }}</td>
                                    <td>{{ prize.point }} pt</td>


                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <div class="col">
                            <button type="button" class="btn btn-primary text-white w-100"
                            :class="{'disabled':ids.length==0}"
                            @click="createStore()"
                            data-bs-dismiss="modal"
                            >+ 選択した商品を追加</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn border w-100"
                            data-bs-dismiss="modal">閉じる</button>
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
            r_api_prize:{ type: String,  default: '', },   //商品
            r_api_create: { type: String,  default: '', },//新規作成

            // r_api_update: { type: String,  default: '', },//更新
            // r_api_destroy: { type: String,  default: '', },//削除
            // r_create:{ type: String,  default: '', },
            // r_edit:{ type: String,  default: '', },
            // r_download_csv:{ type: String,  default: '', },//csvファイルダウンロードパス
            category_id:{ type: String,  default: '', },
        },
        data() { return {

            loading: true,

            categories:[],//ガチャ カテゴリー
            prizes:  [],/* 商品 */

            inputs: { },
            reset_inputs: {
                key_words: '',
                where_rank_id: 1
            },


            selects: {
                prize_ranks: {},
            },

            keyWords: '',

            ids: [],/*チェックボックスのID*/

        } },
        watch: {
            category_id:{ handler(){
                // console.log(this.category_id);
                this.inputs.category_id = this.category_id;
                this.getData();/* データ取得 */
            }, deep: true },
        },

        mounted() {

            this.inputs = {...this.reset_inputs}; //入力値のリセット
            this.inputs._token = this.token; //token保存
            this.inputs.category_id = this.category_id ;
            this.getData();

        },
        methods:{

            /* 商品データ取得 */
            getData(route = this.r_api_prize) {

                this.loading = true;//読み込み中

                axios.post( route , {_token: this.token, ...this.inputs} )
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
                        this.getData( nextPageUrl );
                    }
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );

                });
            },


            /** キーワード検索 */
            changeKeyWord() {
                this.inputs.key_words = this.keyWords;
                this.getData(); /* データ取得 */
            },


            /** 新規追加 */
            createStore() {
                // console.log(this.r_api_create)

                const route = this.r_api_create;
                axios.post( route , {_token: this.token, ids: this.ids } )
                .then(json => {
                    // console.log(json.data);

                    /*リセット*/
                    this.inputs = {...this.reset_inputs}; //入力値のリセット
                    this.ids = [];//入力値のリセット
                    this.getData();

                    this.$emit('create-store');

                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );

                });


            },

        },

    };
</script>
