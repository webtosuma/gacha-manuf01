<template>
    <div class="">


        <loading-cover-component :loading="loading" />


        <!--送信データ-->
        <input v-for="( id,  key ) in ids" :key="key"
        type="hidden" :value="id" name="ids[]">


        <!--ボトムメニュー-->
        <div class="position-fixed bottom-0 end-0 w-100 pb-3 bg-dark text-white border"
        style="border-radius: 1rem 1rem 0 0; z-index:50;">
            <div class="container p-0">

                <div class="row justify-content-between align-items-center g-2 px-2">
                    <div class="col-auto">
                        <span>合計買取価格</span>
                    </div>
                    <div class="col-auto">
                        <span>¥</span>
                        <span class="fs-1 fw-bold">{{ totalAmont.toLocaleString() }}</span>
                    </div>
                </div>
                <div class="row justify-content-between align-items-center g-2 px-2">
                    <div class="col-auto">
                    </div>
                    <div class="col-auto">
                        <span>合計点数</span>
                        <span class="fs-5 fw-bold">{{ totalCount.toLocaleString() }}</span>
                        <span>点</span>
                    </div>
                </div>

                <!--BTN-->
                <div class="w-100 mt-3">
                    <div class="p-2 pt-0 col-md-8 mx-auto">

                        <button @click="goBack()"
                        type="button"
                        class="btn btn-light border rounded-pill w-100 fs-"
                        >買取表に戻る</button>

                    </div>
                </div>
            </div>
        </div>


        <div class="row g-3 gy- mx-auto" style="max-width:1000px;">

            <!-- side -->


            <!-- main -->
            <div class="col-12 col-lg- mx-auto">


                <div class="row gy-3 ">
                    <div v-for="(purchase, key) in purchases" :key="key"
                    class="col-12 col-md-6 col-lg-3">


                        <div class="row g-0">
                            <div class="col-4 col-lg-12"> 

                                <!--商品画像-->
                                <ratio-image-component
                                style_class="ratio ratio-3x4 rounded shiny"
                                :url=" purchase.prize.image_path " />

                            </div>
                            <div class="col">

                                <div class="bg-d text- px-2">
                                    {{ purchase.prize.name}}
                                </div>

                                <!--買取価格-->
                                <div class="px-2  fs-4">
                                    <div class="row g-1 align-items-center justify-content-">
                                        <span class="col-12" style="font-size:14px;">買取価格</span>
                                        <span class="col-auto">¥</span>
                                        <span class="col-auto">{{ purchase.price.toLocaleString() }}</span>
                                    </div>
                                </div>


                                <!--数量変更-->
                                <div class="p-3">
                                    <div class="input-group rounded-pill mb-2">

                                        <div class="form-control text-center " style="flex:2;">{{ purchase.count }}</div>

                                        <!-- リセット -->
                                        <button type="button" class="form-control"
                                        @click="countReset( key )"
                                        ><i class="bi bi-x"></i></button>
                                    </div>

                                    <div class="input-group rounded-pill mb-2">
                                        <!-- +1 -->
                                        <button type="button" class="form-control btn btn-dark"
                                        @click="countUpdate( key, 1 )"
                                        >+ 1</button>

                                        <!-- +3 -->
                                        <button type="button" class="form-control btn btn-dark"
                                        @click="countUpdate( key, 3 )"
                                        >+ 3</button>

                                        <!-- +10 -->
                                        <button type="button" class="form-control btn btn-dark"
                                        @click="countUpdate( key, 10 )"
                                        >+ 10</button>
                                    </div>

                                </div>


                            </div>
                        </div>


                    </div>
                </div>


                <div v-if="purchases.length<1"
                class="col-12 text-secondary bg-light-subtle
                p-3 fs-5 rounded-3 shadow
                ">*該当する商品がありません。</div>

                <div  v-show="nextPageUrl"  class="mt-3">
                    <a @click.prevent="getData( nextPageUrl )"
                    class="btn btn-light border w-100"
                    href="">もっと読み込む</a>
                </div>


            </div>



            <!-- Modal  -->
            <div class="">


                <!-- フォルダ削除Modal -->
                <div class="modal fade"
                :id="'deleteModal'" tabindex="-1"
                :aria-labelledby="'deleteModal'+'Label'" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fs-6" :id="'deleteModal'+'Label'">登録商品削除</h5>
                            </div>
                            <div class="modal-body">
                                選択した登録商品を全て削除します。<br>
                                よろしいですか？
                            </div>
                            <div class="modal-footer">
                                <button @click="changeBulk('delete')"
                                type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">削除</button>
                                <button type="button" class="btn border"  data-bs-dismiss="modal">キャンセル</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>


    </div>
</template>

