<template>
    <div>

        <loading-cover-component :loading="loading" />



        <ul class="row g-3 rounded-3 p-0 m-0" style="list-style:none;">

            <li v-for="(userPrize, key) in user_prizes" :key="key"
            class="col-6 col-md-2">

                <div class="row g-0" v-if="userPrize.prize">
                    <div class="col-4 px-0 position-relative pe-2"  style="cursor: pointer;">
                        <div class="position-relativeee ">

                            <div class="position-absoluteee top-0 start-0 w-100" style="font-size:11px; z-index:3; opacity:1;">
                                <div class="bg-dark text-white text-center px-1">No.{{ Number(key+1) }}</div>
                            </div>

                            <ratio-image-component
                            style_class="ratio ratio-3x4 rounded-3 mt-1"
                            :url=" userPrize.image_path " />


                        </div>
                    </div>
                    <div class="col-8 p-0">


                        <div style="font-size:11px;" >{{ formatDate(userPrize.created_at) }}</div>
                        <h6 classs="fw-bold">{{ userPrize.prize.name }}</h6>

                        <h6 classs="mt-2" style="font-size:12px;">{{ userPrize.user.name }}</h6>

                    </div>
                </div>
                <div v-else class="py-5">
                    <!--商品情報が削除されたとき-->
                    *商品情報が削除されました
                </div>
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


    /** 日付データをテキスト変換（時:分まで表示） */
    const formatDate = (inputString) => {
        const date = new Date(inputString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0'); // 時を2桁にパディング
        const minutes = String(date.getMinutes()).padStart(2, '0'); // 分を2桁にパディング

        return `${year}/${month}/${day} ${hours}:${minutes}`;
    };


</script>
