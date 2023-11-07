<template>
    <div>
        <button v-show="!disabled" :class="style_class" :disabled="btn_disabled"
        @click="click" :type="type" :name="name" :value="value"
        >{{btn_text}}</button>

        <button v-show="disabled" :class="style_class" disabled type="button" >送信中・・・</button>
    </div>
</template>

<script>
import { valueToNode } from '@babel/types';

    export default {
        data : function() {
            return{
                btn_text: '',
                disabled: false,
            }
        },
        props: {
            //最初に表示する画像のパス
            text:        { type: String, default: 'ボタン', },
            style_class: { type: String, default: 'btn btn-primary', },
            name:    { type: String, default: '', },
            value:   { type: String, default: '', },
            type:    { type: String, default: 'submit', },
            btn_disabled:{ type: [Boolean, Number, String], default: false, }
        },
        mounted() {
            this.btn_text = this.text;
        },
        methods:{
            click : function(){ this.disabled = true; }
        }
    }
</script>
