<template>
    <div>
        <button class="btn btn-light border rounded-pill w-100"
        @click="post()" type="button"
        >実行</button>


        <div v-show="loading"
        class="modal-backdrop faid show" style="opacity: .9;">
            <div class="d-flex align-items-center justify-content-center h-100 text-white">


                <div class="text-center">
                    <div class="mb-5">
                        <h5 class="fs-1">ページを閉じないでください</h5>
                        <div class="my-3">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="fs-3">{{prize_label}}</h5>

                        <div class="progress rounded-pill" style="height: 1.6rem;">
                            <div class="progress-bar bg-primary" role="progressbar"
                            :style="'width:'+prize_progress+'%;'" :aria-valuenow="prize_progress" aria-valuemin="0" aria-valuemax="100"
                            >{{ prize_progress+'%' }}</div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <h5 class="fs-3">{{point_label}}</h5>

                        <div class="progress rounded-pill" style="height: 1.6rem;">
                            <div class="progress-bar bg-success" role="progressbar"
                            :style="'width:'+point_progress+'%;'" :aria-valuenow="point_progress" aria-valuemin="0" aria-valuemax="100"
                            >{{ point_progress+'%' }}</div>
                        </div>
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
            r_redirect: { type: String, default: '',},//
            r_api_deadline_prize: { type: String, default: '',},//
            r_api_deadline_point: { type: String, default: '',},//
        },
        data(){ return {

            loading: false,/* 送信中 */

            prize_label: '期限切れユーザー商品のポイント交換',
            prize_progress: 0,

            point_label: '期限切れユーザーポイントのリセット',
            point_progress: 0,


        } },
        mounted() {


        },
        methods: {

            /* 商品のポイント交換 */
            post( route = this.r_api_deadline_prize ) {

                // 送信中カバーの表示
                this.loading = true;

                axios.post( route , {_token: this.token} )
                .then(json => {
                    // console.log(json.data);

                    const r_next        = json.data.next_page_url;     //URLの更新
                    const current_page  = json.data.current_page;//表示中ページ
                    const last_page     = json.data.last_page;   //最終ページ
                    this.prize_progress = Math.ceil(current_page/last_page*100);


                    if( current_page != last_page ){
                        this.post( r_next );
                    }
                    else{
                        this.prize_progress = 100;

                        /* ポイントのリセット */
                        this.point_reset()
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


            /* ポイントのリセット */
            point_reset( route = this.r_api_deadline_point ) {

                // 送信中カバーの表示
                this.loading = true;

                axios.post( route , {_token: this.token} )
                .then(json => {
                    // console.log(json.data);

                    const r_next        = json.data.next_page_url;     //URLの更新
                    const current_page  = json.data.current_page;//表示中ページ
                    const last_page     = json.data.last_page;   //最終ページ
                    this.point_progress = Math.ceil(current_page/last_page*100);


                    if( current_page != last_page ){
                        this.point_reset( r_next );
                    }
                    else{
                        this.point_progress = 100;
                        // 完了後、一覧ページへリダイレクト
                        this.redirect();
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


            /* リダイレクト */
            redirect() {

                setTimeout(() => {
                    window.location.href = this.r_redirect;
                }, 2*1000);
                return;
            },
        }
    }
</script>
