<template>
  <div>
    <svg ref="chart" style="width:100%; height:300px;"></svg>
  </div>
</template>

<script>
export default {
  name: 'LineChart',
  props: {
    s_labels: {
      type: Array,
      default: () => []
    },
    s_data: {
      type: Array,
      default: () => []
    }
  },
  mounted() {
    this.drawChart();
  },
  watch: {
    s_labels: 'drawChart',
    s_data: 'drawChart'
  },
  methods: {
    drawChart() {
        if (typeof nv === 'undefined' || !this.s_labels.length || !this.s_data.length) {
            // データがない時の処理（空のグラフかメッセージ表示など）
            d3.select(this.$refs.chart).html(''); // グラフクリア
            return;
        }

        const labels = this.s_labels;
        const values = this.s_data.map(Number);
        const maxYRaw = Math.max(...values);

        // 最大値が0以下のときは1にセット
        const maxY = maxYRaw > 0 ? maxYRaw : 1;
        const paddedMaxY = Math.ceil(maxY * 1.2);

        const data = [
            {
            key: "データ系列",
            values: labels.map((label, i) => ({ x: i, y: values[i] }))
            }
        ];

        nv.addGraph(() => {
            const chart = nv.models.lineChart()
            .useInteractiveGuideline(true)
            .margin({ left: 50, right: 30, top: 30, bottom: 50 })
            .showLegend(false);

            chart.xAxis
            .axisLabel('日付')
            .tickFormat(i => labels[i] || '');

            chart.yAxis
            // .axisLabel('値')
            .tickFormat(d3.format(',.0f'));

            // Y軸は0始まり、上限は paddedMaxY（最低1以上）
            chart.forceY([0, paddedMaxY]);

            d3.select(this.$refs.chart).html(''); // クリア
            d3.select(this.$refs.chart)
            .datum(data)
            .call(chart);

            nv.utils.windowResize(chart.update);
            return chart;
        });
    }
  }
};
</script>
