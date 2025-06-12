<template>
    <div class="text-dark">

        <loading-cover-component :loading="loading" />


        <!--カード一覧-->
        <div class="row justify-content-center align-items-center g-3 gy-4 mb-4"
        style="min-height: 50vh;" >

            <div v-if="loading"
            class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div v-if="userPrizes.length==0 && !loading"
            class="text-center fs-5">*表示できる商品はありません</div>

            <div v-for="(userPrize, key) in userPrizes" :key="key"
            :class="userPrizes.length==1 ? 'col-6' : 'col-3'">
                <div class="d-flex align-items-center justify-content-center h-100">


                    <div class="w-100" data-aos="zoom-in">
                        <!-- <label class="w-100" > -->

                            <div class="position-relative">
                                <!--チェックボックス-->
                                <div class="position-absolute top-0 start-0 translate-middle" style="z-index:3">
                                    <input v-model="ids" @change="changeChildren()"
                                    class="form-check-input float-xl-none m-0 rounded-pill"
                                    style="width:2em; height:2em;"
                                    type="checkbox" name="user_prize_ids[]" :value="userPrize.id">
                                </div>

                                <!--カード画像-->
                                <ratio-image-component
                                style_class="ratio ratio-3x4 rounded-3"
                                :url="userPrize.prize.image_path"
                                ></ratio-image-component>


                            </div>

                            <!--ポイント表示-->
                            <div class="bg-white text-center mt-1 px-1 rounded-pill position-relative">

                                <number-comma-component :number="userPrize.point" />pt

                                <!-- @if($user_prize->point_history_id) -->
                                <!--ポイント交換済み-->
                                <div v-if="userPrize.point_history_id"
                                class="position-absolute top-50 start-0 translate-middle-y ps-1">
                                    <span class="text-warning">●</span>
                                </div>

                                <!-- @if($user_prize->shipped_id) -->
                                <!--ポイント交換済み-->
                                <div v-if="userPrize.shipped_id"
                                class="position-absolute top-50 start-0 translate-middle-y ps-1">
                                    <span class="text-primary">●</span>
                                </div>

                            </div>


                                <!--商品説明モーダル-->
                                <button v-if="userPrize.prize.discription_text"
                                class="btn btn-sm btn-dark rounded-pill w-100"
                                type="button"
                                data-bs-toggle="modal"
                                :data-bs-target="'#PrizeDiscriptionModal'+userPrize.id"
                                ><i class="bi bi-search me-2"></i>商品説明</button>
                                <div v-else style="height: 2rem;"></div>

                        <!-- </label> -->
                    </div>


                </div>
            </div>


            <!--商品の説明モーダル-->
            <div class="h-0 overflow-hidden">
                <div v-for="(userPrize, key) in userPrizes" :key="key">
                    <u-prize-discription
                    v-if="userPrize.prize.discription_text"
                    :id         ="userPrize.id"
                    :name       ="userPrize.prize.name"
                    :image_path ="userPrize.prize.image_path"
                    :discription="userPrize.prize.discription_text"
                    size       ="2rem"
                    :src_icon   ="userPrize.prize.discription_icon_path"
                    no_btn     ="1"
                    ></u-prize-discription>
                </div>
            </div>

        </div>

        <!-- ポイント交換ボタン -->
        <div v-if="show_change_btn!=0"
        class="rounded-3 p-3" style="background: rgb(0, 0, 0, .7);">
            <div data-aos="fade-in">


                <div class="d-flex justify-content-between align-items-start text-white">
                    <div class="form-check mb-">
                        <input v-model="allCheck" @change="changeAll()"
                        class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            全て選択
                        </label>
                    </div>

                    <div class="form-check mb-">
                        <span class="fs-1 fw-bold">
                            <number-comma-component :number="totalPoint" />
                        </span>pt
                    </div>
                </div>
                <p class="text-white form-text m-0 mb-3">
                    *選択されなかった商品は、「取得した商品一覧」に移動します。
                </p>
                <div class="col-md-8 mx-auto">
                    <button type="button"
                    data-bs-toggle="modal" data-bs-target="#exchangeModal"
                    class="btn btn-warning rounded-pill w-100" :disabled="disabled"
                    >選択した商品をポイント交換する</button>
                </div>
                <div class="col-md-8 mx-auto mt-2">
                    <a :href="r_gacha_category"
                    class="btn text-danger rounded-pill w-100" :disabled="disabled"
                    >SKIP</a>
                </div>


            </div>
        </div>
        <div v-else>
            <div class="d-flex justify-content-center gap-3 fw-bold" style="font-size:14px; text-shadow: #fff 0px 0 5px;">
                <div class="">
                    <span class="text-warning">●</span>
                    <span>ポイント交換済み</span>
                </div>
                <div class="">
                    <span class="text-primary">●</span>
                    <span>発送申請済み</span>
                </div>
            </div>
        </div>




        <!-- ポイント交換Modal -->
        <div class="modal fade" id="exchangeModal" tabindex="-1" aria-labelledby="exchangeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h5 class="modal-title" id="exchangeModalLabel">
                            <p>ポイント交換しますか？</p>
                            <p>商品を<strong class="fs-3"><number-comma-component :number="totalPoint" />pt</strong>と交換する</p>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button"
                                class="btn p-md-33 btn-light border rounded-pill w-100"
                                data-bs-dismiss="modal"
                                >キャンセル</button>
                            </div>
                            <div class="col-6">
                                <button type="submit"
                                class="btn p-md-33 btn-warning text-white rounded-pill w-100"
                                >交換する</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</template>
