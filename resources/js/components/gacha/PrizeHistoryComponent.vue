<template>
    <div>

        <loading-cover-component :loading="loading" />



        <ul class="row g-3 rounded-3 p-0 m-0" style="list-style:none;">

            <li v-for="(userPrize, key) in user_prizes" :key="key"
            class="col-6 col-md-4 col-lg-3">
                <!-- <label class="d-block " style="cursor:pointer;"> -->
                    <div class="row" v-if="userPrize.prize">
                        <label class="col-4 px-0 pe-3 position-relative"  style="cursor: pointer;">


                            <ratio-image-component
                            style_class="ratio ratio-3x4 rounded-3"
                            :url=" userPrize.prize.image_path " />


                        </label>
                        <div class="col-8 p-0">
                            <div class="form-text">{{ formatDate(userPrize.created_at) }}</div>
                            <h6 classs="fw-bold">{{ userPrize.prize.name }}</h6>

                            <!--ユーザー情報-->
                            <!-- <div class="d-flex align-items-center">
                                <div style="width: 18px">
                                    <ratio-image-component
                                    style_class="ratio ratio-1x1 rounded-pill border"
                                    :url=" userPrize.user.image_path "
                                    ></ratio-image-component>
                                </div>

                                <span class="mt-1">{{userPrize.user.name}}</span>
                            </div> -->

                        </div>
                    </div>
                    <div v-else class="py-5">
                        <!--商品情報が削除されたとき-->
                        *商品情報が削除されました
                    </div>


                <!-- no_exchange_point -->
            </li>


        </ul>



    </div>
</template>

<script setup>
    import { ref, watch, onMounted, onUnmounted, } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:       { type: String, default: '' },
        r_api_list:  { type: String, default: '' },
    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */

    const user_prizes = ref(''); //ガチャ商品履歴

    const inputs = ref({
        _token: props.token,

    });

    /* 監視 */
    // watch(data, () => getData());

    /* 初回データ取得 */
    onMounted(() => { getData(); });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {

        try {

            const response = await axios.post(route, inputs.value);
            const paginate = response.data['user_prizes'];

            user_prizes.value =
            route === props.r_api_list ? paginate.data : [...user_prizes.value, ...paginate.data];


            const { current_page, last_page, next_page_url } = paginate;
            // nextPageUrl.value = current_page !== last_page ? next_page_url : null; targetHight

            // 次のデータの読み込み
            if (current_page !== last_page) {
                getData(next_page_url);
            }
            else{
                loading.value = false;
            }


        } catch (error) {

            console.error(error.response.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };


    /** 日付データをテクスト変換  */
    const formatDate = (inputString) => {
        const date = new Date(inputString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1し、2桁にパディング
        const day = String(date.getDate()).padStart(2, '0'); // 日も2桁にパディング

        return `${year}/${month}/${day}`;
    }



</script>
