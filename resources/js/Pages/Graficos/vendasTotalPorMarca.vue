<template>
    <div >
        <v-chart :option="VendasPorMarca" class="chart-container" />
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
                this.VendasPorMarca.xAxis[0].data = newData.map(item => item.name);
                this.VendasPorMarca.series[0].data = newData.map(item => item.value);
            },
            immediate: true // Trigger the handler immediately on component mount
        }
    },
  data() {
    return {
      VendasPorMarca: {
        title: {
                    text: 'Valor total vendas Por marca',
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
            },
            axisLabel: {
                interval: 0, // Force all labels to show
                rotate: 45,  // Prevents overlap
                fontSize: 10 // Adjust text size
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
            type: 'bar',
            barWidth: '60%',
            data: this.data.map( item => item.value),
            label: {
                    show: true,
                    position: ['50%','-15%'], // Display value inside the bar
                    formatter: (params) => {
                    return `R$ ${params.value.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    })}`;
                    },
                    rotate:10,
                    color: '#000', // White text for contrast
                    fontSize: 14,
                },
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