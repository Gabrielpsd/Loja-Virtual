<template>
    <div >
        <v-chart :option="VendasPorSexo" class="chart-container" />
    </div>
</template>

<script>

export default {
    props: {
        data:{
            type: [Array],
            required: true
        }
    },
    watch: {
        data: {
            handler(newData) {
                // Update the chart's data when the prop changes
                this.VendasPorSexo.series[0].data = newData;
            },
            immediate: true // Trigger the handler immediately on component mount
        }
    },
  data() {
    return {
      VendasPorSexo: {
        title: {
                    text: 'Quantidade de vendas por Sexo',
                    subtext: 'Total ',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item'
                },
                legend: {
                    orient: 'vertical',
                    left: 'left'
                },
                color: ["#6495ED", "#FF69B4","#27e002"],
                series: [
                    {
                        name: 'Total venda por sexo',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        center: ['50%', '70%'],
                        // adjust the start and end angle
                        startAngle: 180,
                        endAngle: 360,
                        data: this.data,
                        emphasis: {
                            itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        label:{
                            show: true,
                            formatter: '{b}: ({d})%'
                        }
                    }
                ]
      },
    };
  },
};
</script>

<style scoped>
.chart-container {
    height: 350px;
    max-width: 100%;
}
</style>