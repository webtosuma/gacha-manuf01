<template>
    <div class="mb-3">



        <div v-if="user_shippeds.length>0"
        class="card bg-white overflow-hidden">
            <table class="table text-center" style="font-size:11px;">
                <thead class="border-bottom">
                    <th>郵便番号</th>
                    <th>氏名</th>
                    <th>敬称</th>
                    <th>住所1</th>
                    <th>住所2</th>
                    <th>住所3</th>
                    <th>住所4</th>
                    <th>内容品</th>
                    <th>備考</th>
                    <th>発送商品</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="( user_shipped, key ) in user_shippeds" :key="key">
                        <td>{{ user_shipped.user_address.postal_code }}</td>
                        <td>{{ user_shipped.user_address.name }}</td>
                        <td>{{ user_shipped.user_address.name+' 様' }}</td>
                        <td>{{ user_shipped.user_address.todohuken }}</td>
                        <td>{{ user_shipped.user_address.shikuchoson }}</td>
                        <td>{{ user_shipped.user_address.number }}</td>
                        <td></td>
                        <td>*商品内容</td>
                        <td>
                            <span v-if="user_shipped.user_address.size" class="me-3">{{ user_shipped.user_address.size }}</span>
                            <span v-if="user_shipped.user_address.remarks_text" class="me-3">{{ user_shipped.user_address.remarks_text }}</span>
                        </td>
                        <td>{{ user_shipped.prizes_string }}</td>
                        <td>
                            <div v-if="user_shipped.shipment_at_format"
                            >{{ user_shipped.shipment_at_format }}</div>
                            <div>{{ user_shipped.created_at_format }}</div>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>


        <div v-else class="py-3">*発送の履歴はありません。</div>



    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({

        user_shippeds:  { type: [Array,Object], default: [] },

    });


    /* 初回データ取得 */
    onMounted(() => { });


</script>
