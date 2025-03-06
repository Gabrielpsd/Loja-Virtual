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

export default {
    props: {
        Veiculos: Array,
        Cores: Array,
        Marcas: Array,
        Clientes: Array
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
        inputIntNumberDigit
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
            edicaoVeiculo: false
        }
    },
    methods:{

        editarVeiculo(veiculo){
            const index = this.veiculos.findIndex( c => c.id === veiculo.id)
            if(index !== -1)
                this.veiculos[index] = veiculo

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

        <!-- Main Content -->
        <div class="py-12">
            <!-- Filters Section -->
            <div class="filters-container">
                <inputIntNumberDigit v-model="id" :maxLengh="4" :label="'Id Veiculo'" />
                <inputText v-model="placa" :maxLengh="7" :Label="'Placa'" />
                <selectListMultiple id="proprietario" :label="'Proprietario'" v-model="proprietario" :options="Clientes" />
                <selectListMultiple id="cores" :label="'Cores'" v-model="cor" :options="Cores" />
                <selectListMultiple id="marcas" :label="'Marcas'" v-model="marca" :options="Marcas" />
                <datePickerYear v-model="AnoFabricacao" :Label="'Ano Fabricacao'" />
                <datePickerYear v-model="AnoModelo" :Label="'Ano Modelo'" />
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
