<template>
    <div class="">

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary text-white"
        data-bs-toggle="modal" data-bs-target="#createAddressModal"
        :disabled="comp"
        >お届け先の新規登録</button>

        <div v-if="use_size!=0"
        class="text-danger mt-2 form-text">スニーカーを発送の際は発送先情報のサイズの欄に入力登録をお願いします。</div>


        <!-- Modal -->
        <div class="modal fade" id="createAddressModal" tabindex="-1" aria-labelledby="createAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAddressModalLabel">お届け先の新規登録</h5>
                    <button v-if="!comp"
                    type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div v-if="!comp">
                        <label class="mb-3 d-block">
                            <div class="form-label">氏名（フルネーム）</div>
                            <input v-model="inputs.name" name="name"
                            :disabled="loading"
                            type="text" class="form-control" placeholder="">
                            <!-- error message -->
                            <div v-if="errors.name" class="text-danger" role="alert">※{{ errors.name[0] }}</div>
                        </label>

                        <label v-if="use_size!=0" class="mb-3 d-block">
                            <div class="form-label">希望の靴サイズ（cm）</div>
                            <select v-model="inputs.size" name="size" class="form-select" :disabled="loading">
                                <option value="">選択してください</option>
                                <option  v-for="(size, key) in form_option.sizes" :key="key"
                                :value="size">{{ size }}</option>
                            </select>

                            <div v-if="errors.size" class="text-danger" role="alert">※{{ errors.size[0] }}</div>
                        </label>

                        <label class="mb-3 d-block">
                            <div class="form-label">メールアドレス</div>
                            <input v-model="inputs.email" name="email"
                            :disabled="loading"
                            type="email" class="form-control" placeholder="例：example@email.co.com">
                            <!-- error message -->
                            <div v-if="errors.email" class="text-danger" role="alert">※{{ errors.email[0] }}</div>
                        </label>

                        <label class="mb-3 d-block">
                            <div class="form-label">電話番号（半角数字のみ）</div>
                            <input v-model="inputs.tell" name="tell"
                            :disabled="loading"
                            type="text" class="form-control" placeholder="例：0000000">
                            <!-- error message -->
                            <div v-if="errors.tell" class="text-danger" role="alert">※{{ errors.tell[0] }}</div>
                        </label>

                        <label class="mb-3 d-block">
                            <div class="form-label">郵便番号（7桁の半角数字のみ）</div>
                            <div class="col-6">
                                <input v-model="inputs.postal_code" name="postal_code"
                                :disabled="loading"
                                type="text" class="form-control"  placeholder="例：0001234">
                            </div>
                            <!-- error message -->
                            <div v-if="errors.postal_code" class="text-danger" role="alert">※{{ errors.postal_code[0] }}</div>
                        </label>


                        <label class="mb-3 d-block">
                            <div class="form-label">都道府県</div>
                            <select v-model="inputs.todohuken" name="todohuken" class="form-select" :disabled="loading">
                                <option value="">選択してください</option>
                                <option  v-for="(todohuken, key) in form_option.todohukens" :key="key"
                                :value="todohuken">{{ todohuken }}</option>
                            </select>
                            <!-- error message -->
                            <div v-if="errors.todohuken" class="text-danger" role="alert">※{{ errors.todohuken[0] }}</div>
                        </label>


                        <label class="mb-3 d-block">
                            <div class="form-label">市町村</div>
                            <input v-model="inputs.shikuchoson" name="shikuchoson"
                            :disabled="loading"
                            type="text" class="form-control" placeholder="例：〇〇市〇〇街">
                            <!-- error message -->
                            <div v-if="errors.shikuchoson" class="text-danger" role="alert">※{{ errors.shikuchoson[0] }}</div>
                        </label>


                        <label class="mb-3 d-block">
                            <div class="form-label">丁目・番地・号・建物名・部屋番号</div>
                            <input v-model="inputs.number" name="number"
                            :disabled="loading"
                            type="text" class="form-control" placeholder="例：1-2-3 〇〇ビル 123">
                            <!-- error message -->
                            <div v-if="errors.number" class="text-danger" role="alert">※{{ errors.number[0] }}</div>
                        </label>



                        <label class="mb-3 d-block">
                            <div class="form-label">備考欄</div>
                            <textarea  v-model="inputs.remarks"
                            name="remarks"
                            class="form-control"
                            id="input_body" style="height:6rem;"></textarea>

                            <!-- error message -->
                            <div v-if="errors.remarks" class="text-danger" role="alert">※{{ errors.remarks[0] }}</div>
                        </label>


                        <div class="col-md-8 mx-auto mt-5">
                            <disabled-button-component @btn-click="store()"
                            style_class="btn btn-lg btn-primary text-white w-100"
                            type="button"
                            :disabled="loading" btn_text="お届け先の登録" />
                        </div>

                    </div>
                    <div v-else>
                        <div class="mb-3 text-center fs-3">
                            お届け先情報を追加しました。
                        </div>
                        <div class="col-md-8 mx-auto my-5">
                            <button @click="close()"
                            data-bs-dismiss="modal"
                            class="btn btn-lg border w-100">閉じる</button>
                        </div>
                    </div>
                </div>



            </div>
                <!-- end modal body -->

                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> -->
            </div>
        </div>
        </div>


