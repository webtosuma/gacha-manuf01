<template>
    <div class="text-dark">

        <!-- button key -->
        <!-- <div>{{ '#'+'gachaCustomModal'+gacha_id }}</div> -->

        <!-- button -->
        <!-- <div class="d-flex gap-3 mb-3">
            <button
            data-bs-toggle="modal"
            :data-bs-target="'#'+'gachaCustomModal'+gacha_id"
            >カスタム</button>
        </div> -->


        <!--modal-->
        <div
        class="modal fade"
        :id="'gachaCustomModal'+ gacha_id"
        :aria-labelledby="'gachaCustomModal'+gacha_id+'Label'"
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
                            <!--数量変更-->
                            <div class="p-3">
                                    <div class="my-3 fs-4">
                                        <div>{{ count.toLocaleString() + '回ガチャる' }}</div>
                                        <div>{{ ( one_play_point*count ).toLocaleString() + 'pt' }}</div>
                                    </div>

                                <div class="input-group rounded-pill mb-3">
                                    <!-- +1 -->
                                    <button type="button" class="form-control btn btn-dark"
                                    @click="countUpdate( 1 )"
                                    >+ 1回</button>

                                    <!-- +3 -->
                                    <button type="button" class="form-control btn btn-dark"
                                    @click="countUpdate( 3 )"
                                    >+ 3回</button>

                                    <!-- +10 -->
                                    <button type="button" class="form-control btn btn-dark"
                                    @click="countUpdate( 10 )"
                                    >+ 10回</button>

                                </div>
                                <div class="input-group rounded-pill mb-3">
                                    <!-- +100 -->
                                    <button type="button" class="form-control btn btn-dark"
                                    @click="countUpdate( 100 )"
                                    >+ 100回</button>

                                    <!-- +3 -->
                                    <button type="button" class="form-control btn btn-dark"
                                    @click="countUpdate( max_count )"
                                    >MAX</button>

                                    <!-- リセット -->
                                    <button type="button" class="form-control btn btn-dark"
                                    @click="countReset()"
                                    ><i class="bi bi-x"></i></button>

                                </div>

                            </div>

                            <div v-if="!disabled"
                            class="my-3">
                                ポイントを消費してガチャを開始します。<br>
                                よろしいですか？
                            </div>
                            <div v-else
                            class="d-flex align-items-center gap-3 justify-content-center h-100">


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
                        </div>
                    </div>

                    <!-- modal-footer -->
                    <div class="modal-footer">
                        <form :action="r_action" method="post" class="col-12">
                            <input type="hidden" name="_token" :value="token">
                            <input type="hidden" name="play_count" v-model="count">

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
                                    :disabled="disabled_0_count"
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
        one_play_point:{ type: [String, Number], default: '', },
        btn_class:     { type: String, default: 'btn-primary', },
        max_count:      { type: [String, Number], default: 10, },
        gacha_id:      { type: [String, Number], default: '1', },
    });


    /* データの状態 */
    const count    = ref(0); //

    const disabled = ref(false);
    const disabled_0_count = ref(true);//count 0のとき、disabled

    /* 監視 */
    watch(() => count.value,  () => {
        disabled_0_count.value = count.value==0 ? true : false ;
    });


    /* 初回データ取得 */
    onMounted(() => {});


    /* Disabledの変更 */
    const changeDisabled = ()=>{ disabled.value = true; }



    /**
     * 数量変更
     *
     * @param Integer add //加算・減算数
     * @return Void
     */
    const countUpdate = ( add ) => {
        count.value += add;//加算・減算

        count.value = count.value > props.max_count ? props.max_count : count.value;
    }



    /**
     * 数量リセット
     *
     * @return Void
     */
    const countReset = (  ) => {
        count.value = 0;
    }



</script>
