<template>
    <div>
        <!-- Button trigger modal -->
        <button type="button" :class="modal_btn_class" :disabled="modal_btn_disabled"
        data-bs-toggle="modal" :data-bs-target="modal_target">
            {{ modal_btn_text }}
        </button>



        <!-- Modal -->
        <div class="modal fade" :id="modal_id" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!--- コンポーネント内フォーム送信あり --->
                    <form v-if="form_action" :action="form_action" method="POST">
                        <input type="hidden" name="_token"  :value="token">
                        <input type="hidden" name="_method" :value="method">



                        <h5 class="modal-body">

                            <!-- モーダル本文 -->
                            <slot name="modal-body">モーダル本文</slot>

                            <!-- フォーム入力パラメーター -->
                            <slot name="form-inputs"></slot>

                        </h5>
                        <div class="modal-footer">
                            <!-- 閉じるボタン -->
                            <button type="button" class="btn text-secondary" data-bs-dismiss="modal"
                            >閉じる</button>

                            <!-- 送信ボタン -->
                            <disabled-button-component v-if="(submit_btn_text != '')&&( submit_btn_disabled=='1' ) "
                            :style_class="submit_btn_class" :text="submit_btn_text" :type="submit_btn_type"
                            ></disabled-button-component>

                            <!-- 関数実行ボタン -->
                            <button v-if="(submit_btn_text != '')&&( submit_btn_disabled!=1 ) "
                            :class="submit_btn_class"  data-bs-dismiss="modal" :type="button"
                            @click="funcBtnClick()"
                            >{{ submit_btn_text }}</button>
                        </div>


                    </form>
                    <!--- コンポーネント内フォーム送信なし --->
                    <div v-else>



                        <h5 class="modal-body">

                            <!-- モーダル本文 -->
                            <slot name="modal-body">モーダル本文</slot>

                            <!-- フォーム入力パラメーター -->
                            <slot name="form-inputs"></slot>

                        </h5>
                        <div class="modal-footer">
                            <!-- 閉じるボタン -->
                            <button type="button" class="btn text-secondary" data-bs-dismiss="modal"
                            >閉じる</button>

                            <!-- 送信ボタン -->
                            <disabled-button-component v-if="(submit_btn_text != '')&&( submit_btn_disabled=='1' ) "
                            :style_class="submit_btn_class" :text="submit_btn_text" :type="submit_btn_type"
                            ></disabled-button-component>

                            <!-- 関数実行ボタン -->
                            <button v-if="(submit_btn_text != '')&&( submit_btn_disabled!=1 ) "
                            :class="submit_btn_class"  data-bs-dismiss="modal" :type="button"
                            @click="funcBtnClick()"
                            >{{ submit_btn_text }}</button>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    /*
        # フォームサブミットモーダル
        <modal-component form_action="hogehoge" token="{{csrf_token()}}" method="delete" modal_name="delete"
        modal_btn_text="削除する" modal_btn_class="btn"
        submit_btn_text="削除"    submit_btn_class="btn btn-danger text-white" >
            <template v-slot:modal-body>
                選択されたスカウトメッセージを全て削除します。<br>
                本当によろしいですか？
            </template>
            <template v-slot:form-inputs>
                <input type="hidden" name="input_name" value="input_value">
            </template>
        </modal-component>



        # 親要素関数( parent() )実行モーダル
        <modal-component modal_name="hogehoge"
        @func_btn_click="parent()"　modal_name="hogehoge"
        modal_btn_text="親要素関数の実行" modal_btn_class="btn btn-primary text-white"
        submit_btn_text="実行"          submit_btn_class="btn btn-success text-white" submit_btn_disabled="0">
            <template v-slot:modal-body>
                関数を実行しますか？
            </template>
        </modal-component>


    */
    export default {
        props: {
            form_action:         { type: String, default: '', }, //フォーム送信URL
            token:               { type: String, default: '', }, //トークン
            method:              { type: String, default: '', }, //メソッド
            modal_name:          { type: String, default: '', }, //モーダル名

            modal_btn_class:     { type: String, default: 'btn btn-primary', }, //モーダル表示ボタンのclass
            modal_btn_text:      { type: String, default: 'モーダル表示', },     //モーダル表示ボタンの表示文
            modal_btn_disabled:  { type: [Boolean, Number, String], default: false, },

            submit_btn_class:    { type: String, default: 'btn btn-success', }, //送信ボタンのclass
            submit_btn_text:     { type: String, default: '送信', },            //送信ボタンの表示文 、''のとき、非表示
            submit_btn_type:     { type: String, default: 'submit', },          //送信ボタンのtype
            submit_btn_disabled: { type: String, default: '1', },          //送信ボタンのtype

        },
        data : function() {
            return{
                //モーダルID名
                modal_id:      'modal-id',
                modal_target: '#modal-id',
            }
        },
        mounted() {

            //モーダルID名の設定
            this.modal_id     =      this.modal_name + 'Modal'
            this.modal_target = '#' + this.modal_name + 'Modal'


        },
        methods:{

            /* 親コンポーネント関数の実行 */
            funcBtnClick: function(){
                this.$emit( 'func_btn_click' );
                // console.log('func-btn-click');
            },
        }
    }
</script>
<style scoped>
/* ここにscssを書く */
/* "lang=scss"を取り除くとcssで書ける */
</style>
