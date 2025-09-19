<template>
    <div class="">
        <loading-cover-component :loading="loading" />

        <!--ボトムメニュー-->
        <div class="position-fixed bottom-0 end-0 w-100 pb-3 bg-dark text-white border"
        style="border-radius: 1rem 1rem 0 0; z-index:50;">
            <div class="container p-0" style="max-width:900px;">

                <div class="row justify-content-between align-items-center g-2 px-2">

                    <!--すべて選択-->
                    <div class="col-auto">
                        <label class="form-check" style="cursor:pointer;">
                            <input v-model="allCheck" @change="changeAll()"
                            class="form-check-input" type="checkbox">
                            <span class="form-check-label fs-">
                                全て選択
                            </span>
                        </label>
                    </div>
                    <div class="col-auto">
                        選択中
                        <span class="fs-2 fw-bold">{{ ids.length.toLocaleString() }}</span>
                    </div>

                    <div class="col-12">
                        ＊選択した商品から、買取金額の査定ができます。
                    </div>
                </div>

                <!--BTN-->
                <div class="w-100 overflow-auto">
                    <div class="p-2 pt-0">

                        <button type="button" :disabled="disabled"
                        data-bs-toggle="modal" data-bs-target="#exchangeModal"
                        class="btn py-md-3 btn-primary text-white rounded-pill w-100 fs-5"
                        >買取金額を査定する</button>

                    </div>
                </div>
            </div>
        </div>


        <div class="row g-3 gy-">

            <!-- side -->
            <div class="col-12 col-lg-auto order-lg-2">
                <div class="position-sticky" style="top: 2rem; ">


                    <!--キーワード検索-->
                    <div class="input-group input-group- mb-3">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text"
                        v-model="inputs.keyword"
                        class="form-control"
                        placeholder="商品名">

                        <span
                        @click="deleteKeyword()"
                        class="btn btn-light border"><i class="bi bi-x"></i></span>
                    </div>


                    <!--カテゴリー選択-->
                    <div class="mb-2">
                        <div class="form-text">カテゴリー選択</div>
                        <select
                        v-model="inputs.category_id"
                        class="form-select form-select-"
                        >
                            <option value="">すべて</option>

                            <option v-for="( category, key ) in categories" :key="key"
                            :value="category.id">{{ category.name }}</option>
                        </select>
                    </div>


                </div>
            </div>



            <!-- main -->
            <div class="col-12 col-lg order-lg-1">


                <div class="row gy-3 gx-1">
                    <div v-for="(purchase, key) in purchases" :key="key"
                    class="col-3 col-md-3 col-lg-3">
                    <!-- class="col-4 col-md-3 col-lg-2" -->


                        <!--商品画像-->
                        <div class="p-2">
                            <div class="position-relative">

                                <!--商品画像-->
                                <ratio-image-component
                                style_class="ratio ratio-3x4 rounded shiny"
                                :url=" purchase.prize.image_path " />

                                <!--チェックボックス-->
                                <div class=" p-1
                                position-absolute top-0 start-0"
                                style="z-index:5">

                                    <!--mobile-->
                                    <input @change="changeChildren()"
                                    v-model="ids" :value="purchase.id"
                                    class=" d-md-none
                                    form-check-input float-xl-none m-0 rounded-pill"
                                    style="width:1.6em; height:1.6em;"
                                    type="checkbox" name="purchase_ids[]" >

                                    <!--PC-->
                                    <input @change="changeChildren()"
                                    v-model="ids" :value="purchase.id"
                                    class=" d-none d-md-block
                                    form-check-input float-xl-none m-0 rounded-pill"
                                    style="width:2em; height:2em;"
                                    type="checkbox" name="purchase_ids[]" >

                                </div>


                                <!--説明モーダル　ボタン-->
                                <div v-if="purchase.prize.discription_text"
                                class=" p-1
                                position-absolute top-0 end-0"
                                style="z-index:6;">

                                    <!--mobile-->
                                    <img :src="purchase.prize.discription_icon_path"
                                    alt="商品説明ボタン"
                                    class=" d-md-none
                                    btn btn-dark p-0 rounded-circle shadow"
                                    style="width:1.6rem;"
                                    data-bs-toggle="modal"
                                    :data-bs-target="'#PrizeDiscriptionModal'+purchase.id"
                                    >

                                    <!--PC-->
                                    <img :src="purchase.prize.discription_icon_path"
                                    alt="商品説明ボタン"
                                    class=" d-none d-md-block
                                    btn btn-dark p-0 rounded-circle shadow"
                                    style="width:2rem;"
                                    data-bs-toggle="modal"
                                    :data-bs-target="'#PrizeDiscriptionModal'+purchase.id"
                                    >
                                </div>
                            </div>
                        </div>

                        <!--商品説明モーダル-->
                        <div v-if="purchase.prize.discription_text">
                            <u-prize-discription
                            :id         ="purchase.id"
                            :name       ="purchase.prize.name"
                            :image_path ="purchase.prize.image_path"
                            :discription="purchase.prize.discription_text"
                            size       ="2rem"
                            :src_icon   ="purchase.prize.discription_icon_path"
                            no_btn     ="1"
                            ></u-prize-discription>
                        </div>



                        <div class="bg-dark text-white px-2  d-none d-md-block">
                            {{ purchase.prize.name}}
                        </div>


                        <!--買取価格 mobile-->
                        <div class="d-md-none bg-dark text-white px-2  fs-">
                            <div class="row g-1 align-items-center justify-content-end"
                            style="font-size:14px;">
                                <span class="col-12" style="font-size:11px;">買取価格</span>
                                <span class="col-auto">¥</span>
                                <span class="col-auto">{{ purchase.price.toLocaleString() }}</span>
                            </div>
                        </div>
                        <!--買取価格 pc-->
                        <div class="d-none d-md-block bg-dark text-white px-2  fs-5">
                            <div class="row g-1 align-items-center justify-content-end">
                                <span class="col-12" style="font-size:14px;">買取価格</span>
                                <span class="col-auto">¥</span>
                                <span class="col-auto">{{ purchase.price.toLocaleString() }}</span>
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
    });


    const loading       = ref(true);
    const purchases   = ref([]); /* ECストアー商品 */

    const categories  = ref([]);   /* カテゴリー一覧 */
    const published_statuses = ref([]);/* 公開状態選択肢 */
    const orders      = ref([]);   /* 並び替え */
    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */
    const messages    = ref([]);   /* ポップアップメッセージ */
    const edit        = ref(false);/* 編集中 */

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


            loading.value = false;/* 読み込み */

            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

            resetBulc();/* 一括処理パラメーターのリセット */
        })
        .catch(error => {
            console.error(error.response?.data);
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        });

    };







    /* 一括処理パラメーターのリセット */
    const resetBulc = () => {

        inputs.value.purchase_ids = [];

        // ポップアップメッセージ
        if(inputs.value.bulk=='delete'){
            messages.value.push('選択した商品を、一括削除しました。');
        }
        // 公開に変更
        if(inputs.value.bulk=='published_true'){
            messages.value.push('選択した商品を、すべて公開に変更しました。');
        }
        // 非公開に変更
        if(inputs.value.bulk=='published_false'){
            messages.value.push('選択した商品を、すべて非公開に変更しました。');
        }
        // スライド表示に変更
        if(inputs.value.bulk=='slide_true'){
            messages.value.push('選択した商品を、すべてスライド表示に変更しました。');
        }
        // スライド非表示に変更
        if(inputs.value.bulk=='slide_false'){
            messages.value.push('選択した商品を、すべてスライド非表示に変更しました。');
        }


        inputs.value.bulk = '';//一括パラメータのリセット
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
