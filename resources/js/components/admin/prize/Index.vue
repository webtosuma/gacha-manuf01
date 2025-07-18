<template>
    <div class="">
        <loading-cover-component :loading="loading" />

        <div v-if="edit">

            <!--一括編集モード-->
            <edit-component
            @toggle-edit="toggleEdit"
            @edit-update="update"

            :token="token"
            :inputs="inputs"
            :categories="categories"
            :prop_prizes="prizes"
            :selects="selects"
            :change_ticket="change_ticket"
            ></edit-component>


        </div>
        <div v-else>
            <div class="row g-3 gy-">


                <!-- mainテーブル -->
                <div class="col order-lg-2">

                    <!--操作ボタン-->
                    <section class="mb-">
                        <div class="row g-3 align-items-center mb-2 px-2"  style="min-height:3rem;">
                            <div class="col-auto">
                                <label  class="form-check">
                                    <input v-model="allCheck"
                                    @change="changeAll()"
                                    class="form-check-input p-2" type="checkbox" >
                                    <div class="form-check-label ps-1">すべて選択</div>
                                </label>
                            </div>


                            <div v-if="inputs.prize_ids.length" class="col-auto">
                                チェックしたものを：
                            </div>
                            <!-- 一括 削除 -->
                            <div v-if="inputs.prize_ids.length " class="col-auto">
                                <button
                                data-bs-toggle="modal" :data-bs-target="'#deletePrizesModal'"
                                class="btn btn-sm border btn-light text-danger">すべて削除</button>
                            </div>
                        </div>

                    </section>

                    <!--テーブル-->
                    <section class="card card-body bg-white my- ">
                        <table class="table bg-white " style="min-width: 600px; font-size: 16px;">
                            <!--ヘッド（並べ替えボタン）-->
                            <thead>
                                <tr class="bg-white">
                                    <th style="width:1rem;"><!--チェックボックス-->
                                        <!-- <input v-model="allCheck" @change="changeAll()"
                                        class="form-check-input" type="checkbox"> -->
                                    </th>

                                    <th scope="col" style="width:4rem;">画像</th>

                                    <th scope="col"><a
                                    @click.prevent="changeOrder( 'order_code' )"
                                    href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                        <span>商品コード</span>
                                        <i v-if="inputs['order_code']!='desc'" class="bi bi-caret-up-fill"></i>
                                        <i v-if="inputs['order_code']!='asc'"  class="bi bi-caret-down-fill"></i>
                                    </a></th>

                                    <th scope="col"><a
                                    @click.prevent="changeOrder( 'order_name' )"
                                    href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                        <span>商品名</span>
                                        <i v-if="inputs['order_name']!='desc'" class="bi bi-caret-up-fill"></i>
                                        <i v-if="inputs['order_name']!='asc'"  class="bi bi-caret-down-fill"></i>
                                    </a></th>

                                    <th scope="col"><div class="row align-items-end g-0">
                                        <div class="col-auto">
                                            <a @click.prevent="changeOrder( 'order_rank_id' )"
                                            href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                                <span>評価ランク</span>
                                                <i v-if="inputs['order_rank_id']!='desc'" class="bi bi-caret-up-fill"></i>
                                                <i v-if="inputs['order_rank_id']!='asc'"  class="bi bi-caret-down-fill"></i>
                                            </a>

                                        </div>
                                    </div></th>

                                    <th scope="col"><a
                                    @click.prevent="changeOrder( 'order_point' )"
                                    href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                        <span>交換ポイント</span>
                                        <i v-if="inputs['order_point']!='desc'" class="bi bi-caret-up-fill"></i>
                                        <i v-if="inputs['order_point']!='asc'"  class="bi bi-caret-down-fill"></i>
                                    </a></th>

                                    <th v-if="change_ticket!=0"
                                    scope="col"><a
                                    @click.prevent="changeOrder( 'order_ticket' )"
                                    href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                        <span>交換チケット</span>
                                        <i v-if="inputs['order_ticket']!='desc'" class="bi bi-caret-up-fill"></i>
                                        <i v-if="inputs['order_ticket']!='asc'"  class="bi bi-caret-down-fill"></i>
                                    </a></th>

                                    <th scope="col"><a
                                    @click.prevent="changeOrder( 'updated_at' )"
                                    href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                        <span>更新</span>
                                        <i v-if="inputs['updated_at']!='desc'" class="bi bi-caret-up-fill"></i>
                                        <i v-if="inputs['updated_at']!='asc'"  class="bi bi-caret-down-fill"></i>
                                    </a></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(prize, key) in prizes" :key="key">
                                    <td>
                                        <input v-model="inputs.prize_ids" :value="prize.id"
                                        @change="changeChildren()"
                                        class="form-check-input" type="checkbox" >
                                    </td>
                                    <td scope="row">
                                        <!-- 画像 -->
                                        <div style="width:3rem;">
                                            <ratio-image-component
                                            style_class="ratio ratio-3x4 rounded"
                                            :url=" prize.image_path " />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="">{{ prize.code }}</div>

                                        <div class="" style="width:4rem;">
                                            <span v-if="prize.is_used"
                                            class="badge rounded-pill bg-success">{{ '利用中' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ prize.name }}

                                        <!-- 商品説明 -->
                                        <button v-if="prize.discription_text"
                                        class="btn btn-sm btn-dark rounded-pill"
                                        data-bs-toggle="modal"
                                        :data-bs-target="'#PrizeDiscriptionModal'+prize.id"
                                        ><i class="bi bi-search me-2"></i>商品説明</button>

                                        <u-prize-discription
                                        v-if="prize.discription_text "
                                        :id         ="prize.id"
                                        :name       ="prize.name"
                                        :image_path ="prize.image_path"
                                        :discription="prize.discription_text "
                                        size       ="2rem"
                                        src_icon   ="prize.discription_icon_path"
                                        no_btn     ="1"
                                        ></u-prize-discription>

                                    </td>

                                    <td>{{ prize.rank.name }}</td>

                                    <td>{{ prize.point.toLocaleString() }} pt</td>

                                    <td v-if="change_ticket!=0">{{ prize.ticket.toLocaleString() }} 枚</td>

                                    <td class="form-text"
                                    style="width:9rem;"
                                    >{{ formatDate( prize.updated_at ) }}</td>

                                    <td class="">
                                        <div class="d-flex gap-2 justify-content-end h-100">
                                            <!-- 編集 -->
                                            <a class="btn btn-sm btn-light border "
                                            :href="r_edit+'/'+prize.id"><i class="bi bi-pencil-fill"></i></a>

                                            <!-- コピーモーダル -->
                                            <delete-modal-component
                                            @parent-func="copy(prize.id)"
                                            :index_key="'copy'+prize.id"
                                            icon="bi-files" color="warning"
                                            button_class="btn btn-sm btn-light border">
                                                <div>この商品をコピーします。<br />よろしいですか？</div>
                                                <div class="form-text">商品コード：{{ prize.code }}</div>
                                                <div class="form-text">商品名：{{ prize.name }}</div>
                                            </delete-modal-component>


                                            <!--削除モーダル-->
                                            <delete-modal-component
                                            @parent-func="destory(prize.id)"
                                            :index_key="'delete'+prize.id"
                                            icon="bi-trash"
                                            button_class=" btn btn-sm btn-light border ">
                                                <div>この商品を削除します。<br />よろしいですか？</div>
                                                <div class="form-text">商品コード：{{ prize.code }}</div>
                                                <div class="form-text">商品名：{{ prize.name }}</div>
                                            </delete-modal-component>

                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="!loading && prizes.length==0">
                                    <td colspan="8" class="text-center text-secondary border-0 py-5">
                                        *商品の登録情報はありません。
                                    </td>
                                </tr>
                                <!--読み込み中-->
                                <tr v-if="loading">
                                    <td colspan="8" class="text-center text-secondary border-0 py-5">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div v-show="nextPageUrl" class="mt-3">
                            <a @click.prevent="getData( nextPageUrl )"
                            class="btn btn-light border"
                            href="">もっと読み込む</a>
                        </div>
                    </section>

                </div>


                <!-- side -->
                <div class="col-12 col-lg-auto order-lg-1">
                    <div class="position-sticky" style="top: 2rem; ">


                        <!--キーワード検索-->
                        <div class="mb-2">
                            <div class="form-text">キーワード検索</div>
                            <input v-model="inputs.key_words"
                            type="text" class="form-control form-control-lgg" placeholder="検索：商品名・商品コード名"
                            aria-label="Username" aria-describedby="basic-addon1" />
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
                        <!--評価ランク-->
                        <div class="mb-2">
                            <div class="form-text">評価ランク</div>
                            <select @change="getData()"
                            v-model="inputs.where_rank_id"
                            class="form-select">
                                <option value="">すべて</option>
                                <option v-for="(prize_rank, key) in selects.prize_ranks" :key="key"
                                :value="prize_rank.id">{{ prize_rank.name }}</option>
                            </select>
                        </div>
                        <!--交換ポイント-->
                        <div class="mb-2">
                            <div class="form-text">交換ポイント</div>
                            <div class="input-group mb-3">
                                <input
                                v-model="inputs.max_point"
                                type="number" class="form-control"
                                placeholder="最大pt" style="width:6rem;">
                                <input
                                v-model="inputs.min_point"
                                type="number" class="form-control"
                                placeholder="最低pt" style="width:6rem;">
                            </div>
                        </div>
                        <!--交換チケット-->
                        <div v-if="change_ticket!=0" class="mb-2">
                            <div class="form-text">交換チケット</div>
                            <div class="input-group mb-3">
                                <input
                                v-model="inputs.max_ticket"
                                type="number" class="form-control"
                                placeholder="最大枚数" style="width:6rem;">
                                <input
                                v-model="inputs.min_ticket"
                                type="number" class="form-control"
                                placeholder="最低枚数" style="width:6rem;">
                            </div>
                        </div>


                    <!--操作ボタン-->
                    <section class="mb-">
                        <div class="row flex-column g-2 ">
                            <div class="col-12">
                                <a :href="r_create+'?gacha_category_id='+inputs.category_id"
                                class="btn btn- btn-primary text-white px-4 shadow w-100"
                                >+ 商品の新規登録</a>
                            </div>
                            <div class="col-12">
                                <button @click="toggleEdit()"
                                class="btn btn-outline-warning w-100" type="button"
                                ><i class="bi bi-pencil-fill fs-"></i>一括編集</button>
                            </div>
                            <div class="col-12">
                                <form :action="r_download_csv" method="post">
                                    <input type="hidden" name="_token" :value="token">
                                    <input v-for="(value, name) in inputs" :key="name"
                                    type="hidden" :name="name" :value="value">

                                    <button class="btn btn- btn-light border  w-100  py-0" type="submit"
                                    ><i class="bi bi-filetype-csv fs-4"></i>ダウンロード</button>
                                </form>
                            </div>
                            <div class="col-12">
                                <a :href="r_import_csv"
                                class="btn btn-light border py-0 mb-3  w-100">
                                    <i data-v-3e26587a="" class="bi bi-filetype-csv fs-4"></i>
                                    インポート
                                </a>
                            </div>
                        </div>
                    </section>

                    </div>
                </div>



            </div>
        </div>



        <!-- フォルダ削除Modal -->
        <div class="modal fade"
        :id="'deletePrizesModal'" tabindex="-1"
        :aria-labelledby="'deletePrizesModal'+'Label'" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center fs-5">
                        選択した商品を全て削除します。<br>
                        よろしいですか？
                    </div>
                    <div class="modal-footer">
                        <div class="col">
                            <button @click="multiple_destroy()"
                            type="button" class="btn btn-danger text-white w-100" data-bs-dismiss="modal">削除</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn border w-100"  data-bs-dismiss="modal">キャンセル</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>
