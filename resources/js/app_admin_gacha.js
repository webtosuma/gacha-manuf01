/*
|=============================================
| サイト管理者ページ ガチャ　コンポーネント
|=============================================
*/
import Vue from 'vue'

    /* カテゴリー並び替え */
    Vue.component('a-category-change-order',
    require('./components/admin/category/ChangeOrder.vue').default);

    /* 商品管理 */
    Vue.component('a-prize-list',
    require('./components/admin/prize/Index.vue').default);

    /* チケット用商品 */
    Vue.component('a-ticket-store-list',
    require('./components/admin/ticket_store/Index.vue').default);

    /* ガチャ管理 */

        // ガチャ一覧
        Vue.component('a-gacha-list',
        require('./components/admin/gacha/Index.vue').default);

        // 商品登録
        Vue.component('a-gachaprize-edit',
        require('./components/admin/gacha/prize/edit/Index.vue').default);

        // ランク
        Vue.component('a-gachaprize-gacharank-container',
        require('./components/admin/gacha/prize/edit/GachaRankContainer.vue').default);

        // ランク キリ番
        Vue.component('a-gachaprize-gacharank-kiri-container',
        require('./components/admin/gacha/prize/edit/GachaRankKiriContainer.vue').default);

        // ランク ゾロ目
        Vue.component('a-gachaprize-gacharank-zoro-container',
        require('./components/admin/gacha/prize/edit/GachaRankZoroContainer.vue').default);

        // ランク ピタリ賞
        Vue.component('a-gachaprize-gacharank-pita-container',
        require('./components/admin/gacha/prize/edit/GachaRankPitaContainer.vue').default);

        // ランク スライド表示商品
        Vue.component('a-gachaprize-gacharank-slide-container',
        require('./components/admin/gacha/prize/edit/GachaRankSlideContainer.vue').default);

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


    /* ポイント売上(改正版)　レポート */
    Vue.component('a-pointsalesreport-list',
    require('./components/admin/point_sales_report/ListComponent').default);

        // 日別詳細
        Vue.component('a-pointsalesreport-daily',
        require('./components/admin/point_sales_report/DailyComponent').default);

        // グラフ
        Vue.component('a-pointsalesreport-chart',
        require('./components/admin/point_sales_report/Chart').default);



export default Vue
