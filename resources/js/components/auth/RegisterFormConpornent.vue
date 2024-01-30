<template>
    <div class="w-100">

        <!-- {{ inputs }} -->

        <steps-component :card_num="card_num" />

        <!----- [ ステップ0 ] ----->
        <step00-component :card_num="card_num"/>

        <!----- [ ステップ1 ] ----->
        <step01-component
        @add-card-num="addCardNum()"
        @insert-parent-inputs="insertParentInputs01"
        :card_num="card_num"
        :r_api_step01="r_api_step01"
        :r_privacy_policy="r_privacy_policy"
        :r_trems="r_trems"
        :test="test"/>


        <!----- [ ステップ2 ] ----->
        <step02-component
        @add-card-num="addCardNum()" @sub-card-num="subCardNum()"
        :verification_code_confirmation="inputs.verification_code"
        :card_num="card_num" :test="test.data"/>


        <!----- [ ステップ3 ] ----->
        <step03-component
        @add-card-num="addCardNum()" @sub-card-num="subCardNum()"
        :token="token"
        :r_register_post ="r_register_post"
        :inputs="inputs" :card_num="card_num" :test="test"/>


    </div>
</template>
<script>
    import axios from 'axios'

    export default {
        components: {
            'steps-component' : require('./component/steps.vue').default,
            'step00-component': require('./component/step00.vue').default,
            'step01-component': require('./component/step01.vue').default,
            'step02-component': require('./component/step02.vue').default,
            'step03-component': require('./component/step03.vue').default,
        },
        props: {

            token:        { type: String,  default: '', },
            r_api_step01: { type: String,  default: '', },
            r_register_post: { type: String,  default: '', },
            r_privacy_policy:{ type: String,  default: '', },
            r_trems:         { type: String,  default: '', },
            login_route:     { type: String,  default: '', },
            gacha_route:     { type: String,  default: '', },
            point_sail_route:{ type: String,  default: '', },


        },
        data() { return {

            test : false,
            // test : true,


            loading: false,/* 通信中 */
            inputs: {
                name:'', email:'', password:'', password_confirmation: '', verification_code: '',
            },
            errors: {},
            card_num : 1,/* 表示中カード番号 */


            registerComp: false,/* このブラウザで会員登録が行われたか、否か */

        } },
        mounted() {
            this.registerComp = localStorage.getItem('registerComp') || false;
            if(this.registerComp){
                this.card_num = 0;
            }

         },
        methods:{

            /** 子コンポーネントの入力値を親コンポーネントへ保存 */
            insertParentInputs01   (inputs){ this.inputs = inputs; },


            /* 次へメソッド */
            addCardNum : function(){
                this.card_num ++;
                window.scroll({top: 0, behavior: 'smooth'});
            },

            /* 前へメソッド */
            subCardNum : function(){
                this.card_num --;
                window.scroll({top: 0, behavior: 'smooth'});
            },

            /* 別タブでページを開く */
            windowOpen : function(url){ window.open(url, '_blank'); },

        },

    };
</script>
