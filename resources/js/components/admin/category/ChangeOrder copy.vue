<template>
    <div class="">
        <div v-for="( category ,key) in categories" :key="key">
            {{ category.name }}
        </div>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:       { type: String, default: '' },
        r_api_list:  { type: String, default: '' },

    });


    /* データの状態 */
    const categories  = ref([]); //


    /* 監視 */
    // watch(data, () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        getData();
    });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {
        const inputs = { _token: props.token, };

        try {

            const response   = await axios.post(route, inputs);
            categories.value = response.data;


        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };





</script>
