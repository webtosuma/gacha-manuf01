<template>
    <div class="">
        <!--サービス内容の選択-->
        <div class="row px-4">
            <div class="col-6">
                <label class="card px-3 py-4 mb-3">
                    <div class="form-check">
                        <input v-model="inputs.service"
                        name="service" value="point"
                        type="radio" class="form-check-input">
                        <h6 class="mb-0 mt-1">ポイント</h6>
                    </div>
                </label>
            </div>
            <div class="col-6">
                <label class="card px-3 py-4 mb-3">
                    <div class="form-check">
                        <input v-model="inputs.service"
                        name="service" value="prize"
                        type="radio" class="form-check-input">
                        <h6 class="mb-0 mt-1">商品</h6>
                    </div>
                </label>
            </div>
        </div>

        <!--付与ポイント(point)-->
        <label v-if="inputs.service=='point'"
        class="d-block mb-4 px-4">
            <div class="form-label">ポイント数</div>

            <div class="row align-items-center">
                <div class="col-md-6">
                    <input v-model="inputs.point"
                    name="point"
                    type="number"
                    min="0"
                    class="form-control text-end">
                </div>
                <div class="col-auto">pt</div>
            </div>

        </label>
        <!--商品(prize_code)-->
        <label v-if="inputs.service=='prize'"
        class="d-block mb-4 px-4">
            <div class="form-label">商品コード</div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <input v-model="inputs.prize_code"
                    name="prize_code"
                    type="text"
                    placeholder="商品コードを入力"
                    class="form-control">
                </div>
            </div>

        </label>

    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        service:     { type: [String], default: 'point' },
        point:       { type: [String,Number], default: 0 },
        prize_code:  { type: [String], default: '' },
    });


    /* データの状態 */
    const inputs = ref({
        service    : 'point',
        point      : 0,
        prize_code : '',
    }); //


    /* 監視 */
    // watch(data, () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        inputs.value.service    = props.service;
        inputs.value.point      = props.point;
        inputs.value.prize_code = props.prize_code;
    });

</script>
