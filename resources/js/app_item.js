/*
|=============================================
| アイテムコンポーネント
|=============================================
*/
import Vue from 'vue'

    /* コピー ボタン */
    Vue.component('coppy-button-component',
    require('./components/Items/CoppyButtonComponent.vue').default);

    /* disabled ボタン */
    Vue.component('disabled-button-component',
    require('./components/Items/DisabledButtonComponent.vue').default);

    /* disabled(ノーマルform用) ボタン */
    Vue.component('disabled-button',
    require('./components/Items/DisabledButton.vue').default);

    /* disabled(ノーマルform用)カバー ボタン */
    Vue.component('disabled-cover-button',
    require('./components/Items/DisabledCoverButton.vue').default);



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

    /* ローディングカバー */
    Vue.component('loading-cover-component',
    require('./components/Items/LoadingCoverComponent.vue').default);

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




export default Vue
