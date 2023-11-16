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

    /* ガチャ結果フォーム */
    Vue.component('u-gacha-result-form',
    require('./components/gacha/ResultFormComponent.vue').default);

    /* ユーザー取得景品一覧フォーム */
    Vue.component('u-user-prize-form',
    require('./components/user_prize/FormComponent.vue').default);


    /* お届け先の新規登録 */
    Vue.component('u-create-user-address-form',
    require('./components/shipped/CreateUserAddressForm.vue').default);

    /* 発送申請フォーム親 */
    Vue.component('u-shipped-parrent-form',
    require('./components/shipped/ParentForm.vue').default);

    /* 発送申請フォーム子供01 */
    Vue.component('u-shipped-children01form',
    require('./components/shipped/children01Form.vue').default);

    /* 発送申請フォーム子供02 */
    Vue.component('u-shipped-children02form',
    require('./components/shipped/children02Form.vue').default);

    /* 発送申請フォーム子供03 */
    Vue.component('u-shipped-children03form',
    require('./components/shipped/children03Form.vue').default);

/*
|=============================================
| サイト管理者ページ　コンポーネント
|=============================================
*/

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

    /* 画像表示 */
    Vue.component('ratio-image-component',
    require('./components/Items/RatioImageComponent.vue').default);

    /* 画像ファイル読み込み　Input */
    Vue.component('read-image-file-component',
    require('./components/Items/ReadImageFileComponent.vue').default);

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








const app = new Vue({ el: '#app', });
