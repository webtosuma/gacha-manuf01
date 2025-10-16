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


            <!-- main -->
            <div class="col order-lg-2">

                <!--header menu-->
                <div v-if="edit" class="mb-1">
                    <div class="row g-3 align-items-center justify-content-between px-2"  style="min-height:3rem;">
                        <div class="col">
                            <label  class="form-check">
                                <input v-model="allChecked" class="form-check-input p-2 mt-0" type="checkbox"
                                @click="toggleAllChecks">
                                <div class="form-check-label">すべて</div>
                            </label>
                        </div>


                        <div v-if="inputs.purchase_ids.length" class="col-auto">
                            チェックしたものを：
                        </div>
                        <!--一括 対応切替-->
                        <div v-if="inputs.purchase_ids.length" class="col-auto">
                            <div class="input-group">
                                <select v-model="inputs.bulk"
                                class="form-select form-select-sm">
                                    <option value="">選択してください</option>
                                    <option v-for="(label, value) in selectBlucks" :key="value"
                                    :value="value"
                                    >{{ label }}</option>
                                </select>
                            </div>
                        </div>
                        <!--一括 削除-->
                        <div v-if="inputs.purchase_ids.length " class="col-auto">
                            <button
                            data-bs-toggle="modal" :data-bs-target="'#deleteModal'"
                            class="btn btn-sm border btn-light text-danger">すべて削除</button>
                        </div>
                    </div>
                </div>

                <!--テーブル-->
                <section class="card card-body bg-white w-100"
                :class="{'border-warning border-3':edit}"
                >
                    <table class="table bg-white " style="min-width: 600px; ">
                        <thead class="text-center">
                            <tr class="bg-white">
                                <th v-if="edit"><!--checkbox--></th>
                                <th scope="col">
                                    <span>公開</span>
                                </th>

                                <th scope="col" colspan="1"></th>

                                <th scope="col" colspan="1" class="text-start">商品</th>

                                <th scope="col"><a
                                @click.prevent="changeOrder( 'order_price' )"
                                href="#" class="btn btn-sm w-100 fw-bold fs-6 p-0">
                                    <span>買取価格(税込)</span>
                                    <i v-if="inputs['order_price']!='asc'"  class="bi bi-caret-up-fill"></i>
                                    <i v-if="inputs['order_price']!='desc'" class="bi bi-caret-down-fill"></i>
                                </a></th>

                            </tr>
                        </thead>

                        <tbody class="text-center">
                            <tr v-for="(purchase, key) in purchases" :key="key">

                                <!--チェックボックス-->
                                <td v-if="edit" style="width:2rem;">
                                    <input v-model="inputs.purchase_ids"
                                    :value="purchase.id"
                                    class="form-check-input p-2"
                                    type="checkbox" >
                                </td>


                                <!--公開-->
                                <td class="text- start" style="width:10rem;">


                                    <div v-if="edit"
                                    class="form-check form-switch ms-4">
                                        <input v-model="purchase.is_published"
                                        @change="update(purchase)"
                                        class="form-check-input" type="checkbox">
                                    </div>

                                    <div v-else >

                                        <span v-if="purchase.is_published"
                                        class="badge rounded-pill bg-success">{{ '公開中' }}</span>
                                        <span v-else-if="!purchase.is_published && purchase.published_at"
                                        class="badge rounded-pill bg-warning">{{ '公開予約' }}</span>
                                        <span v-else
                                        class="badge rounded-pill bg-danger">{{ '非公開' }}</span>

                                        <!-- 公開日 -->
                                        <div class="form-text">{{ formatAt(purchase.published_at) }}</div>

                                        <!-- 購入数 -->
                                        <!-- <div class="form-text">
                                            <i class="bi bi-bag-check me-2"></i>{{ purchase.purchased_count.toLocaleString() }}
                                        </div> -->

                                        <!-- 表示数 -->
                                        <!-- <div class="form-text">
                                            <i class="bi bi-eye me-2"></i>{{ purchase.showed_count.toLocaleString() }}
                                        </div> -->

                                    </div>


                                </td>
                                <!--画像-->
                                <td style="width:6rem;">

                                    <ratio-image-component
                                    style_class="ratio ratio-3x4 rounded"
                                    :url=" purchase.prize.image_path " />

                                </td>
                                <!--商品名-->
                                <td class="text-start">
                                    <div class="border rounded-pill form-text d-inline-block px-2">{{ purchase.prize.category.name }}</div>
                                    <div class="fw-bold mt-2">{{ purchase.prize.name }}</div>

                                    <movie-modal-component
                                    v-if="purchase.movie_path"
                                    :id   ="purchase.id+'-movie'"
                                    :title="purchase.name"
                                    :src  ="purchase.movie_path ?? purchase.youtube_url "
                                    btn_label="動画再生"
                                    max_width="400px"
                                    ></movie-modal-component>

                                </td>

                                <!--買取価格-->
                                <td>
                                    <div class="row g-1 align-items-center justify-content-center">
                                        <div class="col" v-if="edit"  style="width:6rem;" >
                                            <input v-model="purchase.price"
                                            @input="update(purchase)"
                                            type="number" class="form-control text-end" min="0">
                                        </div>
                                        <div class="col-auto" v-else>{{ purchase.price.toLocaleString() }}</div>
                                        <div class="col-auto">円(税込)</div>
                                    </div>
                                </td>


                            </tr>
                        </tbody>

                        <tfoot v-show="nextPageUrl"><tr><td colspan="7" class="border-0">
                            <div  class="mt-3">
                                <a @click.prevent="getData( nextPageUrl )"
                                class="btn btn-light border"
                                href="">もっと読み込む</a>
                            </div>
                        </td></tr></tfoot>
                    </table>
                </section>
            </div>



            <!-- side -->
            <div class="col-12 col-lg-auto order-lg-1">
                <div class="position-sticky" style="top: 2rem; ">


                    <!--新規登録-->
                    <div class="d-flex flex-md-column gap-3 mb-3 px-3">
                        <a :href="r_create+'?category_id='+inputs.category_id"
                        class="btn btn-primary text-white"
                        ><i class="bi bi-plus-lg me-2 "></i>ガチャ商品から登録</a>

                        <button
                        @click="cahngeEdit"
                        :class="edit ? 'btn-warning' : 'btn-outline-warning'"
                        class="btn "><i class="bi bi-pencil-fill fs-"></i>
                        {{edit ? '一括編編集終了' : '一括編集'}}</button>

                    </div>


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


                    <!--公開状態-->
                    <div class="col">
                        <div class="form-text">公開状態</div>
                        <select v-model="inputs.published_status" class="form-select">
                            <option value="">すべて</option>

                            <option v-for="(published_status, key) in published_statuses" :key="key"
                            :value="published_status.key"
                            >{{ published_status.label }}</option>
                        </select>
                    </div>


                    <!--並び替え-->
                    <!-- <div class="col">
                        <div class="form-text">並び替え</div>
                        <select v-model="inputs.order" class="form-select">
                            <option value="">すべて</option>

                            <option v-for="(order, key) in orders" :key="key"
                            :value="order.key"
                            >{{ order.label }}</option>
                        </select>
                    </div> -->



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
