<template>
    <!--
    ==================================
     カードコンポーネント
    ==================================
    -->
    <div class="listCard" :class="[{'bg-light-primary':active}, style_class]">
        <div class="d-flex">

            <!--チェックボックス-->
            <label v-if="data_name"
            class="input-group-text p-2 bg-transparent border-0" style="cursor:pointer;">

                <input type="checkbox" :name="data_name" :value="data_value"
                v-model="data_ids" @change="changeChack()"
                class="listCardChildlenInput form-check-input mt-0">

            </label>

            <!--card-body-->
            <slot class="d-flex"></slot>

            <!-- <div>{{ data_value }}</div> -->
            <!-- <div>{{ data_name }}</div> -->
            <!-- <div>{{ copy_ids }}</div> -->
            <!-- <div>{{ data_ids }}</div> -->
        </div>
    </div>

</template>
<script>
    export default {
        props: {

            copy_ids:   { type: [Array,Object],  default: [], }, //
            data_value: { type: [String,Number], default: 0, },
            data_name:  { type: String,          default: '', },
            style_class: { type: String, default: 'card border-0', }, //
        },
        data : function() {
            return{

                /*チェックされたIDの配列*/
                //この中に値(data_value)が含まれていれば、チェックがつく
                data_ids: [],

                //子要素(data_value)がチェックされているか
                active: false,
            }
        },
        mounted() {
            this.data_ids = this.copy_ids;
        },
        watch: {
            //親コンポーネントの[全ての子チェック]が変更された時の監視
            copy_ids:{
                handler: function(){

                    //親チェックされたID配列をコピー
                    this.data_ids = this.copy_ids;

                    //子要素(data_value)がチェックされているか
                    this.active = this.data_ids.includes( this.data_value ) ;
                },
                deep: true,

            },
        },
        methods:{

            /* チェックボックスがチェックされたとき */
            changeChack: function(){
                // console.log(this.data_ids);
                this.$emit('change-children',this.data_ids);
            },
        }
    }
</script>
<style scoped>
    .bg-light-primary{
        background-color: rgb(0, 184, 230, .2) !important;
    }
</style>