<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';

    const props = defineProps({

        token: { type: String, default: '' },
        r_api_use_gacha_history_show: { type: String, default: '' },
        r_gacha_category: { type: String, default: '' },
        show_change_btn:  { type: String, default: '1' },
    });

    const loading     = ref(true); //
    const userPrizes  = ref([]);   /* ユーザー取得商品 */
    const ids         = ref([]);   /*チェックボックスのID*/

    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */
    const allCheck    = ref(false);/*全てチェック*/
    const totalPoint  = ref(0);    /*チェック中のユーザー商品の合計ポイント*/
    const disabled    = ref(true); //

    /* 入力値 */
    const inputs      = ref({
        _token:          props.token,
        show_change_btn: props.show_change_btn,
    });


    /* 初回データ取得 */
    onMounted(() => {

        /* データ取得 */
        getData();


    });




    /* データ取得 */
    const getData = async (route = props.r_api_use_gacha_history_show) => {
        try {
            const response = await axios.post(route, inputs.value);

            /*ページネーションの保存*/
            const paginate = response.data['userPrizes'];

            /*ガチャ商品データの保存*/
            userPrizes.value =
            route === props.r_api_list ? paginate.data : [...userPrizes.value, ...paginate.data];

            /*次のページネーションURLの保存*/
            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

            loading.value = false;

            /* 次のデータの読み込み */
            if( current_page != last_page ){
                const nextPageUrl = paginate.next_page_url;     //URLの更新
                getData( nextPageUrl );
            }


        } catch (error) {
            alert('通信エラーが発生しました。');
        }
    };

    /** 全て選択をクリック */
    const changeAll = () => {
        const idsList = userPrizes.value.map(value => value.id);
        ids.value = allCheck.value ? idsList : [];
        calcTotalPoint();
    };

    /** 子チェックをクリック */
    const changeChildren = () => {
        const idsList = userPrizes.value.map(value => value.id);
        allCheck.value = ids.value.length === idsList.length;
        calcTotalPoint();
    };

    /** ポイント合計値の計算 */
    const calcTotalPoint = () => {
        totalPoint.value = userPrizes.value.reduce((sum, userPrize) => {
            return ids.value.includes(userPrize.id) ? sum + userPrize.point : sum;
        }, 0);
        disabled.value = totalPoint.value === 0;
    };

</script>
