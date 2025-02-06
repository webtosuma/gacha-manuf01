<template>
    <div class="row g-2 mt-1">

        <!--1回ボタン-->
        <div class="col">
            <form :action="r_action" method="post">
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
            <form :action="r_action" method="post">
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


        <!--カスタムボタン-->
        <div class="col-12"  v-if="is_disabled_custom_btn>-1" >
            <a :href="r_costom"
            :class    ="coustom_style_class"
            >{{ custom_label }}</a>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            r_action : { type: String,  default: '', },//ルート:ガチャる
            r_costom   : { type: String,  default: '', },//ルート:カスタム
            one_play_point          : { type: [String,  Number],  default: 0, },
            is_disabled_oneplay_btn : { type: [String,  Number],  default: 0, }, //1回ガチャるボタンのdisabled
            is_disabled_tenplay_btn : { type: [String,  Number],  default: 0, }, //10連ガチャるボタンのdisabled
            is_disabled_custom_btn  : { type: [String,  Number],  default: 0, }, //カスタムボタンのdisabled
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
        },
        data() { return {

            /*csrf token*/
            token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

            /* 1回ガチャる　  ラベル */
            one_play_label:  '1回ガチャる',
            /* 10連ガチャる　 ラベル */
            ten_play_label:  '10連ガチャる',
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
            btn btn-light bg-gradient fw-bold w-100 pb-0
            rounded-pill border-secondary border-0 shadow-sm
            position-relative shiny overflow-hidden
            `,
            /* 1回ガチャる　スタイル(売り切れ) */
            soldout_one_play_style_class: `
            btn btn-light bg-gradient fw-bold w-100 pb-0 text-danger
            rounded-pill border-secondary border-0 shadow-sm
            `,
            /* 10連ガチャる　スタイル */
            ten_play_style_class: `
            btn btn-dark bg-gradient text- fw-bold w-100 pb-0
            rounded-pill border-danger border-0 shadow-sm
            position-relative shiny overflow-hidden
            `,
            /* 10連ガチャる　スタイル(売り切れ) */
            soldout_ten_play_style_class: `
            btn btn-dark bg-gradient text- fw-bold w-100 pb-0 text-danger
            rounded-pill border-secondary border-0 shadow-sm
            `,
            /* カスタムボタン　スタイル */
            coustom_style_class: `
            btn btn-info bg-gradient text-white fw-bold w-100 pb-
            rounded-pill border-danger border-0 shadow-sm
            position-relative shiny overflow-hidden
            `,
            /* カスタムボタン　スタイル(売り切れ) */
            soldout_coustom_style_class: `
            btn btn-info bg-gradient text-danger fw-bold w-100 pb-
            rounded-pill border-danger border-0 shadow-sm
            position-relative shiny overflow-hidden
            disabled
            `,

        } },
    }
</script>
