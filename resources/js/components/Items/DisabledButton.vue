<template>
    <div>
        <button v-show="!data_disabled"
        @click="click()"
        :class="style_class"
        :type="type" :name="name" :value="value"
        :disabled="disabled!=0"
        >{{btn_text}}</button>

        <button v-show="data_disabled" :class="style_class" disabled type="button" >送信中・・・</button>
    </div>
</template>

<script>
import { valueToNode } from '@babel/types';

    export default {
        props: {
            //最初に表示する画像のパス
            // disabled: { type: Boolean, default: false, },
            btn_text: { type: String, default: 'ボタン', },
            style_class: { type: String, default: 'btn btn-primary', },
            name:     { type: String, default: '', },
            value:    { type: String, default: '', },
            type:     { type: String, default: 'submit', },
            disabled: { type: String, default: '0', },
        },
        data() { return {

            data_disabled: false

        } },
        mounted() { },
        methods:{
            /* ボタンをクリックしたとき */
            click: function(){ this.data_disabled = true; },
        }
    }
</script>
