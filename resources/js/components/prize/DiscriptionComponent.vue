<template>
    <div>

        <!-- Button trigger modal -->
        <img v-if="no_btn!=1"
        :src="src_icon"
        alt="商品説明ボタン"
        class="btn btn-dark p-0 rounded-circle shadow"
        :style="'width:'+size+';'+'height:'+size+';'"
        data-bs-toggle="modal"
        :data-bs-target="'#PrizeDiscriptionModal'+id"
        >


        <!-- Modal -->
        <div :id="'PrizeDiscriptionModal'+id"
        class="modal fade text-dark" tabindex="-1"
        :aria-labelledby="'PrizeDiscriptionModalLabel'+id"
        aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header"
                    :class="{'bg-dark text-white':bg_dark}" >
                        <h5 class="modal-title fs-5" :id="'PrizeDiscriptionModalLabel'+id">{{ name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"
                    :class="{'bg-dark text-white':bg_dark}" >


                        <!--カード画像-->
                        <div class="col-8  p-3 mx-auto mb-3">
                            <ratio-image-component
                            style_class="ratio ratio-3x4 rounded-3"
                            :url="image_path"
                            ></ratio-image-component>
                        </div>



                        <replace-text-component :text="discription" />


                    </div>
                    <div :class="{'bg-dark text-white':bg_dark}"
                    class="modal-footer p-0">
                        <button type="button"
                        :class="!bg_dark ? 'btn btn-light' : 'btn btn-dark'"
                        class="w-100" data-bs-dismiss="modal"
                        ><i class="bi bi-x me-3"></i>閉じる</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        id:          { type: [String,Number], default: '' },
        name:        { type: String, default: '' },
        image_path:  { type: String, default: '' },
        discription: { type: String, default: '' },
        size:        { type: String, default: '3rem' },
        src_icon:    { type: String, default: '' },
        no_btn:      { type: [String,Number], default: 0 },
        bg_dark:     { type: [String,Number], default: 0 },
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