<script setup>
    import { ref, computed, watch, onMounted, } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token: { type: String, default: '' },
        r_api_list:     { type: String, default: '' }, // 一覧表示
        r_api_update:   { type: String, default: '' }, // 非同期更新
        r_create:       { type: String, default: '' }, // 新規登録
        category_id:    { type: String, default: '' }, // カテゴリーID
        props_ids:      { type: String, default: '' }, // カテゴリーID
    });


    const loading       = ref(true);
    const purchases   = ref([]); /* ECストアー商品 */

    const categories  = ref([]);   /* カテゴリー一覧 */
    const published_statuses = ref([]);/* 公開状態選択肢 */
    const orders      = ref([]);   /* 並び替え */
    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */
    // const messages    = ref([]);   /* ポップアップメッセージ */
    // const edit        = ref(false);/* 編集中 */

    const totalCount  = ref( 0 );/* 合計数 */
    const totalAmont  = ref( 0 );/* 合計価格 */


    // チェック
    const ids          = ref([]);   //チェックボックスのID
    const allCheck     = ref(false);//全てチェック
    const disabled     = ref(true); //


    const inputs   = ref({
        _token: props.token,

        keyword: '',
        category_id: props.category_id,
        published_status: '',
        order: '',

        /* 一括処理 */
        purchase_ids: [],

        bulk: 'bulr', //一括削除

        /* 並び替え */
        order_points_redemption: '',
        order_price: '',
        order_count: '',

        /* ID選択(文字列) */
        ids : props.props_ids,

    });



    const selectBlucks = ref({ /* 一括処理セレクト */
        published_true:  'すべて公開に変更',
        published_false: 'すべて非公開に変更',
        // slide_true:  'すべてスライド表示に変更',
        // slide_false: 'すべてスライド非表示に変更',
    });


    /* [コンピューティッド]すべてチェック */
    const allChecked = computed({
        get: () => inputs.value.purchase_ids.length === purchases.value.length,
        set: (value) => {
            if (value) {
                // すべて選択
                inputs.value.purchase_ids = purchases.value.map(purchase => purchase.id);
            } else {
                // すべて解除
                inputs.value.purchase_ids = [];
            }
        }
    });

    /* 監視 */
    watch(() => inputs.value.keyword,          () => getData());
    watch(() => inputs.value.category_id,      () => getData());
    watch(() => inputs.value.published_status, () => getData());
    watch(() => inputs.value.order,            () => getData());
    watch(() => inputs.value.bulk,             () => getData());//一括処理


    /* 初回データ取得 */
    onMounted(() => {
        getData();  /* データ取得 */
        // loading.value = false;/* 読み込み */

    });


    /* データ取得 */
    const getData = (route = props.r_api_list) => {

        loading.value = true;/* 読み込み */

        axios.post(route, inputs.value)
        .then(response => {

            const paginate = response.data['purchases'];

            console.log(paginate);
            purchases.value =
            route === props.r_api_list ? paginate.data : [...purchases.value, ...paginate.data];

            /*カテゴリーデータの保存*/
            categories.value          = response.data['categories'] ;

            /* 公開状態選択肢の保存 */
            published_statuses.value  = response.data['published_statuses'] ;

            /* 並び替え選択肢の保存 */
            orders.value              = response.data['orders'] ;



            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

            if( nextPageUrl.value == null){
                getTotalCount();  //合計数の計算
                getTotalAmount(); //合計価格の計算
                loading.value = false;/* 読み込み */
            }else{
                /* 次の読み込み */
                getData(nextPageUrl.value);
            }

        })
        .catch(error => {
            console.error(error.response?.data);
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        });

    };



    /* キーワードの削除 */
    const deleteKeyword = () => {
        inputs.value.keyword = '';
        getData();
    };


    /** 全て選択をクリック */
    const changeAll = () => {
        const allIds = purchases.value.map(v => v.id);
        ids.value = allCheck.value ? allIds : [];
        disabled.value = !( ids.value.length > 0 );//選択なしのときは、disabled
    };

    /** 子チェックをクリック */
    const changeChildren = () => {
        const allIds = purchases.value.map(v => v.id);
        allCheck.value = ids.value.length === allIds.length;
        disabled.value = !( ids.value.length > 0 );//選択なしのときは、disabled
    };



    /**
     * 数量変更
     *
     * @param Integer key //store_keeps　キー
     * @param Integer add //加算・減算数
     * @return Void
     */
    const countUpdate = ( key, add ) => {
        purchases.value[key].count += add;//加算・減算

        getTotalCount();  //合計数の計算
        getTotalAmount(); //合計価格の計算
    }



    /**
     * 数量リセット
     *
     * @param Integer key //store_keeps　キー
     * @param Integer add //加算・減算数
     * @return Void
     */
    const countReset = ( key ) => {
        purchases.value[key].count = 0;

        getTotalCount();  //合計数の計算
        getTotalAmount(); //合計価格の計算
    }


    /**
     * 合計数の計算
     *
     * @return Void
     */
    const getTotalCount = () => {
        totalCount.value = purchases.value.reduce((sum, item) => sum + item.count, 0);
    }

    /**
     * 合計価格の計算
     *
     * @return Void
     */
    const getTotalAmount = () => {
        totalAmont.value = purchases.value.reduce((sum, item) => {
            return sum + (item.price * item.count);
        }, 0);
   }



   /* 前のページへ戻る */
   const goBack = () => { window.history.back(); }　

</script>
<!-- <style scoped>
    @keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px); /* 50px下から */
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
    }

    /* ポップアップアニメーション */
    .fade-in-message {
        opacity: 0; /* 初期状態を透明に */
        animation: fadeInUp .8s ease-out forwards;
    }
</style> -->
