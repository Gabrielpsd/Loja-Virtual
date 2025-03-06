<script>
import rotas from '../Assets/ArquivosConfiguracao/apiconfig'
import inputIntNumber from '../utils/inputIntNumber.vue'
import datePicker from '../Utils/datePicker.vue'
import CpfCNPJinput from '../utils/CpfCNPJinput.vue'
import inputText from '../Utils/inputText.vue'
import modalCadastraVeiculo from '../modal/modalCadastraVeiculo.vue';
import modalEditaVeiculo from '../modal/modalEditaVeiculo.vue';

export default{
    props:{
        Cliente: {
            type: Object,
        },
        Clientes: {
            type: Object,
        },
        Veiculos: {
            type: Array
        },
        Cores:{
            type: Array
        },
        Marcas:{
            type: Array
        }
    },
    data(){
        return{
            id: this.Cliente.id,
            veiculosCliente: this.Veiculos.filter( (item) => item.id_proprietario == this.Cliente.id),
            nome: this.Cliente.nome,
            tamanhoNomeInvalido: false,
            dataNascimentoInvalida: false,
            cpf_cnpj: this.Cliente.cpf_cnpj,
            cpfInvalido: false,
            sexoInvalido: false,
            sexo: this.Cliente.sexo,
            dataNascimento: this.Cliente.data_nascimento,
            cpfExistente: false,
            loading: false,
            ativo: this.Cliente.ativado,
            edicaoVeiculo: false,
            cadastroVeiculoAtivo: false,
            veiculoSelecionado: false 
          }
    },
    components:{
        inputIntNumber,
        datePicker,
        CpfCNPJinput,
        inputText,
        modalCadastraVeiculo,
        modalEditaVeiculo
    },
    methods: {
        editarVeiculo(veiculo){
            const index = this.veiculosCliente.findIndex( c => c.id === veiculo.id)
            if(index !== -1)
            {
                this.veiculosCliente[index] = veiculo
                this.$emit('atualizarVeiculos',veiculo)
            }

        },
        openModal(veiculo){
            const index = this.veiculosCliente.findIndex( c => c.id == veiculo.id)
            if(index !== -1)
            {
                this.veiculoSelecionado = this.veiculosCliente[index]
                this.edicaoVeiculo = true
            }
        },

        salvaAlteracoes(){
            if(this.validacoes())
            {
                this.loading = true; // Set loading state

                const csrfToken = document.getElementsByName("_token")[0].value; // Get CSRF token

                const pessoa = {
                    nome: this.nome,
                    data_nascimento: this.dataNascimento,
                    sexo: this.cpf_cnpj.replace(/\D/g,'').length === 14 ? 'E' : this.sexo,
                    ativado: this.ativo,
                };

                fetch(rotas.pessoas.editaPessoa(this.id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // Include CSRF token in headers
                },
                body: JSON.stringify(pessoa), // Convert data to JSON
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
            this.cpfInvalido = false
            this.tamanhoNomeInvalido = false

            if(this.dataNascimento == null)
            {
                this.dataNascimentoInvalida = true
                retorno = false
            }
            else 
                this.dataNascimentoInvalida = false

            if(this.nome.length <= 3)
            {
                this.tamanhoNomeInvalido = true
                retorno = false
                
            }
            else
                this.tamanhoNomeInvalido = false

            if(this.sexo === null)
            {
                this.sexoInvalido = true
                retorno =  false
                
            }
            else
                this.sexoInvalido = false
            

            return retorno 
        },

        cancelaAdicao(){
            this.nome = ''
            this.fornecedor = false
            this.edicaoInativa = true,
            this.tamanhoNomeInvalido = false
            this.cpf_cnpj = ''
            this.sexo = null

        },
        fechaModal(){
            this.cancelaAdicao()
          this.$emit('fechaModal')
        },
        adicionarVeiculo(veiculo){
            this.veiculosCliente.push(veiculo)
            this.$emit('insereVeiculo',veiculo)
        }

    },
}



</script>

<template>
       <!-- Modals -->

    <modalEditaVeiculo style=" z-index: 1060;" :ocultarCliente="true" v-if="edicaoVeiculo"  :Veiculo="veiculoSelecionado" :Clientes="Clientes" :Marcas="Marcas" :Cores="Cores" @fechaModal="edicaoVeiculo = false; veiculoSelecionado = null" @editar="editarVeiculo" />
    <modalCadastraVeiculo style=" z-index: 1060;" :IdProprietario="id"  v-if="cadastroVeiculoAtivo" :Clientes="Clientes" :Marcas="Marcas" :Cores="Cores" @fechaModal="cadastroVeiculoAtivo = false" @adicionar="adicionarVeiculo" />

<div class="modal fade show modal-dialog-centered" tabindex="-1" aria-hidden="true" style="display: block;">
  <div class="modal-dialog  modal-dialog-centered"> <!-- Added modal-dialog-centered here too -->
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Cliente</h1>
        <button type="button" class="btn-close" @click="fechaModal()" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card border-dark mb-3 w-100"> <!-- Removed max-width limitation -->
          <div class="card-body text-dark d-flex flex-column align-items-right justify-content-center">
            <inputText v-model="nome" :maxLength="30" :Label="'Nome'" />
            <p v-if="tamanhoNomeInvalido">Nome deve conter no mínimo 3 caracteres</p>
            <div>
                <h6 v-if="!(this.cpf_cnpj.length === 18) ">Sexo</h6>
                <h6 v-if="(this.cpf_cnpj.length === 18) " class="mt-1">Sexo n/ aplica</h6>
                <p v-if="sexoInvalido && !(this.cpf_cnpj.length === 18)">Selecione o sexo do cliente</p>
                <div v-if="!(this.cpf_cnpj.length === 18) ">
                    <input type="radio" id="one" value="M" v-model="sexo" />
                    <label for="one">Masculino</label>
                    <input type="radio" id="two" value="F" v-model="sexo" />
                    <label for="two">Feminino</label>
                </div>
              <CpfCNPJinput v-model="cpf_cnpj" :Label="'CPF/CNPJ'" :disable="true" />
              <datePicker v-model="dataNascimento" :Label="'Data Nascimento'" />
              <p v-if="dataNascimentoInvalida">Selecione uma data de nascimento</p>
              <div class="d-flex align-items-center gap-3">
                <input type="radio" id="true" :value="false" v-model="ativo" />
                <label for="true">Desativado</label>
                <input type="radio" id="false" :value="true" v-model="ativo" />
                <label for="false">Ativado</label>
              </div>
            </div>
          </div>
        </div>
        <div class="table-body-container">
                
                <h6>Veiculos</h6>
                <button class="btn btn-primary" @click="cadastroVeiculoAtivo = true"  data-bs-dismiss="modal">
                    Adicionar               
                    <img width="15" height="15" src="../Assets/car.png" alt="create-new"/>
                </button>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Placa</th>
                            <th scope="col">cor</th>
                            <th scope="col">marca</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="veiculo in this.veiculosCliente" >
                        <th scope="row">{{veiculo.placa}}</th>
                        <td> {{ veiculo.cor }}</td>
                        <td>{{ veiculo.marca }}</td>
                        <td style="vertical-align: top;">
                        <button class="btn-primary" @click="openModal(veiculo)"  data-bs-dismiss="modal">
                            <img width="24" height="24" src="https://img.icons8.com/material-rounded/24/create-new.png" alt="create-new"/>
                        
                        </button>
                        </td>
                  </tr>
                </tbody>
              </table>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" :disabled="loading" @click="salvaAlteracoes()">
          {{ loading ? 'Salvando' : 'Salvar' }}
          <div v-if="loading" class="spinner-grow spinner-grow-sm" role="status"></div>
        </button>
        <button type="button" class="btn btn-secondary" :disabled="loading" @click="fechaModal()">Sair sem salvar</button>
      </div>
    </div>
  </div>
</div>

   
</template>

<style scoped>

p{
    color: red;
    font-size: small;
}
</style>