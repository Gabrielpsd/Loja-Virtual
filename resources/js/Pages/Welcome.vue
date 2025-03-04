<template>
    <div class="px-4 py-5 my-5 text-center">
        <!-- Center the image using mx-auto and block -->
        <img src="../Pages/Assets/logo.png" alt="Logo_empresa" class="mx-auto block mb-4 h-16 w-auto">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-4">Sistema De Controle de Oficina</h1>
        <p class="text-gray-600 mb-6">Selecione a opção desejada para continuar</p>
        <div class="max-w-lg mx-auto">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <Link
                    :href="loginRoute"
                    class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-all"
                >
                    Login
                </Link>
                <Link
                    :href="registerRoute"
                    class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-all"
                >
                    Criar Conta
                </Link>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import config from './Assets/ArquivosConfiguracao/apiconfig'

export default {
    components: {
        Link
    },
    data(){
        return{
            loginRoute: config.login,
            registerRoute: config.register
        }
    }
}
</script>