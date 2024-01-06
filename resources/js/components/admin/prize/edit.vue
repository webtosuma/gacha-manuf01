<template>
    <div>


        <!--カテゴリー-->
        <section class="mb-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a
                    :class="{'active disabled': inputs.category_id == ''}" class="nav-link"
                    >{{ 'すべて' }}</a>

                </li>
                <li v-for="(category, key) in categories" :key="key"
                class="nav-item">
                    <a
                    :class="{'active disabled': inputs.category_id == category.id}" class="nav-link text-secondary"
                    >{{ category.name }}</a>

                </li>
            </ul>
        </section>


        <!--操作ボタン-->
        <section class="mb-2">
            <div class="row g-3 ">
                <div class="col-auto">
                    <button @click="toggleEdit()"
                    class="btn btn-lg btn-warning text-white" type="button"
                    ><i class="bi bi-pencil-fill me-2"></i>一括編集を終了する</button>
                </div>
                <!-- <div class="col fs-1 text-danger">未完成　テスト段階　使用不可！！</div> -->
            </div>
        </section>


        <!--テーブル-->
        <section class="card card-body bg-white my-3 overflow-auto border-warning border-3" style="height: 90vh;">
            <table class="table" style="min-width: 600px; font-size: 16px;">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="">

                        <th scope="col" style="width:4rem;">画像</th>

                        <th scope="col">商品コード</th>

                        <th scope="col">商品名</th>

                        <th scope="col">評価ランク</th>

                        <th scope="col">交換ポイント</th>

                        <th scope="col">更新</th>

                        <th><!--編集--></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(prize, key) in prizes" :key="key">
                        <td scope="row">
                            <!--画像-->
                            <div style="width:3rem;">
                                <ratio-image-component
                                style_class="ratio ratio-3x4 rounded-3"
                                :url=" prize.image_path " />
                            </div>
                        </td>
                        <td>
                            <!--商品コード-->
                            <input v-model="prize.code"
                            @change="update(prize)"
                            type="text" class="form-control">
                        </td>
                        <td>
                            <!--商品名-->
                            <input v-model="prize.name"
                            @change="update(prize)"
                            type="text" class="form-control">
                        </td>
                        <td>
                            <!--評価ランク-->
                            <select v-model="prize.rank_id"
                            @change="update(prize)"
                            class="form-select form-select-sm fw-bold">
                                <option v-for="(prize_rank, key) in selects.prize_ranks" :key="key"
                                :value="prize_rank.id">{{ prize_rank.name }}</option>
                            </select>
                        </td>
                        <td>
                            <!--交換ポイント-->
                            <div class="row g-2 align-items-center">
                                <div class="col">
                                    <input v-model="prize.point"
                                    @change="update(prize)"
                                    type="text" class="form-control text-end">
                                </div>
                                <div class="col-auto">pt</div>
                            </div>
                        </td>
                        <td class="form-text">{{ formatDate( prize.updated_at ) }}</td>
                        <td class="">
                            <div class="d-flex gap-2 justify-content-end h-100">
                                <div class="" style="width:4rem;">
                                    <span v-if="prize.is_used"
                                    class="badge rounded-pill bg-success">{{ '利用中' }}</span>
                                </div>
                                <!--削除モーダル-->
                                <!-- <delete-modal-component
                                @parent-func="destory(prize.id)"
                                :indexKey="'delete'+prize.id"
                                icon="bi-trash"
                                :button_class=" prize.is_used ? 'disabled btn btn-sm btn-secondary border' :'btn btn-sm btn-light border' ">
                                    <div>この商品を削除します。<br />よろしいですか？</div>
                                    <div class="form-text">商品コード：{{ prize.code }}</div>
                                    <div class="form-text">商品名：{{ prize.name }}</div>
                                </delete-modal-component> -->
                            </div>
                        </td>
                    </tr>

                    <tr v-if="prizes.length==0">
                        <td colspan="8" class="text-center text-secondary border-0 py-5">
                            *商品の登録情報はありません。
                        </td>
                    </tr>

                </tbody>
            </table>
        </section>

    </div>
</template>
<script>
    import axios from 'axios'

    export default {

        props: {
            token:{ type: String,  default: '', },
            inputs:     { type: [Array,Object],  default: '', },
            categories: { type: [Array,Object],  default: '', },
            prop_prizes:{ type: [Array,Object],  default: '', },
            selects:    { type: [Array,Object],  default: '', },
        },
        data() { return {

            prizes:  [],/* 商品 */


        } },
        watch: {
            prop_prizes: {
                handler(){ this.prizes = this.prop_prizes; },deep:true
            },
        },
        methods: {

            /** 編集モード切り替え */
            toggleEdit(){
                this.$emit('toggle-edit')
            },

            /** 編集モード切り替え */
            update(prize){ this.$emit('edit-update',prize) },


            /** 日付データをテクスト変換  */
            formatDate(inputString) {
                const date = new Date(inputString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1し、2桁にパディング
                const day = String(date.getDate()).padStart(2, '0'); // 日も2桁にパディング

                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');

                return `${year}/${month}/${day} ${hours}:${minutes}:${seconds}`;

            },

        }
    }
</script>
