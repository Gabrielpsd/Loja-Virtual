<script>
import rotas from '../Assets/ArquivosConfiguracao/apiconfig'
import inputIntNumber from '../utils/inputIntNumber.vue'
import datePicker from '../Utils/datePicker.vue'
import CpfCNPJinput from '../utils/CpfCNPJinput.vue'
import inputText from '../Utils/inputText.vue'
export default{
    props:{
        cadastros: Array
    },
    data(){
        return{
            nome: '',
            tamanhoNomeInvalido: false,
            dataNascimentoInvalida: false,
            cpf_cnpj: '',
            cpfInvalido: false,
            sexo: null,
            dataNascimento: null,
            cpfExistente: false,
            loading: false,
            sexoInvalido: false
          }
    },
    components:{
        inputIntNumber,
        datePicker,
        CpfCNPJinput,
        inputText
    },
    methods: {
        salvaAlteracoes(){
            if(this.validacoes())
            {

                this.loading= true
                const csrfToken = document.getElementsByName("_token")[0].value; // Get CSRF token

                    const pessoa = {
                        nome: this.nome,
                        data_nascimento: this.dataNascimento,
                        cpf_cnpj: this.cpf_cnpj,
                        sexo: this.sexo,
                    };

                    fetch(rotas.pessoas.inserirPessoa, {
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
            
            console.log(this.cpf_cnpj.replace(/\D/g,'').length)
            if(this.cpf_cnpj.replace(/\D/g,'').length != 14 && this.cpf_cnpj.replace(/\D/g,'').length != 11)
            {
                this.cpfInvalido = true
                retorno = false
                
            }
            else
                this.cpfInvalido = false

            this.cadastros.forEach((item)=>{
                if(item.cpf_cnpj == this.cpf_cnpj.replace(/\D/g,''))
                    this.cpfExistente = true
            })

            return retorno 
        },

        cancelaAdicao(){
            this.nome = ''
            this.fornecedor = false
            this.edicaoInativa = true,
            this.tamanhoNomeInvalido = false
            this.cpf_cnpj = ''
            this.sexo = null
            this.cpfExistente = false
        },
        fechaModal(){
            this.cancelaAdicao()
          this.$emit('fechaModal')
        }

    },
}



</script>

<template>
 <!-- Modal -->
<div class="modal fade show modal-dialog-centered " tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Cliente</h1>
      </div>
      <div class="modal-body">
        <div class="card border-dark mb-3" style="max-width: 18rem;" >
        <div class="card-body text-dark" >
            <inputText v-model="nome" :maxLengh="30" :Label="'Nome'"/>
            <p v-if="tamanhoNomeInvalido">Nome deve conter no minimo 3 caracteres </p>
            <div>
                <h6>Sexo</h6>
                <p v-if="sexoInvalido">Selecione o sexo do cliente</p>
                <div>
                    <input type="radio" id="one" value="M" v-model="sexo" />
                    <label for="one">Masculino</label>
                    <input type="radio" id="two" value="F" v-model="sexo" />
                    <label for="two">Feminino</label>
                </div>
                <CpfCNPJinput v-model="cpf_cnpj" :Label="'CPF/CNPJ'"/>
                <p v-if="cpfInvalido">CFP/CNPJ incompleto</p>
                <p v-if="cpfExistente">CPF/CNPJ já cadastrado</p>
                <datePicker v-model="dataNascimento" :Label="'Data Nascimento'" />
                <p v-if="dataNascimentoInvalida">Selecione uma data de nascimento</p>
                </div> 
        </div>

    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" :disabled="loading" @click="salvaAlteracoes()">
            {{ loading ? 'Salvando' : 'salvar' }}
            <div v-if="loading" class="spinner-grow spinner-grow-sm" role="status">
            </div>
        </button>
        <button type="button" class="btn btn-secondary" @click="fechaModal()">Sair sem salvar</button>
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