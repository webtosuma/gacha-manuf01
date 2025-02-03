<template>
    <div>
        <button class="btn btn-light border rounded-pill w-100"
        @click="post()" type="button"
        >実行</button>



        <div v-show="loading"
        class="modal-backdrop faid show" style="opacity: .9;">
            <div class="d-flex align-items-center justify-content-center h-100 text-white">


                <div class="text-center">
                    <h5 class="fs-1">{{label}}を実行しています。</h5>

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
    export default {
        props: {
            token:      { type: String, default: '', },
            r_api_post:     { type: String, default: '',},//
            r_redirect: { type: String, default: '',},//
        },
        data(){ return {

            label: '期限切れユーザーポイントのリセット',

            postUrl: "",/* メール送信URL(書き換えあり) */

            loading: false,/* 送信中 */
            progress: 0,


        } },
        mounted() {

            this.postUrl = this.r_api_post;//メール送信URLのセット

        },
        methods: {


        /* お知らせメールの送信 */
        post() {

            // 送信中カバーの表示
            this.loading = true;

            const route = this.postUrl;
            axios.post( route , {_token: this.token} )
            .then(json => {
                // console.log(json.data);

                this.postUrl = json.data.next_page_url;     //URLの更新
                const current_page = json.data.current_page;//表示中ページ
                const last_page    = json.data.last_page;   //最終ページ
                this.progress      = Math.ceil(current_page/last_page*100);


                if( current_page != last_page ){
                    this.post();
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
                    alert(`通信エラーが発生しました`)
                    console.log( error.response.data );
                } else {
                    alert(`通信エラーが発生しました`)
                    console.log( error.response.data );
                }
                this.loading = false;

            });
        },


    }
}
</script>
