<template>
    <div>
        <select @change="redirectTo()" v-model="selected"
        class="form-select border-0 bg-white text-center fw-bold text-primary">

            <option v-for="(month, key) in allMonths" :key="key"
            :value="month" class="text-dark">{{ formatMonth(month) }}</option>

        </select>
    </div>
</template>
<script>
    export default {
        props: {
            r_point_history:{ type: String,  default: '', },
            all_months:{ type: String,  default: '', },
            selected_month:{ type: String,  default: '', },
        },
        data() { return {

            allMonths: [],//選択肢
            selected: '',//選択中の値

        } },
        mounted() {
            // テキストを配列へ変換
            this.allMonths = this.all_months.split(',');
            this.selected  = this.selected_month;
        },
        methods:{

            /**日付表記を年月テキストに変更するメソッド*/
            formatMonth(inputDate) {
                // 入力された日付文字列からDateオブジェクトを作成
                var dateObject = new Date(inputDate);

                // 年、月、日を取得
                var year = dateObject.getFullYear();
                var month = ("0" + (dateObject.getMonth() + 1)).slice(-2); // 月は0から始まるため+1する
                var day = ("0" + dateObject.getDate()).slice(-2);

                // フォーマットに従って日付を組み立てる
                var formattedDate = year + "年" + month + "月";

                return formattedDate;
            },


            /** リダイレクトする関数 */
            redirectTo() {
                const url = this.r_point_history+'/'+this.selected;
                window.location.href = url;
            },
        },
    }
</script>
