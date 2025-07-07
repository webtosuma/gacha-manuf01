<template>
    <div class="">

        <loading-cover-component :loading="loading" />

        <div class="row g-3 gy-">


            <!-- mainテーブル -->
            <div class="col order-lg-2">
                <section class="card card-body bg-white my- overflow-auto" style="height: 90vh;">
                    <table class="table bg-white " style="min-width: 600px; font-size: 16px;">
                        <!--ヘッド（並べ替えボタン）-->
                        <thead>
                            <tr class="bg-white">
                                <th style="width:1rem;"><!--チェックボックス-->
                                    <input v-model="allCheck" @change="changeAll()"
                                    class="form-check-input" type="checkbox">
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
                                        <select @change="getData()"
                                        v-model="inputs.where_rank_id"
                                        class="form-select form-select-sm fw-bold">
                                            <option value="">評価ランク</option>
                                            <option v-for="(prize_rank, key) in selects.prize_ranks" :key="key"
                                            :value="prize_rank.id">{{ prize_rank.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <a @click.prevent="changeOrder( 'order_rank_id' )"
                                        href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                            <!-- <span>評価ランク</span> -->
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

                                <th scope="col"><a
                                @click.prevent="changeOrder( 'updated_at' )"
                                href="#" class="btn btn-sm w-100 fw-bold fs-6 text-start p-0">
                                    <span>更新</span>
                                    <i v-if="inputs['updated_at']!='desc'" class="bi bi-caret-up-fill"></i>
                                    <i v-if="inputs['updated_at']!='asc'"  class="bi bi-caret-down-fill"></i>
                                </a></th>
                                <!-- <th></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(prize, key) in prizes" :key="key">
                                <td>
                                    <input v-model="ids" :value="prize.id" name="prize_ids[]"
                                    @change="changeChildren()"
                                    class="form-check-input" type="checkbox" >
                                </td>
                                <td scope="row">
                                    <!--画像-->
                                    <div style="width:3rem;">
                                        <ratio-image-component
                                        style_class="ratio ratio-3x4 rounded"
                                        :url=" prize.image_path " />
                                    </div>
                                </td>
                                <td>{{ prize.code }}</td>
                                <td>{{ prize.name }}</td>
                                <td>{{ prize.rank.name }}</td>
                                <td>{{ prize.point }} pt</td>
                                <td class="form-text">{{ formatDate( prize.updated_at ) }}</td>
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
                </section>
            </div>


            <!-- side -->
            <div class="col-12 col-lg-auto order-lg-1">
                    <!--キーワード検索-->
                    <div class="mb-2">
                        <div class="form-text">キーワード検索</div>
                        <!-- @change="changeKeyWord()" -->
                            <input v-model="inputs.key_words"
                            type="text" class="form-control form-control-lgg" placeholder="検索：商品名・商品コード名"
                            aria-label="Username" aria-describedby="basic-addon1" />
                        </div>
                    <div class="position-sticky" style="top: 2rem; ">


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

                    <!--送信ボタン-->
                    <div class="mt-5">
                        <button class="btn btn-primary w-100" type="submit"
                        :disabled="!ids.length>0"
                        >選択したガチャ用商品を登録</button>
                    </div>
                </div>
            </div>



        </div>


    </div>
</template>
<script setup>
    import { ref, reactive, onMounted, watch } from 'vue'
    import axios from 'axios'
    import { toRefs } from 'vue'
    import { defineProps } from 'vue'

    const props = defineProps({
        token: { type: String, default: '' },
        r_api_prize: { type: String, default: '' },
        r_api_category: { type: String, default: '' },
        category_id: { type: String, default: '' },
    })

    // ---------- State ----------
    const loading = ref(true)
    const categories = ref([])
    const prizes = ref([])

    const inputs = ref({
        _token:        props.token,
        key_words:     '',
        category_id:   props.category_id,
        order_code:    '',
        order_name:    '',
        order_rank_id: '',
        order_point:   '',
        updated_at:    '',
        where_rank_id: '',
        max_point:     null,
        min_point:     null,
    })

    const selects = reactive({
        prize_ranks: {},
    })

    const keyWords = ref('')
    const ids = ref([])
    const allCheck = ref(false)
    const disabled = ref(true)
    const edit = ref(false)



    /* 監視 */
    watch(() => inputs.value.category_id, () => getData());
    watch(() => inputs.value.key_words,   () => getData());
    watch(() => inputs.value.max_point,   () => getData());
    watch(() => inputs.value.min_point,   () => getData());


    // ---------- Hooks ----------
    onMounted(() => {
        inputs.category_id = props.category_id
        getCategoryData()
    })

    // ---------- Methods ----------
    const getCategoryData = async () => {
        try {
            const res = await axios.post(props.r_api_category, inputs)
            categories.value = res.data
            setActiveCategory(props.category_id)
        } catch (error) {
            alert('通信エラーが発生しました。')
            console.error(error.response?.data)
        }
    }

    const getData = async (route = props.r_api_prize) => {
        try {
            loading.value = true
            const res = await axios.post(route, inputs.value)

            const paginate = res.data.prizes
            if (route === props.r_api_prize) {
            prizes.value = paginate.data
            } else {
            prizes.value = [...prizes.value, ...paginate.data]
            }

            selects.prize_ranks = res.data.prize_ranks

            const current_page = paginate.current_page
            const last_page = paginate.last_page
            if (current_page !== last_page) {
            getData(paginate.next_page_url)
            }
        } catch (error) {
            alert('通信エラーが発生しました。')
            console.error(error.response?.data)
        } finally {
            loading.value = false
        }
    }


    const setActiveCategory = (category_id) => {
        inputs.key_words = ''
        keyWords.value = ''
        inputs.category_id = category_id
        getData()
    }

    const changeOrder = (key) => {
        switch (inputs[key]) {
            case '':
                inputs[key] = 'asc'
                break
            case 'asc':
                inputs[key] = 'desc'
                break
            default:
                inputs[key] = ''
                break
        }
        getData()
    }

    const changeAll = () => {
        const allIds = prizes.value.map(item => item.id)
        ids.value = allCheck.value ? allIds : []
    }

    const changeChildren = () => {
        const allIds = prizes.value.map(item => item.id)
        allCheck.value = ids.value.length === allIds.length
    }

    const toggleEdit = () => {
        getData()
        edit.value = !edit.value
    }

    const formatDate = (inputString) => {
        const date = new Date(inputString)
        const y = date.getFullYear()
        const m = String(date.getMonth() + 1).padStart(2, '0')
        const d = String(date.getDate()).padStart(2, '0')
        const h = String(date.getHours()).padStart(2, '0')
        const mi = String(date.getMinutes()).padStart(2, '0')
        const s = String(date.getSeconds()).padStart(2, '0')
        return `${y}/${m}/${d} ${h}:${mi}:${s}`
    }
</script>

<style scoped>
    th, td {
        background-color: #fff;
    }
</style>
