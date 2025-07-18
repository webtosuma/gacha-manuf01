/*
|=============================================
| サイト管理者ページ ECストアー　コンポーネント
|=============================================
*/
import Vue from 'vue'


    /* ECストアー商品 一覧 */
    Vue.component('a-store-item-list',
    require('./components/admin/store_item/ListComponent').default);

    /* ECストアー商品 ガチャ商品 一覧 */
    Vue.component('a-store-item-prize-list',
    require('./components/admin/store_item/PrizeListComponent').default);

    /* 発送履歴 */
    Vue.component('a-store-shipped-list',
    require('./components/admin/store_shipped/ListComponent').default);

    /* レポート */
    Vue.component('a-store-salesreport-list',
    require('./components/admin/store_sales_report/ListComponent').default);

    Vue.component('a-store-salesreport-chart',
    require('./components/admin/store_sales_report/Chart').default);
export default Vue
