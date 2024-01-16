<template>
    <div class="mb-3 w-100">
        <div class="form-steps-pill mx-auto text-secondary py-3">
            <div class="step-box"     :class="{'text-primary':card_num >= 1}" >
                <div class="step_num" :class="{' bg-primary':card_num  >= 1}">1</div>
                <span style="font-size:10px;">ステップ1</span>
            </div>
            <i class="bi bi-caret-right-fill mb-3" :class="{'text-primary':card_num >= 2}"></i>
            <div class="step-box"     :class="{'text-primary':card_num >= 2}" >
                <div class="step_num" :class="{' bg-primary':card_num  >= 2}">2</div>
                <span style="font-size:10px;">ステップ2</span>
            </div>
            <i class="bi bi-caret-right-fill mb-3" :class="{'text-primary':card_num >= 3}"></i>
            <div class="step-box"     :class="{'text-primary':card_num >= 3}" >
                <div class="step_num" :class="{' bg-primary':card_num  >= 3}">3</div>
                <span style="font-size:10px;">入力確認</span>
            </div>
            <i class="bi bi-caret-right-fill mb-3" :class="{'text-primary':card_num >= 4}"></i>
            <div class="step-box"     :class="{'text-primary':card_num >= 4}" >
                <div class="step_num" :class="{' bg-primary':card_num  >= 4}">4</div>
                <span style="font-size:10px;">完了</span>
            </div>
            <!-- <i class="bi bi-caret-right-fill mb-3" :class="{'text-primary':card_num >= 5}"></i>
            <div class="step-box"     :class="{'text-primary':card_num >= 5}" >
                <div class="step_num" :class="{' bg-primary':card_num  >= 5}">5</div>
                <span style="font-size:10px;">完了</span>
            </div> -->
        </div>


    </div>
</template>
<script>

    export default{

        props: {
            card_num: { type: Number, default: 1, },
        },
        computed: {
            appName() { return this.$store.getters['env/appName']; },
        },



    }
</script>
<style scoped>

    .form-steps-pill {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .form-steps-pill .step-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1;
        color: hsl(204deg, 20%, 60%);
        font-weight: bold;
        width: 3rem;
    }
    .form-steps-pill .step_num {
        width: 2rem;
        height: 2rem;
        line-height: 2rem;
        background-color: hsl(204deg, 20%, 60%);
        color: white;
        border-radius: 50%;
        text-align: center;
    }
</style>
