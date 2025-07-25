<template>
    <div class="">

        <loading-cover-component :loading="loading" />


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
            </div>


            {{ inputs.active_key }}
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


        <!-- {{ data_list.labels.join(',') }}<br> -->
        <!-- {{ active_data }} -->

        <section v-if="active_data.length"
        class="card card-body bg-white">
            <!-- <a-store-salesreport-chart
            :s_labels=" data_list.labels.join(',') "
            :s_data  =" active_data.join(',') "
            /> -->
            <a-store-salesreport-chart
            :s_labels=" data_list.labels"
            :s_data  =" active_data"
            />

        </section>


        <section class="card card-body bg-white my-5 overflow-auto overflou-auto" style="max-height:50vh;">
            <div class="mb-3">日別レポート</div>


            <table class="table bg-white ">
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

                        <td>{{ day }}</td>

                        <th v-for="(total,t_key) in totals" :key="t_key" scope="col">
                            <!-- {{ t_key }} -->
                              {{ data_list[t_key][d_key] }}
                        </th>

                    </tr>
                </tbody>
                <tfoot class="text-center">
                    <tr class="bg-white text-center">

                        <th scope="col" class="border-0">合計</th>

                        <th v-for="(total,t_key) in totals" :key="t_key" scope="col" class="border-0">
                            {{ total.value }}
                        </th>

                    </tr>
                </tfoot>

            </table>


        </section>


        <section class="card card-body bg-white my-5 overflow-auto overflou-auto" style="max-height:50vh;">
            <div class="mb-3">顧客レポート</div>

        </section>


        <section class="card card-body bg-white my-5 overflow-auto overflou-auto" style="max-height:50vh;">
            <div class="mb-3">商品レポート</div>

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
        payment_count :        {value: 0, label: '販売回数'},    //販売回数
        sales_prodact_count:   {value: 0, label: '販売商品数'},  //販売商品数
        redemption_point_count:{value: 0, label: '還元ポイント'},//還元ポイント
    });

    /* 日付の種類選択 */
    const select_day_types = ref(null);

    /* 入力値 */
    const inputs = ref({

        _token: props.token,
        active_key: 'sales',      //選択中データの種類
        days_type:  'this_month', //日付の種類
        start_day:  '',           //開始日
        last_day:   '',           //終了日

    });

    /* 監視 */
    watch(() => inputs.value.active_key,  () =>{
        // active_data.value.unshift( data_list.value[ inputs.value.active_key ] );
        active_data.value = data_list.value[ inputs.value.active_key ];
    });
    watch(() => inputs.value.days_type,  () =>{
        if( inputs.value.days_type!='custom' ){ getData(); }
    });
    watch(() => inputs.value.start_day, () => getData());
    watch(() => inputs.value.last_day,  () => getData());


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
            // active_data.value.unshift( data_list.value[ inputs.value.active_key ] );
            active_data.value = data_list.value[ inputs.value.active_key ];

            /*データ範囲*/
            inputs.value.start_day = response.data['start_day_format'];
            inputs.value.last_day  = response.data['last_day_format'];

            /*合計値リスト*/
            totals.value = response.data['totals'];

            /*日付の種類選択*/
            select_day_types.value = response.data['select_day_types'];
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
