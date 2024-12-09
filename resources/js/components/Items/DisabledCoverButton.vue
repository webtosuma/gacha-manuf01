<template>
    <div class="">

        <button v-show="!disabled"
        @click="click()"
        :class="style_class"
        :type="type" :name="name" :value="value"
        >{{btn_text}}</button>

        <button v-show="disabled" :class="style_class" disabled type="button" >送信中・・・</button>

        <div v-if="disabled"
        class="modal-backdrop faid show" style="opacity: .8;">
            <div class="d-flex align-items-center gap-3 justify-content-center h-100">


                <div class="text-white">
                    <h5 class="fs-3 mb-3">
                        <div class="">処理に時間がかかります。</div>
                        <div class="">そのままお待ちください。</div>
                    </h5>

                    <div class="d-flex gap-3 justify-content-center">
                        <div class="spinner-grow text-dark" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="spinner-grow text-dark" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="spinner-grow text-dark" role="status">
                            <span class="visually-hidden">Loading...</span>
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
            //最初に表示する画像のパス
            btn_text: { type: String, default: 'ボタン', },
            style_class: { type: String, default: 'btn btn-primary', },
            name:     { type: String, default: '', },
            value:    { type: String, default: '', },
            type:     { type: String, default: 'submit', },
        },
        data() { return {

            disabled: false

        } },
        mounted() { },
        methods:{
            /* ボタンをクリックしたとき */
            click: function(){ this.disabled = true; },
        }
    }
</script>
