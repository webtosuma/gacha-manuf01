<template>
    <div v-if="is_popup_btn"
    class="text-dark">

        <!-- button key -->
        <!-- <div v-for="( count, b_key ) in counts" :key="b_key+'key'">{{ '#'+'gachaPlayModal'+gacha_id+'-'+count }}</div> -->

        <!-- button -->
        <!-- <div class="d-flex gap-3 mb-3">
            <button v-for="( count, key ) in counts" :key="key+'pbk'"
            data-bs-toggle="modal"
            :data-bs-target="'#'+'gachaPlayModal'+gacha_id+'-'+count"
            >{{ count+'回ガチャ' }}</button>
        </div> -->


        <!--modal-->
        <div v-for="( count, key ) in counts" :key="key"
        class="modal fade"
        :id="'gachaPlayModal'+gacha_id+'-'+count"
        :aria-labelledby="'gachaPlayModal'+gacha_id+'-'+count+'Label'"
        data-bs-backdrop="static" tabindex="-1"
        data-bs-keyboard="false"
        aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4">

                    <!-- modal-body -->
                    <div class="modal-body">
                        <div class="w-50 mx-auto rounded-4 overflow-hidden">
                            <slot><!--ガチャ画像--></slot>
                        </div>
                        <div class="text-center">
                            <div class="my-3 fs-5">
                                <div>{{ count + '回ガチャる' }}</div>
                                <div>{{ ( one_play_point*count ).toLocaleString() + 'pt' }}</div>
                            </div>
                            <div v-if="!disabled"
                            class="my-3">
                                ポイントを消費してガチャを開始します。<br>
                                よろしいですか？
                            </div>
                            <div v-else class="mt-4">


                                <div class="d-flex align-items-center gap-3 justify-content-center">
                                    <div class="spinner-grow text-dark" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-dark" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-dark" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>

                                <div class="fw-bold text-danger py-4">
                                    ガチャを開始します。そのままお待ちください。
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- modal-footer -->
                    <div class="modal-footer">
                        <form :action="r_action" method="post" class="col-12">
                            <input type="hidden" name="_token" :value="token">

                            <div class="row">

                                <div class="col">
                                    <button
                                    type="button"
                                    class="btn border rounded-pill w-100"
                                    :disabled="disabled"
                                    data-bs-dismiss="modal"
                                    >キャンセル</button>
                                </div>

                                <div class="col">
                                    <!--開始前-->
                                    <button
                                    v-show="!disabled"
                                    type="submit"
                                    name="play_count"
                                    :value="count"
                                    class="btn text-white rounded-pill w-100"
                                    :class="btn_class"
                                    @click="changeDisabled()"
                                    >ガチャを開始する</button>

                                    <!--開始中-->
                                    <button
                                    v-show="disabled"
                                    type="button"
                                    class="btn text-white rounded-pill w-100"
                                    :class="btn_class"
                                    disabled
                                    >通信中・・・</button>
                                </div>

                            </div>
                        </form>
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
        token:         { type: String, default: '' },
        r_action:      { type: String, default: '', },//ルート:ガチャる
        name:          { type: String, default: '', },
        one_play_point:{ type: [String, Number], default: '', },
        btn_class:     { type: String, default: 'btn-primary', },
        is_popup_btn:  { type: [String,Number,Boolean],  default: false,},//ポップアップの表示
        gacha_id:      { type: [String, Number], default: '1', },
    });


    /* データの状態 */
    const counts = ref(['1','10','100']); //

    const disabled = ref(false);

    /* 監視 */
    // watch(data, () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        // getData();
    });


    /* Disabledの変更 */
    const changeDisabled = ()=>{ disabled.value = true; }





</script>
