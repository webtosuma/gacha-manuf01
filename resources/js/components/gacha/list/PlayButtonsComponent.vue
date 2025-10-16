<template>
    <div class="">

        <div v-if="show_play_bottons && !r_prize_history" class="row g-2 mt-1">

            <!--1回ボタン-->
            <div class="col">

                <!--POPUP BTN(1)-->
                <u-gacha-btn v-if="is_popup_btn"
                :label      ="one_play_label"
                :point      ="one_play_point.toLocaleString()+'pt'"
                :disabled   ="is_disabled_oneplay_btn==0 ?0:1"
                :style_class="one_play_style_class"
                :data-bs-toggle="is_disabled_oneplay_btn==0 ? 'modal' : ''"
                :data-bs-target="'#'+'gachaPlayModal'+gacha_id+'-'+'1'"
                />


                <!--NO POPUP BTN(1)-->
                <form v-else
                :action="r_action" method="post">

                    <input type="hidden" name="_token" :value="token">

                    <u-gacha-btn
                    name="play_count"
                    value="1"
                    :label      ="one_play_label"
                    :point      ="one_play_point.toLocaleString()+'pt'"
                    :disabled   ="is_disabled_oneplay_btn==0 ?0:1"
                    :style_class="one_play_style_class"
                    />
                </form>
            </div>


            <!--10連ボタン-->
            <div class="col" v-if="is_disabled_tenplay_btn>-1" >

                <!--POPUP BTN(10)-->
                <u-gacha-btn v-if="is_popup_btn"
                :label      ="ten_play_label"
                :point      ="(one_play_point*10).toLocaleString()+'pt'"
                :disabled   ="is_disabled_tenplay_btn==0 ?0:1"
                :style_class="ten_play_style_class"
                :data-bs-toggle="is_disabled_oneplay_btn==0 ? 'modal' : ''"
                :data-bs-target="'#'+'gachaPlayModal'+gacha_id+'-'+'10'"
                />


                <!--NO POPUP BTN(10)-->
                <form v-else
                :action="r_action" method="post">
                    <input type="hidden" name="_token" :value="token">

                    <u-gacha-btn
                    name="play_count"
                    value="10"
                    :label      ="ten_play_label"
                    :point      ="(one_play_point*10).toLocaleString()+'pt'"
                    :disabled   ="is_disabled_tenplay_btn==0 ?0:1"
                    :style_class="ten_play_style_class"
                    />
                </form>
            </div>


            <!--100連ボタン-->
            <div class="col-12" v-if="is_disabled_hundredplay_btn>-1" >

                <!--POPUP BTN(100)-->
                <u-gacha-btn v-if="is_popup_btn"
                :label      ="hundred_play_label"
                :point      ="(one_play_point*100).toLocaleString()+'pt'"
                :disabled   ="is_disabled_hundredplay_btn==0 ?0:1"
                :style_class="hundred_play_style_class"
                :data-bs-toggle="is_disabled_oneplay_btn==0 ? 'modal' : ''"
                :data-bs-target="'#'+'gachaPlayModal'+gacha_id+'-'+'100'"
                />

                <!--NO POPUP BTN(100)-->
                <form v-else
                :action="r_action" method="post">
                    <input type="hidden" name="_token" :value="token">

                    <u-gacha-btn
                    name="play_count"
                    value="100"
                    :label      ="hundred_play_label"
                    :point      ="(one_play_point*100).toLocaleString()+'pt'"
                    :disabled   ="is_disabled_hundredplay_btn==0 ?0:1"
                    :style_class="hundred_play_style_class"
                    />
                </form>
            </div>

            <!--カスタムボタン-->
            <!-- <div class="col-12"  v-if="is_disabled_custom_btn>-1" >
                <a :href="r_costom"
                :class    ="coustom_style_class"
                >{{ custom_label }}</a>
            </div> -->
            <div class="col-12"  v-if="is_disabled_custom_btn>-1" >
                <button type="button"
                :class    ="coustom_style_class"
                data-bs-toggle="modal"
                :data-bs-target="'#'+'gachaCustomModal'+gacha_id"
                >{{ custom_label }}</button>
            </div>


        </div>

        <!-- 商品履歴リンク -->
        <a v-if="r_prize_history"
        :href="r_prize_history"
        class="btn btn-warning text-dark fw-bold rounded-pill w-100 mt-3"
        >商品履歴を見る</a>



        <!-- カウントダウン時のテキスト -->
        <div v-if="hidden_play_bottons_text!==''"
        class="text-center text-white mt-3 py-2 rounded bg-dark"
        >{{ hidden_play_bottons_text }}</div>
    </div>
