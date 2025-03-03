<script>
import rotas from '../Assets/ArquivosConfiguracao/apiconfig'
import datePicker from '../Utils/datePicker.vue'
import InputFloatNumber from '../Utils/inputFloatNumber.vue'
import selectListOne from '../Utils/selectListOne.vue'
import inputIntNumber from '../utils/inputIntNumber.vue'
import ElInputNumber from 'element-plus'
import inputFloatNumberManual from '../Utils/inputFloatNumberManual.vue'
import inputTextLarge from '../Utils/inputTextLarge.vue'
export default{
    props:{
        OrdemServico: Object,
        Clientes:Array,
        Veiculos:Array,
        Servicos: Array,
    },
    data(){
        return{
            cliente: this.OrdemServico.id_cliente,
            clienteInvalido: false,
            veiculo: this.OrdemServico.id_veiculo,
            veiculoInvalido: false,
            data: this.OrdemServico.data,
            dataInvalida: false,
            loading: false,
            servicos: JSON.parse(this.OrdemServico.servicos) || [],
            precoTotal: 0,
            servicosAdicao: this.Servicos,
            servicoSelecionadoAdicao: null,
            nenhumSelecionado: false,
            itemComValorZerado: false,
            obs: this.OrdemServico.obs || ''
          }
    },
    components:{
        datePicker,
        selectListOne,
        inputIntNumber,
        ElInputNumber,
        InputFloatNumber,
        inputFloatNumberManual,
        inputTextLarge
    },
    methods: {
        removeRepetidos(){
            if(this.servicos == null)
                return

            const lista = new Set(this.servicos.map(item => item.id))
            this.servicosAdicao = this.servicosAdicao.filter(item => !lista.has(item.id))
        },
        salvaAlteracoes(){
            if(this.validacoes())
            {

                this.loading= true
                const csrfToken = document.getElementsByName("_token")[0].value; // Get CSRF token

                    const servico = {
                        id_cliente: this.cliente,
                        id_veiculo: this.veiculo,
                        data: this.data,
                        servicos: JSON.stringify(this.servicos),
                        obs: this.obs
                    };

                    fetch(rotas.ordemServico.editar(this.OrdemServico.id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken, // Include CSRF token in headers
                    },
                    body: JSON.stringify(servico), // Convert data to JSON
                    })
                    .then(response => {
                        if (!response.ok) {
                        throw new Error('Network response was not ok');
                        }
                        return response.json(); // Parse JSON response
                    })
                    .then(data => {
                        this.$emit('editar', data); // Emit the 'editar' event with the response data
                    })
                    .catch(error => {
                        console.error('Error:', error); // Handle errors
                    })
                    .finally(() => {
                        this.loading = false; // Reset loading state
                        this.fechaModal(); // Close the modal
                    });     

            } 

        },
        validacoes()
        {   
            
            let retorno = true

            if(this.cliente == null)
            {
                this.clienteInvalido = true
                retorno = false
            }
            else 
                this.clienteInvalido = false
            
            if(this.veiculo == null )
            {
                this.veiculoInvalido = true
                retorno = false
            }
            else 
                this.veiculoInvalido = false

            if(this.data == null )
            {
                this.dataInvalida = true
                retorno = false
            }
            else 
                this.dataInvalida = false

            if(this.servicos.length == 0)
            {
                this.nenhumSelecionado = true
                retorno = false
            }
            else 
                this.nenhumSelecionado = false

            this.servicos.forEach( s => {
                if(s.preco == 0)
                {
                    this.itemComValorZerado = true
                    retorno = false
                }
                else
                    this.itemComValorZerado = false
            })

            return retorno 
        },

        cancelaAdicao(){
            this.descricao = ''
            this.preco = null
            this.descricaoInvalida = false
            this.precoInvalido = false
        },
        fechaModal(){
            this.cancelaAdicao()
            this.$emit('fechaModal')
        },
        calculaTotal(){

            let valor = 0.00

            this.servicos.forEach(element => {
                valor += element.preco * element.quantidade
            });

            this.precoTotal = valor
        },
        removeServico(servico){
            this.servicos = this.servicos.filter( s => s.id != servico.id)
            this.calculaTotal()
            this.removeRepetidos()
            this.servicosAdicao.push(servico)
        }

    },
    watch:{
        servicoSelecionadoAdicao(){
            if(this.servicoSelecionadoAdicao == null)
            {
                console.log("Looping")
                return
            }

            let servico =this.servicosAdicao.find( s => s.id == this.servicoSelecionadoAdicao)
            servico.quantidade = 1
            servico.preco = 1
            this.servicos.push(servico)
            this.servicoSelecionadoAdicao = null
            this.calculaTotal()
            this.removeRepetidos()
        }
    },
    mounted(){
        this.removeRepetidos()
    },
    computed: {
        veiculosCliente(){
            if(this.cliente == null)
                return []
            else
                return this.Veiculos.filter( v => v.id_proprietario == this.cliente)
        },
        precoTotal() {
            if(this.servicos == null)
                return 0
            else
                return this.servicos.reduce((acc, servico) => acc + (servico.preco * servico.quantidade), 0);
        }
    }
}



