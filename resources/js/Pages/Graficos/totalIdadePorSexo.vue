<template>
    <div >  
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

        empresaData() {
            return this.uniqueAges.map(age => {
                const item = this.data.find(d => d.idade === age && d.sexo === 'Empresa');
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
                title: {
                    text: 'Q. clientes por sexo',
                    left: 'right'
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    padding: 5
                },
                legend: {
                    data: ['Masculino', 'Feminino', 'Empresa'],
                    left:'left',
                    itemHeight: 20
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
                        },
                        axisTick: {
                            alignWithLabel: true
                        },
                    }
                ],
                yAxis: [
                    {
                        type: 'value',
                        name: 'Quantidade'
                    }
                ],
                color: ["#6495ED", "#FF69B4","#27e002"],
                series: [
                    {
                        name: 'Masculino',
                        type: 'bar',
                        barHeight: '30%',
                        stack: 'stack', // Use the same stack name for both series
                        emphasis: {
                            focus: 'series'
                        },
                        data: this.masculinoData
                    },
                    {
                        name: 'Feminino',
                        type: 'bar',
                        barHeight: '30%',
                        stack: 'stack', // Use the same stack name for both series
                        emphasis: {
                            focus: 'series'
                        },
                        data: this.femininoData
                    },
                    {
                        name: 'Empresa',
                        type: 'bar',
                        barHeight: '30%',
                        stack: 'stack', // Use the same stack name for both series
                        emphasis: {
                            focus: 'series'
                        },
                        data: this.empresaData
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