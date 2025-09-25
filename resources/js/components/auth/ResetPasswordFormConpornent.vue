<template>
    <div class="w-100">

        <!----- [ ステップ１ ] ----->
        <div v-if="card_num === 1" class="anima-fadein-bottom">
            <div class="card shadow border-0 w-100 p-3 mb-3">
                <div class="card-body">


                    <div class="mb-5">
                        <label for="email">アカウントのメールアドレス</label>
                        <input id="email" type="email" class="form-control" name="email"
                        v-model="inputs.email" required autocomplete="email" autofocus>

                        <div v-if="errors.email" class="text-danger" role="alert">※{{ errors.email }}</div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-sm-8 offset-sm-2">

                            <disabled-button-component
                            @btn-click="nextToStep(step01_route)"
                            style_class="btn btn-lg btn-primary text-white w-100"
                            :disabled="loading" btn_text="次へ" />

                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!----- [ ステップ２ ] ----->
        <div v-if="card_num === 2" class="anima-fadein-bottom">
            <div class="card shadow border-0 w-100 p-3 mb-3">
                <div class="card-body">


                    <div class="mb-5">
                        <p class="bg-light p-3">
                            入力されたメールアドレス宛にメールをお送りしました。<br>
                            メールに記載された6ケタの認証番号を入力してください。
                        </p>

                        <label for="reset_pass_code">{{ '認証番号（半角数字）' }}</label>

                        <div class="d-flex gap-3">
                            <input id="reset_pass_code" type="text" class="form-control w-50" name="reset_pass_code"
                            v-model="inputs.reset_pass_code" required autocomplete="current-reset_pass_code">

                            <input type="button" @click="resendVerificationEmail()"
                            value="認証メール再送" class="btn btn-outline-secondary"/>
                        </div>

                        <div v-if="errors.reset_pass_code">
                            <div v-for="(error, key) in errors.reset_pass_code" :key="key" class="text-danger"
                            role="alert">※{{ error }}</div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="password" class="d-flex justify-content-between align-items-center">
                            {{ 'パスワード' }}
                            <a href="" class="btn btn-link" @click.prevent="toggleDisplayPassword"
                            style="font-size:.5rem; text-decoration:none;">{{ form_option.input_password.text }}</a>
                        </label>
                        <p class="mb-0" style="font-size:.8rem;">※8文字以上20文字以下の半角英数字</p>

                        <input id="password" class="form-control" name="password"
                        :type="form_option.input_password.type"
                        v-model="inputs.password" required autocomplete="current-password">

                        <div v-if="errors.password">
                            <div v-for="(error, key) in errors.password" :key="key" class="text-danger"
                            role="alert">※{{ error }}</div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="password_confirmation">{{ 'パスワードの確認' }}</label>
                        <p class="mb-0" style="font-size:.8rem;">※8文字以上20文字以下の半角英数字</p>

                        <input id="password_confirmation" class="form-control" name="password_confirmation"
                        :type="form_option.input_password.type"
                        v-model="inputs.password_confirmation" required autocomplete="current-password_confirmation">

                        <div v-if="errors.password_confirmation">
                            <div v-for="(error, key) in errors.password_confirmation" :key="key" class="text-danger"
                            role="alert">※{{ error }}</div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-sm-8 offset-sm-2">
                            <button type="button" @click="nextToStep(step02_route)"
                            class="btn btn-curve btn-primary text-white w-100"
                            :class="{'disabled':loading}">

                                <span v-if="!loading">{{ '次へ' }}</span>
                                <span v-else>{{ '通信中・・・' }}</span>

                            </button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!----- [ ステップ３ ] ----->
        <div v-if="card_num === 3" class="anima-fadein-bottom">
            <div class="card shadow border-0 w-100 p-3 mb-3">
                <div class="card-body">

                    <h5 class="text-secondary fw-bold text-center mt-3">新しいパスワードに変更しました。</h5>

                    <div class="row g-3 mt-5 mb-3">
                        <div class="col-md-8 offset-md-2">
                            <a :href="login_route" onclick="stopOnbeforeunload()"
                            class="w-100 py-2 mb-2 btn btn-lg text-white btn-primary rounded-pill shadow"
                            >ログインする</a>
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <a href="#" @click.prevent="historyBack()" onclick="stopOnbeforeunload()"
                            class="w-100 py-2 mb-2 btn btn-lg text-white btn-warning rounded-pill shadow"
                            >前のページに戻る</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        props: {
            token:        { type: String,  default: '', },
            step01_route: { type: String,  default: '', },
            step02_route: { type: String,  default: '', },

            login_route:  { type: String,  default: '', },
        },
        data() {return{

            test :false,/* test用フォーム・データの利用 */

            card_num : 1,/* 表示中カード番号 */

            /* 入力内容 */
            inputs : {
                email: '',
                reset_pass_code : '',//認証コード(入力)
                reset_pass_code_confirmation : '',//認証コード
                password : '',
                password_confirmation : '',
            },

            errors : {},/* エラー内容 */

            loading: false,/* 通信中 */


            /* オプションデータ */
            form_option : {
                // パスワード入力の表示形式
                input_password :{ type : 'password', text : 'パスワードを表示',}
            },

        } },
        mounted() {
            /* テストデータの挿入 */
            if(this.test){ this.inputs = {
                email: 'contact@tosucare.com',
                reset_pass_code : '',//認証コード(入力)
                reset_pass_code_confirmation : '000000',//認証コード
                password : 'password',
                password_confirmation : 'password',

            } }


            /* トークンの保存 */
            this.inputs = { ...this.inputs, _token:this.token }

        },
        methods:{


            /* 次のステップメソッド */
            nextToStep :function( route ,addCard=true ){

                this.loading = true;// 通信中
                axios.post( route, this.inputs )
                .then(json   => {

                    // 認証コードの保存
                    // const verification_code = json.data.verification_code;
                    // if( verification_code ){
                    //     this.inputs.reset_pass_code_confirmation = verification_code;
                    // }

                    if( addCard ){//パスワード再発行の時はスキップ
                        this.addCardNum();
                        this.errors = {};
                    }
                    this.loading = false;// 通信中終了
                })
                .catch(error => {

                    //バリデーション結果の受け取り
                    if( error.response.status == 422 ){
                        // console.log(error.response.data);
                        this.errors = error.response.data.errors;
                        this.loading = false;
                    }
                    //その他のエラー
                    else{ alert('データ送信エラーが発生しました。'); }
                    // console.log( error.response.data );
                    this.loading = false;// 通信中終了
                });
            },


            /* 認証メールの再送 */
            resendVerificationEmail(){
                this.inputs.reset_pass_code_confirmation = '';//認証コードが空のとき、再発行
                this.nextToStep( this.step01_route, false);
            },



            /* 次へメソッド */
            addCardNum : function(){
                this.card_num ++;
                window.scroll({top: 0, behavior: 'smooth'});
            },


            /* パスワード入力の表示切替 */
            toggleDisplayPassword : function(){

                let type = this.form_option.input_password.type;
                if( type === 'password' )
                {
                    this.form_option.input_password.text = 'パスワードを非表示';
                    this.form_option.input_password.type = 'text';
                }
                else
                {
                    this.form_option.input_password.text = 'パスワードを表示';
                    this.form_option.input_password.type = 'password';
                }
            },


            /* 前のページに戻る */
            historyBack: function(){ history.back(); },


            /* 離脱防止アラートの解除 */
            stopOnbeforeunload : function(){ window.onbeforeunload = null; },
        }
    }
</script>
