<template>
    <form id="fincode-form" class="m-0 bg-white mt-3 mx-auto" style="max-width: 600px;">
        <div id="fincode"></div>


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


        },
    }
</script>

  <style scoped>
  /* 必要に応じてCSSを追加してください */
  </style>
