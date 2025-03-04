<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import inputText from '../Utils/inputText.vue';
import { Head } from '@inertiajs/vue3';
import inputIntNumberDigit from '../Utils/inputIntNumberDigit.vue';
import selectListMultiple from '../Utils/selectListMultiple.vue';
import modalCadastraOrdemServico from '../modal/modalCadastraOrdemServico.vue';
import modalEditaOrdemServico from '../modal/modalEditaOrdemServico.vue';
import datePickerInterval from '../Utils/datePickerInterval.vue'; 
export default {
    props: {
        OrdemServicos: Array,
        Clientes: Array,
        Veiculos: Array,
        Servicos: Array,
    },
    components:{
        AuthenticatedLayout,
        Head,
        inputText,
        inputIntNumberDigit,
        modalCadastraOrdemServico,
        selectListMultiple,
        modalEditaOrdemServico,
        datePickerInterval
    },
    data(){
        return{
            ordemServicos: this.OrdemServicos,
            id: null,
            clientes: null,
            veiculos: null,
            preco: null,
            cadastroOrdemServico: false,
            edicaoServico: false ,
            ordemServicoSelecionado: null,
            datasFiltro: null
        }
    },
    methods:{

        editarServico(servico){
            
            const index = this.ordemServicos.findIndex( c => c.id === servico.id)
            if(index !== -1)
                this.OrdemServicos[index] = servico

            this.ordemServicoSelecionado == null
        },
        openModal(ordemServico){
            const index = this.ordemServicos.findIndex( c => c.id == ordemServico.id)
            this.ordemServicoSelecionado = this.ordemServicos[index]
            this.edicaoServico = true
        },
        calculaTotal(servicos){
            let valor = 0.0
            servicos = JSON.parse(servicos)
            if(servicos)
                servicos.forEach(element => {
                    valor += element.preco * element.quantidade
                });

            return valor
        },
        maskedDate(date){
            const [year, month, day] = date.split('-');
            return `${day}/${month}/${year}`;

        },
        adicionaOrdem(ordem){
            this.ordemServicos.push(ordem)
            this.openModal(ordem)
        }
    },

    computed:{
        filtraBusca(){
            let items =  this.ordemServicos
            .filter((item)=>{
                if(this.id == null || this.id == '')
                    return true

                //console.log(((item.cpf_cnpj).toLowerCase().indexOf(this.cpf_cnpj.toLowerCase().replace(/\D/g,'')) > -1) )
                return item.id == this.id    
            })
            .filter((item)=>{
                if(this.clientes == null || this.clientes.length === 0)
                    return false

                //console.log(((item.cpf_cnpj).toLowerCase().indexOf(this.cpf_cnpj.toLowerCase().replace(/\D/g,'')) > -1) )
                return this.clientes.includes(item.id_cliente) 
            })
            .filter((item)=>{
                if(this.veiculos == null || this.veiculos.length === 0)
                    return false

                //console.log(((item.cpf_cnpj).toLowerCase().indexOf(this.cpf_cnpj.toLowerCase().replace(/\D/g,'')) > -1) )
                return this.veiculos.includes(item.id_veiculo) 
            })
            .filter((item)=>{

                if(this.datasFiltro == null)
                    return true

                const dataServico = new Date(item.data)
                const dataInicial = new Date(this.datasFiltro[0])
                const dataFinal = new Date(this.datasFiltro[1])

                return (dataServico >= dataInicial && dataServico <= dataFinal)
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ordem Serviços</h2>
            <button class="btn btn-primary" @click="cadastroOrdemServico = !cadastroOrdemServico">Adicionar Serviço</button>
        </template>

        <!-- Modals -->
        <modalEditaOrdemServico v-if="edicaoServico" :OrdemServico="ordemServicoSelecionado" :Servicos="Servicos" :Clientes="Clientes" :Veiculos="Veiculos" @fechaModal="edicaoServico = false; ordemServicoSelecionado = null" @editar="editarServico" />
        <modalCadastraOrdemServico v-if="cadastroOrdemServico" :Clientes="Clientes" :Veiculos="Veiculos" @fechaModal="cadastroOrdemServico = false" @adicionar="adicionaOrdem" />

        <!-- Main Content -->
        <div class="py-12">
            <!-- Filters Section -->
            <div class="filters-container">
                <inputIntNumberDigit v-model="id" :maxLengh="4" :label="'Id'" />
                <selectListMultiple id="Cliente" :label="'Cliente'" v-model="clientes" :options="Clientes" />
                <selectListMultiple id="Veiculo" :label="'Veiculo'" v-model="veiculos" :options="Veiculos" />
                <datePickerInterval v-model="datasFiltro" :Label="'Data do Servico'" />
            </div>

            <!-- Table Section -->
            <div class="table-container">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Veículo</th>
                            <th scope="col">Data Serviço</th>
                            <th scope="col">Preço Total</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ordemServico in filtraBusca" :key="ordemServico.id">
                            <th scope="row">{{ ordemServico.id }}</th>
                            <td>{{ ordemServico.nome }}</td>
                            <td>{{ ordemServico.descricao_veiculo }}</td>
                            <td>{{ maskedDate(ordemServico.data) }}</td>
                            <td>R$ {{ calculaTotal(ordemServico.servicos).toFixed(2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" @click="openModal(ordemServico)">Editar</button>
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