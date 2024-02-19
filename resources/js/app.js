require('./bootstrap');

window.Vue = require('vue').default;


Vue.component('example-component',
require('./components/ExampleComponent.vue').default);



/*
|=============================================
| ユーザーページ　コンポーネント
|=============================================
*/
    /* 会員登録フォーム */
    Vue.component('u-register-form',
    require('./components/auth/RegisterFormConpornent.vue').default);

    /* パスワード変更フォーム */
    Vue.component('u-reset-password-form',
    require('./components/auth/ResetPasswordFormConpornent.vue').default);

    /* 動画自動再生コンポーネント */
    Vue.component('u-movie-play',
    require('./components/gacha/MoviePlayComponent.vue').default);

    /* ガチャ結果フォーム */
    Vue.component('u-gacha-result-form',
    require('./components/gacha/ResultFormComponent.vue').default);

    /* ユーザー取得景品一覧フォーム */
    Vue.component('u-user-prize-form',
    require('./components/user_prize/FormComponent.vue').default);

    /* 発送申請入力フォーム */
    Vue.component('u-shipped-form',
    require('./components/shipped/Form.vue').default);

        /* お届け先一覧 */
        Vue.component('u-addressーlist-form',
        require('./components/shipped/AddressListForm.vue').default);

        /* お届け先の新規登録 */
        Vue.component('u-create-user-address-form',
        require('./components/shipped/CreateUserAddressForm.vue').default);

        /* 発送景品リスト */
        Vue.component('u-userprize-list',
        require('./components/shipped/UserPrizes.vue').default);

    /* お問い合わせフォーム */
    Vue.component('contact-form-component', require('./components/contact/FormComponent.vue').default);
/*
|=============================================
| サイト管理者ページ　コンポーネント
|=============================================
*/
    /* 商品管理 */
    Vue.component('a-prize-list',
    require('./components/admin/prize/Index.vue').default);

    /* ガチャ管理 */

        // 商品登録
        Vue.component('a-gachaprize-edit',
        require('./components/admin/gacha/prize/edit/Index.vue').default);

            // ランク
            Vue.component('a-gachaprize-gacharank-container',
            require('./components/admin/gacha/prize/edit/GachaRankContainer.vue').default);

            // 商品リスト
            Vue.component('a-gachaprize-prize-list',
            require('./components/admin/gacha/prize/edit/PrizeList.vue').default);


    /* ポイント売上 */

    // 年月選択
    Vue.component('a-pointhistory-selectmonth',
    require('./components/admin/point_history/SelectMonth.vue').default);
    // グラフ
    Vue.component('a-pointhistory-chart',
    require('./components/admin/point_history/Chart.vue').default);

    /* お知らせ */
    Vue.component('a-infomation-sendemail',
    require('./components/admin/infomation/Sendemail.vue').default);


    /* お問い合わせ一覧 */
    Vue.component('contact-list-component',
    require('./components/admin/contact/ListComponent.vue').default);

/*
|=============================================
| アイテムコンポーネント
|=============================================
*/
    /* コピー ボタン */
    Vue.component('coppy-button-component',
    require('./components/Items/CoppyButtonComponent.vue').default);

    /* disabled ボタン */
    Vue.component('disabled-button-component',
    require('./components/Items/DisabledButtonComponent.vue').default);

    /* disabled(ノーマルform用) ボタン */
    Vue.component('disabled-button',
    require('./components/Items/DisabledButton.vue').default);

    /* 画像表示 */
    Vue.component('ratio-image-component',
    require('./components/Items/RatioImageComponent.vue').default);

    /* 画像ファイル読み込み　Input */
    Vue.component('read-image-file-component',
    require('./components/Items/ReadImageFileComponent.vue').default);

    /* 動画ファイル読み込み　Input */
    Vue.component('read-movie-file-component',
    require('./components/Items/ReadMovieFileComponent.vue').default);

    /* 文章置換え（改行・リンクタグ対応） */
    Vue.component('replace-text-component',
    require('./components/Items/ReplaceTextComponent.vue').default);

    /* リストカード */
    Vue.component('list-card-component',
    require('./components/Items/ListCardComponent.vue').default);

    Vue.component('list-card-menu-component',
    require('./components/Items/ListCardMenuComponent.vue').default);

    /* ローディング表示コンテナ */
    Vue.component('loading-container-component',
    require('./components/Items/LoadingContainerComponent.vue').default);

    /* アラートモーダル */
    Vue.component('alert-modal-comp-component',
    require('./components/Items/AlertModalCompComponent.vue').default);

    /* 削除モーダル */
    Vue.component('delete-modal-component',
    require('./components/Items/DeleteModalComponent.vue').default);

    /* コンフォームモーダル */
    Vue.component('confirm-modal-component',
    require('./components/Items/ConfirmeModalComponent.vue').default);

    /* 数字にカンマを入れるコンポーネント */
    Vue.component('number-comma-component',
    require('./components/Items/NumberCommaComponent.vue').default);

    /* エスケープテキストエリア*/
    Vue.component('encodedーtextarea-component',  require('./components/Items/EncodedTextareaComponent.vue').default);
    /* エスケープインプットテキスト*/
    Vue.component('encodedーinputtext-component',  require('./components/Items/EncodedInputtextComponent.vue').default);


    /* 動画モーダルコンポーネント */
    Vue.component('movie-modal-component',
    require('./components/Items/MovieModalComponent.vue').default);


    /* PWAインストールボタン */
    Vue.component('pwa-install-btn',
    require('./components/Items/PwaInstallBtn.vue').default);



const app = new Vue({ el: '#app', });
