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
                        <th scope="col">アカウント名</th>
                        <th scope="col">購入ポイント</th>
                        <th scope="col">売上金額</th>
                        <th scope="col"><!--サブスク--></th>
                        <th scope="col">受付時間</th>
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

                        <!-- 購入ポイント -->
                        <td scope="col">{{ data.value.toLocaleString()+'pt' }}</td>

                        <!-- 購入金額 -->
                        <td scope="col">{{ data.price.toLocaleString()+'円' }}</td>

                            <td>
                                <!--サブスク-->
                                <div v-if="data.reason_id>2000" class="badge bg-info">サブスク</div>
                            </td>

                        <!-- 受付時間 -->
                        <td scope="col">{{ data.created_at_format }}</td>

                        <!-- メニューリンク -->
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light border rounded-pill" type="button"
                                :id="'dropdownMenuButton'+data.id"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                ><i class="bi bi-three-dots-vertical"></i></button>


                                <ul class="dropdown-menu bg-white"
                                :aria-labelledby="'dropdownMenuButton'+data.id">
                                    <li><a  :href="data.ra_user_show"
                                    class="dropdown-item">ユーザー情報</a></li>
                                    <li><a :href="data.ra_user_point_history"
                                    class="dropdown-item">ポイント購入履歴</a></li>
                                </ul>
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
        sales_prodact_count:   {value: 0, label: 'ガチャ回転数'},  //ガチャ回転数
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
            console.log(response.data)

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
