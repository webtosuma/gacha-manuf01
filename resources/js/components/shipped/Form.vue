<template>

    <div class="mx-auto" style="max-width:900px;">
        <!-- お届け先選択 -->
        <section class="my-5">
            <h5>お届け先の選択</h5>

            <u-addressーlist-form
            :token="token" :r_index="r_index"
            :r_store="r_store" :r_destroy="r_destroy"
            @update-address="updateSelectedAddressId"
            ></u-addressーlist-form>
        </section>

        <!-- 利用ポイント -->
        <section class="my-5">
            <h5>利用ポイント</h5>
            <ul class="list-group bg-white">
                <li class="list-group-item p-3">
                    <div class="d-flex justify-content-between">
                        <span class="form-text">配送料・手数料：</span>
                        <span>0pt</span>
                    </div>
                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span class="">合計利用ポイント：</span>
                        <span class="text-danger">0pt</span>
                    </div>
                </li>
            </ul>
        </section>

        <!-- 発送する商品 -->
        <section class="my-5">
            <u-userprize-list
            :token="token"
            :u_prize_ids="u_prize_ids"
            :r_find="r_find"
            ></u-userprize-list>
        </section>


        <section class="my-5">
            <div class="col-md-8 mx-auto my-3">
                <div v-if="!selectedAddress" class="text-danger">*お届け先住所が選択されていません</div>

                <button type="submit"
                class="btn btn-lg btn-warning text-white w-100"
                :disabled="!selectedAddress"
                >発送内容を確認する</button>
            </div>
            <div class="col-md-8 mx-auto my-3">
                <a @click.prevent="historyBack()" href="#"
                class="btn btn-lg btn-light border w-100"
                >発送する商品を変更する</a>
            </div>
        </section>
    </div>

</template>
<script>
    export default {
        props: {
            token:{ type: String,  default: '', },
            /* お届け先一覧 */
            r_index:  { type: [String,Number], default: null },
            r_store:  { type: [String,Number], default: null },//＊新規作成コンポーネントで利用
            r_destroy:{ type: [String,Number], default: null },
            /* 発送商品リスト */
            u_prize_ids:  { type: [String], default: '' },
            r_find:  { type: [String,Number], default: null },
        },
        data() { return {

            selectedAddress: 0,

        } },
        methods: {

            /** お届け先アドレスの選択変更*/
            updateSelectedAddressId( id ) {
                this.selectedAddress = id;
            },


            /** 戻るボタン */
            historyBack() {
                history.back(); return false;
            },
        }
    }
</script>
