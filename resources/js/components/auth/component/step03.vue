<template>
    <div v-if="card_num===3" class="anima-fadein-bottom">

        <div class="card shadow border-0 w-100 p-3 mb-3 bg-white">
            <div class="card-body">

                <div class="card border-0 bg-white  mb-5">
                    <div class="card-body">
                        <h5 class="card-title fw-bold  text-center mb-3">入力内容の確認</h5>
                        <p class="bg-light p-3">
                            入力内容にお間違いなければ登録完了へお進みください。<br>
                            ※登録内容は会員登録設定から変更可能です。
                        </p>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{ 'アカウント名' }}</div>
                            <div class="col-md-8 text-secondary">{{prop_inputs.name}}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{ 'メールアドレス' }}</div>
                            <div class="col-md-8 text-secondary">{{prop_inputs.email}}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{ 'パスワード' }}</div>
                            <div class="col-md-8 text-secondary">{{ '*'.repeat(prop_inputs.password.length) }}</div>
                        </div>

                    </div>
                </div>


                <div class="row mb-3">



                    <input type="hidden" name="_token" :value="token">

                    <input v-for="(value, name) in inputs" :key="name"
                    :value="value" :name="name" type="hidden">



                    <div class="col-sm-8 offset-sm-2 mb-3">

                        <button type="submit" v-show="!inputs.loading"
                        @click="registerComp"
                        class="btn btn-curve btn-primary text-white w-100">
                            {{ 'この内容で登録' }}
                        </button>

                        <button type="button" v-show="inputs.loading"
                        class="btn btn-curve btn-primary text-white w-100" disabled>
                            {{ '登録中・・・' }}
                        </button>

                    </div>

                    <div class="col-sm-8 offset-sm-2">
                        <button @click="subCardNum" type="button" class="btn btn-curve btn-secondary text-white w-100">
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

            token:        { type: String,  default: '', },
            r_register_post: { type: String,  default: '', },
            prop_inputs:   { type: Object,  default: {}, },
            card_num: { type: Number,  default: 1, },
            test:     { type: Boolean, default: false, },

        },
        data() { return {

            inputs: {
                loading: false,/* 通信中(親に送信) */
                name:'', email:'', password:'', password_confirmation: '', verification_code: '',
            },

        } },
        mounted() {
        },
        methods: {


            /** ローカルストレージに登録完了保存 */
            registerComp(){
                this.inputs = this.prop_inputs;
                this.inputs.loading = true;
                this.insertParentInputs(); //子コンポーネントの入力値を親コンポーネントへ保存
            },


            /** 子コンポーネントの入力値を親コンポーネントへ保存 */
            insertParentInputs() { this.$emit('insert-parent-inputs', this.inputs ); },


            /* 次へメソッド */
            addCardNum : function(){ this.$emit('add-card-num'); },

            /* 前へメソッド */
            subCardNum : function(){ this.$emit('sub-card-num'); }

        }
    }
</script>
