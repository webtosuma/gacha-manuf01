<template>
    <div>
        <textarea v-model="body"
        :name="'default_'+name"
        :class="style_class"
        :required="required!=0?true:false"
        :id="id" :rows="rows"
        :placeholder="placeholder"
        :maxlength="maxlength"
        ></textarea>

        <input type="hidden" :name="name" :value="urlEncoded(body)">
    </div>
</template>

<script>
    export default {
        props: {
            //最初に表示する画像のパス
            style_class: { type: String, default: 'form-control', },
            placeholder: { type: String, default: '', },
            name:        { type: String, default: '', },
            id:          { type: String, default: '', },
            rows:        { type: String, default: '6', },
            default_body:{ type: String, default: '', },
            required:    { type: [String,Number], default: 0, },
            maxlength:{ type: String, default: '', },
        },
        data : function() {
            return{
                body : '',
            }
        },
        watch: {
            //表示中データが変更したとき、
            default_body:{
                handler: function(){
                    this.body = this.default_body;
                },
                deep:true
            },
        },
        mounted() {

            this.body = this.default_body;


        },
        methods:{

            /* 文字列のエンコード処理 */
            urlEncoded: function(){
                return encodeURIComponent(this.body);
            },

        }
    }
</script>
