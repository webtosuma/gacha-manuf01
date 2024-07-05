<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
        <!-- headタグ内に追加 -->
        <script src="https://js.test.fincode.jp/v1/fincode.js"></script>
        <script>
            // const fincode = Fincode(`{{config('fincode.secret_key','')}}`)
            const fincode = Fincode("m_test_YWQ1YWFhNTAtMTYzMC00YzkyLWE2MWYtMDVmN2VhZDZiZmU1MWI2NTE0NDYtNGJmOC00MDhiLWFhNTEtMDgwOTZlMjZiM2Fkc18yNDAzMTI4ODU2Nw");

            var ui = fincode.ui({layout: "vertical"})
            ui.create("payments",{layout: "vertical"})
            ui.mount("fincode",'400');

            // const appearance = {
            //     layout: "vertical",
            //     hideLabel: false,
            //     hideHolderName: false,
            //     hidePayTimes: true,
            //     labelCardNo: "カード番号",
            //     labelExpire: "有効期限",
            //     labelCvc: "セキュリティコード",
            //     labelHolderName: "カード名義人",
            //     cardNo: "1234 5678 9012 3456",
            //     expireMonth: "01",
            //     expireYear: "25",
            //     cvc: "001",
            //     holderName: "TARO YAMADA",
            //     colorBackground: "fff",
            //     colorBackgroundInput: "f5f5f5",
            //     colorText: "0f0f0f",
            //     colorPlaceHolder: "grey",
            //     colorLabelText: "6e6e6e",
            //     colorBorder: "e6e6e6",
            //     colorError: "c12424",
            //     colorCheck: "000054",
            // }
            // const ui = fincode.ui(appearance)
            // ui.create("payments", appearance)
            // ui.mount("fincode","420")
        </script>

</body>
</html>
