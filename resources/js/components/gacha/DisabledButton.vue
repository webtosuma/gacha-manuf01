<template>
    <div>
        <!-- no disabled -->
        <button v-show=" !(disabled_data || disabled!=0) "
        @click="click()"
        :class="style_class"
        :type="type" :name="name" :value="value"
        >
            <div class="">{{ label }}</div>
            <div class="text-warning">{{ point }}</div>
        </button>

        <!-- disabled -->
        <button v-show=" (disabled_data || disabled!=0) "
        disabled
        :class="style_class"
        :type="type" :name="name" :value="value"
        >
            <div class="">{{ label }}</div>
            <div class="text-warning" :class="{'invisible': disabled!=0}">{{ point }}</div>

        </button>
    </div>
</template>

<script>
    export default {
        data() { return{
            disabled_data: 0,
        } },
        props: {
            //最初に表示する画像のパス
            disabled: { type: [String,Number], default: 0, },
            style_class: { type: String, default: 'btn btn-primary', },
            name:     { type: String, default: '', },
            value:    { type: String, default: '', },
            type:     { type: String, default: 'submit', },

            label:     { type: String, default: '', },
            point:     { type: [String, Number], default: '', },
        },
        mounted() {

            // this.disabled_data = this.disabled;

        },
        methods:{
            /* ボタンをクリックしたとき */
            click: function(){ this.disabled_data=1; },
        }
    }
</script>
