<template>
    <div class="">

        <loading-cover-component :loading="loading" />

        <h3>{{ day_format }}</h3>


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


        <section class="card card-body bg-white mb-5 overflow-auto">
            <table class="table bg-white ">
                <!--ヘッド-->
                <thead>
                    <tr class="bg-white text-center">
                        <th scope="col">
                            アカウント
                        </th>
                        <!-- <th scope="col">
                            発送コード
                        </th> -->
                        <th scope="col">
                            売上
                        </th>
                        <th scope="col" style="max-width:3rem;">
                            点数
                        </th>
                        <th scope="col" style="max-width:3rem;">
                            利用pt
                        </th>
                        <th scope="col" style="max-width:3rem;">
                            還元pt
                        </th>
                        <th scope="col" style="max-width:3rem;">
                            受付時間
                        </th>
                        <th scope="col" style="max-width:3rem;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data, key) in data_list" :key="key"
                    class="bg-white text-center">
                        <!-- アカウント名 -->
                        <td scope="col">

                            <a :href=" data.ra_user_show " class="d-block mb-2"
                            >{{ 'ID:'+data.user.id+' '+data.user.name }}</a>

                        </td>

                        <!-- 売上(円) -->
                        <td scope="col">{{ data.total_price.toLocaleString()+'円' }}</td>

                        <!-- 点数 -->
                        <td scope="col">{{ data.sum_count.toLocaleString() }}</td>

                        <!-- 利用ポイント(pt) -->
                        <td scope="col">{{ data.use_point.toLocaleString()+'pt' }}</td>

                        <!-- 還元ポイント(pt) -->
                        <td scope="col">{{ data.sum_points_redemption.toLocaleString()+'pt' }}</td>

                        <!-- 受付時間 -->
                        <td scope="col">{{ data.done_at.substring(11, 16) }}</td>


                        <!-- 注文詳細リンク -->
                        <td>
                            <a :href="data.ra_store_shipped_show" class="d-block" style="font-size:14px;"
                            >{{ '注文詳細' }}</a>
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

    /* 次のデータの読み込みURL */
    const nextPageUrl = ref('');

    /* データリスト */
    const data_list = ref({}); //

    /* 日付フォーマット */
    const day_format = ref('');

    /* 合計値 */
    const totals = ref({
        sales:                 {value: 0, label: '売上'}, //売上
        visiters_count:        {value: 0, label: '客数'}, //客数
        reprater_count:        {value: 0, label: 'リピーター数'}, //リピーター数
        payment_count :        {value: 0, label: '販売回数'},    //販売回数
        sales_prodact_count:   {value: 0, label: '販売商品数'},  //販売商品数
        redemption_point_count:{value: 0, label: '還元ポイント'},//還元ポイント
    });


    /* 入力値 */
    const inputs = ref({

        _token: props.token,
        order: '',

    });

    /* 初回データ取得 */
    onMounted(() => { getData(); });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {
        loading.value = true;
        try {

            const response = await axios.post(route, inputs.value);

            /*データリスト*/
            const paginate = response.data['data_list'];
            data_list.value =
            route === props.r_api_list ? paginate.data : [...data_list.value, ...paginate.data];

            /*合計値リスト*/
            totals.value = response.data['totals'];

            /*日付フォーマット*/
            day_format.value = response.data['day_format'];

            /* 次のデータURLの保存 */
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
