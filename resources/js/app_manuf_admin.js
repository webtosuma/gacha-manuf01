/*
|=============================================
| Manufacturer　 Admin コンポーネント
|=============================================
*/
import Vue from 'vue'

    // Vue.component('a-machine-prize-edit',
    // require('./components/admin/gacha/prize/edit/Index.vue').default);


    // 商品登録
    Vue.component('a-machine-prize-edit',
        require('./components/manuf_admin/gacha/prize/edit/Index.vue').default);

        // ランク
        Vue.component('a-machine-prize-gacharank-container',
        require('./components/manuf_admin/gacha/prize/edit/GachaRankContainer.vue').default);


        // ランク キリ番
        Vue.component('a-machine-prize-gacharank-kiri-container',
        require('./components/manuf_admin/gacha/prize/edit/GachaRankKiriContainer.vue').default);

        // ランク ゾロ目
        Vue.component('a-machine-prize-gacharank-zoro-container',
        require('./components/manuf_admin/gacha/prize/edit/GachaRankZoroContainer.vue').default);

        // ランク ピタリ賞
        Vue.component('a-machine-prize-gacharank-pita-container',
        require('./components/manuf_admin/gacha/prize/edit/GachaRankPitaContainer.vue').default);

        // ランク スライド表示商品
        Vue.component('a-machine-prize-gacharank-slide-container',
        require('./components/manuf_admin/gacha/prize/edit/GachaRankSlideContainer.vue').default);

        // 商品リスト
        Vue.component('a-machine-prize-prize-list',
        require('./components/manuf_admin/gacha/prize/edit/PrizeList.vue').default);

        // 商品リスト ピタリ賞
        Vue.component('a-machine-prize-prize-list-pita',
        require('./components/manuf_admin/gacha/prize/edit/PrizeListPita.vue').default);

            
export default Vue
