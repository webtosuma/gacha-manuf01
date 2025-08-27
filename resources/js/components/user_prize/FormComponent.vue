<template>
    <div class="text-dark">

        <!--ボトムメニュー-->
        <div v-if="bottom_menu == 'true'"
        class="position-fixed bottom-0 end-0 w-100 pb-3 bg-white border"
        style="border-radius: 1rem 1rem 0 0; z-index:50;">
            <div class="container p-0" style="max-width:900px;">

                <div class="row justify-content-between align-items-center g-2 px-2">
                    <!--すべて選択-->
                    <div :class="change_ticket!=0&&no_exchange_point==0 ? 'col-12 col-md' : 'col'">
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
                                    <span class="fs-3 fw-bold">{{ totalTickets.toLocaleString() }}</span>枚
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--BTN-->
                <div class="w-100 overflow-auto">
                    <div class="p-2 pt-0">
                        <div class="row g-2 justify-content-center align-items-end" >
                            <!--選択した商品をポイントと交換 -->
                            <div v-if="no_exchange_point==0" class="col order-2">

                                <button type="button" :disabled="disabled"
                                data-bs-toggle="modal" data-bs-target="#exchangeModal"
                                class="btn py-md-3 btn-warning text-white rounded-pill w-100"
                                >ポイントと交換</button>

                            </div>
                            <!--選択した商品をチケットと交換 -->
                            <div v-if="change_ticket!=0" class="col order-3">

                                <button type="button" :disabled="disabled"
                                data-bs-toggle="modal" data-bs-target="#exchangeTicketModal"
                                class="btn py-md-3 btn-success text-white rounded-pill w-100"
                                >チケットと交換</button>

                            </div>
                            <!--発送申請BTN-->
                            <div :class="change_ticket!=0&&no_exchange_point==0 ? 'col-12 col-md order-4 order-md-1' : 'col order-1'">

                                <form :action="r_shipped_appli" method="post">
                                    <input type="hidden" name="_token" :value="token">

                                    <input v-for="(id, key) in ids" :key="key"
                                    type="hidden" name="user_prize_ids[]" :value="id">

                                    <button type="submit" :disabled="disabled"
                                    class="btn py-md-3 btn-primary text-white rounded-pill w-100"
                                    >発送申請</button>
                                </form>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!--Headー-->
        <div class="row align-items-center gy-2">
            <div class="col-12 position-relative">
                <input v-model="search_key"
                @change="getData()"
                type="text" class="form-control rounded-pill" placeholder="商品名検索">

                <button @click="resetSearchKey"
                class="btn position-absolute top-50 translate-middle-y"
                style="right:1rem;">×</button>
            </div>
            <div class="col-12 col-lg">

                <select v-model="category_id"
                @change="getData()"
                class="form-select form-select-sm" >
                    <option v-for="(category,key) in categories" :key="key" :value="category.id">{{ category.name }}</option>
                </select>


            </div>
            <div class="col-12 col-md">
                <div class="d-flex gap-1">
                    <button v-for="(select_order,key) in select_orders" :key="key"
                    @click="changeOrder( select_order.value )"
                    class="btn btn-sm border rounded-pill"
                    :class=" order==select_order.value ? 'disabled btn-primary text-white' : 'btn-light' "
                    style="opacity:1;"
                    >{{ select_order.lable }}</button>
                </div>
            </div>

            <div class="col-auto">
                取得商品数：
                <span class="fs-1 fw-bold">
                    <!-- <number-comma-component :number="userPrizes.length" /> -->
                    <number-comma-component :number="total" />
                </span>
            </div>
        </div>


        <!--商品一覧-->
        <ul class="row px-3 bg-white text-dark rounded-3 mx-2 gy-3 mt-0" style="list-style:none;">

            <!--読み込み中-->
            <li v-if="loading"
            class="list-group-item bg-white text-dark py-5 fs-5 text-secondary">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </li>

            <li v-for="(userPrize, key) in userPrizes" :key="key"
            class="col-12 col-sm-6 col-lg-4">
                <!-- <label class="d-block " style="cursor:pointer;"> -->
                    <div class="row" v-if="userPrize.prize">
                        <label class="col-4 px-0 pe-3 position-relative"  style="cursor: pointer;">


                            <!--チェックボックス-->
                            <div v-if="bottom_menu == 'true'"
                            class="position-absolute top-0 start-0 translate-middle" style="z-index:5">

                                <input @change="changeChildren()"
                                v-model="ids" :value="userPrize.id"
                                class="form-check-input float-xl-none m-0 rounded-pill"
                                style="width:2em; height:2em;"
                                type="checkbox" name="user_prize_ids[]" >

                            </div>

                            <ratio-image-component
                            style_class="ratio ratio-3x4 rounded-3"
                            :url=" userPrize.prize.image_path " />


                        </label>
                        <div class="col-8 p-0">
                            <div class="form-text">取得日：{{ formatDate(userPrize.created_at) }}</div>
                            <h6 classs="fw-bold">{{ userPrize.prize.name }}</h6>

                            <!--交換ポイント-->
                            <div v-if="no_exchange_point==0" class="my-1">
                                <div class="d-flex gap-0 align-items-center justify-content-start" style="font-size:14px;">
                                    <i class="bi bi-p-circle  fs-5 text-warning"></i>

                                    <i class="bi bi-x"></i>

                                    <span class="fs-6">{{userPrize.point.toLocaleString()+'pt'}}</span>
                                </div>
                            </div>


                            <!--交換チケット-->
                            <div v-if="change_ticket!=0" class="my-1">
                                <div class="d-flex gap-0 align-items-center justify-content-start" style="font-size:14px;">
                                    <i class="bi bi-ticket-perforated-fill fs-5 text-success"></i>

                                    <i class="bi bi-x"></i>

                                    <span v-if="userPrize.prize.ticket" class="fs-6">{{userPrize.ticket.toLocaleString()+'枚'}}</span>
                                    <span v-else style="font-size:11px;">チケット交換なし</span>
                                </div>
                            </div>


                            <div class="form-text text-danger">{{ userPrize.deadline_text }}</div>

                            <div class="">


                                <!--商品説明モーダル-->
                                <button v-if="userPrize.prize.discription_text"
                                class="btn btn-sm btn-dark rounded-pill"
                                type="button"
                                data-bs-toggle="modal"
                                :data-bs-target="'#PrizeDiscriptionModal'+userPrize.id"
                                ><i class="bi bi-search me-2"></i>商品説明</button>

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
                    <div v-else class="py-5">
                        <!--商品情報が削除されたとき-->
                        *商品情報が削除されました
                    </div>


                <!-- </label> -->
            </li>

            <li v-if="!loading && userPrizes.length==0"
            class="py-3">*取得した商品はありません。</li>

        </ul>


        <!-- ポイント交換Modal -->
        <div v-if="no_exchange_point==0"
        class="modal fade" id="exchangeModal" tabindex="-1" aria-labelledby="exchangeModalLabel" aria-hidden="true">
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
        class="modal fade" id="exchangeTicketModal" tabindex="-1" aria-labelledby="exchangeTicketModalLabel" aria-hidden="true">
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
    import { ref, reactive, onMounted } from 'vue'
    import axios from 'axios'

    // Propsの定義
    const props = defineProps({

        token:             { type: String, default: '' },
        user_id:           { type: [String, Number], default: '' },
        bottom_menu:       { type: String, default: 'true' },
        no_exchange_point: { type: [String, Number], default: 0 },
        change_ticket:     { type: [String, Number], default: 0 },

        r_api_user_prize:  { type: String, default: '' }, //データ取得ルート
        r_shipped_appli:   { type: String, default: '' },//発送申請ルート

        r_api_exchange_points:       { type: String, default: '' },
        r_redirect_exchange_points:  { type: String, default: '' },
        r_api_exchange_tickets:      { type: String, default: '' },
        r_redirect_exchange_tickets: { type: String, default: '' },

    });

    // data 相当
    const loading      = ref(true); //
    const categories   = ref([]);   //ガチャ カテゴリー
    const userPrizes   = ref([]);   //ユーザー取得商品
    const total        = ref(0);    //ユーザー取得商品数

    const ids          = ref([]);   //チェックボックスのID
    const allCheck     = ref(false);//全てチェック
    const totalPoint   = ref(0);    //チェック中のユーザー商品の合計ポイント
    const totalTickets = ref(0);    //チェック中のユーザー商品の合計チケット

    const disabled     = ref(true); //
    const category_id  = ref(0);    //カテゴリーID
    const search_key   = ref('');   //検索キーワード
    const order        = ref('desc_created');//並び順


    const select_orders = [
        { lable: '新しい順', value: 'desc_created' },
        { lable: '古い順', value: 'asc_created' },
        { lable: '高ポイント順', value: 'desc_point' },
        { lable: '低ポイント順', value: 'asc_point' },
    ];//


    // onMounted でデータ取得
    onMounted(() => { getData(); });


    // methods 相当

    /* データ取得 */
    const getData = async (route = props.r_api_user_prize) => {
        const params = {
            _token: props.token,
            user_id: props.user_id,
            search_key: search_key.value,
            order: order.value,
            category_id: category_id.value,
        };

        try {
            const res = await axios.post(route, params);
            const data = res.data;
            categories.value = data.categories;
            categories.value[0].id = 0;

            // 商品情報の登録（新規登録・ページネーション追加）
            const paginate = data.user_prizes;
            userPrizes.value = route === props.r_api_user_prize
            ? paginate.data
            : [ ...userPrizes.value ,...paginate.data];

            total.value        = paginate.total;
            loading.value      = false;
            ids.value          = [];
            allCheck.value     = false;
            totalPoint.value   = 0;
            totalTickets.value = 0;

            /* 次のデータの読み込み */
            if (paginate.current_page !== paginate.last_page) {
                getData(paginate.next_page_url);
            }
        }
        catch (error) {
            alert('通信エラーが発生しました。')
            console.log(error.response?.data || error)
        }
    }

    /* 並び順の変更 */
    const changeOrder = (value) => {
        order.value = value;
        getData();
    }

    /* 検索キーワードのリセット */
    const resetSearchKey = () => {
        search_key.value = '';
        getData();
    };

    /** 全て選択をクリック */
    const changeAll = () => {
        const allIds = userPrizes.value.map(v => v.id);
        ids.value = allCheck.value ? allIds : [];
        calcTotalPoint();  //ポイント合計値の計算
        calcTotalTickets();//チケット合計値の計算
        disabled.value = !( ids.value.length > 0 );//選択なしのときは、disabled
    };

    /** 子チェックをクリック */
    const changeChildren = () => {
        const allIds = userPrizes.value.map(v => v.id);
        allCheck.value = ids.value.length === allIds.length;
        calcTotalPoint();  //ポイント合計値の計算
        calcTotalTickets();//チケット合計値の計算
        disabled.value = !( ids.value.length > 0 );//選択なしのときは、disabled
    };

    /** ポイント合計値の計算 */
    const calcTotalPoint = () => {
        totalPoint.value = 0;
        userPrizes.value.forEach(userPrize => {
            if (ids.value.includes(userPrize.id) && userPrize.prize) {
                totalPoint.value += userPrize.point;
            }
        });
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
    };

    /** 日付データをテクスト変換  */
    const formatDate = (inputString) => {
        const date = new Date(inputString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}/${month}/${day}`;
    };
</script>
