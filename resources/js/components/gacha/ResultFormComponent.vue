<template>
    <div class="">
        vue component
    </div>
</template>
<script>
    import axios from 'axios'

    export default {
        props: {
            token:        { type: String,  default: '', },
        },
        data() { return {

            loading: false,/* 通信中 */

        } },
        mounted() {

        },
        methods:{

            /* データ送信 */
            submit :function(){

                this.loading = true;// 通信中

                axios.post( this.submit_route, this.inputs )
                .then(json => {
                    // console.log(json.data);

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
                    console.log( error.response.data );

                });

            },
        },

    };
</script>
