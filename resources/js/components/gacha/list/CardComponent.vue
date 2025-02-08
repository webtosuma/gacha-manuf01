<template>
    <div>
        <div :class="card_style_class">



            <!--image-->
            <a :href="gacha.route"  :class="href_class">

                <u-gacha-image
                :gacha_name            ="gacha.name"
                :gacha_ratio           ="gacha.ratio"
                :gacha_image_path      ="gacha.image_path"

                :initial_time          ="gacha.i_time"
                :limitted_i_time       ="gacha.limitted_i_time"
                :published_at_format   ="gacha.published_at_format"
                :remaining_count       ="gacha.remaining_count"
                :add_chance_image_path ="gacha.add_chance_image_path"
                :add_chance_count      ="gacha.add_chance_count"
                :have_user_rank        ="gacha.have_user_rank"
                :user_played_count     ="gacha.user_played_count"

                :img_path_one_chance   ="gacha.img_path_one_chance "
                :img_path_one_time     ="gacha.img_path_one_time"
                :img_path_only_oneday  ="gacha.img_path_only_oneday"
                :img_path_only_new_user="gacha.img_path_only_new_user"
                :img_path_user_rank    ="gacha.img_path_user_rank"
                />

            </a>


            <!-- スライダー   -->
            <div v-if="gacha.slide_imgs"
            :id="'splide_gacha'+gacha.id" class="splide_gacha splide"
            :class="gacha.type=='only_new_user' ? 'bg-success-subtle' : 'bg-white'"
            >
                <div class="splide__track">
                    <ul class="splide__list">

                        <li v-for="( img_path, key ) in gacha.slide_imgs" :key="key"
                        class="splide__slide p-1">

                            <div class="ratio ratio-1x1 rounded-2 slide-img-bg" :style="'background-image: url('+ img_path +');'"></div>

                        </li>

                    </ul>
                </div>
            </div>


            <!--metter & price-->
            <a :href="gacha.route"  :class="href_class">
                <div class="position-relative">

                    <u-gacha-metter
                    :sm_card="sm_card"
                    :new_label_path="gacha.new_label_path"
                    :img_path_point="gacha.img_path_point"
                    :bg_color="gacha.type=='only_new_user' ? 'bg-success-subtle' : 'bg-white'"
                    :gacha_type="gacha.type"
                    :sponsor_ad="gacha.sponsor_ad"
                    :gacha_play_point="gacha.one_play_point"
                    :is_meter       ="gacha.is_meter"
                    :remaining_ratio="gacha.remaining_ratio"
                    :remaining_count="gacha.remaining_count"
                    :max_count      ="gacha.max_count"
                    />

                    <!-- カウントダウンがあるとき -->
                    <div v-if="gacha.i_time"
                    class="position-absolute top-0 start-0 w-100 h-100 bg-dark"
                    style="z-index:1;" ></div>

                </div>
            </a>

        </div>

        <!--play_buttons(非表示：カードサイズSM　または、カウントダウンあり) -->
        <div v-if="show_play_bottons">
            <u-gacha-play-buttons
            :r_action="gacha.r_action"
            :r_costom="gacha.r_costom"
            :one_play_point         ="gacha.one_play_point"
            :is_disabled_oneplay_btn="gacha.is_disabled_oneplay_btn"
            :is_disabled_tenplay_btn="gacha.is_disabled_tenplay_btn"
            :is_disabled_custom_btn ="gacha.is_disabled_custom_btn"
            />
        </div>

        <!-- カウントダウン時のテキスト -->
        <div v-if="hidden_play_bottons_text"
        class="text-center text-white mt-3 py-2 rounded-pill bg-dark"
        >{{ hidden_play_bottons_text }}</div>




    </div>
</template>

<script>
    export default {
        props: {

            gacha:         { type: [Object,Array],  default: {}, },
            sm_card:       { type: [String,Number,Boolean],  default: 0, },//カードの表示サイズ

        },
        data() { return {


            /*ガチャカード　クラス*/
            card_style_class: `
                card border-0 shadow bg-transparent
                text-dark text-center overflow-hidden text-decoration-none
                position-relative rounded-4
                shiny hover_anime
                border-white border-5 shadow
            `,


            /*リンク　クラス*/
            href_class:'d-block text-dark bg-white',
            href_disabled_class:'d-block btn p-0 border-0 disabled',


            /* プレイボタンの表示 */
            show_play_bottons: true,

            /* プレイボタン非表示のときのテキスト */
            hidden_play_bottons_text: '',


        } },
        mounted() {

            /* 詳細ページリンクの停止(カウントダウンがあるとき) */
            if( this.gacha.i_time ){
                this.href_class = this.href_disabled_class;
            }


            /*
            * プレイボタンの非表示表示
            *
            * 新規ガチャカウントダウンがあるとき
            * 時間限定ガチャカウントダウンがあるとき
            * カードサイズ:smのとき
            */
            if( this.gacha.i_time || this.gacha.limitted_i_time || this.sm_card!=0 ){
                this.show_play_bottons = false;
            }



            /*
            * プレイボタンの非表示表示
            *
            * 時間限定ガチャカウントダウンがあるとき
            * カードサイズ:smのとき
            */
            if( (this.gacha.i_time || this.gacha.limitted_i_time) && this.sm_card==0 ){
                this.hidden_play_bottons_text = '公開までお待ちください';
            }



            /* Splideインスタンスを作成 */
            this.splide();



        },
        methods:{


            /* Splideインスタンスを作成 */
            splide: function(){
                const sliders = document.querySelectorAll('.splide_gacha');

                // 各スライダーに対してSplideインスタンスを作成
                sliders.forEach((slider) => {

                    new Splide( '#'+slider.id , {

                        type      : 'loop',
                        focus     : 'center',
                        perPage   : 6,
                        autoplay  : true,
                        pagination: false,


                    }).mount();

                });
             },
        }

    }
</script>
<style  scoped>
    /* .ratio-image-parent-component
    {
        overflow: hidden;
    } */
    .slide-img-bg
    {
        background-repeat  : no-repeat;
        background-size    : cover;
        background-position: center center;
        width: 100%;
    }
</style>

