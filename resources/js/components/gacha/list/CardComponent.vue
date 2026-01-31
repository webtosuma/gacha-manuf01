<template>
    <div>

        <div :class="card_style_class">

            <!--image-->
            <a :href="gacha.route"  :class="href_class"  style="opacity:1 !important;">

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
            <div v-if="gacha.slide_imgs && !gacha.i_time "
            :id="'splide_gacha'+gacha.id" class="splide_gacha splide"
            :class="gacha.type=='only_new_user' ? 'bg-success-subtle' : 'bg-white'"
            >
                <div class="splide__track">
                    <ul class="splide__list">

                        <li v-for="( img_path, key ) in gacha.slide_imgs" :key="key"
                        class="splide__slide pe-1 py-1">

                            <div class="ratio ratio-3x4 rounded-2 slide-img-bg" :style="'background-image: url('+ img_path +');'"></div>

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
                    :bg_color="gacha.type=='only_new_user' ? 'bg-success-subtle text-dark' : 'bg-white bg-rainbow-index'"
                    :gacha_type="gacha.type"
                    :sponsor_ad="gacha.sponsor_ad"
                    :gacha_play_point="gacha.one_play_point"
                    :is_meter       ="gacha.is_meter"
                    :remaining_ratio="gacha.remaining_ratio"
                    :remaining_count="gacha.remaining_count"
                    :max_count      ="gacha.max_count"
                    :type_n_remaining_count_label="gacha.type_n_remaining_count_label"
                    />

                    <!-- カウントダウンがあるとき -->
                    <div v-if="gacha.i_time"
                    class="position-absolute top-0 start-0 w-100 h-100 bg-dark"
                    style="z-index:1;" ></div>

                </div>
            </a>


            <!--サブスクプラン-->
            <div v-if="gacha.subscription"
            class="bg-white fw-bold">『{{gacha.subscription.sub_label}}』専用</div>


        </div>


        <!-- play_buttons(非表示：カードサイズSM　または、カウントダウンあり) -->
        <div class="mx-1">
            <u-gacha-play-buttons
            :r_action="gacha.r_action"
            :r_costom="gacha.r_costom"
            :r_prize_history="gacha.r_prize_history"
            :one_play_point         ="gacha.one_play_point"
            :is_disabled_oneplay_btn="gacha.is_disabled_oneplay_btn"
            :is_disabled_tenplay_btn="gacha.is_disabled_tenplay_btn"
            :is_disabled_hundredplay_btn="gacha.is_disabled_hundredplay_btn"
            :is_disabled_custom_btn ="gacha.is_disabled_custom_btn"

            :i_time                 ="gacha.i_time"
            :limitted_i_time        ="gacha.limitted_i_time"
            :dont_auth_user_rank    ="gacha.dont_auth_user_rank ?true :false"
            :sub_auth_user          ="gacha.sub_auth_user       ?true :false"
            :sm_card="sm_card"
            :gacha_id="gacha.id"
            :is_popup_btn="gacha.is_popup_btn"
            />
        </div>

        <!-- :show_play_bottons      ="show_play_bottons"
        :hidden_play_bottons_text="hidden_play_bottons_text" -->


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
                card border-0 bg-transparent
                text-dark text-center overflow-hidden text-decoration-none
                position-relative rounded-4
                shiny
            `,


            /*リンク　クラス*/
            href_class:'d-block text-dark bg-body',
            href_disabled_class:'d-block btn p-0 border-0 disabled',


        } },
        mounted() {


            /* 公開予告カウントダウンがあるとき*/
            if( this.gacha.i_time ){

                //詳細ページリンクの停止(
                this.href_class = this.href_disabled_class;

                //ホバーアニメーションを除く
                this.card_style_class = this.card_style_class.replace( 'hover_anime', '');
            }

            //show_play_bottons

            /* Splideインスタンスを作成 */
            if( this.gacha.slide_imgs && !this.gacha.i_time ){
                this.splide();
            }



        },
        methods:{


            /* Splideインスタンスを作成 */
            splide: function(){

                const slider = document.querySelector('#'+'splide_gacha'+this.gacha.id);

                // 各スライダーに対してSplideインスタンスを作成
                // sliders.forEach((slider) => {

                    new Splide( '#'+slider.id , {

                        type      : 'loop',
                        focus     : 'center',
                        perPage   : 6,
                        autoplay  : true,
                        pagination: false,


                    }).mount();

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

