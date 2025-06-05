<template>
    <div  style="min-height:60vh;">




        <!-- 表示ボタン -->
        <button v-if=" show==0 "
        id="playButton"
        @click="show = 1;"
        type="button"
        class="position-fixed top-0 start-0 h-100 w-100
        btn rounded-0
        d-flex align-items-center justify-content-center
        "
        style="z-index:100; background: rgb(0, 0, 0, 1);"
        >

            <div class="d-flex flex-column align-items-center justify-content-center gap-3 text-white">


                <!--プレゼント画像-->
                <ratio-image-component
                id="gift-box"
                :url="box_image_path"
                style_class="ratio ratio-1x1"
                style="width:300px;"
                ></ratio-image-component>

                <div
                class="btn btn-lg btn-info text-white rounded-pill fs-3 px-4"
                >タップして商品を受け取る</div>


                <ratio-image-component
                id="finger"
                :url="finger_image_path"
                style_class="ratio ratio-1x1"
                style="width:6rem;"
                ></ratio-image-component>

            </div>
        </button>


        <!-- 商品一覧の表示 -->
        <section v-else>
            <div class="position-relative"
            data-aos="zoom-in"
            >
                <div class="ratio ratio-1x1 d-flex align-items-center justify-content-center"></div>

                <div class="w-50 position-absolute top-50 start-50 translate-middle">

                    <ratio-image-component
                    :url="coupon_image_path"
                    :style_class="ratio+' ratio'"
                    ></ratio-image-component>

                </div>
            </div>
        </section>



    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        ratio            :{ type: String, default: '' },
        coupon_image_path:{ type: String, default: '' },
        box_image_path   :{ type: String, default: '' },
        finger_image_path:{ type: String, default: '' },
        prop_show        :{ type: String, default: 0 },
    });


    /* データの状態 */
    const show = ref(props.prop_show); //

    /* 初回データ取得 */
    onMounted(() => {
        // getData();
    });



</script>
<style scoped>
    /* 箱のスタイル */
    #gift-box {
        display: inline-block;
        animation: shake 3s infinite ease-in-out; /* 基本アニメーション設定 */
    }

    /* 揺れる動きのキーフレーム */
    @keyframes shake {
        0%, 100% {
            transform: rotate(0deg);
        }
        10% {
            transform: rotate(-4deg);
        }
        20% {
            transform: rotate(4deg);
        }
        30% {
            transform: rotate(-2deg);
        }
        40% {
            transform: rotate(2deg);
        }
        50% {
            transform: rotate(0deg);
        }
    }




    /* 指の画像 */
    #finger {
        display: inline-block;
        animation: swipe-up 2.5s infinite ease-in-out;
    }
    /* 指が下から上に現れ、タップする動き */
    @keyframes swipe-up {
        0% {
            transform: translateY(100px) rotate(0deg); /* 下から登場 */
            opacity: 0;
        }
        30% {
            transform: translateY(0px) rotate(0deg); /* 指が現れる */
            opacity: 1;
        }
        50% {
            transform: translateY(-10px) rotate(0deg); /* 上に少し動く */
        }
        70% {
            transform: translateY(0px) rotate(0deg); /* 軽くタップを表現 */
        }
        100% {
            transform: translateY(100px) rotate(0deg); /* 再び消える */
            opacity: 0;
        }
    }
</style>
