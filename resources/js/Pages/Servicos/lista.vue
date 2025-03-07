<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import inputText from '../Utils/inputText.vue';
import { Head } from '@inertiajs/vue3';
import inputIntNumberDigit from '../Utils/inputIntNumberDigit.vue';
import ModalCadastraServico from '../modal/modalCadastraServico.vue';
import selectListMultiple from '../Utils/selectListMultiple.vue';
import modalEditaServico from '../modal/modalEditaServico.vue';

export default {
    props: {
        Servicos: Array
    },
    components:{
        AuthenticatedLayout,
        Head,
        inputText,
        inputIntNumberDigit,
        ModalCadastraServico,
        selectListMultiple,
        modalEditaServico
    },
    data(){
        return{
            servicos: this.Servicos,
            id: null,
            descricao: '',
            preco: null,
            cadastroServicoAtivo: false,
            ativado: null,
            servicoSelecionado: null,
            edicaoServico: false,
        }
    },
    methods:{

        editarServico(servico){
            const index = this.servicos.findIndex( c => c.id === servico.id)
            if(index !== -1)
                this.servicos[index] = servico

            this.servicoSelecionado == null
        },
        openModal(servico){
            const index = this.servicos.findIndex( c => c.id == servico.id)
            this.servicoSelecionado = this.servicos[index]
            this.edicaoServico = true
        }
    },
    computed:{
        filtraBusca(){
            let items =  this.servicos.filter((item)=>{
                if(this.descricao == null || this.descricao == '') return true
                
                //console.log(((item.descricao).toLowerCase().indexOf(this.descricao.toLowerCase()) > -1))
                return ((item.descricao).toLowerCase().indexOf(this.descricao.toLowerCase()) > -1)     
            })
            .filter((item)=>{
                if(this.id == null || this.id == '')
                    return true

                //console.log(((item.cpf_cnpj).toLowerCase().indexOf(this.cpf_cnpj.toLowerCase().replace(/\D/g,'')) > -1) )
                return item.id == this.id    
            })
            .filter((item)=>{

                if(this.ativado == null || this.ativado.length === 0)
                    return false

                return this.ativado.includes(item.ativado) 
            })

            return items
        },
     },

}
</script>
<template>
    <Head title="Servicos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Serviços</h2>
            <button class="btn btn-primary" @click="cadastroServicoAtivo = !cadastroServicoAtivo">Adicionar Serviço</button>
        </template>

        <!-- Modals -->
        <modalEditaServico v-if="edicaoServico" :Servico="servicoSelecionado" :Servicos="servicos" @fechaModal="edicaoServico = false; servicoSelecionado = null" @editar="editarServico" />
        <ModalCadastraServico v-if="cadastroServicoAtivo" :Servicos="Servicos" @fechaModal="cadastroServicoAtivo = false" @adicionar="servico => servicos.push(servico)" />

        <!-- Main Content -->
        <div class="py-12">
            <!-- Filters Section -->
            <div class="filters-container bg-white p-6 rounded-lg shadow-md mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Id</label>
                        <inputIntNumberDigit v-model="id" :maxLengh="4" class="w-full mt-1 p-2 border rounded-md" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descrição</label>
                        <inputText v-model="descricao" :maxLengh="30" class="w-full mt-1 p-2 border rounded-md" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cadastro Ativo</label>
                        <selectListMultiple id="Ativado" v-model="ativado" :options="[{ id: true, descricao: 'Ativado' }, { id: false, descricao: 'Desativado' }]" class="w-full mt-1 p-2 border rounded-md" />
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-container">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Preço</th>
                            <th scope="col">Situação</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="servico in filtraBusca" :key="servico.id">
                            <th scope="row">{{ servico.id }}</th>
                            <td>{{ servico.descricao }}</td>
                            <td>R$ {{ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(servico.preco) }}</td>
                            <td>{{ servico.ativado ? 'Ativado' : 'Desativado' }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" @click="openModal(servico)">Editar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
/* Base styles for filters container */
.filters-container {
    display: flex;
    flex-wrap: nowrap; /* Prevent wrapping to the next line */
    gap: 16px; /* Spacing between items */
    margin-bottom: 20px;
}

/* Styles for each filter item */
.filters-container > * {
    flex: 1; /* Distribute available space equally */
    box-sizing: border-box; /* Ensure padding and border are included in the width */
}

/* Media query for smaller screens */
@media (max-width: 1024px) {
    .filters-container {
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
    }

    .filters-container > * {
        flex: 1 1 calc(50% - 16px); /* Two items per row with spacing */
    }
}

/* Media query for smartphones */
@media (max-width: 767px) {
    .filters-container > * {
        flex: 1 1 100%; /* Stack items vertically on small screens */
    }
}
/* Table container */
.table-container {
    overflow-x: auto; /* Enable horizontal scrolling for small screens */
}

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f8f9fa;
}

/* Button styles */
.btn {
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 14px;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: #0056b3;
}
</style>
