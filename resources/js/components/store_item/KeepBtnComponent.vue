<template>
    <div>
        <loading-cover-component :loading="loading" />

        <!-- トーストポップアップ -->
        <div id="toast_container" class="position-fixed bottom-0 end-0 p-2" style="z-index:10;">
            <div v-for="(message, key) in messages" :key="key"
            class="toast fade show mb-1 fade-in-message" role="alert" aria-live="assertive" aria-atomic="true" >
                <div class="toast-header bg-dark text-white">
                    <strong class="me-auto">{{ message }}</strong>
                    <button type="button" class="btn px-1 py-0 text-white fs-5" data-bs-dismiss="toast"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
        </div>


        <div class="my-3 col-8 mx-auto" >
            <div class="row align-items-center w-100 ms-2">
                <div class="col-12 col-md-aut0">数量：</div>

                <!--減算ボタン-->
                <div class="col-auto">
                    <button @click="subCount"
                    type="button"
                    class="btn btn-outline-secondary btn-sm rounded-circle"
                    :disabled=" inputs.count<=1 "
                    style="width:3rem; height:3rem;">
                        <div class="d-flex align-item-center justify-content-center h-100">
                            <i class="bi bi-dash fs-3"></i>
                        </div>
                    </button>
                </div>


                <div class="col">
                    <input
                    :value="inputs.count"
                    name="count"
                    type="number"
                    class="form-control form-control-lg text-end bg-white fs-4"
                    min="1" :max="max_count"
                    >
                </div>


                <!--加算ボタン-->
                <div class="col-auto">
                    <button @click="addCount"
                    type="button"
                    class="btn btn-outline-secondary btn-sm rounded-circle"
                    :disabled=" inputs.count>=max_count "
                    style="width:3rem; height:3rem;">
                        <div class="d-flex align-item-center justify-content-center h-100">
                            <i class="bi bi-plus fs-3"></i>
                        </div>
                    </button>
                </div>

            </div>
        </div>


        <div class="my-5">

            <div v-if="disabled" class="text-danger text-center"
            >＊この商品は買い物カートに入っています。</div>

            <div class="row gy-3">
                <!--カートに入れる-->
                <div class="col-12 col-lg">
                    <button
                    @click="keep"
                    type="button"
                    class="btn btn-lg btn-primary rounded-pill w-100"
                    :disabled="disabled"
                    ><i class="bi bi-cart4"></i>カートに入れる</button>
                </div>

                <!--今すぐ購入する-->
                <div class="col-12 col-lg">
                    <button
                    type="submit"
                    class="btn btn-lg btn-outline-info text-dark rounded-pill w-100"
                    >今すぐ購入する</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:           { type: String, default: '' },
        r_api_keep:      { type: String, default: '' },
        default_count:   { type: [String,Number], default: 1 },//初期数量
        default_disabled:{ type: [String,Number], default: 0 },//初期数量
        max_count:       { type: [String,Number], default: 10 },//注文上限(在庫数)
    });


    /* 読み込み中 */
    const loading  = ref( false );

    /* カートに入れるボタンのdisable */
    const disabled = ref( props.default_disabled==0 ? false : true );

    /* ポップアップメッセージ */
    const messages = ref([]);

    /* 入力値 */
    const inputs = ref({
        _token : props.token,
        count  : props.default_count,//数量
    });

    /* 監視 */
    watch( () => inputs.value.count,
        (newVal) => {
            if (newVal > props.max_count) {
                inputs.value.count = props.max_count
            }
            else if (newVal < 1) {
                inputs.value.count = 1
            }
        }
    );

    onMounted(() => {/* ~ */});


    /* データ取得 */
    const keep = async () => {

        loading.value  = true;
        disabled.value = true;

        try {

            const response = await axios.post( props.r_api_keep, inputs.value);

            /*エラーメッセージ*/
            if(response.data.message){
                messages.value.push(response.data.message);//エラーメッセージの表示
                loading.value  = false;
                disabled.value = false;
                return;
            }

            messages.value.push('商品が買い物カートに入りました。');
            loading.value  = false;



        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };





    /* 数量の減算数 */
    const subCount = () =>    {

        if( inputs.value.count>1){ inputs.value.count--; }

    }

    /* 数量の加算 */
    const addCount = () => {

        if( props.max_count>inputs.value.count){ inputs.value.count++; }

    }

</script>
<style scoped>
    @keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px); /* 50px下から */
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
    }

    .fade-in-message {
        opacity: 0; /* 初期状態を透明に */
        animation: fadeInUp .8s ease-out forwards;
    }
</style>

