
<template>
    <div>

        <loading-cover-component :loading="loading" />

        <h3>{{ day_format }}</h3>

        <!-- 合計 -->
        <section class="mb-3">
            <div class="row mt-3 g-0">
                <div v-for="(total, key) in totals" :key="key" class="col-6 col-md">
                    <div class="btn text-start w-100">
                        <div>{{ total.label }}</div>
                        <div class="h3 fw-bold">
                            {{ (total.value ?? 0).toLocaleString() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- テーブル -->
        <section class="card card-body bg-white mb-5 overflow-auto">
            <table class="table bg-white">

                <thead>
                    <tr class="text-center">
                        <th>アカウント名</th>
                        <th>購入ポイント</th>
                        <th>売上金額</th>
                        <th></th>
                        <th>受付時間</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(data, key) in data_list" :key="key" class="text-center">

                        <!-- ユーザー -->
                        <td>
                            <a :href="data.ra_user_show" class="d-block mb-2">
                                {{ 'ID:' + (data.user?.id ?? '-') + ' ' + (data.user?.name ?? '') }}
                            </a>
                        </td>

                        <!-- ポイント -->
                        <td>
                            {{ (data.value ?? 0).toLocaleString() + 'pt' }}
                        </td>

                        <!-- 金額 -->
                        <td>
                            {{ (data.price ?? 0).toLocaleString() + '円' }}
                        </td>

                        <!-- サブスク -->
                        <td>
                            <div v-if="(data.reason_id ?? 0) >= 2000" class="badge bg-info">
                                サブスク
                            </div>
                        </td>

                        <!-- 時刻 -->
                        <td>
                            {{ data.created_at_format ?? '' }}
                        </td>

                        <!-- メニュー -->
                        <td>
                            <div class="dropdown">
                                <button
                                    class="btn btn-light border rounded-pill"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                >
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>

                                <ul class="dropdown-menu bg-white">
                                    <li>
                                        <a :href="data.ra_user_show" class="dropdown-item">
                                            ユーザー情報
                                        </a>
                                    </li>
                                    <li>
                                        <a :href="data.ra_user_point_history" class="dropdown-item">
                                            ポイント購入履歴
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>

                    </tr>
                </tbody>

            </table>

            <!-- もっと読み込む -->
            <div v-if="nextPageUrl" class="mt-3">
                <a @click.prevent="getData(nextPageUrl)" class="btn btn-light border">
                    もっと読み込む
                </a>
            </div>

        </section>

    </div>
</template>
<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';

    const props = defineProps({
        token: { type: String, default: '' },
        r_api_list: { type: String, default: '' },
    });

    /* 読み込み中 */
    const loading = ref(true);

    /* 次ページURL */
    const nextPageUrl = ref(null);

    /* データ */
    const data_list = ref([]);

    /* 日付 */
    const day_format = ref('');

    /* 合計 */
    const totals = ref({
        sales:              { value: 0, label: '売上' },
        visiters_count:     { value: 0, label: '客数' },
        reprater_count:     { value: 0, label: 'リピーター数' },
        payment_count:      { value: 0, label: '販売回数' },
        gacha_played_count: { value: 0, label: 'ガチャ回転数' },
    });

    /* 入力 */
    const inputs = ref({
        _token: props.token,
        order: '',
    });

    /* 初回 */
    onMounted(() => {
        getData();
    });

    /* データ取得 */
    const getData = async (route = props.r_api_list) => {
        loading.value = true;

        try {
            const response = await axios.post(route, inputs.value);

            const paginate = response.data?.data_list ?? {};

            // 配列安全取得
            const list = paginate.data ?? [];

            // 初回 or 追加
            data_list.value =
                route === props.r_api_list
                    ? list
                    : [...data_list.value, ...list];

            // totals
            totals.value = response.data?.totals ?? totals.value;

            // 日付
            day_format.value = response.data?.day_format ?? '';

            // ページング
            const current = paginate.current_page ?? 1;
            const last = paginate.last_page ?? 1;
            nextPageUrl.value =
                current !== last ? paginate.next_page_url : null;

        } catch (error) {
            console.error(error?.response?.data ?? error);

            if (confirm('通信エラーが発生しました。再読み込みしますか？')) {
                location.reload();
            }

        } finally {
            loading.value = false;
        }
    };
</script>
