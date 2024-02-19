<template>
    <div class="position-absolute top-0 start-0 w-100 h-100"
    style="z-index:1; background-color: rgb(0 0 0 / 70%);">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="">
                <div class="fs-3 fw-bold text-white">新作公開まであと</div>
                <div class="fs-1 fw-bold text-white">{{ formattedTime }}</div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        initial_time:{ type: String,  default: '01:00:00', },

    },
    data() { return {


        remainingTime: this.timeToSeconds(this.initial_time),
        timer: null


    }; },
    computed: {
        formattedTime() {
            const hours = Math.floor(this.remainingTime / 3600);
            const minutes = Math.floor((this.remainingTime % 3600) / 60);
            const seconds = this.remainingTime % 60;
            return `${this.padZero(hours)}:${this.padZero(minutes)}:${this.padZero(seconds)}`;
        }
    },
    methods: {
    startTimer() {
        this.timer = setInterval(() => {
            if (this.remainingTime > 0) {
                this.remainingTime--;
            } else {
                clearInterval(this.timer);
                window.location.reload(); // タイマーが0になったときにページをリロード
            }
        }, 1000);
    },
    padZero(value) {
        return (value < 10 ? '0' : '') + value;
    },
    timeToSeconds(time) {
        const [hours, minutes, seconds] = time.split(':').map(Number);
        return hours * 3600 + minutes * 60 + seconds;
    },
    },
    created() {
        this.startTimer();
    }};
</script>
