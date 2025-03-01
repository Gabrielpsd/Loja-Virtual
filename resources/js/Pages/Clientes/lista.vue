<script>
import pessoaCard from '../cartoes/pessoaCard.vue'
import adicionar from '../Clientes/adiciona.vue'
import rotas from '../Assets/ArquivosConfiguracao/apiconfig'

export default {
    props: {
        ClienteFornecedor: Array
    },
    components:{
        pessoaCard,
        adicionar
    },
    data(){
        return{
            pessoasData: this.ClienteFornecedor,
            fornecedor: '0',
            descricao: '',
            sexo: '0',
            cpf_cnpj: '',
            idade: 0,
            id_cliente: 0,
            id_lancamento: '',
            key: 1,
            baseColor: '#04d627',
            listaFiltrada: [],
            dadosGraficoPessoasPorSexo: {labels: [], data:[], carregado: false, colors: [],key:0},
            dadosGraficoPessoasPorIdade: {labels: [], data:[], carregado: false, colors: [],key:0},
            ativado: true
            
        }
    },
    methods:{
        adicionarPessoa(pessoa)
        {
            this.pessoasData.push(pessoa)

        },
        deletaPessoa(arg)
        {
            this.listaFiltrada = this.pessoasData.map((pessoa)=>{
                if(pessoa.id === arg)
                {
                  pessoa.ativado = false  
                }

                return pessoa
            })
        },
        ativarPessoa(arg)
        {
            console.log("Estou dentro da funcao")
            this.listaFiltrada = this.pessoasData.map((pessoa)=>{
                if(pessoa.id === arg)
                {
                  pessoa.ativado = true  
                }

                return pessoa
            })
        },
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
        }
    },
    computed:{
        filtraBusca: function(){
            let items =  this.pessoasData.filter((item)=>{
                return ((item.id+'').toLowerCase().indexOf(this.id_lancamento) > -1) 
            }).filter((item)=>{
                return ((item.descricao+'').toLowerCase().indexOf(this.descricao.toLowerCase()) > -1)     
            })
            .filter((item)=>{
                return ((item.cpf_cnpj+'').toLowerCase().indexOf(this.cpf_cnpj.toLowerCase().replace(/\D/g,'')) > -1)     
            }).filter((item)=>{
                if(this.fornecedor === '0' )
                    return true 
                return (item.fornecedor == this.fornecedor)     
            }).filter((item)=>{
                if(this.sexo == '0')
                    return true
                return (item.sexo == this.sexo)
            }).filter((item)=>{

                if(this.ativado == 'else')
                    return true
            
               return (item.ativado === this.ativado)
            })


            this.listaFiltrada = items 
           return items
        }
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
    <div >
        <div class="filtros">
        <h3 class="device-view"> Clientes e fornecedores</h3>
        <h4>Filtros</h4>
            <input v-model="id_lancamento" placeholder="ID lancamento" />
            <input v-model="cpf_cnpj" v-mask="['###.###.###-##', '##.###.###/####-##']" placeholder="CPF ou CNPJ" />
            <input v-model="descricao" placeholder="Nome" />
            <select v-model="sexo">
                <option  :value="'0'">Sexo</option>
                <option  :value="'M'">Masculino</option>
                <option  :value="'F'">Feminino</option>
            </select>
            <select v-model="fornecedor">
                <option  value="0">Cliente / Fornecedor</option>
                <option  :value="false">Cliente</option>
                <option  :value="true">Fornecedor</option>
            </select>
            <select v-model="ativado">
                <option  value="else"> Desativado / ativados </option>
                <option  :value="true">Ativado</option>
                <option  :value="false">Desativado</option>
            </select>
        </div>
        <div class="grid">
            <adicionar @adicionar="(arg)=>adicionarPessoa(arg)" :cadastros="pessoasData"></adicionar>
            <pessoaCard :pessoa='pessoa' v-for="pessoa in filtraBusca" @deletaPessoa="(arg)=>deletaPessoa(arg)" @ativarPessoa="(arg)=>ativarPessoa(arg)":key="pessoa.id"></pessoaCard>
        </div>
    </div>
</template>

<style scoped>
   .grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
    margin: 0 auto;
    width: 90%;
    padding: 20px;
    gap: 10px;
}

.filtros {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
}

.filtros select {
    width: 100%;
    max-width: 250px;
}


.chartArea {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 10px;
    background-color: rgb(241, 241, 241);
    padding: 3px;
}

.chart {
    width: 80%;
    max-width: 350px;
}


.device-view {
    display: none;
}

.computer-view {
    display: inline;
}

.date{
    width: 30px;
    height: 30px;
}

@media (max-width: 768px) {
    h4{
        width: 100%;
        text-align: center;
    }
    .grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .chart {
        max-width: 300px;
    }

    .device-view {
    display: inline;
    }

    .computer-view {
        display: none;
    }
}

@media (max-width: 480px) {
    h3 {
        color: #2c3e50;
        font-size: 1.8rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding-bottom: 8px;
        border-bottom: 2px solid #3498db;
        text-transform: capitalize;
        margin: 1.5rem 0;
        position: relative;
        }
    h4{
        width: 100%;
        text-align: center;
    }
    .grid {
        grid-template-columns: 1fr;
    }

    .chart {
        max-width: 100%;
    }

    .device-view {
    display: inline;
    }

    .computer-view {
        display: none;
    }
}

</style>