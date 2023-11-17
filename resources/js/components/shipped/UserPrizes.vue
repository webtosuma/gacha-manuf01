<template>
    <div>
        <h5>発送する商品</h5>

        <ul class="list-group bg-white">
            <li class="list-group-item">
                <div class="row">

                    <div v-for="(userPrize, key) in userPrizes" :key="key"
                    class="col-3 col-md-2 p-0 pe-2">
                        <input type="hidden" name="user_prize_ids[]" :value="userPrize.id">
                        <div class="">
                            <ratio-image-component
                            style_class="ratio ratio-3x4 rounded-3"
                            :url="userPrize.prize.image_path" />
                        </div>
                        <h6 classs="form-text">{{ userPrize.prize.name }}</h6>
                    </div>

                </div>
            </li>
            <li class="list-group-item text-end">
                <span class="me-3">合計</span>
                <span class="fs-3">{{ userPrizes.length }}</span>点
            </li>
        </ul>
    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        props: {
            token:{ type: String,  default: '', },
            u_prize_ids:  { type: [String], default: '' },
            r_find:  { type: [String,Number], default: null },
        },
        data() { return {

            userPrizes: [],

        } },
        mounted() {

            /* 一覧取得 */
            this.getList();

        },
        methods: {


            /* 一覧取得 */
            getList() {

                const route = this.r_find; //一覧取得
                const params = {
                    _token: this.token,
                    user_prize_ids: this.u_prize_ids.split(','),//ID文字列->配列
                }
                axios.post( route, params )
                .then(json => {
                    // console.log( json.data );
                    this.userPrizes = json.data;
                })
                .catch(error => {
                    // alert('データ送信エラーが発生しました。');
                    console.log( 'err:getUserPrizeList' );
                    // console.log( error.response.data );
                });
            },

        },
    }
</script>
