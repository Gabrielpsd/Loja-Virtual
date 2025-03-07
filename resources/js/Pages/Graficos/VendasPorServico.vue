<template>
    <div >
        <v-chart :option="vendasPorServico" class="chart-container" />
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
                this.vendasPorServico.series[0].data = newData;
            },
            immediate: true // Trigger the handler immediately on component mount
        }
    },
  data() {
    return {
      vendasPorServico: {
                title: {
                    text: 'Quantidade de vendas por serviços',
                    subtext: 'Total ',
                    left: 'right'
                },
                tooltip: {
                    trigger: 'item'
                },
                legend: {
                    orient: 'vertical',
                    left: 'left'
                },
                series: [
                    {
                        name: 'Vendas por Servico',
                        type: 'pie',
                        data:this.data,
                        radius: '40%',
                        emphasis: {
                            itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        label:{
                            show: true,
                            formatter: '{c}-({d}%)'
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
    height: 400px;
    max-width: 100%;
}
</style>