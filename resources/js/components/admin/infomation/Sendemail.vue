<template>
    <div class="">
        <button class="btn btn-success text-white w-100 shadow"
        @click="postEmail()" type="button"
        >メールを送信する</button>

        <div v-show="sending"
        class="modal-backdrop faid show" style="opacity: .9;">
            <div class="d-flex align-items-center justify-content-center h-100 text-white">


                <div class="text-center">
                    <h5 class="fs-1">お知らせメールを一括送信しています。</h5>

                    <h5 class="fs-1">ページを閉じないでください</h5>

                    <div class="my-3">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    </div>
                    <div class="progress rounded-pill" style="height: 1.6rem;">
                        <div class="progress-bar bg-warning" role="progressbar"
                        :style="'width:'+progress+'%;'" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100"
                        >{{ progress+'%' }}</div>
                    </div>
                </div>



            </div>
        </div>

    </div>
</template>
<script>
    import axios from 'axios';
    export default{
        props: {
            token:           { type: String,  default: '', },
            r_api_email_post:{ type: String, default: '',},//
            r_redirect:      { type: String, default: '',},//
        },
        data(){ return {

            postUrl: "",/* メール送信URL(書き換えあり) */

            sending: false,/* 送信中 */
            progress: 0,


        } },
        mounted() {

            this.postUrl = this.r_api_email_post;//メール送信URLのセット

        },
        methods: {


            /* お知らせメールの送信 */
            postEmail() {

                // 送信中カバーの表示
                this.sending = true;

                const route = this.postUrl;
                axios.post( route , {_token: this.token} )
                .then(json => {
                    console.log(json.data);

                    this.postUrl = json.data.next_page_url;     //URLの更新
                    const current_page = json.data.current_page;//表示中ページ
                    const last_page    = json.data.last_page;   //最終ページ
                    this.progress      = Math.ceil(current_page/last_page*100);


                    if( current_page != last_page ){
                        this.postEmail();
                    }
                    else{
                        // 完了後、一覧ページへリダイレクト
                        setTimeout(() => {
                            window.location.href = this.r_redirect;
                        }, 2*1000);
                        return;
                    }


                })
                .catch(error => {

                    if (error.response && error.response.status === 450) {
                        alert(`メール送信の上限に達しました。。\n時間を置いてから再開してください。`)
                        console.log( error.response.data );
                    } else {
                        alert(`メール送信の上限に達しました。。\n時間を置いてから再開してください。`)
                        console.log( error.response.data );
                    }
                    this.sending = false;

                });
            },


        }
    }
</script>
