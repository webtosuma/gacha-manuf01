<template>
    <div class="row g-3">
        <div class="col-md-auto">

            <loading-cover-component :loading="loading" />


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

                <div class="col-auto">
                    <select
                    v-model="month_stamp"
                    class="form-select">
                        <option value="">公開日絞り込み</option>

                        <option v-for="( month, key ) in months" :key="key"
                        :value="month.date_stanp"
                        selected>{{month.format}}</option>
                    </select>
                </div>
            </div>

        </div>
        <div class="col">


            <div class="list-group rounded-4" style="background:rgb(255, 255, 255, .7);">


                <div v-if="!infomations.length" class="list-group-item py-5">
                    * お知らせはありません
                </div>


                <div v-for="(info,key) in infomations" :key="key"
                class="list-group-item list-group-item-action pozition-relative">

                    <a :href="info.r_show" class="text-dark">
                        <div class="d-flex align-items-center">
                            <div class="col py-2">

                                <!--公開日-->
                                <div>{{info.published_at_format??'--.--.--'}}</div>

                                <!--種類ラベル-->
                                <div v-if="info.is_use_types">
                                    <div
                                    class="px-2 bg-dark text-white d-inline-block"
                                    style="font-size:11px;"
                                    >{{info.type_label}}</div>
                                </div>

                                <!--タイトル-->
                                <div class="">{{ info.title }}</div>

                            </div>
                            <!--サムネ画像-->
                            <div class="col-auto" style="width:4rem;">
                                <ratio-image-component
                                v-if="info.image_path"
                                :url="info.image_path"
                                style_class="ratio ratio-1x1 w-100 rounded"
                                ></ratio-image-component>
                            </div>
                            <div class="col-auto text-dark ps-3">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </a>

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

        token:           { type: String, default: '' },
        r_api_list:      { type: String, default: '' },   // カテゴリーcode
        use_mail:        { type: String, default: null }, // メールの利用
        is_published:    { type: [String, Number], default: 1 }, // ページ読み込み時の公開状態
        no_types_string: { type: String, default: null }, //非表示にするお知らせの種類(文字列)
    });

    /* データの状態 */
    const r_create = ref('');     /* 新規作成ページURL */
    const loading = ref(true);    /* 読み込み中 */
    const infomations = ref([]);  /* 一覧 */
    const months = ref([]);       /* 年月絞り込み */
    const nextPageUrl = ref('');  /* 次のデータの読み込みURL */


    const published = ref(props.is_published);/* 公開状態 */ //2:予約中 1:公開 0:未公開
    const month_stamp = ref('');  /* 月の絞り込み */
    const title_keyword = ref('');/* タイトルキーワード */


    /* 監視 */
    watch(title_keyword, () => getData());
    watch(month_stamp, () => changePublished(1, month_stamp.value));


    /* 初回データ取得 */
    onMounted(() => {
        published.value = props.is_published;
        getData();
    });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {
        const inputs = {
            _token: props.token,
            published:      published.value,
            month:          month_stamp.value,
            title_keyword:  title_keyword.value,
            no_types_array: props.no_types_string ? props.no_types_string.split(',') : null,
        };

        try {
            const response = await axios.post(route, inputs);
            const paginate = response.data['infomations'];

            infomations.value =
            route === props.r_api_list ? paginate.data : [...infomations.value, ...paginate.data];

            months.value = response.data.months;
            r_create.value = response.data.r_create;
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

