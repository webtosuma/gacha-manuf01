<template>
    <div class="position-relative"
    data-bs-toggle="tooltip" data-bs-placement="bottom" :title="gacha_name">


        <!--image-->
        <div class="overflow-hidden" :class="{'mosaic_image':initial_time}">
            <ratio-image-component
            :url="gacha_image_path"
            :style_class="gacha_ratio+' ratio bg-body'"
            ></ratio-image-component>
        </div>

        <!--売り切れ-->
        <div v-if="remaining_count==0"
        class="position-absolute top-0 start-0 w-100 h-100"
        style="z-index:3; background: rgba(0, 0, 0, .7);"
        ><div class="d-flex align-items-center justify-content-center h-100 fs-3 text-white"
        >SOLD OUT</div></div>


        <!-- 新規カウントダウン(日時) -->
        <!-- <u-countdown-datetime-gacha
        v-if="initial_time"
        :initial_datetime="published_at_format"
        text="新作公開まで"
        /> -->

        <!--新規カウントダウン-->
        <u-countdown-gacha
        v-if="initial_time"
        text="新作公開まで"
        :initial_time="initial_time"
        />


        <!--時間限定ガチャカウントダウン-->
        <u-countdown-gacha
        v-if="limitted_i_time"
        text="販売開始まで"
        :initial_time="limitted_i_time"
        />


        <!-- 個人ガチャ 個人PLAY数 -->
        <div v-if="have_user_rank"
        class="position-absolute bottom-0 end-0"
        style="z-index:11; opacity:.8;">
            <div class="bg-dark text-white px-2 mb-3 border border-2 border-info"
            style="border-radius: 50rem 0 0 50rem; border-right:none !important;">
                <span style="font-size:.8rem;">個人PLAY数</span>
                <span class="fs-5">{{user_played_count}}</span>
                <span style="font-size:.6rem;">回</span>
            </div>
        </div>


        <!-- ワンチャンス限定 -->
        <div v-if="img_path_one_chance"
        class="position-absolute p-2 top-0 end-0 text-end w-100">
            <img :src="img_path_one_chance" style="width:30%;">
        </div>

        <!-- 1回限定 -->
        <div v-if="img_path_one_time"
        class="position-absolute p-2 top-0 end-0 text-end w-100">
            <img :src="img_path_one_time" style="width:30%;">
        </div>

        <!-- 1日限定 -->
        <div v-if="img_path_only_oneday"
        class="position-absolute p-2 top-0 end-0 text-end w-100">
            <img :src="img_path_only_oneday" style="width:30%;">
        </div>

        <!-- 新規会委員限定 -->
        <div v-if="img_path_only_new_user"
        class="position-absolute p-2 top-0 end-0 text-end w-100">
            <img :src="img_path_only_new_user" style="width:30%;">
        </div>

        <!-- 会員ランク限定 -->
        <div v-if="img_path_user_rank"
        class="position-absolute p-2 top-0 end-0 text-start w-100">
            <img :src="img_path_user_rank" style="width:30%;">
        </div>


        <!--アド確定予告-->
        <div v-if="add_chance_image_path"
        class="position-absolute top-0 start-0 w-100 h-100 gacha_chance"
        style="z-index:9;">
            <div class="position-relative">

                <ratio-image-component
                :url="add_chance_image_path"
                :style_class="gacha_ratio+' ratio bg-body'"
                ></ratio-image-component>

            </div>
        </div>


        <!-- アド確定予告メーター -->
        <div v-if="add_chance_image_path"
        class="position-absolute bottom-0 start-0 w-100 px-5 py-1"
        style="z-index:10; opacity:.8;">
            <div class="progress bg-danger-subtle text-danger fw-bold">
                <div class="progress-bar bg-danger" role="progressbar"
                :style="'width:'+add_chance_ration+'%'" :aria-valuenow="add_chance_ration" aria-valuemin="0" :aria-valuemax="add_chance_ration"
                >{{add_chance_ration>50 ? add_count_down_label : '' }}</div>
                {{ add_chance_ration>50 ? '' : add_count_down_label }}
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: {

            gacha_name             : { type: String,  default: '', },
            gacha_ratio            : { type: String,  default: '', },//画像比率
            gacha_image_path       : { type: String,  default: '', },

            initial_time           : { type: String,  default: '', },          //カウントダウン時間(新規)
            limitted_i_time        : { type: String,  default: '', },          //カウントダウン時間(時間限定ガチャ)
            published_at_format    : { type: String,  default: '', },          //カウントダウン用ガチャ公開日(新規・日時)
            remaining_count        : { type: [String, Number],  default: 0, }, //残数(売り切れ判断)
            add_chance_image_path  : { type: String,  default: '', },          //アド確定予告画像パス
            add_chance_count       : { type: [String, Number],  default: null, },//天井系ガチャのアド確定までの回転数
            have_user_rank         : { type: [String, Number, Boolean,],  default: 0, },//個人のプレイ数の商品登録
            user_played_count      : { type: [String, Number],  default: 0, },//

            img_path_one_chance    : { type: String,  default: '', },//ワンチャンス限定
            img_path_one_time      : { type: String,  default: '', },//一回限定
            img_path_only_oneday   : { type: String,  default: '', },//1日一回限定
            img_path_only_new_user : { type: String,  default: '', },//新規会委員限定
            img_path_user_rank     : { type: String,  default: '', },//会員ランク限定
        },
        data() { return {

            add_chance_ration: 0, //アド予告メーター比率
            add_count_down_label:  '',

        } },
        mounted() {

            this.add_chance_ration = ( 1 - ( ( this.add_chance_count+0 ) /10) ) *100;
            this.add_count_down_label = 'あと'+(this.add_chance_count+1)+'回でアド確定';

        }
    }
</script>
<style scoped>
    /*モザイク加工*/
    .mosaic_image {
        z-index: 0;
        -ms-filter: blur(16px);
        filter: blur(16px);
    }
</style>
