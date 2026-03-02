<template>
    <div class="position-relative card-body py-0 pb- px-2 px-md-3" :class="bg_color">


        <!--在庫・価格-->
        <div id="discription-price"
        class="row justify-content-between">



            <div class="col-auto text-start fw-bold mb-1" style="font-size:11px; line-height:14px;">
                <div class="
                px-3 py-1 text-success bg-success-subtle
                border border-success border-1 rounded-pill
                ">
                    <span class="fs-">X月</span>頃 発送
                </div>
            </div>



            <div class="col-auto">

                <!-- <div class="d-flex gap-3 justify-content-center" style="font-size:11px;">
                    <div class=" bg-light border text-dark px-2 rounded-pill">
                        <span class="">残り</span>
                        {{remaining_count.toLocaleString()}}
                    </div>

                    <div class=" bg-warning px-2 rounded-pill">
                        <span class="">待機中</span>
                        {{(waiting_count??0).toLocaleString()}}
                    </div>
                </div> -->


                <!--価格-->
                <div class="text-center fw-bold"  style="line-height:18px;">
                    <span style="font-size:11px;">１回</span>
                    <span style="font-size:11px; line-height:11px;">(税込)</span>
                        <br>
                    <span class="fs-4 text-">¥</span>
                    <span class="fs-3 text-"> {{(100).toLocaleString()}}</span>
                </div>

            </div>



        </div>


        <!-- 通常メーター -->
        <div v-if="gacha_type!='only_new_user' && is_meter!=0"
        class="position-relative text-center">



            <!-- メーター -->
            <div class="progress rounded-pill bg- " :style="merter_height" >
                <div :class=" progress_style_class" role="progressbar"
                :style="'width:'+remaining_ratio+'%'"
                :aria-valuenow="remaining_ratio" aria-valuemin="0"
                :aria-valuemax="remaining_ratio"
                ></div>
            </div>


            <!-- 残数 -->
            <div class=""
            style="font-size:11px;">
                <span v-if="sm_card==0">残り</span>

                <number-comma-component :number=" remaining_count "></number-comma-component>
                /
                <number-comma-component :number=" max_count "></number-comma-component>
            </div>

            <!-- 限定n回 残数 -->
            <div v-if="sm_card==0 && type_n_remaining_count_label"
            class="position-absolute top-0 start-50 translate-middle w-100 text-end "
            style="font-size:11px;">
                <div class="px-3 text-light bg-dark rounded-pill d-inline-block">{{ type_n_remaining_count_label }}</div>
            </div>


        </div>


            <!-- メーター表示なし -->
        <!-- <div v-else style="height:2rem"></div> -->


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
            merter_height:   'height:1.2rem;',
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
            this.icon_height = this.sm_card!=0 ?  'height:1.2rem;' : 'height: 2.0rem;'  ,


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
