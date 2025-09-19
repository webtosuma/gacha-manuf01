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
            :class="userPrizes.length==1 ? 'col-6' : 'col-4 col-md-3'">
                <div class="d-flex align-items-center justify-content-center h-100">


                    <div class="w-100" data-aos="zoom-in">
                        <!-- <label class="w-100" > -->

                            <label class="d-block position-relative" style="cursor: pointer;">
                                <!--チェックボックス-->
                                <div v-if="show_change_btn!=0"
                                class="position-absolute top-0 start-0 translate-middle" style="z-index:3">
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



                                <!-- ポイント交換済み -->
                                <div v-if="userPrize.point_history_id"
                                style="z-index:5; font-size:14px; background:rgb(0, 0, 0, .5);"
                                class="d-flex justify-content-center align-items-center flex-column
                                fw-bold rounded  text-warning
                                position-absolute top-0 start-0 h-100 w-100 ">
                                    <i class="bi bi-check-circle-fill fs-1 "></i>
                                    <span >ポイント<br>交換済み</span>
                                </div>

                                <!-- チケット交換済み -->
                                <div v-if="userPrize.to_ticket_history_id"
                                style="z-index:5; font-size:14px; background:rgb(0, 0, 0, .5);"
                                class="d-flex justify-content-center align-items-center flex-column
                                fw-bold rounded  text-success
                                position-absolute top-0 start-0 h-100 w-100 ">
                                    <i class="bi bi-check-circle-fill fs-1 "></i>
                                    <span >チケット<br>交換済み</span>
                                </div>

                                <!-- 発送済み -->
                                <div v-if="userPrize.shipped_id"
                                style="z-index:5; font-size:14px; background:rgb(0, 0, 0, .5);"
                                class="d-flex justify-content-center align-items-center flex-column
                                fw-bold rounded  text-primary
                                position-absolute top-0 start-0 h-100 w-100 ">
                                    <i class="bi bi-check-circle-fill fs-1 "></i>
                                    <span >発送申請済み</span>
                                </div>


                            </label>

                            <!--ポイント表示-->
                            <div v-if="no_exchange_point==0"
                            class="bg-warning text-end text-dark
                            mt-1 px-2 rounded-pill position-relative
                            position-relative">

                                <div class="position-absolute top-50 start-0 translate-middle-y px-1" style="padding-top:2px;">
                                    <i class="bi bi-p-circle  fs-5 text-"></i>
                                </div>


                                {{ userPrize.point.toLocaleString()+' pt' }}
                            </div>

                            <!--交換チケット-->
                            <div v-if="change_ticket!=0"
                            class="bg-success text-end text-dark
                            mt-1 px-2 rounded-pill position-relative
                            position-relative">

                                <div class="position-absolute top-50 start-0 translate-middle-y px-1" style="padding-top:2px;">
                                    <i class="bi bi-ticket-perforated-fill fs-5 text-"></i>
                                </div>


                                <span v-if="userPrize.prize.ticket" class="fs-6">{{userPrize.ticket.toLocaleString()+' 枚'}}</span>
                                <span v-else style="font-size:11px;">交換なし</span>
                            </div>

                            <!--商品説明モーダル-->
                            <button v-if="userPrize.prize.discription_text"
                            class="btn btn-sm btn-dark rounded-pill w-100 my-1"
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

        <!-- ポイント交換 or 商品発送 ボタン -->
        <div id="all_check"
        v-if="show_change_btn!=0"
        class="rounded-3 p-3" style="background: rgb(0, 0, 0, .5);">
            <div data-aos="fade-in">


                <div class="row justify-content-between align-items-center g-2 px-2 text-white mb-2">
                    <!--すべて選択-->
                    <div class="col-12">
                        <label class="form-check" style="cursor:pointer;">
                            <input v-model="allCheck" @change="changeAll()"
                            class="form-check-input" type="checkbox">
                            <span class="form-check-label fs-">
                                全て選択
                            </span>
                        </label>
                    </div>
                    <!--選択中ポイント合計-->
                    <div v-if="no_exchange_point==0" class="col">
                        <div class="form-check">
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="">
                                    <i class="bi bi-p-circle fs-3 text-warning"></i>
                                    <i class="bi bi-x"></i>
                                </div>

                                <div class="">
                                    <span class="fs-3 fw-bold">{{ totalPoint.toLocaleString() }}</span>pt
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--選択中チケット合計-->
                    <div v-if="change_ticket!=0" class="col">
                        <div class="form-check">
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="">
                                    <i class="bi bi-ticket-perforated-fill fs-3 text-success"></i>
                                    <i class="bi bi-x"></i>
                                </div>

                                <div class="">
                                    <span class="fs-3 fw-bold">{{ totalTickets.toLocaleString()}}</span>
                                    <span> 枚</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gy-3">
                    <div class="col-12 col-md">
                        <!--ポイント交換ボタン(モーダル表示)-->
                        <button v-if="no_exchange_point==0"
                        type="button"
                        data-bs-toggle="modal" data-bs-target="#exchangeModal"
                        class="btn btn-warning text-white rounded-pill w-100" :disabled="disabled"
                        >ポイントと交換する</button>

                        <!--商品発送ボタン-->
                        <button v-else
                        type="submit"
                        class="btn btn-primary text-white rounded-pill w-100" :disabled="disabled"
                        >選択した商品を発送する</button>
                    </div>


                    <div v-if="change_ticket!=0" class="col-12 col-md">
                        <!--チケット交換ボタン(モーダル表示)-->
                        <button
                        type="button"
                        data-bs-toggle="modal" data-bs-target="#exchangeTicketModal"
                        class="btn btn-success text-white rounded-pill w-100" :disabled="disabled"
                        >チケットと交換する</button>
                    </div>
                </div>
                <p class="text-white form-text text-md-center m-0 my-3">
                    *選択されなかった商品は、
                    <a :href="r_user_prize" class="btn btn-link p-0 text-white">『取得した商品一覧』</a>に移動します。
                </p>
                <div class="">
                    <a :href="r_gacha_category"
                    class="btn text-danger rounded-pill w-100" :disabled="disabled"
                    >SKIP</a>
                </div>


            </div>
        </div>
        <!-- <div v-else>
            <div class="d-flex flex-wrap justify-content-center gap-3 fw-bold" style="font-size:14px; text-shadow: #fff 0px 0 5px;">
                <div  v-if="no_exchange_point==0">
                    <span class="text-warning">●</span>
                    <span>ポイント交換済み</span>
                </div>
                <div v-if="change_ticket!=0">
                    <span class="text-success">●</span>
                    <span>チケット交換済み</span>
                </div>
                <div>
                    <span class="text-primary">●</span>
                    <span>発送申請済み</span>
                </div>
            </div>
        </div> -->



        <!-- ポイント交換Modal -->
        <div v-if="no_exchange_point==0"
        class="modal fade" id="exchangeModal"
        tabindex="-1" aria-labelledby="exchangeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header justify-content-center border-0 pb-0">

                        <h5 id="AlertModalLabel" class="modal-title" style="font-size: 6rem;">
                            <i class="bi bi-p-circle text-warning"></i>
                        </h5>

                    </div>
                    <div class="modal-body text-center">
                        <h5 class="modal-title" id="exchangeTicketModalLabel">
                            <p>
                                商品を{{totalPoint.toLocaleString()+'pt'}}と交換します。<br>
                                よろしいですか？
                            </p>
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

                                <u-user-prize-exchange-point-btn
                                :token="token"
                                :r_api_post="r_api_exchange_points"
                                :r_redirect="r_redirect_exchange_points"
                                :user_prize_ids="ids"
                                btn_style_class="btn p-md-33 btn-warning text-white rounded-pill w-100"
                                btn_label="交換する"
                                />


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- チケット交換Modal -->
        <div v-if="change_ticket!=0"
        class="modal fade" id="exchangeTicketModal"
        tabindex="-1" aria-labelledby="exchangeTicketModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header justify-content-center border-0 pb-0">

                        <h5 id="AlertModalLabel" class="modal-title" style="font-size: 6rem;">
                            <i class="bi bi-ticket-perforated-fill text-success"></i>
                        </h5>

                    </div>
                    <div class="modal-body text-center">
                        <h5 class="modal-title" id="exchangeTicketModalLabel">
                            <p>
                                商品をチケット{{totalTickets.toLocaleString()+'枚'}}と交換します。<br>
                                よろしいですか？
                            </p>
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

                                <u-user-prize-exchange-ticket-btn
                                :token="token"
                                :r_api_post="r_api_exchange_tickets"
                                :r_redirect="r_redirect_exchange_tickets"
                                :user_prize_ids="ids"
                                btn_style_class="btn p-md-33 btn-success text-white rounded-pill w-100"
                                btn_label="交換する"
                                />


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
        r_user_prize:     { type: String, default: '',},//[ルーティング]取得商品一覧

        show_change_btn:  { type: String, default: '1' },
        no_exchange_point:{ type: [String,Number], default: 0 },
        change_ticket:    { type: [String, Number], default: 0 },

        r_api_exchange_points:     { type: String, default: '' },
        r_redirect_exchange_points:{ type: String, default: '',},//
        r_api_exchange_tickets:      { type: String, default: '' },
        r_redirect_exchange_tickets: { type: String, default: '' },

    });


    const loading     = ref(true); //
    const userPrizes  = ref([]);   /* ユーザー取得商品 */
    const ids         = ref([]);   /*チェックボックスのID*/

    const nextPageUrl = ref('');   /* 次のデータの読み込みURL */
    const allCheck    = ref(false);/*全てチェック*/
    const totalPoint  = ref(0);    /*チェック中のユーザー商品の合計ポイント*/
    const totalTickets = ref(0);   /*チェック中のユーザー商品の合計チケット*/

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
        calcTotalPoint();  //ポイント合計値の計算
        calcTotalTickets();//チケット合計値の計算
        disabled.value = !( ids.value.length > 0 );//選択なしのときは、disabled
    };

    /** 子チェックをクリック */
    const changeChildren = () => {
        const idsList = userPrizes.value.map(value => value.id);
        allCheck.value = ids.value.length === idsList.length;
        calcTotalPoint();  //ポイント合計値の計算
        calcTotalTickets();//チケット合計値の計算
        disabled.value = !( ids.value.length > 0 );//選択なしのときは、disabled
    };

    /** ポイント合計値の計算 */
    const calcTotalPoint = () => {
        totalPoint.value = userPrizes.value.reduce((sum, userPrize) => {
            return ids.value.includes(userPrize.id) ? sum + userPrize.point : sum;
        }, 0);
        // disabled.value = totalPoint.value === 0;
    };

    /** チケット合計値の計算 */
    const calcTotalTickets = () => {
        totalTickets.value = 0;

        //チケット交換がないとき
        if (props.change_ticket == 0){ return; }

        userPrizes.value.forEach(userPrize => {
            if (ids.value.includes(userPrize.id) && userPrize.prize) {
                totalTickets.value += userPrize.ticket;
            }
        })
        // disabled.value = totalTickets.value === 0;
    };

</script>
