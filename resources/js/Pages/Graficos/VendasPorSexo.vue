<template>
    <div class="py-12">
        <h2>Vendas Por Sexo</h2>
        <v-chart :option="VendasPorSexo" style="height: 400px;" />
    </div>
</template>

<script>
import config from '../Assets/ArquivosConfiguracao/apiconfig'
const csrfToken = document.getElementsByName("_token")[0].value; // Get CSRF token

export default {
  data() {
    return {
      VendasPorSexo: null,
    };
  },

  beforeMount() {
    // Fetch data from API and update the chart
    fetch(config.dashboard.vendasPorSexo,{
        method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken, // Include CSRF token in headers
                    },
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        this.VendasPorSexo = {
                title: {
                    text: 'Vendas Por Sexo',
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
                color: ["#6495ED","#FF69B4"],
                series: [
                    {
                        name: 'Vendas por Sexo',
                        type: 'pie',
                        radius: '50%',
                        data: data,
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
            };
      });
  },
};
</script>

<style scoped>
.chart-container {
    position: relative;
    height: 80px;
    width: 10%;
}
</style>