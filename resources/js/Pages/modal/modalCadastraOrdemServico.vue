<script>
import rotas from '../Assets/ArquivosConfiguracao/apiconfig'
import datePicker from '../Utils/datePicker.vue'
import selectListOne from '../Utils/selectListOne.vue'
import inputTextLarge from '../Utils/inputTextLarge.vue'

export default{
    props:{
        Clientes:Array,
        Veiculos:Array,
    },
    data(){
        return{
            clientesOptions: this.Clientes.filter( d => d.ativado == true),
            veiculosOtions: this.Veiculos.filter( d=> d.ativado == true),
            cliente: null,
            clienteInvalido: false,
            veiculo: null,
            veiculoInvalido: false,
            data: null,
            dataInvalida: false,
            loading: false,
            obs: ''
          }
    },
    components:{
        datePicker,
        selectListOne,
        inputTextLarge,
    },
    methods: {
        salvaAlteracoes(){

            console.log("to aqui")
            console.log(this.clientesOptions)
            console.log(this.veiculosOtions)
            if(this.validacoes())
            {

                this.loading= true
                const csrfToken = document.getElementsByName("_token")[0].value; // Get CSRF token

                    const servico = {
                        id_cliente: this.cliente,
                        id_veiculo: this.veiculo,
                        data: this.data,
                        obs: this.obs
                    };

                    fetch(rotas.ordemServico.inserir, {
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
                        this.$emit('adicionar', data); // Emit the 'editar' event with the response data
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
        }

    },
    computed: {
        veiculosCliente(){

            if(this.cliente == null)
                return []
            else
                return this.Veiculos.filter( v => v.id_proprietario == this.cliente)
        }
    }
}



</script>

<template>
 <!-- Modal -->
<div class="modal fade show modal-dialog-centered " tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Nova Ordem Servico</h1>
      </div>
      <div class="modal-body">
        <div class="card border-dark mb-3" style="max-width: 18rem;" >
        <div class="card-body text-dark" >
            <h6> Selecione Cliente</h6>
            <selectListOne v-model="cliente" :options="clientesOptions" :label="'Clientes'"></selectListOne>
            <p v-if="clienteInvalido"> Selecione um cliente</p>
            <h6> Selecione Veiculo</h6>
            <selectListOne v-model="veiculo" :options="veiculosOtions" :label="'Veiculos'"></selectListOne>
            <p v-if="veiculoInvalido"> Selecione um veiculo</p>
            <datePicker v-model="data" :Label="'Data servico'"/>
            <p v-if="dataInvalida"> Selecione uma data</p>
            <h6>Observações</h6>
            <inputTextLarge v-model="obs" :label="'Observacao'" ></inputTextLarge>
        </div>

    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" :disabled="loading" @click="salvaAlteracoes()">
            {{ loading ? 'Salvando' : 'salvar' }}
            <div v-if="loading" class="spinner-grow spinner-grow-sm" role="status">
            </div>
        </button>
        <button type="button" class="btn btn-secondary" @click="fechaModal() "  :disabled="loading" >Sair sem salvar</button>
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
</style>