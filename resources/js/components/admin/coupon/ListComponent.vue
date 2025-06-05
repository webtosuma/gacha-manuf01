<template>
    <div>
        <loading-cover-component :loading="loading" />


        <div class="row g-3 gy-">

            <!-- side -->
            <div class="col-12 col-lg-auto">
                <div class="position-sticky" style="top: 2rem; ">

                    <div class="row g-3 mb-2">
                        <div class="col">
                            <a :href="r_create"
                            class="btn btn-primary text-white shadow w-100">
                            <i class="bi bi-plus-lg"></i>
                            {{'新規登録'}}
                            </a>
                        </div>
                        <div class="col">
                            <a :href="r_history"
                            class="btn btn-light border w-100">
                            {{'クーポン履歴'}}
                            </a>
                        </div>
                    </div>

                    <div class="row flex-column g-2 mb-2">

                        <!--キーワード検索-->
                        {{ inputs.keyword }}
                        <div class="col input-group mb-3">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text"
                            v-model="inputs.keyword"
                            class="form-control"
                            placeholder="タイトル・クーポンコード">

                            <span
                            @click="deleteKeyword()"
                            class="btn btn-light border"><i class="bi bi-x"></i></span>
                        </div>
                        <!--クーポンコード-->
                        <div class="col">
                            <h6>クーポンコード</h6>
                            <select
                            v-model="inputs.is_use_code"
                            class="form-select">
                                <option value="all">選択してください</option>
                                <option v-for="(label ,value) in selects.is_use_codes" :key="value"
                                :value="value"
                                >{{ label }}</option>
                            </select>
                        </div>
                        <!--サービス-->
                        <div class="col">
                            <h6>プレゼント内容</h6>
                            <select
                            v-model="inputs.service"
                            class="form-select">
                                <option v-for="(label ,value) in selects.services" :key="value"
                                :value="value"
                                >{{ label }}</option>
                            </select>
                        </div>
                        <!--利用回数制限の種類-->
                        <div class="col">
                            <h6>利用回数制限の種類</h6>
                            <select
                            v-model="inputs.user_type"
                            class="form-select">
                                <option value="all">選択してください</option>
                                <option v-for="(label ,value) in selects.user_types" :key="value"
                                :value="value"
                                >{{ label }}</option>
                            </select>
                        </div>
                        <!--有効期限-->
                        <div class="col">
                            <h6>有効期限</h6>
                            <select
                            v-model="inputs.is_expiration"
                            class="form-select">
                                <option value="all">選択してください</option>
                                <option v-for="(label ,value) in selects.is_expirations" :key="value"
                                :value="value"
                                >{{ label }}</option>
                            </select>
                        </div>
                        <!--公開設定-->
                        <div class="col">
                            <h6>公開設定</h6>
                            <select
                            v-model="inputs.is_published"
                            class="form-select">
                                <option value="all">選択してください</option>
                                <option v-for="(label ,value) in selects.is_publisheds" :key="value"
                                :value="value"
                                >{{ label }}</option>
                            </select>
                        </div>


                    </div>
                </div>
            </div>

            <!-- main -->
            <div class="col">
                <div class="list-group ">


                    <div v-if="!coupons.length" class="list-group-item bg-white border-0 py-5">
                        * クーポンはありません
                    </div>


                    <div v-for="(coupon,key) in coupons" :key="key"
                    class="list-group-item bg-white border-0">
                        <hr class="">
                        <div class="row align-items-  py-2 g-1">

                            <!--image-->
                            <div class="col-auto pe-3" style="width: 10rem">
                                <div class="position-relative">
                                    <div :class="card_ration"
                                    class="ratio bg-body rounded border
                                    d-flex align-items-center justify-content-center"></div>

                                    <div class="w-50 position-absolute top-50 start-50 translate-middle">

                                        <ratio-image-component
                                        :url="coupon.image_path"
                                        :style_class=" 'ratio '+ (coupon.prize ? 'ratio-3x4' : 'ratio-1x1' )"
                                        ></ratio-image-component>

                                    </div>
                                </div>
                            </div>
                            <!--body-->
                            <div class="col pe-3">

                                <!--公開状態-->
                                <div class="d-flex align-items-center gap-2 mb-2 ">

                                    <div class="">
                                        <!--未公開-->
                                        <span v-if="coupon.published_state==0" class="badge rounded-pill bg-danger" >{{ '未公開' }}</span>
                                        <!--公開-->
                                        <span v-if="coupon.published_state==1" class="badge rounded-pill bg-success">{{ '公開中' }}</span>
                                        <!--公開予約-->
                                        <span v-if="coupon.published_state==2"   class="badge rounded-pill bg-warning">{{ '予約中' }}</span>
                                    </div>

                                    <span class="form-text">{{coupon.published_at_format??'--.--.--'}}</span>

                                    <div v-if="coupon.is_new" class="text-danger">NEW</div>

                                </div>

                                <!--タイトル-->
                                <h5 class="fw-bold">{{coupon.title}}</h5>

                                <!--共通クーポンコード-->
                                <div v-if="coupon.is_use_code" class="my-2">
                                    <div>共通クーポンコード</div>
                                    <div class="col-">
                                        <coppy-button-component :copy_word="coupon.code" />
                                    </div>
                                </div>
                                <div v-else class="form-text">*クーポンコードなし</div>

                                <!--複数コード-->
                                <div v-if="coupon.children.length" class="dropdown">
                                    <button class="btn btn-light border dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false"
                                    >一回限定コード</button>

                                    <ul class="dropdown-menu overflow-auto" style="max-height:50vh;">
                                        <li v-for="( coupon_child, key ) in coupon.children" :key="key" class="dropdown-item">

                                            <div v-if="coupon_child.is_done"  class="input-group">
                                                <input :value="coupon_child.code" type="text" class="form-control" disabled />
                                                <span class="input-group-text text-danger" >利用済</span>
                                            </div>

                                            <!-- <div v-if="coupon_child.is_done" class="border rounded px-2">{{ coupon_child.code }}</div> -->
                                            <coppy-button-component v-else :copy_word="coupon_child.code" />
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-md">

                                <!--説明文-->
                                <div class="my-2">{{coupon.discription_text}}</div>

                                <!--利用回数-->
                                <div v-if="coupon.count>0" class="my-2">

                                    <!--先着-->
                                    <div v-if="coupon.user_type=='all_user'">
                                        <div class="d-flex align-items-center form-text">
                                            <span>先着で</span>
                                            <span>{{ coupon.count }}回</span>
                                            <span>までご利用できます。</span>
                                        </div>
                                        <div v-if="coupon.admin_remaining_count"
                                        class="border px-2 d-flex align-items-center">
                                            <span >先着あと</span>
                                            <div class="fw-bold fs-5 m-0 px-2">{{coupon.admin_remaining_count}}</div>
                                            <span >名</span>
                                        </div>
                                        <div v-else
                                        class="border px-2 text-center text-danger">終了しました。</div>
                                    </div>

                                    <!--おひとり様-->
                                    <div v-if="coupon.user_type=='user'">
                                        <div class="d-flex align-items-center form-text">
                                            <span>おひとり様</span>
                                            <span>{{ coupon.count }}回</span>
                                            <span>までご利用できます。</span>
                                        </div>
                                        <!-- <div v-if="coupon.admin_remaining_count"
                                        class="border px-2 d-flex align-items-center">
                                            <span>あと</span>
                                            <div class="fw-bold fs-5 m-0 px-2">{{coupon.admin_remaining_count}}</div>
                                            <span>回</span>
                                        </div>
                                        <div v-else
                                        class="border px-2 text-center text-danger">終了しました。</div> -->
                                    </div>


                                </div>
                                <div v-else>何回でも利用可能</div>


                                <!--利用済み回数-->
                                <div class="borderrr px- d-flex align-items-center">
                                    <span>利用済み回数：</span>
                                    <span class="text-success">{{ coupon.histories_count }}回</span>
                                </div>


                                <!--有効期限-->
                                <div class="d-flex flex-column gap-0">
                                    <span v-if="coupon.expiration_at_format" class="text-secondary">
                                        有効期限：{{coupon.expiration_at_format}}まで
                                    </span>
                                    <span v-if="coupon.is_expiration_done" class="text-danger">
                                        有効期限切れ
                                    </span>
                                </div>


                            </div>
                            <!--編集ボタン-->
                            <div class="col-auto">
                                <a :href="coupon.r_edit"
                                class="btn btn-sm btn-light border fs-4"
                                ><i class="bi bi-pencil-fill"></i></a>
                            </div>
                            <!--コピー-->
                            <div class="col-auto">
                                <form :action="coupon.r_copy" method="post">
                                    <input type="hidden" name="_token" :value="token">
                                    <button
                                    class="btn btn-sm btn-light border fs-4"
                                    ><i class="bi bi-copy"></i></button>
                                </form>
                            </div>

                            <!--削除モーダル-->
                            <div class="col-auto">
                                <form :action="coupon.r_destroy" method="post">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" :value="token">

                                    <delete-modal-component
                                    :index_key="'delete'+coupon.id"
                                    icon="bi-trash"
                                    func_btn_type="submit"
                                    button_class="btn btn-sm btn-light border fs-4">
                                        <div>
                                            <span class="fw-bold">『{{coupon.title}}』</span>を削除します。
                                            <br />よろしいですか？
                                        </div>
                                    </delete-modal-component>
                                </form>
                            </div>


                        </div>
                    </div>

                    <div v-show="nextPageUrl" class="mt-3">
                        <a @click.prevent="getData( nextPageUrl )"
                        class="btn btn-light border"
                        href="">もっと読み込む</a>
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
        token:        { type: String, default: '' },
        r_api_list:   { type: String, default: '' },
        r_create:     { type: String, default: '' },
        r_history:    { type: String, default: '' },
        card_ration:  { type: String, default: '' },
    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */
    const messages    = ref([]);   /* ポップアップメッセージ */

    const coupons = ref([]); //クーポン

    /* 入力値 */
    const inputs   = ref({
        _token: props.token,
        keyword:       '',
        is_use_code:   'all',//クーポン配布方法
        service:       'all',//プレゼント内容
        user_type:     'all',//利用回数の種類
        is_expiration: 'all',//有効期限
        is_published:  'all',//公開設定
    });

    /* セレクト要素 */
    const selects = ref({
        is_use_codes:   { 0: 'なし', 1:'あり' },//クーポンコード
        services:       { all:'選択してください', point:'ポイント', prize:'商品' },//プレゼント内容
        user_types:      { user:'おひとり様回数', all_user:'先着回数' ,no_count: 'なし' },//利用回数制限
        is_expirations: { 0: 'なし', 1:'期限内', 2:'期限切れ' },//有効期限
        is_publisheds:  {  1:'公開中', 2:'公開予約' ,3: '非公開',},//公開設定
    });



    /* 監視 */
    watch(() => inputs.value.keyword,       () => getData());
    watch(() => inputs.value.is_use_code,   () => getData());
    watch(() => inputs.value.service,       () => getData());
    watch(() => inputs.value.user_type,     () => getData());
    watch(() => inputs.value.is_expiration, () => getData());
    watch(() => inputs.value.is_published,  () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        // resetBulc();/* 一括処理パラメーターのリセット */
        getData();
    });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {

        try {

            const response = await axios.post(route, inputs.value);
            const paginate = response.data['coupons'];

            coupons.value =
            route === props.r_api_list ? paginate.data : [...coupons.value, ...paginate.data];

            loading.value = false;

            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;


        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };



    /* キーワードの削除 */
    const deleteKeyword = () => {
        inputs.value.keyword = '';
        getData();
    };


</script>
