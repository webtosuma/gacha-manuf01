/*
|=============================================
| ECストアー　コンポーネント
|=============================================
*/
import Vue from 'vue'


    /* 商品 一覧 */
    Vue.component('u-store-item-list',
    require('./components/store_item/ListComponent').default);

        // 商品画像
        Vue.component('u-store-item-image',
        require('./components/store_item/ImageComponent').default);

        // カートに入れるボタン
        Vue.component('u-store-item-keep-btn',
        require('./components/store_item/KeepBtnComponent.vue').default);

        // 検索キーワードの入力
        Vue.component('u-store-item-search-keyword',
        require('./components/store_item/SearchKeywordComponent').default);


    /* 買い物カート */
    Vue.component('u-store-keep-list',
    require('./components/store_keep/ListComponent').default);


    /* 購入手続き */
    Vue.component('u-store-purchase-appli',
    require('./components/store_purchase/AppliComponent').default);

        // 買い物カートより選択した商品
        Vue.component('u-store-purchase-appli-storekeeps',
        require('./components/store_purchase/AppliStorekeepsComponent').default);

    /* 購入した商品 */
    Vue.component('u-store-purchased-list',
    require('./components/store_purchased/ListComponent').default);

    /* 発送履歴 */
    Vue.component('u-store-shipped-list',
    require('./components/store_shipped/ListComponent').default);


    /*  */

export default Vue
