<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import selectListMultiple from '../Utils/selectListMultiple.vue'
import CpfCNPJinput from '../utils/CpfCNPJinput.vue';
import inputText from '../Utils/inputText.vue';
import datePickerInterval from '../Utils/datePickerInterval.vue';
import rotas from '../Assets/ArquivosConfiguracao/apiconfig'
import { Head } from '@inertiajs/vue3';
import modalCadastraCliente from '../modal/modalCadastraCliente.vue';
import modalEditaCliente from '../modal/modalEditaCliente.vue';
import inputIntNumberDigit from '../Utils/inputIntNumberDigit.vue';
export default {
    props: {
        Cliente: Array
    },
    components:{
        AuthenticatedLayout,
        Head,
        selectListMultiple,
        CpfCNPJinput,
        inputText,
        datePickerInterval,
        modalCadastraCliente,
        modalEditaCliente,
        inputIntNumberDigit
    },
    data(){
        return{
            id: null,
            data_nascimento: null, 
            pessoasData: this.Cliente,
            nome: '',
            cadastroclienteAtivo: false,
            sexo: null,
            cpf_cnpj: '',
            baseColor: '#04d627',
            listaFiltrada: [],
            dadosGraficoPessoasPorSexo: {labels: [], data:[], carregado: false, colors: [],key:0},
            dadosGraficoPessoasPorIdade: {labels: [], data:[], carregado: false, colors: [],key:0},
            ativado: null,
            datasFiltro: null,
            clienteSelecionado: null,
            editarCliente: false
        }
    },
    methods:{
        geraGraficos(){

            this.dadosGraficoPessoasPorSexo={labels: [], data:[], carregado: false, colors: [],key:0}
            this.dadosGraficoPessoasPorIdade={labels: [], data:[], carregado: false, colors: [],key:0}
            
            let  filtroPorSexo  = {}
            let  filtroPorIdade  = {}

            this.listaFiltrada.forEach((element)=>{
                filtroPorSexo[element.sexo] = (filtroPorSexo[element.sexo] || 0) + 1
                filtroPorIdade[element.idade] = (filtroPorIdade[element.idade] || 0) + 1
            })

            for(let item of Object.entries(filtroPorSexo))
            {
                this.dadosGraficoPessoasPorSexo.data.push(item[1])
                this.dadosGraficoPessoasPorSexo.labels.push(item[0])
                if(item[0] == 'M')
                    this.dadosGraficoPessoasPorSexo.colors.push('#0011aa')
                if(item[0] == 'F')
                    this.dadosGraficoPessoasPorSexo.colors.push('#f927d3')
            }

            for(let item of Object.entries(filtroPorIdade))
            {
                this.dadosGraficoPessoasPorIdade.data.push(item[1])
                this.dadosGraficoPessoasPorIdade.labels.push(item[0])
                this.dadosGraficoPessoasPorIdade.colors.push(rotas.geraCor(this.baseColor))
            }

            this.dadosGraficoPessoasPorIdade.carregado = true
            this.dadosGraficoPessoasPorSexo.carregado = true
        },

        maskedCpfCnpj(value) {
            if (!value) return "";
    
            // Remove all non-numeric characters
            value = value.replace(/\D/g, "");

            // Apply CPF mask (###.###.###-##) if it has 11 digits
            if (value.length === 11) {
            return value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
            }

            // Apply CNPJ mask (##.###.###/####-##) if it has 14 digits
            if (value.length === 14) {
            return value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
            }

            // If it doesn't match CPF or CNPJ, return as-is
            return value;
        },
        
        maskedBirthDay(date){
            const [year, month, day] = date.split('-');
            return `${day}/${month}/${year}`;

        },

        editarPessoa(pessoa){
            console.log("Editando ")
            console.log(pessoa)
            const index = this.pessoasData.findIndex( c => c.id === pessoa.id)
            if(index !== -1)
                this.pessoasData[index] = pessoa

            this.clienteSelecionado == null
        },
        openModal(cliente){
            const index = this.pessoasData.findIndex( c => c.id == cliente.id)
            this.clienteSelecionado = this.pessoasData[index]
            this.editarCliente = true
        }
    },
    computed:{
        filtraBusca(){
            let items =  this.pessoasData.filter((item)=>{
                if(this.nome == null || this.nome == '') return true

                //console.log(((item.nome).toLowerCase().indexOf(this.nome.toLowerCase()) > -1))
                return ((item.nome).toLowerCase().indexOf(this.nome.toLowerCase()) > -1)     
            })
            .filter((item)=>{
                if(this.cpf_cnpj == null || this.cpf_cnpj == '')
                    return true

                //console.log(((item.cpf_cnpj).toLowerCase().indexOf(this.cpf_cnpj.toLowerCase().replace(/\D/g,'')) > -1) )
                return ((item.cpf_cnpj).toLowerCase().indexOf(this.cpf_cnpj.toLowerCase().replace(/\D/g,'')) > -1)     
            })
            .filter((item)=>{
                if(this.id == null || this.id == '')
                    return true

                //console.log(((item.cpf_cnpj).toLowerCase().indexOf(this.cpf_cnpj.toLowerCase().replace(/\D/g,'')) > -1) )
                return item.id == this.id    
            })
            .filter((item)=>{
                
                if(this.sexo == null || this.sexo.length === 0)
                return false
            
                //console.log(this.sexo.includes(item.sexo))
                return this.sexo.includes(item.sexo)
                
            }).filter((item)=>{
                if(this.ativado == null || this.ativado.length === 0)
                return false
            
                //console.log(this.ativado.includes(item.ativado))
                return this.ativado.includes(item.ativado)
            }).filter((item)=>{

                if(this.datasFiltro == null)
                    return true
                
                const dataNascimento = new Date(item.data_nascimento)
                const dataInicial = new Date(this.datasFiltro[0])
                const dataFinal = new Date(this.datasFiltro[1])

                return (dataNascimento >= dataInicial && dataNascimento <= dataFinal)
            })

            this.listaFiltrada = items 
            return items
        },
     },
    watch: {
        listaFiltrada()
        {
            this.geraGraficos()
        }
    }
}
</script>

