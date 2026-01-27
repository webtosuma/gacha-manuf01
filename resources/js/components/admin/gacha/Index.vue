<template>
    <div class="">

        <loading-cover-component :loading="loading" />


        <!--新規作成-->
        <div class="position-fixed bottom-0 end-0 " style="z-index:200;">
            <a :href="r_create+'/'+inputs.category_code"
            class="d-flex align-items-center justify-content-center text-center
            text-white m-3 bg-primary rounded-pill shadow"
            style="height:8rem; width:8rem;">
                <div class="text-center" style="line-height:1.6rem;">
                    <div class="fs-1">+</div>
                    <div class="">新規作成</div>
                </div>
            </a>
        </div>



        <!--ソート-->
        <section class="mb-3">
            <div class="row g-1 gy-2 align-items-end">
                <!--カテゴリー選択-->
                <div class="col-12 col-md-4">
                    <div class="form-text">カテゴリー選択</div>
                    <select
                    v-model="inputs.category_code"
                    class="form-select"
                    name="category_code" >
                        <option value="">すべて</option>

                        <option v-for="( category, key ) in categories" :key="key"
                        :value="category.code_name">{{ category.name }}</option>
                    </select>
                </div>
                <!--タイトル検索-->
                <div class="col-12 col-md-4">
                    <div class="form-text">タイトル検索</div>
                    <div class="input-group">
                        <input v-model="inputs.title"
                        type="text" class="form-control"
                        placeholder="タイトル検索">
                    </div>
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
                <div class="col">
                    <div class="form-text">並び替え</div>
                    <select v-model="inputs.order" class="form-select">
                        <option v-for="(order, key) in orders" :key="key"
                        :value="order.key"
                        >{{ order.label }}</option>
                    </select>
                </div>

            </div>
        </section>



        <!--カード一覧-->
        <section class="row gy-5 py-3 overflow-hidden g-2">
            <div v-for="(gacha, key) in gachas" :key="key"  class="col-12 col-md-4 col-lg-3 ">
                <div class="position-relative">

                    <div class="">公開{{gacha.published_at_format}}</div>

                    <a :href="gacha.r_admin_show" :class="card_class" style="border-radius:1rem;">

                        <div class="d-flex gap-1 flex-wrap p-2" style="font-size:11px;">
                            <!--広告-->
                            <div v-if="gacha.sponsor_ad"
                            class="border border-danger text-danger px-3 rounded-pill"> 広告</div>
                            <!--ガチャの種類ラベル(Admin用)-->
                            <span class="border px-3 rounded-pill">{{ gacha.type_label_admin }}</span>
                            <!--ランクの指定-->
                            <span v-if="gacha.user_rank_label"
                            class="border px-3 rounded-pill">{{ gacha.user_rank_label }}会員</span>
                            <!--時間帯-->
                            <span class="border px-3 rounded-pill">{{ gacha.min_time +'〜'+ gacha.max_time }}</span>
                        </div>


                        <!--image-->
                        <u-gacha-image
                        :gacha_name            ="gacha.name"
                        :gacha_ratio           ="gacha.ratio"
                        :gacha_image_path      ="gacha.image_path"

                        :initial_time          ="gacha.i_time"
                        :limitted_i_time       ="gacha.limitted_i_time"
                        :published_at_format   ="gacha.published_at_format"
                        :remaining_count       ="gacha.remaining_count"
                        :add_chance_image_path ="gacha.add_chance_image_path"
                        :add_chance_count      ="gacha.add_chance_count"
                        :have_user_rank        ="gacha.have_user_rank"
                        :user_played_count     ="gacha.user_played_count"

                        :img_path_one_chance   ="gacha.img_path_one_chance "
                        :img_path_one_time     ="gacha.img_path_one_time"
                        :img_path_only_oneday  ="gacha.img_path_only_oneday"
                        :img_path_only_new_user="gacha.img_path_only_new_user"
                        :img_path_user_rank    ="gacha.img_path_user_rank"
                        />

                        <!--サブスクプラン-->
                        <div v-if="gacha.subscription_id"
                        class="bg-white fw-bold">『{{gacha.subscription.sub_label}}』専用</div>

                        <!--metter-->
                        <div class="">
                            <u-gacha-metter v-if="gacha.is_published && gacha.category.is_published"
                            :new_label_path="gacha.new_label_path"
                            :img_path_point="gacha.img_path_point"
                            :bg_color="gacha.type=='only_new_user' ? 'bg-success-subtle' : 'bg-white'"
                            :gacha_type="gacha.type"
                            :sponsor_ad="gacha.sponsor_ad"
                            :gacha_play_point="gacha.one_play_point"
                            :is_meter       ="gacha.is_meter"
                            :remaining_ratio="gacha.remaining_ratio"
                            :remaining_count="gacha.remaining_count"
                            :max_count      ="gacha.max_count"
                            />


                            <div v-else-if="gacha.published_at && gacha.category.is_published"
                            class="card-body bg-warning text-center text-white">
                                <h5 class="m-0">公開予約中</h5>
                                <div >{{gacha.published_at_format+'公開予定'}}</div>
                            </div>

                            <div v-else
                            class="card-body bg-secondary text-center text-white">
                                <h3>非公開</h3>
                                <div v-if="!gacha.category.is_published" class="text-danger">＊カテゴリー非公開</div>
                            </div>

                        </div>


                    </a>


                    <!--menu-->
                    <div class="dropdown position-absolute top-0 end-0" style="z-index:100;">
                        <button
                        class="btn border bg-white rounded-circle" type="button"
                        :id="'dropdownMenuButton'+gacha.id"
                        data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>

                        <ul class="dropdown-menu" :aria-labelledby="'dropdownMenuButton'+gacha.id"  style="z-index:100;">
                            <li><a class="dropdown-item"
                            :href=" gacha.r_admin_show "
                            >詳細情報を見る</a></li>

                            <li><a class="dropdown-item"
                            :href="gacha.r_admin_edit"
                            >編集する</a></li>

                            <li><form :action="gacha.r_admin_copy" method="POST">
                                <input type="hidden" name="_token" :value="token">
                                <button type="submit" class="dropdown-item"
                                >コピーする</button>
                            </form></li>

                            <li><button type="button" data-bs-toggle="modal"
                            :data-bs-target="'#deleteModal'+'delete'+gacha.id"
                            class="dropdown-item"
                            >削除する</button></li>
                        </ul>
                    </div>




                </div>
            </div>
        </section>




    <!--削除モーダル-->
    <div class="overflow-hidden" style="height: 0;">
        <form v-for="(gacha, key) in gachas" :key="key"
        :action="gacha.r_admin_destroy" method="post">
            <input type="hidden" name="_token" :value="token">
            <input type="hidden" name="_method" value="DELETE">

            <delete-modal-component
            :index_key="'delete'+gacha.id"
            icon="bi-trash"
            func_btn_type="submit"
            button_class="invisible">
                <div>
                    <span class="fw-bold">『{{gacha.name}}』</span>を削除します。
                    <br />よろしいですか？
                </div>
            </delete-modal-component>
        </form>
    </div>



        <!--もっと見る-->
       <div class="my-3">
            <button v-show="nextPageUrl"
            @click="getData(nextPageUrl)"
            type="button"
            class="btn btn-lg btn-light border"
            >もっと見る</button>
       </div>


    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:         { type: String, default: '' },
        r_api_list:    { type: String, default: '' },//api route
        r_create:       { type: String, default: '' },//route 新規作成
        category_code: { type: String, default: '' },//カテゴリーコード
    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */

    const categories  = ref([]);       /* カテゴリー一覧 */
    const gachas      = ref([]);       /* ガチャ一覧 */
    const orders      = ref([]);       /* 並び替え */
    const published_statuses = ref([]);/* 公開状態選択肢 */

    /* 入力値 */
    const inputs      = ref({
        _token: props.token,
        category_code:   props.category_code, //カテゴリーコード
        title:           '', //タイトル
        order:           'desc_published_at', //並び替え
        published_status:'', //公開状態
    });


    /*カードクラスｋ */
    const card_class  = ref(`
    card border-secondary border-0 shadow bg-white h-100
    text-dark text-center overflow-hidden text-decoration-none
    hover_anime
    `);


    /* 監視 */
    watch(inputs, () => getData(), { deep: true });


    /* 初回データ取得 */
    onMounted(() => {

        /* データ取得 */
        getData();

    });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {
        try {

            /*通信開始*/
            loading.value = true;
            const response = await axios.post(route, inputs.value);

            /*ページネーションの保存*/
            const paginate = response.data['gachas'];

            /*ガチャデータの保存*/
            gachas.value =
            route === props.r_api_list ? paginate.data : [...gachas.value, ...paginate.data];

            /*カテゴリーデータの保存*/
            categories.value          = response.data['categories'] ;
            /* 公開状態選択肢の保存 */
            published_statuses.value  = response.data['published_statuses'] ;
            /* 並び替え選択肢の保存 */
            orders.value              = response.data['orders'] ;

            /*次のページネーションURLの保存*/
            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

            loading.value = false;


        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };





</script>