<script setup>
    import { ref, watch, reactive, onMounted } from 'vue';
    import axios from 'axios';

    import EditComponent from './edit.vue';

    const props = defineProps({
        token:{ type: String,  default: '', },
        r_api_prize:   { type: String,  default: '', },//商品
        r_api_category:{ type: String,  default: '', },//ガチャ カテゴリー
        r_api_update:  { type: String,  default: '', },//更新
        r_api_copy:    { type: String,  default: '', },//コピー
        r_api_destroy: { type: String,  default: '', },//削除
        r_api_multiple_destroy: { type: String,  default: '', },//複数削除

        r_create:      { type: String,  default: '', },
        r_edit:        { type: String,  default: '', },
        r_download_csv:{ type: String,  default: '', },//csvファイルダウンロードパス
        r_import_csv:  { type: String,  default: '', },//csvファイルインポートパス
        category_id:{ type: String,  default: '', },
        change_ticket:{ type: [String,Number],  default: 0, },//チケット交換があるか否か
    });

    const loading     = ref(true);
    const edit        = ref(false);
    const categories  = ref([]);
    const prizes      = ref([]);
    const selects     = reactive({ prize_ranks: {} });
    const keyWords    = ref('');//
    const allCheck    = ref(false);
    const nextPageUrl = ref('');  /* 次のデータの読み込みURL */

    const inputs = ref({
        _token: props.token,
        category_id: props.category_id || '',
        key_words:     '',
        order_code:    '',
        order_name:    '',
        order_rank_id: '',
        order_point:   '',
        order_ticket:  '',
        updated_at:    '',
        where_rank_id: '',
        max_point:     null,
        min_point:     null,
        max_ticket:    null,
        min_ticket:    null,
        prize_ids:     [],
    });

    /* 監視 */
    watch(() => inputs.value.category_id, () => getData());
    watch(() => inputs.value.key_words,   () => getData());
    watch(() => inputs.value.max_point,   () => getData());
    watch(() => inputs.value.min_point,   () => getData());
    watch(() => inputs.value.max_ticket,  () => getData());
    watch(() => inputs.value.min_ticket,  () => getData());

    onMounted(() => {

        getCategoryData();

    });


    /* カテゴリー　データ取得 */
    const getCategoryData = () => {
        axios.post(props.r_api_category, inputs).then((res) => {
            categories.value = res.data;
            setActiveCategory(props.category_id);
        })
        .catch(error => {
            alert('通信エラーが発生しました。')
            console.log( error.response.data );

        });
    };


    /* 商品データ取得 */
    const getData = (route = props.r_api_prize) => {
        loading.value = true;
        axios.post(route, inputs.value ).then((res) => {

            //ページネーションデータ
            const paginate = res.data.prizes;

            // 商品情報の登録（新規登録・ページネーション追加）
            prizes.value = route === props.r_api_prize ? paginate.data : [...prizes.value, ...paginate.data];

            // ランクの登録
            selects.prize_ranks = res.data.prize_ranks;
            loading.value = false;

            /* 次のデータの読み込み */
            // if (paginate.current_page !== paginate.last_page) {
            //     getData(paginate.next_page_url);
            // }

            loading.value = false;/* 読み込み */

            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

        })
        .catch(error => {
            alert('通信エラーが発生しました。')
            console.log( error.response.data );

        });
    };


    /** 更新 */
    const update = (prize) => {
        axios.patch(`${props.r_api_update}/${prize.id}`, { _token: props.token, ...prize })
        .then(() => {})
        .catch(error => {
            alert('通信エラーが発生しました。')
            console.log( error.response.data );
        });
    };


    /** コピー */
    const copy = (id) => {
        axios.post(`${props.r_api_copy}/${id}`, { _token: props.token })
        .then(() => getData())
        .catch(error => {
            alert('通信エラーが発生しました。')
            console.log( error.response.data );
        });
    };


    /** 削除 */
    const destory = (id) => {
        axios.delete(`${props.r_api_destroy}/${id}`, { data: { _token: props.token } })
        .then(() => getData())
        .catch(error => {
            alert('通信エラーが発生しました。')
            console.log( error.response.data );
        });
    };


    /** 複数削除 */
    const multiple_destroy = () => {
    axios.patch(props.r_api_multiple_destroy, inputs.value)
        .then((res) => {
            // console.log(res);
            inputs.value.prize_ids = [];
            getData();
        })
        .catch(error => {
            alert('通信エラーが発生しました。')
            console.log( error.response.data );
        });
    };


    /** キーワード検索 */
    // const changeKeyWord = () => {
    //     inputs.key_words = keyWords.value;
    //     getData();
    // };


    /** アクティブなカテゴリーのセット */
    const setActiveCategory = (category_id) => {
        inputs.key_words = '';
        keyWords.value = '';
        inputs.category_id = category_id;
        getData();
    };


    /** 並び替え */
    const changeOrder = (key) => {
        const order = inputs.value[key];
        inputs.value[key] = order === '' ? 'asc' : order === 'asc' ? 'desc' : '';
        getData();
    };


    /** 全て選択をクリック */
    const changeAll = () => {
        const ids = prizes.value.map(p => p.id);
        inputs.value.prize_ids = allCheck.value ? ids : [];
    };


    /** 子チェックをクリック */
    const changeChildren = () => {
        const ids = prizes.value.map(p => p.id);
        allCheck.value = inputs.value.prize_ids.length === ids.length;
    };


    /** 編集モード切り替え */
    const toggleEdit = () => {
        getData();
        edit.value = !edit.value;
    };


    /** 日付データをテクスト変換  */
    const formatDate = (input) => {
        const date = new Date(input);
        return `${date.getFullYear()}/${String(date.getMonth() + 1).padStart(2, '0')}/${String(date.getDate()).padStart(2, '0')} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}:${String(date.getSeconds()).padStart(2, '0')}`;
    };


</script>
<style scoped>
    th, td {
        background-color: #fff;
    }
</style>
