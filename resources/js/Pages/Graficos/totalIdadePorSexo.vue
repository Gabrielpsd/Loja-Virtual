<template>
    <div >
        <h4>Idades por sexo</h4>
        <v-chart :option="totalIdadePorSexo" class="chart-container" />
        <p>Idade</p>
    </div>
</template>
<script>
export default {
    props: {
        data: {
            type: Array,
            required: true
        }
    },
    computed: {
        // Compute unique ages for xAxis labels
        uniqueAges() {
            return [...new Set(this.data.map(item => item.idade))];
        },
        // Compute data for Masculino series
        masculinoData() {
            return this.uniqueAges.map(age => {
                const item = this.data.find(d => d.idade === age && d.sexo === 'Masculino');
                return item ? item.quantidade : 0;
            });
        },
        // Compute data for Feminino series
        femininoData() {
            return this.uniqueAges.map(age => {
                const item = this.data.find(d => d.idade === age && d.sexo === 'Feminino');
                return item ? item.quantidade : 0;
            });
        },
        // Compute the chart options
        totalIdadePorSexo() {
            return {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                legend: {
                    data: ['Masculino', 'Feminino']
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
                        data: this.uniqueAges,
                        name: 'Idade',
                        axisLabel: {
                            rotate: 45 // Rotate labels if needed
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value',
                        name: 'Quantidade'
                    }
                ],
                color: ["#6495ED", "#FF69B4"],
                series: [
                    {
                        name: 'Masculino',
                        type: 'bar',
                        stack: 'stack', // Use the same stack name for both series
                        emphasis: {
                            focus: 'series'
                        },
                        data: this.masculinoData
                    },
                    {
                        name: 'Feminino',
                        type: 'bar',
                        stack: 'stack', // Use the same stack name for both series
                        emphasis: {
                            focus: 'series'
                        },
                        data: this.femininoData
                    }
                ]
            };
        }
    }
};
</script>

<style scoped>
.chart-container {
    height: 350px;
    max-width: 100%;
}
</style>