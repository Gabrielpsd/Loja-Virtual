<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import selectListMultiple from '../Utils/selectListMultiple.vue'
import CpfCNPJinput from '../utils/CpfCNPJinput.vue';
import inputText from '../Utils/inputText.vue';
import datePickerInterval from '../Utils/datePickerInterval.vue';
import { Head } from '@inertiajs/vue3';
import modalCadastraVeiculo from '../modal/modalCadastraVeiculo.vue';
import modalEditaVeiculo from '../modal/modalEditaVeiculo.vue';
import inputIntNumberDigit from '../Utils/inputIntNumberDigit.vue';
import datePickerYear from '../Utils/datePickerYear.vue';
import modalCadastraOrdemServico from '../modal/modalCadastraOrdemServico.vue';

export default {
    props: {
        Veiculos: Array,
        Cores: Array,
        Marcas: Array,
        Clientes: Array,
        Servicos: Array
    },
    components:{
        AuthenticatedLayout,
        Head,
        selectListMultiple,
        CpfCNPJinput,
        inputText,
        datePickerYear,
        modalCadastraVeiculo,
        modalEditaVeiculo,
        inputIntNumberDigit,
        modalCadastraOrdemServico
    },
    data(){
        return{
            veiculos: this.Veiculos,
            id: null,
            data_nascimento: null, 
            placa: '',
            id_proprietario: null,
            proprietario:'',
            cor: null,
            marca: null,
            cadastroVeiculoAtivo: false,
            ativado: null,
            AnoFabricacao: null,
            AnoModelo: null,
            clienteSelecionado: null,
            edicaoVeiculo: false,
            cadastroOrdemServico: false,
            veiculoSelecionado: null,
            ClientesOpcao: this.Clientes,
            veiculos: this.Veiculos.map(item=>({
                ...item,
                descricao: `${item.placa} || ${item.cor} || ${item.marca}`,
            })),
        }
    },
    methods:{

        editarVeiculo(veiculo){
            const index = this.veiculos.findIndex( c => c.id === veiculo.id)
            if(index !== -1)
                this.veiculos[index] = veiculo

        },
        novaOrdemServico(item)
        {
            console.log(item)
            console.log(this.ClientesOpcao)
            console.log(this.veiculos)
            this.cadastroOrdemServico = true
            this.IdSelecionado = item.id_proprietario 
            this.veiculoSelecionado =item.id
        },
        openModal(veiculo){
            const index = this.veiculos.findIndex( c => c.id == veiculo.id)
            this.veiculoSelecionado = this.veiculos[index]
            this.edicaoVeiculo = true
        }
    },
    computed:{
        filtraBusca(){
            let items =  this.veiculos.filter((item)=>{
                if(this.placa == null || this.placa == '') return true

                //console.log(((item.placa).toLowerCase().indexOf(this.placa.toLowerCase()) > -1))
                //console.log ((item.placa).toLowerCase().indexOf(this.placa.toLowerCase()) > -1)     
                return ((item.placa).toLowerCase().indexOf(this.placa.toLowerCase()) > -1)     
            })
            .filter((item)=>{
                if(this.id == null || this.id == '')
                    return true

                //console.log(((item.cpf_cnpj).toLowerCase().indexOf(this.cpf_cnpj.toLowerCase().replace(/\D/g,'')) > -1) )
                //console.log(item.id == this.id)    
                return item.id == this.id    
            })
            .filter((item)=>{
                if(this.ativado == null || this.ativado.length === 0)
                return true
            
                //console.log(this.ativado.includes(item.ativado))
                return this.ativado.includes(item.ativado)
            }).filter((item)=>{

                if(this.AnoModelo == null)
                    return true
                
                //console.log(item.AnoModelo === this.AnoModelo)
                return (item.ano_modelo + '' === this.AnoModelo)
            })
            .filter((item)=>{

                if(this.AnoFabricacao == null)
                    return true

                /* console.log(item.ano_fabricacao)
                console.log(this.AnoFabricacao)
                console.log(item.ano_fabricacao === this.AnoFabricacao) */
                return (item.ano_fabricacao + '' === this.AnoFabricacao)
            })
            .filter((item)=>{
                
                if(this.proprietario == null || this.proprietario.length === 0)
                    return false

                //console.log(this.proprietario.includes(item.id_proprietario))
                return this.proprietario.includes(item.id_proprietario)
            })
            .filter((item)=>{
                if(this.cor == null || this.cor.length === 0)
                    return false

                //console.log(this.cor.includes(item.id_cor))
                return this.cor.includes(item.id_cor)
            })
            .filter((item)=>{
                if(this.marca == null || this.marca.length === 0 )
                    return false

                //console.log(this.marca.includes(item.id_marca))
                return this.marca.includes(item.id_marca)
            })

            return items
        },
     },

}
</script>
<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Veiculos</h2>
            <button class="btn btn-primary" @click="cadastroVeiculoAtivo = !cadastroVeiculoAtivo">Adicionar Veiculo</button>
        </template>

        <!-- Modals -->
        <modalEditaVeiculo v-if="edicaoVeiculo" :Veiculo="veiculoSelecionado" :Clientes="Clientes" :Marcas="Marcas" :Cores="Cores" @fechaModal="edicaoVeiculo = false; veiculoSelecionado = null" @editar="editarVeiculo" />
        <modalCadastraVeiculo v-if="cadastroVeiculoAtivo" :Clientes="Clientes" :Marcas="Marcas" :Cores="Cores" @fechaModal="cadastroVeiculoAtivo = false" @adicionar="veiculo => veiculos.push(veiculo)" />
        <modalCadastraOrdemServico v-if="cadastroOrdemServico" :IdSelecionado="IdSelecionado" :idVeiculoSelecionado="veiculoSelecionado" :Clientes="ClientesOpcao" :Servicos="Servicos" :Veiculos="veiculos" @fechaModal="cadastroOrdemServico = false"/>

        <!-- Main Content -->
        <div class="py-12">
            <!-- Filters Section -->
            <div class="filters-container bg-white p-6 rounded-lg shadow-md mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Id Veiculo</label>
                        <inputIntNumberDigit v-model="id" :maxLengh="4" class="w-full mt-1 p-2 border rounded-md" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Placa</label>
                        <inputText v-model="placa" :maxLengh="7" class="w-full mt-1 p-2 border rounded-md" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Proprietario</label>
                        <selectListMultiple id="proprietario" v-model="proprietario" :options="Clientes" class="w-full mt-1 p-2 border rounded-md" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cores</label>
                        <selectListMultiple id="cores" v-model="cor" :options="Cores" class="w-full mt-1 p-2 border rounded-md" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Marcas</label>
                        <selectListMultiple id="marcas" v-model="marca" :options="Marcas" class="w-full mt-1 p-2 border rounded-md" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ano Fabricação</label>
                        <datePickerYear v-model="AnoFabricacao" class="w-full mt-1 p-2 border rounded-md" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ano Modelo</label>
                        <datePickerYear v-model="AnoModelo" class="w-full mt-1 p-2 border rounded-md" />
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-container">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Placa</th>
                            <th scope="col">Proprietario</th>
                            <th scope="col">Cor</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Ano Fabricacao</th>
                            <th scope="col">Ano Modelo</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="veiculo in filtraBusca" :key="veiculo.id">
                            <th scope="row">{{ veiculo.id }}</th>
                            <td>{{ veiculo.placa }}</td>
                            <td>{{ veiculo.nome }}</td>
                            <td>{{ veiculo.cor }}</td>
                            <td>{{ veiculo.marca }}</td>
                            <td>{{ veiculo.ano_fabricacao }}</td>
                            <td>{{ veiculo.ano_modelo }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" @click="openModal(veiculo)">Editar</button>
                                <button class="btn btn-sm btn-primary mx-1" :title="'Lançar ordem de serviço'" @click="novaOrdemServico(veiculo)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-plus" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5"/>
                                        <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
                                    </svg>
                                </button>
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
    flex-wrap: wrap; /* Allow wrapping to the next line */
    gap: 16px; /* Spacing between items */
    margin-bottom: 20px;
}

/* Styles for each filter item */
.filters-container > * {
    flex: 1 1 calc(25% - 16px); /* Four items per row with spacing */
    box-sizing: border-box; /* Ensure padding and border are included in the width */
}

/* Adjust the last row to have three items */
.filters-container > *:nth-last-child(-n+3) {
    flex: 1 1 calc(33.33% - 16px); /* Three items per row with spacing */
}

/* Media query for device screens (smartphones and tablets) */
@media (max-width: 1024px) {
    .filters-container {
        flex-direction: column; /* Stack items vertically */
        gap: 12px; /* Adjust spacing for vertical layout */
    }

    .filters-container > * {
        flex: 1 1 auto; /* Reset flex behavior for vertical layout */
        width: 100%; /* Full width for each item */
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
