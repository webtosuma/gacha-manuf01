<template>
    <form id="fincode-form"
    @submit.prevent="handlePayment"
    class="m-0 bg-white mt-3 mx-auto" style="max-width: 600px;">

        <div id="fincode"></div>

        <div class="text-danger text-center p-3">{{ errorMessage }}</div>


        <button id="submit" class="btn btn-primary text-white rounded-pill w-100">
            <span>お支払い</span>
        </button>
    </form>
</template>

<script>
    export default {
        data() { return {

            errorMessage: '',

        }; },
        mounted() {

            /* UIのマウント */
            this.uiMount();


        },
        methods: {

            /* UIのマウント */
            uiMount() {
                const fincode = Fincode('m_test_YWQ1YWFhNTAtMTYzMC00YzkyLWE2MWYtMDVmN2VhZDZiZmU1MWI2NTE0NDYtNGJmOC00MDhiLWFhNTEtMDgwOTZlMjZiM2Fkc18yNDAzMTI4ODU2Nw');
                const appearance = {
                    layout: 'vertical',
                    hideLabel: false,
                    hideHolderName: false,
                    hidePayTimes: true,
                    labelCardNo: 'カード番号',
                    labelExpire: '有効期限',
                    labelCvc: 'セキュリティコード',
                    labelHolderName: 'カード名義人',
                    cardNo: '1234 5678 9012 3456',
                    expireMonth: '01',
                    expireYear: '25',
                    cvc: '001',
                    holderName: 'TARO YAMADA',
                    colorBackground: 'fff',
                    colorBackgroundInput: 'f5f5f5',
                    colorText: '0f0f0f',
                    colorPlaceHolder: 'grey',
                    colorLabelText: '6e6e6e',
                    colorBorder: 'e6e6e6',
                    colorError: 'c12424',
                    colorCheck: '000054',
                };
                const ui = fincode.ui(appearance);
                ui.create('payments', appearance);
                ui.mount('fincode', '420');
            },

            handlePayment() {
                console.log(
                    document.querySelector('input[name="cardNumber"]')
                );

                const transaction = {
                    // id: document.getElementById('orderId').value ,       // オーダーID
                    // pay_type: document.getElementById('payType').value , // 決済種別
                    // access_id: document.getElementById('accessId').value , // 取引ID


                    // card_no: document.getElementById('cardNumber').value ,   // カード番号

                    // cardExpirationMonth: document.getElementById('cardExpirationMonth').value,
                    // cardExpirationYear: document.getElementById('cardExpirationYear').value,



                    // expire: document.getElementById('expire').value,    // カード有効期限(yymm)
                    // method: document.getElementById('method').value ,    // 支払方法
                };
                // fincode.payments(
                //     transaction,
                //     (status, response) => {
                //     if (status === 200) {
                //         console.log(response);
                //         // リクエスト正常時の処理
                //     } else {
                //         this.errorMessage = '決済に失敗しました。';
                //     }
                //     },
                //     () => {
                //     this.errorMessage = '通信エラーが発生しました。';
                //     }
                // );
            },
        },
    }
</script>

  <style scoped>
  /* 必要に応じてCSSを追加してください */
  </style>
