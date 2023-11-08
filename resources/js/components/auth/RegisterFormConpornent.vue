<template>
    <div class="w-100">

        <div v-if="card_num===1" class="anima-fadein-bottom">

            <form @submit.prevent="submit()"
            v-if="card_num===1" class="w-100">

                <h2 class="h3 mb-3 fw-bold text-center">会員登録（無料）</h2>

                <div class="row mb-3">
                    <label for="name" class=" col-form-label text-start">{{ 'アカウント名' }}</label>

                    <input v-model="inputs.name" name="name"
                    :class="{'is-invalid':errors.name}"
                    id="name" type="text" class="form-control"
                    autocomplete="name" autofocus>

                    <div class="text-danger" v-if="errors.name">※{{ errors.name[0] }}</div>
                </div>
                <div class="row mb-3">
                    <label for="email" class=" col-form-label text-start">{{ 'メールアドレス' }}</label>

                    <input v-model="inputs.email" name="email"
                    :class="{'is-invalid':errors.email}"
                    id="email" type="text" class="form-control"
                    autocomplete="email" autofocus>

                    <div class="text-danger" v-if="errors.email">※{{ errors.email[0] }}</div>
                </div>
                <div class="row mb-3">
                    <label for="password" class=" col-form-label text-start">{{ 'パスワード' }}</label>

                    <input v-model="inputs.password" name="password"
                    :class="{'is-invalid':errors.password}"
                    id="password" type="text" class="form-control"
                    autocomplete="password" autofocus>

                    <div class="text-danger" v-if="errors.password">※{{ errors.password[0] }}</div>
                </div>
                <div class="row mb-3">
                    <label for="password_confirmation" class=" col-form-label text-start">{{ '確認用パスワード' }}</label>

                    <input v-model="inputs.password_confirmation" name="password_confirmation"
                    :class="{'is-invalid':errors.password_confirmation}"
                    id="password_confirmation" type="text" class="form-control"
                    autocomplete="password_confirmation" autofocus>

                    <div class="text-danger" v-if="errors.password_confirmation">※{{ errors.password_confirmation[0] }}</div>
                </div>
                <div class="p3">
                    本サービスの
                    <a @click.prevent="windowOpen(trems_route)" href="">利用規約</a>と
                    <a @click.prevent="windowOpen(privacy_policy_route)" href="">プライバシーポリシー</a>に
                    同意した時のみ、登録を行なってください。登録後は、同意したものとみなされます。

                </div>
                <div class="row my-5">
                    <div class="col-md-6 mx-auto">

                        <disabled-button-component
                        style_class="btn btn-lg btn-primary w-100"
                        :disabled="loading" btn_text="登録する" />

                    </div>
                </div>

            </form>

            <hr class="my-4 w-100">
            <div class="text-center w-100">
                <div class="col-md-6 mx-auto">
                    <small class="text-body-secondary">既にアカウントをお持ちの方はこちら</small>
                    <a :href="login_route"
                    class="w-100 py-2 mb-2 btn btn-outline-primary rounded-3"
                    >ログイン</a>
                </div>
            </div>

        </div>
        <div v-if="card_num===2" class="anima-fadein-bottom">
            <div class="card shadow-sm border-0 w-100 p-3 mb-3">
                <div class="card-body">

                    <h5 class="text-secondary fw-bold mb-3 text-center">会員登録が完了しました。</h5>

                    <h3 class="text-secondary fw-bold mb-3 text-center">
                        ようこそ、{{inputs.name}} さん
                    </h3>

                    <div class="row g-3 mt-5 mb-3">
                        <div class="col-md-8 offset-md-2">
                            <a :href="gacha_route"
                            class="w-100 py-2 mb-2 btn btn-lg btn-primary rounded-pill shadow"
                            >ガチャで遊ぶ</a>
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <a :href="point_sail_route"
                            class="w-100 py-2 mb-2 btn btn-lg btn-warning rounded-pill shadow"
                            >ポイントを購入する</a>
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

            token:        { type: String,  default: '', },
            submit_route: { type: String,  default: '', },

            privacy_policy_route: { type: String,  default: '', },
            trems_route:     { type: String,  default: '', },
            login_route:     { type: String,  default: '', },
            gacha_route:     { type: String,  default: '', },
            point_sail_route:{ type: String,  default: '', },


        },
        data() { return {

            loading: false,/* 通信中 */
            inputs: {
                name:'', email:'', password:'', password_confirmation: '',
            },
            errors: {},
            card_num : 1,/* 表示中カード番号 */

        } },
        mounted() {

            /* トークンの保存 */
            this.inputs = { ...this.inputs, _token:this.token }

        },
        methods:{

            /* データ送信 */
            submit :function(){

                this.loading = true;// 通信中

                axios.post( this.submit_route, this.inputs )
                .then(json => {
                    // console.log(json.data);

                    this.addCardNum();
                })
                .catch(error => {

                    //バリデーション結果の受け取り
                    if( error.response.status == 422 ){
                        // console.log( error.response.data.errors );
                        this.errors = error.response.data.errors;
                        this.loading = false;// 通信中終了
                    }
                    //その他のエラー
                    else{ alert('データ送信エラーが発生しました。'); }
                    console.log( error.response.data );

                });

            },

            /* 次へメソッド */
            addCardNum : function(){
                this.card_num ++;
                window.scroll({top: 0, behavior: 'smooth'});
            },

            /* 別タブでページを開く */
            windowOpen : function(url){ window.open(url, '_blank'); },

        },

    };
</script>
