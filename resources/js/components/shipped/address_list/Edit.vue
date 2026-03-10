<template>
    <div>

        <loading-cover-component :loading="loading" />



        <div v-if="!loading" class="mx-auto col-lg-6" >

            <u-edit-user-address-form
            :token="token"
            :use_size="use_size"
            :edit_address="address"
            :r_api_update="r_api_update"
            :default_email="default_email"
            />

        </div>



    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({

        token:        { type: String, default: '' },
        r_api_show:   { type: String, default: '' },
        r_api_update: { type: String, default: '' },
        use_size:     { type: [String,Number], default: 0 },
        default_email:{ type: String, default: '' },


    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */

    const address = ref({}); //


    /* 監視 */
    // watch(data, () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        getData();
    });


    /* データ取得 */
    const getData = async (route = props.r_api_show) => {
        const inputs = {
            _token: props.token,

        };

        try {

            const response = await axios.post(route, inputs);
            address.value = response.data;

            loading.value = false;


        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };





</script>
