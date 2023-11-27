<template>
    <div>
        <canvas id="fukuoka_temperature_chart" class="w-100" height="300"></canvas>
    </div>
</template>
<script>
    export default {
        props: {
            s_labels:{ type: String,  default: '', },
            s_data:  { type: String,  default: '', },
        },
        data() { return {

            labels: ['01', '02', '03', '04', '05', '06', '07',],
            data:   [10,11,13,9,12,16,17,],

            max: 100, //グラフの最大値
        } },
        mounted() {

            this.labels = this.s_labels.split(',');
            this.data   = this.s_data.split(',');
            this.max =  Math.floor( Math.max( ...this.data )*1.1 );

            // 外部スクリプトの読み込み・新規作成
            this.createChart();

        },
        methods:{


            /** 外部スクリプトの読み込み・新規作成*/
            createChart(){

                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/chart.js@3.7.1';
                script.async = true;
                script.onload = () => {

                    // ここにChart.jsを使用するコードを記述する
                    this.newChart();

                };

                // body要素にスクリプトを追加
                document.body.appendChild(script);

            },


            /** 表の作成 */
            newChart(){
                let context = document.querySelector("#fukuoka_temperature_chart").getContext('2d')
                new Chart(context, {
                    type: 'line',
                    data: {
                        labels: this.labels,
                        datasets: [
                            {
                                label: "月間売上",
                                data:  this.data,
                                borderColor:     '#55b5d8',
                                backgroundColor: '#55b5d8',
                            },
                            // {
                            //     label: "前の期間",
                            //     data: [17,10,11,13,9,12,16,],
                            // },

                        ],
                    },
                    options: {
                        responsive: false,
                        scales: {
                            y: { min: 0, max: this.max, }
                        },
                    }
                })
            }
        },
    }
</script>
