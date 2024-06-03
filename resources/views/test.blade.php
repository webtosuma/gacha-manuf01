<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <!-- bodyタグ内に追加-->
    <form id="fincode-form" onsubmit="return payment(e)">
        <div id="fincode"></div>

        <button id="submit" type="button" onclick="payment()">お支払い</button>
    </form>


    <!-- headタグ内に追加 -->
    <script src="https://js.test.fincode.jp/v1/fincode.js"></script>
    <script>
        const fincode = Fincode( 'm_test_YWQ1YWFhNTAtMTYzMC00YzkyLWE2MWYtMDVmN2VhZDZiZmU1MWI2NTE0NDYtNGJmOC00MDhiLWFhNTEtMDgwOTZlMjZiM2Fkc18yNDAzMTI4ODU2Nw' )
        const appearance = {
            layout: "vertical",
            hideLabel: false,
            hideHolderName: false,
            hidePayTimes: false,
            labelCardNo: "カード番号",
            labelExpire: "有効期限",
            labelCvc: "セキュリティコード",
            labelHolderName: "カード名義人",
            labelPaymentMethod: "お支払い回数",
            cardNo: "1234 5678 9012 3456",
            expireMonth: "01",
            expireYear: "25",
            cvc: "001",
            holderName: "TARO YAMADA",
            colorBackground: "fff",
            colorBackgroundInput: "f5f5f5",
            colorText: "0f0f0f",
            colorPlaceHolder: "grey",
            colorLabelText: "6e6e6e",
            colorBorder: "e6e6e6",
            colorError: "c12424",
            colorCheck: "000054",

        }
        const ui = fincode.ui(appearance)
        ui.create("payments", appearance)
        ui.mount("fincode","420")


        // 決済実行を行う
        const payment = ()=>{
            // e.preventDefault();
            alert("フォームの送信が停止されました！");

            const transaction = {
                // id: document.getElementById('orderId').value,           // オーダーID
                // pay_type: document.getElementById('payType').value, // 決済種別
                // access_id: document.getElementById('accessId').value,   // 取引ID
                // card_no: document.getElementById('cardNo').value,       // カード番号
                // expire: document.getElementById('expire').value,        // カード有効期限(yymm)
                // method: document.getElementById('method').value,        // 支払方法
            }
            fincode.payments(transaction,
                function (status, response) {
                    if (status === 200) {
                    // リクエスト正常時の処理
                    } else {
                    // リクエストエラー時の処理
                    }
                },
                function () {
                    // 通信エラー処理。
                }
            );
        }
    </script>




</body>
</html>
