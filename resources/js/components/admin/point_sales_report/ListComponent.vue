<template>
    <div class="">

        <loading-cover-component :loading="loading" />


        <!-- 選択エリア -->
        <section class="mb-3">
            <div class="row align-items-center g-2">
                <div class="col-auto pe-3">
                    <select v-model="inputs.days_type"
                    class="form-select form-select-lg" aria-label="Default select example">
                        <option v-for="( between_days , key ) in select_day_types" :key="key"
                        :value="key"
                        >{{ between_days }}</option>
                    </select>
                </div>
                <div class="col-auto">
                    <div class="form-floating">
                        <input v-model="inputs.start_day"
                        type="date" class="form-control"
                        :disabled="inputs.days_type!='custom'"
                        id="startDayInput">
                        <label for="startDayInput">開始日</label>
                    </div>
                </div>
                <div class="col-auto">〜</div>
                <div class="col-auto">
                    <div class="form-floating">
                        <input v-model="inputs.last_day"
                        type="date" class="form-control"
                        :disabled="inputs.days_type!='custom'"
                        id="lastDayInput">
                        <label for="lastDayInput">終了日</label>
                    </div>
                </div>
                <div class="col-auto h-100">
                    <button v-if="inputs.days_type=='custom'"
                    @click="getData()"
                    class="btn btn-light border"
                    type="button"><i class="bi bi-arrow-clockwise"></i>更新</button>
                </div>
            </div>
        </section>


        <!-- 合計値 -->
        <section class="mb-3">
            <div class="row mt-3 g-0">
                <div v-for="( total, key  ) in totals" :key="key"
                class="col-6 col-md">
                    <button @click="inputs.active_key=key"
                    :class="inputs.active_key==key ? 'bg-primary-subtle' : '' "
                    class="btn text-start w-100" type="button" >
                        <div class="">{{ total.label }}</div>
                        <div class="h3 fw-bold">{{ total.value.toLocaleString() }}</div>
                    </button>
                </div>
            </div>
        </section>


        <!-- グラフ -->
        <section v-if="active_data.length"
        class="card card-body bg-white">

            <a-pointsalesreport-chart
            :s_labels=" data_list.labels"
            :s_data  =" active_data"
            />

        </section>


        <section class="card card-body bg-white my-5 overflow-auto ">

            <div class="d-flex gap-2 mb-3">
                <div v-for="(label, key) in table_types" :key="key">
                    <input v-model="active_table_type"
                    type="radio"
                    :id="key"
                    name="table"
                    :value="key"
                    class="btn-check"
                    autocomplete="off">
                    <label class="btn border"
                    :class="key==active_table_type ? 'btn-primary text-white' : ''"
                    :for="key">{{ label }}</label>
                </div>
            </div>


            <!-- 日別データ テーブル -->
            <table v-if=" active_table_type=='selse' "
            class="table bg-white ">
                <!--head-->
                <thead>
                    <tr class="bg-white text-center">
                        <th scope="col">
                            日付
                        </th>
                        <th v-for="(total,t_key) in totals" :key="t_key" scope="col">
                            {{ total.label }}
                        </th>
                    </tr>
                </thead>
                <!--body-->
                <tbody class="text-center">
                    <tr v-for="(day,d_key) in data_list.labels" :key="d_key">

                        <td>
                            <a :href="data_list.r_daily_array[d_key]"
                            >{{ day + data_list.w_labels[d_key] }}</a>
                        </td>

                        <td v-for="(total,t_key) in totals" :key="t_key" scope="col">
                              {{ data_list[t_key][d_key].toLocaleString() }}
                        </td>

                    </tr>
                </tbody>
                <tfoot class="text-center">
                    <tr class="bg-white text-center">

                        <th scope="col" class="border-0">合計</th>

                        <th v-for="(total,t_key) in totals" :key="t_key" scope="col" class="border-0">
                            {{ total.value.toLocaleString() }}
                        </th>

                    </tr>
                </tfoot>

            </table>


            <!-- 顧客データ　テーブル -->
            <table v-if=" active_table_type=='visiters' "
            class="table bg-white ">
                <!--head-->
                <thead>
                    <tr class="bg-white text-center">

                        <th scope="col">アカウント名</th>

                        <th scope="col">購入金額(円)</th>

                        <th scope="col">購入回数</th>

                    </tr>
                </thead>
                <!--body-->
                <tbody class="text-center">
                    <tr v-for="(visiter,v_key) in data_list_visiters" :key="v_key">

                        <!-- アカウント名 -->
                        <td scope="col">

                            <a :href=" visiter.ra_user_show " class="d-block mb-2"
                            >{{ 'ID:'+visiter.id+' '+visiter.name }}</a>

                        </td>

                        <!-- 購入金額(円) -->
                        <td scope="col">{{ Number(visiter.total_price).toLocaleString()+'円' }}</td>

                        <!-- 購入回数(sales_count) -->
                        <td scope="col">{{ Number(visiter.sales_count).toLocaleString() }}</td>

                    </tr>
                </tbody>

            </table>


            <!-- 販売ポイントデータ　テーブル -->
            <table v-if=" active_table_type=='products' "
            class="table bg-white ">
                <!--head-->
                <thead>
                    <tr class="bg-white text-center">

                        <th scope="col">販売ポイント</th>

                        <th scope="col">販売数</th>

                        <th scope="col">売上金額(円)</th>

                    </tr>
                </thead>
                <!--body-->
                <tbody class="text-center">
                    <tr v-for="(product,v_key) in data_list_products" :key="v_key">

                        <!-- アカウント名 -->
                        <td scope="col">

                            <a :href=" product.ra_show " class="d-block mb-2"
                            >{{ product.name }}</a>

                        </td>

                        <!-- 販売数 -->
                        <td scope="col">{{ Number(product.sum_count).toLocaleString() }}</td>

                        <!-- 売上金額(sum_price) -->
                        <td scope="col">{{ Number(product.sum_price).toLocaleString()+'円' }}</td>

                    </tr>
                </tbody>

            </table>


        </section>


    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:       { type: String, default: '' },
        r_api_list:  { type: String, default: '' },

    });


    /* 読み込み中 */
    const loading     = ref(true);

    /* データリスト */
    const data_list = ref({}); //
    const active_data = ref([]);

    /* 今日の日付フォーマット */
    const todayFormatted = ref('');

    /* 合計値 */
    const totals = ref({
        sales:                 {value: 0, label: '売上'}, //売上
        visiters_count:        {value: 0, label: '客数'}, //客数
        reprater_count:        {value: 0, label: 'リピーター数'}, //リピーター数
        payment_count :        {value: 0, label: '販売回数'},    //購入回数
        gacha_played_count:    {value: 0, label: 'ガチャ回転数'},  //ガチャ回転数
    });

    /* 日付の種類選択 */
    const select_day_types = ref(null);


    /* テーブルの種類 */
    const table_types = ref({
        selse:    '売上レポート',
        visiters: '顧客レポート',
    });

    /* 選択中のテーブルの種類 */
    const active_table_type = ref('selse');

    /* テーブルデータリスト */
    const data_list_visiters = ref({});//顧客データ

    /* APIルーティング */
    const r_api_visiters = ref('');//API 顧客一覧


    /* 入力値 */
    const inputs = ref({

        _token: props.token,
        active_key: 'sales',      //選択中データの種類
        days_type:  '7days', //日付の種類
        start_day:  '',           //開始日
        last_day:   '',           //終了日

    });


    /* 監視：選択中のデータの種類 */
    watch(() => inputs.value.active_key,  () =>{
        active_data.value = data_list.value[ inputs.value.active_key ];
    });
    /* 監視：日付の種類 */
    watch(() => inputs.value.days_type,  () =>{
        if( inputs.value.days_type!='custom' ){ getData(); }
    });
    /* 監視：開始日 */
    //watch(() => inputs.value.start_day, () => getData());
    /* 監視：終了日 */
    // watch(() => inputs.value.last_day,  () => getData());
    /* 監視：テーブルの切り替え */
    watch(() => active_table_type.value, () => {

        // 顧客履歴データの取得
        if( active_table_type.value == 'visiters' ){
            loading.value = true;
            getDataVisiters();
        }

    });




    /* 初回データ取得 */
    onMounted(() => {

        getTodayFormatted();/* 今日の日付フォーマット */

        getData();
    });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {
        loading.value = true;
        try {

            const response = await axios.post(route, inputs.value);

            /*データリスト*/
            data_list.value   = response.data['data_list'];
            active_data.value = data_list.value[ inputs.value.active_key ];

            /*データ範囲*/
            inputs.value.start_day = response.data['start_day_format'];
            inputs.value.last_day  = response.data['last_day_format'];

            /*合計値リスト*/
            totals.value = response.data['totals'];

            /*日付の種類選択*/
            select_day_types.value = response.data['select_day_types'];

            /*テーブルAPIルーティング*/
            r_api_visiters.value = response.data['r_api_visiters'];//API 顧客一覧

            /* テーブルデータ */
            // 顧客履歴データの取得
            if( active_table_type.value == 'visiters' ){
                getDataVisiters();
            }


            loading.value = false;


        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };


    /* 顧客履歴データ取得 */
    const getDataVisiters = async () => {
        loading.value = true;
        try {
            const response = await axios.post( r_api_visiters.value, inputs.value);

            /*データリスト*/
            data_list_visiters.value   = response.data['visiters'];
            loading.value = false;


        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };


    /* 今日の日付フォーマット */
    const getTodayFormatted = () => {
        const today = new Date();
        const YY = String(today.getFullYear()).slice(-4);
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');

        todayFormatted.value = `${YY}-${mm}-${dd}`;
    };


</script>
