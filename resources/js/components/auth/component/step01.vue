<template>
    <div v-if="card_num===1" class="anima-fadein-bottom">
        <div class="card shadow border-0 w-100 p-3 mb-3 bg-white">
            <div class="card-body">


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
                        <div v-for="(error, key) in errors.password_confirmation" :key="key" class="text-danger" role="alert">※{{ error }}</div>
                    </div>
                </div>


                <div class="p-3">
                    本サービスの
                    <a @click.prevent="windowOpen(r_trems)" href="">利用規約</a>と
                    <a @click.prevent="windowOpen(r_privacy_policy)" href="">プライバシーポリシー</a>に
                    同意した時のみ、登録を行なってください。登録後は、同意したものとみなされます。

                </div>

                <div class="row mb-3">
                    <div class="col-sm-8 offset-sm-2">
                        <button type="button" @click="nextToStep01"
                        class="btn btn-curve btn-primary text-white w-100"
                        :class="{'disabled':loading}">

                            <span v-if="!loading">{{ '次のステップへ　進む' }}</span>
                            <span v-else>{{ '通信中・・・' }}</span>

                        </button>
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
            card_num: { type: Number,  default: 1, },
            test:     { type: Boolean, default: false, },
            r_api_step01: { type: String,  default: '', },

            r_privacy_policy:{ type: String,  default: '', },
            r_trems:         { type: String,  default: '', },
        },
        data() { return {

            loading: false,/* 通信中 */
            route:'/api/worker/auth/register_step01',
            inputs: {
                name:'', email:'', password:'', password_confirmation: '', verification_code: '',
            },
            errors: {},

            /* オプションデータ */
            form_option : {
                // 誕生日の最大値
                max_barthday : this.maxBarthday(),

                // パスワード入力の表示形式
                input_password :{ type : 'password', text : 'パスワードを表示', },
            },
        } },
        mounted() {

            this.insertTestData();     /** テスト用入力データの挿入 */
            this.insertParentInputs(); //子コンポーネントの入力値を親コンポーネントへ保存

        },
        methods: {

            /** Step01 */
            nextToStep01 :function(){

                this.loading = true;// 通信中

                axios.post( this.r_api_step01, this.inputs )
                .then(json => {
                    // console.log(json);

                    this.inputs.verification_code = json.data.verification_code;
                    this.insertParentInputs(); //子コンポーネントの入力値を親コンポーネントへ保存
                    this.loading = false;// 通信中
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
                    // console.log( error.response.data );

                });
            },


            /** 入力できる誕生日の最大値 */
            maxBarthday : function(){
                const now =new Date();
                const max_year = now.getFullYear() - 15;
                return  max_year+'-12-31';
            },


            /** 子コンポーネントの入力値を親コンポーネントへ保存 */
            insertParentInputs() { this.$emit('insert-parent-inputs', this.inputs ); },

            /** 次へメソッド */
            addCardNum() { this.$emit('add-card-num'); },

            /* 別タブでページを開く */
            windowOpen : function(url){ window.open(url, '_blank'); },



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


            /** テスト用入力データの挿入 */
            insertTestData: function(){
                if( this.test ){ this.inputs = {
                    name:'test太朗', email:'aek1214@yahoo.co.jp',
                    password:'password', password_confirmation: 'password',
                }; }
            },


        }
    }
</script>
