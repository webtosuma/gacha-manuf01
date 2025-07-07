<template>
    <div class="position-relative">

        <ratio-image-component
        :style_class="'ratio rounded-0 w-100 border-0 bg-body text-right '+ration"
        :url="is_prize==1 ? '' : image_path" />


        <!--ガチャ用商品画像-->
        <div v-if="is_prize==1"
        class="w-50 position-absolute top-50 start-50 translate-middle">

            <ratio-image-component
            :url="image_path"
            style_class="ratio ratio-3x4 "
            ></ratio-image-component>

        </div>


        <!--new label-->
        <div v-if="new_label_path"
        class="position-absolute top-0 start-0 p-0 w-25">
            <img :src="new_label_path" alt="new" class="w-100">
        </div>

        <!--売り切れ-->
        <div v-if="is_sold_out!=''"
        class="position-absolute top-0 start-0 w-100 h-100 text-center"
        style="z-index:3; background: rgba(0, 0, 0, .7);"
        ><div class="d-flex align-items-center justify-content-center h-100 fs-3 text-white"
        >SOLD OUT</div></div>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';
import { isInteger } from 'lodash';


    const props = defineProps({
        ration:        { type: String, default: '' },
        image_path:    { type: String, default: '' },
        new_label_path:{ type: String, default: '' },
        is_sold_out:   { type: [String,Number,Boolean], default: '' },
        is_prize:      { type: [String,Number,Boolean], default: 0 },
    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */

    const listData = ref(''); //


    /* 監視 */
    // watch(data, () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        // getData();
    });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {
        const inputs = {
            _token: props.token,

        };

        try {

            const response = await axios.post(route, inputs);
            const paginate = response.data['listData'];

            listData.value =
            route === props.r_api_list ? paginate.data : [...listData.value, ...paginate.data];

            loading.value = false;

            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;


        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };





</script>
