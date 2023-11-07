require('./bootstrap');

window.Vue = require('vue').default;


Vue.component('example-component',
require('./components/ExampleComponent.vue').default);


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
