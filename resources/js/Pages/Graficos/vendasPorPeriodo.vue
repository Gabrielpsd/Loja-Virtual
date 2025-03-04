<template>
    <div >
        <v-chart :option="vendasPorPeriodo" class="chart-container" />
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
                this.vendasPorPeriodo.xAxis[0].data = newData.map(item => item.name);
                this.vendasPorPeriodo.series[0].data = newData.map(item => item.value);
            },
            immediate: true // Trigger the handler immediately on component mount
        }
    },
  data() {
    return {
      vendasPorPeriodo: {
        title: {
                    text: 'Vendas Por data',
                    subtext: 'Total ',
                    left: 'center'
                },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
            type: 'shadow'
            },
            formatter: (params) => {
                        // Format tooltip values as R$ X.XXX,XX
                        const param = params[0];
                        const value = param.value;
                        const formattedValue = value.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        });
                        return `${param.name}: R$ ${formattedValue}`;
            },
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: [
            {
            type: 'category',
            data:this.data.map( item => item.name),
            axisTick: {
                alignWithLabel: true
            }
            }
        ],
        yAxis: [
            {
                type: 'value',
                axisLabel: {
                    formatter: (value)=> {return `R$ ${value.toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })}`;
                    }
                }  
            }        
        ],
        series: [
            {
            name: 'Direct',
            type: 'line',
            barWidth: '60%',
            data: this.data.map( item => item.value),
            }
        ]
        }
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