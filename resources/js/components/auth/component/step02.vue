<template>
    <div v-if="card_num===2" class="anima-fadein-bottom">
        <div class="card shadow border-0 w-100 p-3 mb-3 bg-white">
            <div class="card-body">


                <!-- {{ [verification_code, verification_code_confirmation] }} -->


                <div v-if="test" class="card p-2 border-danger">{{ inputs.verification_code_confirmation }}</div>

                <div class="mb-5">
                    <p class="bg-light p-3">
                        入力されたメールアドレス宛にメールをお送りしました。<br>
                        メールに記載された6ケタの認証番号を入力してください。
                    </p>

                    <label for="verification_code">{{ '認証番号（半角数字）' }}</label>
                    <input v-model="verification_code"
                    id="verification_code"
                    type="text" class="form-control w-50" name="verification_code"
                    autocomplete="current-verification_code">

                    <div v-if="errors.verification_code">
                        <div v-for="(error, key) in errors.verification_code" :key="key" class="text-danger"
                        role="alert">※{{ error }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-8 offset-sm-2 mb-3">
                        <button type="button" @click="nextToStep02"
                        class="btn btn-curve btn-primary text-white w-100"
                        :class="{'disabled':loading}">

                            <span v-if="!loading">{{ '次のステップへ　進む' }}</span>
                            <span v-else>{{ '通信中・・・' }}</span>

                        </button>
                    </div>
                    <div class="col-sm-8 offset-sm-2">
                        <button @click="subCardNum" type="button"
                        class="btn btn-curve btn-secondary text-white w-100">
                            {{ '前のステップへ　戻る' }}
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
            verification_code_confirmation: { type: [String],  default: '', },
            card_num: { type: Number,  default: 1, },
            test:     { type: Boolean, default: false, },
        },
        data() { return {

            loading: false,/* 通信中 */
            verification_code : '',//認証コード(入力)

            errors: {},

        } },
        mounted() {},
        methods: {

            /** Step02 */
            nextToStep02 :function(){

                this.loading = true;// 通信中

                if(this.verification_code == this.verification_code_confirmation ) //[ バリデーション・成功 ]
                {
                    this.errors = {};
                    this.loading = false;// 通信中
                    this.addCardNum();
                }
                else //[ バリデーション・失敗 ]
                {
                    this.errors = {verification_code: ['認証番号が一致しません。']};
                    this.loading = false;// 通信中終了
                }

            },


            /* 次へメソッド */
            addCardNum : function(){ this.$emit('add-card-num'); },

            /* 前へメソッド */
            subCardNum : function(){ this.$emit('sub-card-num'); },


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

        }
    }
</script>
