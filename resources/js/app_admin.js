/*
|=============================================
| サイト管理者ページ　コンポーネント
|=============================================
*/
import Vue from 'vue'


    /* お知らせ */
    Vue.component('a-infomation-list',
    require('./components/admin/infomation/Index.vue').default);

        // メール一括送信
        Vue.component('a-infomation-sendemail',
        require('./components/admin/infomation/Sendemail.vue').default);


    /* 操作履歴 */
    Vue.component('a-log-list',
    require('./components/admin/log/Index.vue').default);

    /* アクセスログ */
    Vue.component('a-access-log-list',
    require('./components/admin/access_log/Index.vue').default);


    /* 登録ユーザー一覧 */
    Vue.component('a-user-list',
    require('./components/admin/user/ListComponent.vue').default);

        // 印刷用テーブル表示
        Vue.component('a-user-table',
        require('./components/admin/user/TableComponent.vue').default);

        // 期限切れポイントのリセット
        Vue.component('a-user-deadlin-point-reset',
        require('./components/admin/user/point/DeadlinePointResetComponent.vue').default);

        // 期限切れ商品のリセット
        Vue.component('a-user-deadlin-prize-change',
        require('./components/admin/user/user_prize/DeadlinePrizeChangeComponent.vue').default);


    /* お問い合わせ一覧 */
    Vue.component('contact-list-component',
    require('./components/admin/contact/ListComponent.vue').default);


    /* クーポン */
    Vue.component('a-coupon-list',
    require('./components/admin/coupon/ListComponent.vue').default);

        // サービスの編集
        Vue.component('a-coupon-edit-servise',
        require('./components/admin/coupon/EditServiceComponent.vue').default);


    /*  */
export default Vue
