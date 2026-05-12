<template>
    <div v-if="card_num===0" class="anima-fadein-bottom">

        <div class="card shadow border-0 w-100 p-3 mb-3 bg-white">
            <div class="card-body">

                <h2 class="fw-bold mb-4">確認事項</h2>

                <p class="mb-2">
                    以下の項目をご確認のうえ、すべてにチェックを入れてください。
                </p>

                <p class="text-danger fw-bold mb-4">
                    ※すべてにチェックを入れないと、次へ進むことはできません。
                </p>

                <hr class="mb-4">

                <div class="d-grid gap-3">

                    <!-- チェック項目 -->
                    <label class="border rounded-4 p-4">

                        <div class="form-check m-0">

                            <input
                                v-model="checks.check1"
                                class="form-check-input"
                                type="checkbox"
                                id="check1"
                            >

                            <div class="form-check-label fs-5 ms-2">
                                未成年者ではないこと
                            </div>

                        </div>

                    </label>

                    <label class="border rounded-4 p-4">

                        <div class="form-check m-0">

                            <input
                                v-model="checks.check2"
                                class="form-check-input"
                                type="checkbox"
                                id="check2"
                            >

                            <div class="form-check-label fs-5 ms-2">
                                反社会的勢力関係者ではないこと
                            </div>

                        </div>

                    </label>

                    <label class="border rounded-4 p-4">

                        <div class="form-check m-0">

                            <input
                                v-model="checks.check3"
                                class="form-check-input"
                                type="checkbox"
                                id="check3"
                            >

                            <div class="form-check-label fs-5 ms-2">
                                同一人物による重複登録ではないこと
                            </div>

                        </div>

                    </label>

                    <label class="border rounded-4 p-4">

                        <div class="form-check m-0">

                            <input
                                v-model="checks.check4"
                                class="form-check-input"
                                type="checkbox"
                                id="check4"
                            >

                            <div class="form-check-label fs-5 ms-2">
                                過去に資格取消、または利用制限を受けていないこと
                            </div>

                        </div>

                    </label>

                </div>

                <!-- ボタン -->
                <div class="row mb-3 mt-5">

                    <div class="col-sm-8 offset-sm-2">

                        <button
                            type="button"
                            @click="addCardNum"
                            class="btn btn-curve btn-primary text-white w-100"
                            :disabled="!isAllChecked"
                        >
                            <span>
                                {{ '次のステップへ　進む' }}
                            </span>
                        </button>

                    </div>

                </div>

            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {

    props: {
        card_num: {
            type: Number,
            default: 1,
        },
    },

    data() {
        return {

            loading: false,/* 通信中 */

            checks: {
                check1: false,
                check2: false,
                check3: false,
                check4: false,
            },

        }
    },

    computed: {

        /** 全チェック確認 */
        isAllChecked() {

            return Object.values(this.checks).every(value => value);

        }

    },

    methods: {

        /** 次へメソッド */
        addCardNum() {

            if (!this.isAllChecked) {
                return;
            }

            this.$emit('add-card-num');

        },

    }

}
</script>