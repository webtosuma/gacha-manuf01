<template>
    <div class="position-relative card-body py-0 pb- px-2 px-md-3 py-" :class="bg_color">

        <!--new-->
        <!-- <img v-if="new_label_path"
        class="position-absolute top-0 start-0 translate-middle-y"
        :src=" new_label_path " :style="icon_height" alt="NEW"> -->

        <div class="row align-items-center justify-content-between gx-1">

            <div :class=" sm_card ? 'col-auto order-1 ' : 'col-auto py-2 ' " >
                <!--new-->
                <img v-if="new_label_path"
                :src=" new_label_path " :style="icon_height" alt="NEW">
            </div>


            <!--メーター-->
            <div :class=" sm_card ? 'col order-2 ' : 'col ' ">


                <!-- 通常メーター -->
                <div v-if="gacha_type!='only_new_user' && is_meter!=0"
                class="position-relative text-center">

                <div class="row gx-1 align-items-center ">
                    <!-- 残数 -->
                    <div :class="  sm_card ? 'col-12 order-' : 'col-12' ">
                        <div class="text-start"
                        style="font-size:11px;">
                            <span v-if="sm_card==0">残り</span>

                            <number-comma-component :number=" remaining_count "></number-comma-component>
                            /
                            <number-comma-component :number=" max_count "></number-comma-component>
                        </div>
                    </div>

                    <!-- メーター -->
                    <div class="col order-">
                        <div class="progress rounded-pill bg-dark border border-2" :style="merter_height" >
                            <div :class=" progress_style_class" role="progressbar"
                            :style="'width:'+remaining_ratio+'%'"
                            :aria-valuenow="remaining_ratio" aria-valuemin="0"
                            :aria-valuemax="remaining_ratio"
                            ></div>
                        </div>
                    </div>
                </div>



                    <!-- 限定n回 残数 -->
                    <div v-if="sm_card==0 && type_n_remaining_count_label"
                    class="w-100 text-start "
                    style="font-size:11px;">
                        <div class="px-3 text-light bg-dark rounded d-inline-block border">{{ type_n_remaining_count_label }}</div>
                    </div>


                </div>


            </div>


            <div :class=" sm_card ? 'col-auto order-3 ' : 'col-auto py-2 ' ">
                <div class="d-flex align-items-center justify-content-center gap-1 fs-6"
                >
                    <div class="d-flex align-items-center justify-content-center"  style="height:1.2rem;">
                        <img :src=" img_path_point " class="h-100">
                    </div>

                    <div class="" style="line-height:18px;">
                        <span :class="point_fs">
                            <number-comma-component :number="gacha_play_point"></number-comma-component>
                        </span>
                        <span v-if="sm_card==0">pt</span>
                    </div>
                </div>
            </div>

            <!-- <div class="col" :class=" sm_card ? 'col order-3' : 'd-none' " ></div> -->

        </div>







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
            type_n_remaining_count_label:{ type: [String, Number],  default: 0, },//[n回限定・1日n回限定] 残り回数ラベル

        },
        data() { return {

            progress_style_class: '',//メーターの表示クラス


            /*メーターの高さ*/
            merter_height:   'height:.8rem;',
            merter_sm_height:'height:.8rem;',

            /*ポイント表示の文字サイズ*/
            point_fs:    'fs-3',
            point_sm_fs: 'fs-6',

            /*アイコンの高さ*/
            icon_height: 'height:.8rem;',

        } },
        mounted() {

            /* カードの表示サイズ対応 */
            this.merter_height = this.sm_card!=0 ? this.merter_sm_height : this.merter_height;
            this.point_fs      = this.sm_card!=0 ? this.point_sm_fs      : this.point_fs;

            /*アイコンの高さ*/
            this.icon_height = this.sm_card!=0 ?  'height:1.2rem;' : 'height: 1.2rem;'  ,


            //メーターの表示クラスを指定
            this.setprogressStyleClass();


        },
        methods:{


            /* メーターの表示クラスを指定 */
            setprogressStyleClass :function(){
                const bg_color = this.remaining_ratio>70 ? 'bg-success' : ( this.remaining_ratio>40 ? 'bg-warning' : 'bg-danger' );
                this.progress_style_class = 'progress-bar progress-bar-striped rounded-0 '+bg_color;
            },



        }

    }
</script>
