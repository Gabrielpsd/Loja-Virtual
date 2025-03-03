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
            <button class="btn btn-primary" @click="cadastroServicoAtivo = !cadastroServicoAtivo"> Adicionar Serviço </button>
        </template>
        <modalEditaServico v-if="edicaoServico" :Servico="servicoSelecionado" :Servicos="this.servicos" @fechaModal="edicaoServico = false ; servicoSelecionado = null" @editar="(servico)=>{editarServico(servico)}"/>
        <ModalCadastraServico v-if="cadastroServicoAtivo" :Servicos="this.Servicos" @fechaModal="cadastroServicoAtivo = false" @adicionar="(servico)=>this.servicos.push(servico)"/>
         <div class="py-12" >
           <div class="bg-white overflow-hidden shadow-sm justify-around sm:rounded-lg flex ">
                <inputIntNumberDigit v-model="id" :maxLengh="4" :label="'Id'"/>
                <inputText v-model="descricao" :maxLengh="30" :Label="'Descricao'"/>
                <selectListMultiple id="Ativado" :label="'Cadastro Ativo'" v-model="ativado" :options="[{id: true, descricao: 'Ativado'},{id: false,descricao: 'Desativado'}]"/>  
            </div>
        <div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mx-auto p-2">
                <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Preço</th>
                                <th scope="col">Situação</th>
                            </tr>
                        </thead>
                    <tbody>
                    <tr v-for="servico in filtraBusca">
                        <th scope="row">{{servico.id}}</th>
                            <td>{{servico.descricao}}</td>
                            <td>R$ {{ servico.preco}}</td>
                            <td>{{servico.ativado ? 'Ativado': 'Desativado'}}</td>
                            <td>
                            <!-- Button to open modal -->
                            <button
                                class="btn btn-sm btn-primary"
                                @click="openModal(servico)"
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
