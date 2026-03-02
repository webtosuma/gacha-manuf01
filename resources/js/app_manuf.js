/*
|=============================================
| Manufacturer　コンポーネント
|=============================================
*/
import Vue from 'vue'

 
    /* ガチャ一覧 */
    Vue.component('u-manuf-gacha-list',
    require('./components/manuf/gacha/list/IndexComponent.vue').default);

        /* ガチャ カード */
        Vue.component('u-manuf-gacha-card',
        require('./components/manuf/gacha/list/CardComponent.vue').default);

        /* ガチャ マシーン */
        Vue.component('u-manuf-gacha-machine',
        require('./components/manuf/gacha/list/MachineComponent.vue').default);

        /* ガチャ 画像 */
        Vue.component('u-manuf-gacha-image',
        require('./components/manuf/gacha/list/ImageComponent.vue').default);

        /* ガチャ メーター */
        Vue.component('u-manuf-gacha-metter',
        require('./components/manuf/gacha/list/MetterComponent.vue').default);

        /* ガチャボタン */
        Vue.component('u-manuf-gacha-play-buttons',
        require('./components/manuf/gacha/list/PlayButtonsComponent.vue').default);


        /* モーダルカスタムボタンコンポーネント */
        Vue.component('u-manuf-gacha-custom-modal',
        require('./components/manuf/gacha/CustomModalComponent.vue').default);
    /*  */

export default Vue
