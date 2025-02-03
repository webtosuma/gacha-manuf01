<template>
    <div class="">
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="z-index:1; background-color: rgb(0, 0, 0, .7 );">
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="">
                    <div class="fs-6 fw-bold text-white">{{ text }}</div>
                    <div class="fs-5 fw-bold text-white">あと<span class="fs-3">{{ remainingDays }}</span>日</div>
                    <div class="fs-1 fw-bold text-white">{{ formattedTime }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        // initial_datetime: { type: String, default: '2025/12/31 23:59:59' },
        initial_datetime: { type: String, default: '2025/12/31 23:59:59' },

        text: { type: String, default: '新作公開まであと' },
    },
    data() { return {
        remainingTime: this.calculateRemainingTime(),
        timer: null,
    }; },
    computed: {
        formattedTime() {
            const hours = Math.floor((this.remainingTime % 86400) / 3600);
            const minutes = Math.floor((this.remainingTime % 3600) / 60);
            const seconds = this.remainingTime % 60;
            return `${this.padZero(hours)}:${this.padZero(minutes)}:${this.padZero(seconds)}`;
        },
        remainingDays() {
            return Math.floor(this.remainingTime / 86400);
        }
    },
    methods: {
        startTimer() {
            this.timer = setInterval(() => {
                if (this.remainingTime > 0) {
                    this.remainingTime--;
                } else {
                    clearInterval(this.timer);
                    window.location.reload();
                }
            }, 1000);
        },
        padZero(value) {
            return (value < 10 ? '0' : '') + value;
        },
        calculateRemainingTime() {
            const now = new Date();
            const targetDate = new Date(this.initial_datetime.replace(/\//g, '-'));
            const diffTime = Math.floor((targetDate - now) / 1000);
            return diffTime > 0 ? diffTime : 0;
        }
    },
    created() {
        this.startTimer();
    }
};
</script>