</template>
<script>
    import axios from 'axios'
    export default {
        props: {
            token:{ type: String,  default: '', },
            r_store: { type: [String, Number], default: null },
            use_size:{ type: [String, Number], default: '0' },
            // user_email:{ type: String, default: '' },
        },
        data() { return {

            inputs: {
                name        :'',//宛名
                size        :'',//靴のサイズ
                email       :'',//メールアドレス
                tell        :'',//電話番号
                postal_code :'',//郵便番号
                todohuken   :'',//住所-都道府県
                shikuchoson :'',//住所-市町村
                number      :'',//住所-番地
                remarks     :'',//備考欄
            },

            errors : {},/* エラー内容 */

            loading: false,/* 通信中 */
            comp: false,   /* 完了 */


            /* オプションデータ */
            form_option : {
                //靴のサイズ
                sizes : [
                    '24.0cm','24.5cm','25.0cm','25.5cm','26.0cm','26.5cm','27.0cm','27.5cm','28.0cm','28.5cm','29.0cm','29.5cm','30.0cm'
                ],
                // 都道府県
                todohukens : {
                    1 : '北海道', 2 : '青森県', 3 : '岩手県', 4 : '宮城県', 5 : '秋田県', 6 : '山形県', 7 : '福島県',
                    8 : '茨城県', 9 : '栃木県', 10 : '群馬県', 11 : '埼玉県', 12 : '千葉県', 13 : '東京都', 14 : '神奈川県',
                    15 : '新潟県', 16 : '富山県', 17 : '石川県', 18 : '福井県', 19 : '山梨県', 20 : '長野県', 21 : '岐阜県',
                    22 : '静岡県', 23 : '愛知県', 24 : '三重県', 25 : '滋賀県',
                    26 : '京都府', 27 : '大阪府', 28 : '兵庫県', 29 : '奈良県', 30 : '和歌山県',
                    31 : '鳥取県', 32 : '島根県', 33 : '岡山県', 34 : '広島県', 35 : '山口県',
                    36 : '徳島県', 37 : '香川県', 38 : '愛媛県', 39 : '高知県',
                    40 : '福岡県', 41 : '佐賀県', 42 : '長崎県', 43 : '熊本県', 44 : '大分県', 45 : '宮崎県', 46 : '鹿児島県', 47 : '沖縄県',
                },
            },
        } },
        mounted() {

            /*データの初期化*/
            this.inputs = this.default();

            /*tokenの保存*/
            this.inputs = {...this.inputs, _token: this.token }

        },
        methods: {


            /* 新規登録*/
            store: function(){
                this.loading = true;// 通信中

                const route = this.r_store; //新規作成

                //エンコード処理
                const post_inputs = Object.assign({}, this.inputs);
                const encode_list = ['name','remarks'];
                encode_list.forEach(encode_param => {
                    post_inputs[encode_param] = this.uriEncoded(post_inputs[encode_param]);
                });

                axios.post( route, post_inputs )
                .then(json => {
                    console.log( json.data );
                    this.comp = true;
                    this.loading = false;// 通信中

                    /** お届け先一覧の更新 */
                    this.getList();
                })
                .catch(error => {

                    //バリデーション結果の受け取り
                    if( error.response.status == 422 ){
                        console.log( error.response.data.errors );
                        this.errors = error.response.data.errors;
                        this.loading = false;// 通信中終了

                    }
                    //その他のエラー
                    else{ alert('データ送信エラーが発生しました。'); }

                });
            },

            /** モーダルを閉じる->初期化 */
            close() {
                this.comp = false;
                this.inputs = this.default();
            },


            /** デフォルトデータ */
            default() { return {
                name        :'',//宛名
                size        :'',//靴のサイズ
                email       :'',//メールアドレス
                tell        :'',//電話番号
                postal_code :'',//郵便番号
                todohuken   :'',//住所-都道府県
                shikuchoson :'',//住所-市町村
                number      :'',//住所-番地
                remarks     :'',//備考欄
            }; },


            /** お届け先一覧の更新 */
            getList(){ this.$emit('my-created'); },


            /* デフォルト文字列のエンコード処理 */
            uriEncoded(body){ return encodeURIComponent(body); },
        },
    }
</script>
