/*
|=============================================
| ガチャ　コンポーネント
|=============================================
*/
import Vue from 'vue'

    /* ガチャ一覧 */
    Vue.component('u-gacha-list',
    require('./components/gacha/list/IndexComponent.vue').default);

        /* ガチャ カード */
        Vue.component('u-gacha-card',
        require('./components/gacha/list/CardComponent.vue').default);

        /* ガチャ マシーン */
        Vue.component('u-gacha-machine',
        require('./components/gacha/list/MachineComponent.vue').default);

        /* ガチャ 画像 */
        Vue.component('u-gacha-image',
        require('./components/gacha/list/ImageComponent.vue').default);

        /* ガチャ メーター */
        Vue.component('u-gacha-metter',
        require('./components/gacha/list/MetterComponent.vue').default);

        /* ガチャボタン */
        Vue.component('u-gacha-play-buttons',
        require('./components/gacha/list/PlayButtonsComponent.vue').default);


    /* カウントダウンガチャコンポーネント */
    Vue.component('u-countdown-gacha',
    require('./components/gacha/CountdownGachaComponent.vue').default);

    /* カウントダウン(日時)ガチャコンポーネント */
    Vue.component('u-countdown-datetime-gacha',
    require('./components/gacha/CountdownDatetimeGachaComponent.vue').default);

    /* 動画自動再生コンポーネント */
    Vue.component('u-movie-play',
    require('./components/gacha/MoviePlayComponent.vue').default);

    /* 動画自動再生コンポーネント(Youtube) */
    Vue.component('u-movie-play-youtube',
    require('./components/gacha/MoviePlayYoutubeComponent.vue').default);

    /* 動画自動再生確認モーダル */
    Vue.component('u-movie-confirm-modal-component',
    require('./components/gacha/ConfirmeModalComponent').default);

    /* ガチャボタンコンポーネント */
    Vue.component('u-gacha-btn',
    require('./components/gacha/DisabledButton.vue').default);

    /* モーダルガチャボタンコンポーネント */
    Vue.component('u-gacha-modal',
    require('./components/gacha/ModalComponent.vue').default);

    /* モーダルカスタムボタンコンポーネント */
    Vue.component('u-gacha-custom-modal',
    require('./components/gacha/CustomModalComponent.vue').default);


    /* ガチャ結果フォーム */
    Vue.component('u-gacha-result-form',
    require('./components/gacha/ResultFormComponent.vue').default);

    /* ガチャ商品履歴 */
    Vue.component('u-gacha-prize-history',
    require('./components/gacha/PrizeHistoryComponent.vue').default);

    /* 商品情報 */
    Vue.component('u-prize-discription',
    require('./components/prize/DiscriptionComponent.vue').default);


    /* ユーザー取得景品一覧フォーム */
    Vue.component('u-user-prize-form',
    require('./components/user_prize/FormComponent.vue').default);

        /* ポイント交換ボタン */
        Vue.component('u-user-prize-exchange-point-btn',
        require('./components/user_prize/ExchangePointBtn.vue').default);

        /* チケット交換ボタン */
        Vue.component('u-user-prize-exchange-ticket-btn',
        require('./components/user_prize/ExchangeTicketBtn.vue').default);

    /* チケット交換一覧フォーム */
    Vue.component('u-ticket-store',
    require('./components/ticket_store/TicketStoreComponent.vue').default);

    /* 発送申請入力フォーム */
    Vue.component('u-shipped-form',
    require('./components/shipped/Form.vue').default);

        /* お届け先一覧 */
        // Vue.component('u-addressーlist-form',
        // require('./components/shipped/AddressListForm.vue').default);

        /* お届け先の新規登録 */
        // Vue.component('u-create-user-address-form',
        // require('./components/shipped/CreateUserAddressForm.vue').default);


        /* お届け先一覧 */
        Vue.component('u-addressーlist-form',
        require('./components/shipped/address_list/index.vue').default);

        /* お届け先の新規登録 */
        // Vue.component('u-create-user-address',
        // require('./components/shipped/address_list/Create.vue').default);

        /* お届け先の編集フォーム */
        Vue.component('u-edit-user-address-form',
        require('./components/shipped/address_list/Form.vue').default);

        /* お届け先の編集コンテナー */
        Vue.component('u-edit-user-address-container',
        require('./components/shipped/address_list/Edit.vue').default);


        /* 発送景品リスト */
        Vue.component('u-userprize-list',
        require('./components/shipped/UserPrizes.vue').default);

    /* 発送履歴 */
    Vue.component('u-shipped-list',
    require('./components/shipped/ListComponent').default);


    /* クーポン完了 */
    Vue.component('u-coupon-comp',
    require('./components/coupon/CompComponent.vue').default);

    /* 買取表一覧 */
    Vue.component('u-purchase-list',
    require('./components/purchase/ListComponent').default);

        /* 査定 */
        Vue.component('u-purchase-appraisal',
        require('./components/purchase/AppraisalComponent').default);

    /**/

export default Vue
