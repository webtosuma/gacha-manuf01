<template>
    <div class="">
        <loading-cover-component :loading="loading" />


        <!-- トーストポップアップ -->
        <div id="toast_container" class="position-fixed bottom-0 end-0 p-2" style="z-index:10;">
            <div v-for="(message, key) in messages" :key="key"
            class="toast fade show mb-1 fade-in-message" role="alert" aria-live="assertive" aria-atomic="true" >
                <div class="toast-header bg-dark text-white">
                    <strong class="me-auto">{{ message }}</strong>
                    <button type="button" class="btn px-1 py-0 text-white fs-5" data-bs-dismiss="toast"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
        </div>




        <div class="row g-3 gy-">

            <!-- side -->
            <div class="col-12 col-lg-auto order-lg-2">
                <div class="position-sticky" style="top: 5rem; ">


                    <!--キーワード検索-->
                    <div class="input-group mb-3">
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
                        class="form-select"
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


                <div class="row gy-3">
                    <div v-for="(purchase, key) in purchases" :key="key"
                    class="col-4 col-md-3 col-lg-2">


                        <!--商品画像-->
                        <div class="position-relative">

                            <ratio-image-component
                            style_class="ratio ratio-3x4 rounded shiny"
                            :url=" purchase.prize.image_path " />

                            <!--説明モーダル　ボタン-->
                            <div v-if="purchase.prize.discription_text"
                            class="position-absolute w-100 text-end"
                            style="z-index:3; top:-5%; left:5%;">
                                <img v-if="no_btn!=1"
                                :src="purchase.prize.discription_icon_path"
                                alt="商品説明ボタン"
                                class="btn btn-dark p-0 rounded-circle shadow"
                                style="width:3rem;"
                                data-bs-toggle="modal"
                                :data-bs-target="'#PrizeDiscriptionModal'+purchase.id"
                                >
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



                        <div class="text-center">
                            {{ purchase.prize.name}}
                        </div>


                        <!--買取価格-->
                        <div class="row g-1 align-items-center justify-content-end
                        bg-dark text-white px-2  fs-5">
                            <span class="col-12" style="font-size:11px;">買取価格</span>
                            <span class="col-auto">¥</span>
                            <span class="col-auto">{{ purchase.price.toLocaleString() }}</span>
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


    /* 非同期更新 */
    const cahngeEdit = ()=>{ edit.value = !edit.value };
    const update = (purchase) => {

        console.log(purchase);

        const route = props.r_api_update
        axios.patch(route, purchase)
        .then(response => {
            console.log(response);
        })
        .catch(error => {
            console.error(error.response?.data);
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        });
    };


    /* 一括処理(削除) */
    const changeBulk = (key) => {
        inputs.value.bulk = key;
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


    /* すべてチェック */
    const toggleAllChecks = () => {
        if (allChecked.value) {
            inputs.value.purchase_ids = [];
        } else {
            inputs.value.purchase_ids = purchases.value.map(purchase => purchase.id);
        }
    };


    /* キーワードの削除 */
    const deleteKeyword = () => {
        inputs.value.keyword = '';
        getData();
    };


    /** 並び替え */
    const changeOrder = (key) => {
        const order = inputs.value[key];

        switch (order) {
            case 'asc':  inputs.value[key]=null;   break;
            case 'desc': inputs.value[key]='asc';  break;
            default:     inputs.value[key]='desc'; break;
        }

        getData(); /* データ取得 */
    };


    /* 日時変換 */
    const formatAt = (inputString) => {
        if(!inputString){return `----/--/--`;}

        const date = new Date(inputString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}/${month}/${day} ${hours}:${minutes}`;
    };



</script>
<style scoped>
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
</style>
