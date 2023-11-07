<template>
    <div v-if="data_count > 0" class="col-12">
    <!-- <div class="col-12"> -->

        <nav class="card">
            <div class="d-flex justify-content-start">

                <!--全て選択チェックボックス-->
                <label class="input-group-text p-2 bg-white border-0" style="cursor:pointer;">
                    <input @change="changeParentInput()" v-model="parentInputCheked"
                    class="listCardParentInput form-check-input mt-0" type="checkbox">
                    <span class="ms-2 text-secondary">全て</span>
                </label>


                <slot></slot>

            </div>
        </nav>

    </div>
</template>
<script>
    export default {
        props: {

            //データ数が0のとき、メニューを非表示
            data_count:{ type: String, default: '1', }, //

        },
        data : function() {
            return{

                //「全て」のチェックボックス
                parentInputCheked: 0,

                // 全てのカードのチェックボックス要素
                listCardChildlenInputs: null,

                // 全ての「全て」のチェックボックス要素
                listCardParentInputs: null,


                listCard: null,
            }
        },
        mounted() {

            // 全てのカードのチェックボックス要素の取得
            this.listCardChildlenInputs = document.querySelectorAll('.listCardChildlenInput');

            // 全ての「全て」のチェックボックス要素の取得
            this.listCardParentInputs = document.querySelectorAll('.listCardParentInput');

            this.listCards = document.querySelectorAll('.listCard');
        },
        methods:{


            /* 「全て」のチェックボックスがクリックされたとき */
            changeParentInput: function(){

                // 全てのカードのチェックボックス要素
                for (let index = 0; index < this.listCardChildlenInputs.length; index++) {
                    const listCardChildlenInput = this.listCardChildlenInputs[ index ];

                    // 「全て」のチェックボックスと同じ値にする
                    listCardChildlenInput.checked = this.parentInputCheked;
                    // console.log( listCardChildlenInput.checked );
                }


                // 全ての「全て」のチェックボックス要素
                for (let index = 0; index < this.listCardParentInputs.length; index++) {
                    const listCardParentInput = this.listCardParentInputs[ index ];

                    // 「全て」のチェックボックスと同じ値にする
                    listCardParentInput.checked = this.parentInputCheked;
                    // console.log( listCardParentInput.checked );
                }


                // リストカードの背景色変更
                // for (let index = 0; index < this.listCards.length; index++) {
                // const listCard = this.listCards[ index ];

                //     if(this.parentInputCheked){
                //         listCard.classList.add('bg-light-primary'); //背景色を付ける
                //     }else{
                //         listCard.classList.remove('bg-light-primary'); //背景色を外す
                //     }
                // }


            }
        }
    }
</script>
