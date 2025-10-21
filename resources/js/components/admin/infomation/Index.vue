<template>
    <div class="row g-3">
        <div class="col-md-auto">

            <loading-cover-component :loading="loading" />

            <a :href="r_create"
            class="btn btn-primary text-white mb-2 shadow">
            <i class="bi bi-plus-lg"></i>
            {{'新規登録'}}
            </a>


            <div class="row flex-md-column g-2 mb-2">
                <div class="col input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text"
                    v-model="title_keyword"
                    class="form-control"
                    placeholder="タイトルキーワード">

                    <span
                    @click="resetTitleKeyword()"
                    class="btn btn-light border"><i class="bi bi-x"></i></span>
                </div>

                <div class="col">
                    <select
                    v-model="month_stamp"
                    class="form-select">
                        <option value="">公開日</option>

                        <option v-for="( month, key ) in months" :key="key"
                        :value="month.date_stanp"
                        selected>{{month.format}}</option>
                    </select>
                </div>

                <div v-if="types" class="col">
                    <select
                    v-model="type"
                    class="form-select">
                        <option value="">お知らせの種類</option>

                        <option v-for="( label, key ) in types" :key="key"
                        :value="key"
                        selected>{{label}}</option>
                    </select>
                </div>
            </div>

            <div class="list-group mb-2">

                <a @click.prevent="changePublished(1)" href="#"
                class="list-group-item"
                :class="{'bg-primary-subtle disabled':published==1}"
                ><i class="bi bi-folder-fill me-1"></i>公開中を表示</a>

                <a @click.prevent="changePublished(2)" href="#"
                class="list-group-item"
                :class="{'bg-primary-subtle disabled':published==2}"
                ><i class="bi bi-folder-fill me-1"></i>予約中を表示</a>

                <a @click.prevent="changePublished(0)" href="#"
                class="list-group-item"
                :class="{'bg-primary-subtle disabled':published==0}"
                ><i class="bi bi-folder-fill me-1"></i>未公開を表示</a>

            </div>




        </div>
        <div class="col">


            <div class="list-group ">
                <div v-if="!infomations.length" class="list-group-item py-5">
                    * お知らせはありません
                </div>

                <div v-for="(info,key) in infomations" :key="key"
                class="list-group-item pozition-relative">
                    <div class="row align-items-center py-2 g-1">


                        <div class="col-auto" style="width: 5rem;">
                            <div v-if="info.is_slide"
                            class="d-inline-block px-2 py-1 bg-light form-text">スライド {{ info.slide }}</div>
                        </div>
                        <div class="col">

                            <!--公開日・公開状況-->
                            <div class="">
                                {{info.published_at_format??'--.--.--'}}
                                <!--未公開-->
                                <span v-if="!info.published_at_format" class="badge rounded-pill bg-danger" >{{ '未公開' }}</span>
                                <!--公開予約-->
                                <span v-else-if="!info.is_published"   class="badge rounded-pill bg-warning">{{ '予約中' }}</span>
                                <!--公開-->
                                <span v-else class="badge rounded-pill bg-success">{{ '公開中' }}</span>
                            </div>

                            <!--種類ラベル-->
                            <div v-if="info.is_use_types">
                                <div
                                class="px-2 bg-dark text-white d-inline-block"
                                style="font-size:11px;"
                                >{{info.type_label}}</div>
                            </div>

                            <!--タイトル-->
                            <a :href="info.r_show"
                            class="text-truncate overflow-hidden" style="width:10rem;"
                            >{{ info.title }}</a>

                        </div>
                        <!--サムネ画像-->
                        <div class="col-auto pe-2" style="width:4rem;">
                            <ratio-image-component
                            v-if="info.image_path"
                            :url="info.image_path"
                            style_class="ratio ratio-1x1 w-100 rounded"
                            ></ratio-image-component>
                        </div>

                        <!--編集ボタン-->
                        <div class="col-auto">
                            <a :href="info.r_edit"
                            class="btn btn-sm btn-light border fs-4"
                            ><i class="bi bi-pencil-fill"></i></a>
                        </div>
                        <!--メール送信ボタン-->
                        <div v-if="use_mail" class="col-auto">
                            <a :href="info.r_email" class="btn btn-sm btn-light border fs-4">
                                <i v-if="info.send_email_at_format"
                                class="bi bi-envelope-check text-success"></i>

                                <i v-else class="bi bi-envelope"></i>
                            </a>
                        </div>
                        <!--削除モーダル-->
                        <div class="col-auto">
                            <form :action="info.r_destroy" method="post">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" :value="token">

                                <delete-modal-component
                                :index_key="'delete'+info.id"
                                icon="bi-trash"
                                func_btn_type="submit"
                                button_class="btn btn-sm btn-light border fs-4">
                                    <div>
                                        <span class="fw-bold">『{{info.title}}』</span>を削除します。
                                        <br />よろしいですか？
                                    </div>
                                </delete-modal-component>
                            </form>
                        </div>



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
</template>


 <script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({

        token: { type: String, default: '' },
        r_api_list: { type: String, default: '' }, // カテゴリーcode
        use_mail: { type: String, default: null }, // メールの利用
        is_published: { type: [String, Number], default: 1 }, // ページ読み込み時の公開状態

    });

    /* データの状態 */
    const r_create    = ref('');   /* 新規作成ページURL */
    const loading     = ref(true); /* 読み込み中 */
    const infomations = ref([]);   /* 一覧 */
    const months      = ref([]);   /* 年月絞り込み 選択肢 */
    const types       = ref([]);   /* お知らせの種類 選択肢 */
    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */


    const published     = ref(props.is_published);/* 公開状態 */ //2:予約中 1:公開 0:未公開
    const month_stamp   = ref('');/* 月の絞り込み */
    const title_keyword = ref('');/* タイトルキーワード */
    const type          = ref('');/*お知らせの種類*/


    /* 監視 */
    watch(title_keyword, () => getData());
    watch(month_stamp,   () => changePublished(1, month_stamp.value));
    watch(type,          () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        published.value = props.is_published;
        getData();
    });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {

        const inputs = {
            _token:        props.token,
            published:     published.value,
            month:         month_stamp.value,
            title_keyword: title_keyword.value,
            type:          type.value,
            admin: true, //サイト管理者データの受信
        };

        try {
            const response = await axios.post(route, inputs);
            const paginate = response.data['infomations'];

            infomations.value =
            route === props.r_api_list ? paginate.data : [...infomations.value, ...paginate.data];

            months.value   = response.data.months;
            types.value    = response.data.types;
            r_create.value = response.data.r_create;
            loading.value  = false;

            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

        } catch (error) {
            console.error(error.response?.data);
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
            location.reload();
            }
        }

    };


    /* 月の絞り込み */
    const setMonthStamp = () => {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = '01';

        month_stamp.value = `${year}/${month}/${day}`;
        published.value = 4;
    };


    /* 公開状況を変更 */
    const changePublished = (num, date_stamp = '') => {
        published.value = num;
        month_stamp.value = date_stamp;
        getData();
    };


    /* タイトルキーワードのリセット */
    const resetTitleKeyword = () => {
        title_keyword.value = '';
        getData();
    };


</script>


