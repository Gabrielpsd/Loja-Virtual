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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ordem servicos</h2>
            <button class="btn btn-primary" @click="cadastroOrdemServico = !cadastroOrdemServico"> Adicionar Serviço </button>
        </template>
        <modalEditaOrdemServico v-if="edicaoServico" :OrdemServico="ordemServicoSelecionado" :Servicos="this.Servicos" :Clientes="this.Clientes" :Veiculos="this.Veiculos" @fechaModal="edicaoServico = false ; servicoSelecionado = null" @editar="(servico)=>{editarServico(servico)}"/>
        <modalCadastraOrdemServico v-if="cadastroOrdemServico" :Clientes="this.Clientes" :Veiculos="this.Veiculos" @fechaModal="cadastroOrdemServico = false" @adicionar="(servico)=>adicionaOrdem(servico)"/>
         <div class="py-12" >
           <div class="bg-white overflow-hidden shadow-sm justify-around sm:rounded-lg flex ">
                <inputIntNumberDigit v-model="id" :maxLengh="4" :label="'Id'"/>
                <selectListMultiple id="Cliente" :label="'Cliente'" v-model="clientes" :options="Clientes"/>  
                <selectListMultiple id="Veiculo" :label="'Veiculo'" v-model="veiculos" :options="Veiculos"/>  
                <datePickerInterval v-model="datasFiltro" :Label="'Data do Servico'"/> 
            </div>
        <div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mx-auto p-2">
                <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Veiculo</th>
                                <th scope="col">Data serviço</th>
                                <th scope="col">Preco Total</th>
                            </tr>
                        </thead>
                    <tbody>
                    <tr v-for="ordemServico in filtraBusca">
                        <th scope="row">{{ordemServico.id}}</th>
                            <td>{{ordemServico.nome}}</td>
                            <td>{{ordemServico.descricao_veiculo}}</td>
                            <td>{{maskedDate(ordemServico.data)}}</td>
                            <td>R$ {{calculaTotal(ordemServico.servicos)}}</td>
                            <td>
                            <!-- Button to open modal -->
                            <button
                                class="btn btn-sm btn-primary"
                                @click="openModal(ordemServico)"
                            >
                                Editar
                            </button>
                    </td>
                    </tr>
                </tbody>
                
            </table>
        </div>
        </div>
    </div>
</AuthenticatedLayout> 

</template>
