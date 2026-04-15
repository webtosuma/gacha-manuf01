require('./bootstrap');

window.Vue = require('vue').default;


Vue.component('example-component',
require('./components/ExampleComponent.vue').default);


/*
|=============================================
| ユーザーページ　コンポーネント
|=============================================
*/
    /* ログインフォーム TFA User・Admin共用 */
    Vue.component('login-form-tfa',
    require('./components/auth/LoginFormTfaComponent.vue').default);

    /* 会員登録フォーム */
    Vue.component('u-register-form',
    require('./components/auth/RegisterFormConpornent.vue').default);

    /* パスワード変更フォーム */
    Vue.component('u-reset-password-form',
    require('./components/auth/ResetPasswordFormConpornent.vue').default);


    /* お問い合わせフォーム */
    Vue.component('contact-form-component',
    require('./components/contact/FormComponent.vue').default);

    /* お知らせ 一覧 */
    Vue.component('u-infomation-list',
    require('./components/infomation/Index.vue').default);

    /* fincode支払いフォーム */
    Vue.component('u-fincode-payment-form',
    require('./components/fincode/PaymentFormComponent.vue').default);




/*
|=============================================
| ガチャ　コンポーネント
|=============================================
*/

    require('./app_gacha.js');


/*
|=============================================
| ECストアー　コンポーネント
|=============================================
*/

    require('./app_store.js');


/*
|=============================================
| Manufacturer　コンポーネント
|=============================================
*/

    require('./app_manuf.js');
    require('./app_manuf_admin.js');



/*
|=============================================
| サイト管理者ページ　コンポーネント
|=============================================
*/

    require('./app_admin.js');
    require('./app_admin_gacha.js');
    require('./app_admin_store.js');


/*
|=============================================
| アイテムコンポーネント
|=============================================
*/

    require('./app_item.js');


const app = new Vue({ el: '#app', });
