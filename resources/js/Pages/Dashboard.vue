<template>
    <Head title="DashBoard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="VendasPorSexo">
                <VendasPorSexo/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import config from '../Pages/Assets/ArquivosConfiguracao/apiconfig'
const csrfToken = document.getElementsByName("_token")[0].value; // Get CSRF token
import VendasPorSexo from '../Pages/Graficos/VendasPorSexo.vue'

export default {

    components: {
        Head,
        AuthenticatedLayout,
        VendasPorSexo
    },
  data() {
    return {
      VendasPorSexo: null,
    };
  },

};
</script>

<style scoped>
.chart-container {
    position: relative;
    height: 400px;
    width: 100%;
}
</style>