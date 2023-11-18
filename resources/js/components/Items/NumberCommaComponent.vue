<template>
    <div class="d-inline-block">{{addCommasToNumber(number)}}</div>
</template>
<script>
    export default {
        props: {
            number: { type: [String,Number], default: '',},
        },
        methods:{

            /** 数字文字列にカンマを追加 */
            addCommasToNumber(number) {
                const string = String(number);
                return string.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },

        }
    };
</script>