<template>
    <Head title="Dashboard" />
    
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clientes</h2>
            <button class="btn btn-primary" @click="cadastroclienteAtivo = !cadastroclienteAtivo"> Adicionar Cliente </button>
        </template>
        <modalEditaCliente v-if="editarCliente" :Cliente="clienteSelecionado" :cadastros="this.pessoasData" @fechaModal="editarCliente = false ; clienteSelecionado = null" @editar="(pessoa)=>{editarPessoa(pessoa)}"/>
        <modalCadastraCliente v-if="cadastroclienteAtivo" :cadastros="this.pessoasData" @fechaModal="cadastroclienteAtivo = false" @adicionar="(pessoa)=>this.pessoasData.push(pessoa)"/>
         <div class="py-12" >
           <div class="bg-white overflow-hidden shadow-sm justify-between sm:rounded-lg flex ">
               <inputIntNumberDigit v-model="id" :maxLengh="4" :label="'Id'"/>
                <inputText v-model="nome" :maxLengh="30" :Label="'Nome'"/>
                <CpfCNPJinput v-model="cpf_cnpj" :Label="'CPF/CNPJ'"/>
                <datePickerInterval v-model="data_nascimento" :Label="'Data nascimento'"/>
                <selectListMultiple id="sexo" :label="'Sexo'" v-model="sexo" :options="[{id: 'M', descricao: 'Masculino'},{id: 'F',descricao: 'Feminino'}]"/>
                <selectListMultiple id="Ativado" :label="'Cadastro Ativo'" v-model="ativado" :options="[{id: true, descricao: 'Ativado'},{id: false,descricao: 'Desativado'}]"/>
            </div>
        <div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mx-auto p-2">
                <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nome</th>
                                <th scope="col">CPF</th>
                                <th scope="col">Data Nascimento</th>
                                <th scope="col">Sexo</th>
                            </tr>
                        </thead>
                    <tbody>
                    <tr v-for="cliente in filtraBusca">
                        <th scope="row">{{cliente.id}}</th>
                            <td>{{cliente.nome}}</td>
                            <td>{{maskedCpfCnpj(cliente.cpf_cnpj)}}</td>
                            <td>{{maskedBirthDay(cliente.data_nascimento)}}</td>
                            <td>{{ cliente.sexo == 'M' ? "Masculino" : "Feminino" }}</td>
                            <td>
                            <!-- Button to open modal -->
                            <button
                                class="btn btn-sm btn-primary"
                                @click="openModal(cliente)"
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
