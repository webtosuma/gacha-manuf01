<template>
    <div class="card-body py-0" :class="bg_color">


        <div class="row align-items-center justify-content-between">

            <div class="col text-start">
                <!--左-->
                <div v-if="new_label_path"
                class="d-inline-block"  style="height:1.6rem;">
                    <img :src=" new_label_path " class="h-100" alt="NEW">
                </div>
            </div>

            <div class="col-auto">
                <div class="d-flex align-items-center justify-content-center gap-2 fs-6">
                    <div class="d-flex align-items-center justify-content-center"  style="height:1.6rem;">
                        <img :src=" img_path_point " class="h-100">
                    </div>

                    <div class="">
                        <!-- 1回× -->
                        <span :class="point_fs">
                            <number-comma-component :number="gacha_play_point"></number-comma-component>
                        </span>pt
                    </div>
                </div>
            </div>

            <div class="col text-end">
                <!--右-->
                <div  v-if="sponsor_ad">
                    <span class="px-1 border form-text fw-bold d-inline-block"
                    style="background: rgb(255 255 255 / 70%);">広告</span>
                </div>
            </div>

        </div>


        <!-- 新規会委員限定 -->
        <div v-if="gacha_type=='only_new_user'"
        class="text-center text-success" style="line-height:2rem">
            ＊一週間限定・一回限定で利用できます
        </div>

        <!-- 通常メーター -->
        <div v-else-if="is_meter">
            <div class="progress" :style="merter_height">
                <div :class=" progress_style_class " role="progressbar"
                :style="'width:'+remaining_ratio+'%'" :aria-valuenow="remaining_ratio" aria-valuemin="0" :aria-valuemax="remaining_ratio"
                ></div>
            </div>


            <p class="text-center m-0" style="font-size:.8rem;">
                残り
                <number-comma-component :number=" remaining_count "></number-comma-component>
                /
                <number-comma-component :number=" max_count "></number-comma-component>
            </p>
        </div>


        <!-- メーター表示なし -->
        <div v-else style="height:2rem"><!--  --></div>



    </div>
</template>

<script>
    export default {
        props: {
            sm_card:         { type: [String,Number,Boolean],  default: 0, },//カードの表示サイズ
            new_label_path:  { type: String,  default: '', },
            img_path_point:  { type: String,  default: '', },
            bg_color:        { type: String,  default: 'bg-white', },
            gacha_type:      { type: String,  default: '', },
            sponsor_ad:      { type: [String, Number, Boolean],  default: 0, },
            gacha_play_point:{ type: [String, Number],  default: 0, },
            is_meter:        { type: [String, Number],  default: 0, },
            remaining_ratio: { type: [String, Number],  default: 0, },//残割合
            remaining_count: { type: [String, Number],  default: 0, },//残数
            max_count:       { type: [String, Number],  default: 0, },//総口数
        },
        data() { return {

            progress_style_class: '',//メーターの表示クラス


            /*メーターの高さ*/
            merter_height:   '',
            merter_sm_height:'height:.5rem;',

            /*ポイント表示の文字サイズ*/
            point_fs:    'fs-3',
            point_sm_fs: 'fs-6',



        } },
        mounted() {

            /* カードの表示サイズ対応 */
            this.merter_height = this.sm_card!=0 ? this.merter_sm_height : this.merter_height;
            this.point_fs      = this.sm_card!=0 ? this.point_sm_fs      : this.point_fs;

            //メーターの表示クラスを指定
            this.setprogressStyleClass();


        },
        methods:{


            /* メーターの表示クラスを指定 */
            setprogressStyleClass :function(){
                const bg_color = this.remaining_ratio>70 ? 'bg-success' : ( this.remaining_ratio>40 ? 'bg-warning' : 'bg-danger' );
                this.progress_style_class = 'progress-bar progress-bar-striped '+bg_color;
            },



        }

    }
</script>