</template>

<script>
    export default {
        props: {
            r_action        : { type: String,  default: '', },//ルート:ガチャる
            r_costom        : { type: String,  default: '', },//ルート:カスタム
            r_prize_history : { type: String,  default: '', },//ルート:商品履歴

            one_play_point          : { type: [String,  Number],  default: 0, },
            is_disabled_oneplay_btn : { type: [String,  Number],  default: 0, }, //1回ガチャるボタンのdisabled
            is_disabled_tenplay_btn : { type: [String,  Number],  default: 0, }, //10連ガチャるボタンのdisabled
            is_disabled_hundredplay_btn: { type: [String,  Number],  default: 0, }, //百連ガチャるボタンのdisabled
            is_disabled_custom_btn  : { type: [String,  Number],  default: 0, }, //カスタムボタンのdisabled
            sub_auth_user           : { type: [String,  Number],  default: 1, }, //ログインユーザーがサブスクガチャを利用できるか

            i_time                  : { type: [String],  default: '',},
            limitted_i_time         : { type: [String],  default: '',},
            dont_auth_user_rank     : { type: [String,Number,Boolean],  default: false,},  //会員ランクのユーザーではないとき
            sub_auth_user           : { type: [String,Number,Boolean],  default: true,},   //サブスクプランに該当
            sm_card                 : { type: [String,Number,Boolean],  default: 0, },//カードの表示サイズ

            gacha_id:     { type: [String,  Number],  default: 0, },//ガチャID
            is_popup_btn: { type: [String,Number,Boolean],  default: false,},//ポップアップボタン
        },
        mounted() {

            /* カスタムボタン */
            this.custom_label = Number( this.is_disabled_custom_btn )==0
            ? this.custom_label : this.soldout_label ;

            this.coustom_style_class = Number( this.is_disabled_custom_btn )==0
            ? this.coustom_style_class : this.soldout_coustom_style_class ;


            /* 1回ガチャる */
            switch ( Number( this.is_disabled_oneplay_btn ) ) {
                case 2://本日終了
                    this.one_play_label       = this.ends_today_label;
                    this.one_play_style_class = this.soldout_one_play_style_class;
                    break;

                case 1://
                    this.one_play_label       = this.soldout_label;
                    this.one_play_style_class = this.soldout_one_play_style_class;
                    break;

                //
            }
            /* 10連ガチャる */
            switch ( Number( this.is_disabled_tenplay_btn ) ) {
                case 2://本日終了
                    this.ten_play_label       = this.ends_today_label;
                    this.ten_play_style_class = this.soldout_ten_play_style_class;
                    break;

                case 1://
                    this.ten_play_label       = this.soldout_label;
                    this.ten_play_style_class = this.soldout_ten_play_style_class;
                    break;

                //
            }
            /* 百連ガチャる */
            switch ( Number( this.is_disabled_hundredplay_btn ) ) {
                case 2://本日終了
                    this.hundred_play_label       = this.ends_today_label;
                    this.hundred_play_style_class = this.soldout_hundred_play_style_class;
                    break;

                case 1://
                    this.hundred_play_label       = this.soldout_label;
                    this.hundred_play_style_class = this.soldout_hundred_play_style_class;
                    break;

                //
            }
            /*　プレイボタンの非表示表示　*/
            {
                // 時間限定ガチャカウントダウンがあるとき
                this.show_play_bottons = (this.i_time || this.limitted_i_time)
                ? false : this.show_play_bottons;

                // 会員ランクのユーザーではないとき
                this.show_play_bottons = (this.dont_auth_user_rank ? 1 : 0)==1
                ? false : this.show_play_bottons;

                // サブスクプランに該当しないとき
                this.show_play_bottons = (this.sub_auth_user ? 1 : 0)==0
                ? false : this.show_play_bottons;

                // カードサイズ:smのとき(非表示)
                this.show_play_bottons = this.sm_card!=0
                ? false : this.show_play_bottons;

            }
            /*　プレイボタン・テキストの非表示表示　*/
            {
                // 時間限定ガチャカウントダウンがあるとき
                this.hidden_play_bottons_text = (this.i_time || this.limitted_i_time)
                ? '公開までお待ちください' : this.hidden_play_bottons_text;

                // 会員ランクのユーザーではないとき
                this.hidden_play_bottons_text = (this.dont_auth_user_rank ? 1 : 0)==1
                ? '会員ランク専用です' : this.hidden_play_bottons_text;

                // サブスクプランに該当しないとき
                this.hidden_play_bottons_text = (this.sub_auth_user ? 1 : 0)==0
                ? 'サブスク申込みが必要です' : this.hidden_play_bottons_text;

                // カードサイズ:smのとき(非表示)
                this.hidden_play_bottons_text = this.sm_card!=0
                ? '' : this.hidden_play_bottons_text;

            }


        },
        data() { return {

            /*csrf token*/
            token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

            /* プレイボタンの表示 */
            show_play_bottons: true,
            /* プレイボタン非表示のときのテキスト */
            hidden_play_bottons_text: '',


            /* 1回ガチャる　  ラベル */
            one_play_label:  '1回ガチャる',
            /* 10連ガチャる　 ラベル */
            ten_play_label:  '10連ガチャる',
            /* 百連ガチャる　 ラベル */
            hundred_play_label:  '100連ガチャる',

            /* カスタムボタン　ラベル */
            custom_label:    '回数をカスタム',
            /* 売り切れ　ラベル */
            soldout_label:   '終了',
            /*本日終了　ラベル */
            ends_today_label:'本日は終了',


            /* 1回ガチャる　  disabled */
            one_play_disabled: false,
            /* 10連ガチャる　 disabled */
            ten_play_disabled: false,


            /* 1回ガチャる　スタイル */
            one_play_style_class: `
            btn btn-sm btn-light bg-gradient fw-bold w-100 py-2
            rounded-pill border-secondary border-0 shadow-sm
            position-relative shiny overflow-hidden
            `,
            /* 1回ガチャる　スタイル(売り切れ) */
            soldout_one_play_style_class: `
            btn btn-sm btn-light bg-gradient fw-bold w-100 py-2 text-danger
            rounded-pill border-secondary border-0 shadow-sm
            `,
            /* 10連ガチャる　スタイル */
            ten_play_style_class: `
            btn btn-sm btn-dark bg-gradient text- fw-bold w-100 py-2
            rounded-pill border-danger border-0 shadow-sm
            position-relative shiny overflow-hidden
            `,
            /* 10連ガチャる　スタイル(売り切れ) */
            soldout_ten_play_style_class: `
            btn btn-sm btn-dark bg-gradient text- fw-bold w-100 py-2 text-danger
            rounded-pill border-secondary border-0 shadow-sm
            `,
            /* 百連ガチャる　スタイル */
            hundred_play_style_class: `
            btn btn-sm btn-info bg-gradient text-white fw-bold w-100 py-2
            rounded-pill border-danger border-0 shadow-sm
            position-relative shiny overflow-hidden
            `,
            /* 百連ガチャる　スタイル(売り切れ) */
            soldout_hundred_play_style_class: `
            btn btn-sm btn-info bg-gradient fw-bold w-100 py-2 text-danger
            rounded-pill border-secondary border-0 shadow-sm
            `,
            /* カスタムボタン　スタイル */
            coustom_style_class: `
            btn btn-  btn-info bg-gradient text-white fw-bold w-100 pb-
            rounded-pill border-danger border-0 shadow-sm
            position-relative shiny overflow-hidden h-100
            `,
            /* カスタムボタン　スタイル(売り切れ) */
            soldout_coustom_style_class: `
            btn btn-  btn-info bg-gradient text-danger fw-bold w-100 pb-
            rounded-pill border-secondary border-0 shadow-sm
            position-relative shiny overflow-hidden h-100
            disabled
            `,

        } },
    }
</script>