</script>
<template>
    <!-- Modal -->
    <div class="modal fade show modal-dialog-centered modal-xl" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Editando Ordem de Servico</h1>
          </div>
            <div class="modal-body">
                <div class="card border-dark mb-5">
                <div class="card-body text-dark d-flex flex-column align-items-center justify-content-center text-center">
                    <h6>Selecione Cliente</h6>
                    <selectListOne v-model="cliente" :options="this.Clientes" :label="'Clientes'"></selectListOne>
                    <p v-if="clienteInvalido">Selecione um cliente</p>
                    <h6>Selecione Veiculo</h6>
                    <selectListOne v-model="veiculo" :options="veiculosCliente" :label="'Veiculos'"></selectListOne>
                    <p v-if="veiculoInvalido">Selecione um veiculo</p>
                    <datePicker v-model="data" :Label="'Data servico'" />
                    <p v-if="dataInvalida">Selecione uma data</p>
                    <h6>Observações</h6>
                    <inputTextLarge v-model="obs" :label="'Observacao'" ></inputTextLarge>
                    <h6>Preco Total</h6>
                    <inputFloatNumberManual v-model="precoTotal" :disabled="true" :size="'large'" :key="precoTotal"/>
                </div>
            </div>
            <div class="table-body-container">
                
                <h6>Adicionar Produto</h6>
                <selectListOne v-model="servicoSelecionadoAdicao" :options="this.servicosAdicao" :label="'Servicos'"></selectListOne>
                <p v-if="nenhumSelecionado"> Os precisa ter um servico</p>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">servico</th>
                            <th scope="col">quantidade</th>
                            <th scope="col">preco</th>
                            <th scope="col">total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="servico in this.servicos">
                        <th scope="row">{{servico.descricao}}</th>
                        <td> <inputIntNumber v-model="servico.quantidade" /></td>
                        <td><inputFloatNumberManual v-model="servico.preco" /></td>
                        <td style="width: 150px">R$ {{(servico.preco * servico.quantidade).toFixed(2) }}</td>
                        <td>
                      <!-- Button to open modal -->
                      <button class="btn btn-sm btn-primary" @click="removeServico(servico)">
                        <img width="10" height="10" src="../Assets/trash.png" alt="trash"/> </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" :disabled="loading" @click="salvaAlteracoes()">
              {{ loading ? 'Salvando' : 'salvar' }}
              <div v-if="loading" class="spinner-grow spinner-grow-sm" role="status"></div>
            </button>
            <button type="button" class="btn btn-secondary" @click="fechaModal()" :disabled="loading">Sair sem salvar</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
<style scoped>
h6{
    margin-top: 5px;
}
p{
    color: red;
    font-size: small;
}

.table-body-container {
  max-height: 300px; /* Adjust the height of tbody */
  overflow-y: auto;
}

</style>