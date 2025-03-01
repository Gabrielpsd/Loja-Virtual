<script>
import rotas from '../Assets/ArquivosConfiguracao/apiconfig'
import inputIntNumber from '../utils/inputIntNumber.vue'

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
            sexo: 0,
            dataNascimento: null,
            locale: rotas.customPtLocale,
            cpfExistente: false
          }
    },
    components:{
        inputIntNumber
    },
    methods: {
        salvaAlteracoes(){
            if(this.validacoes())
            {

                let obj = this
                const csrfToken = document.getElementsByName("_token")[0].value
                var request = new XMLHttpRequest()
                
                let pessoa = JSON.stringify({'descricao':this.nome, 
                                            'fornecedor': true,
                                            'cpf_cnpj': this.cpf_cnpj.toLowerCase().replace(/\D/g,''),
                                            'data_nascimento': this.dataNascimento,
                                            'sexo': this.sexo})
                request.open('POST',rotas.pessoas.inserirPessoa,true)
                request.setRequestHeader("Content-Type","application/json")
                request.setRequestHeader('X-CSRF-TOKEN',csrfToken)
                
                request.onload = function(){
                    if(this.readyState == XMLHttpRequest.DONE)
                        if(this.status == 200)
                        {
                            console.log(this.responseText)
                            obj.$emit('adicionar',JSON.parse(this.responseText))
                        }
                } 

                request.send(pessoa)

                this.cancelaAdicao()        

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

            if(this.sexo === '0')
            {
                this.sexoInvalido = true
                retorno =  false
                
            }
            else
                this.sexoInvalido = false
            
            if(this.cpf_cnpj.toLowerCase().replace(/\D/g,'').length != 14 && this.cpf_cnpj.toLowerCase().replace(/\D/g,'').length != 11)
            {
                this.cpfInvalido = true
                retorno = false
                
            }
            else
                this.cpfInvalido = true

            return retorno 
        },

        cancelaAdicao(){
            this.nome = ''
            this.fornecedor = false
            this.edicaoInativa = true,
            this.tamanhoNomeInvalido = false
            this.cpf_cnpj = ''
            this.sexo = 0
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Fornecedor</h1>
      </div>
      <div class="modal-body">
        <div class="card border-dark mb-3" style="max-width: 18rem;" >
        <div class="card-body text-dark" >
            <h5 class="card-title">Nome<input type="text" class="form-control" v-model="nome" :class="{campoInvalido: tamanhoNomeInvalido}"></h5>
            <div>
                <h6>Sexo</h6>
                <div>
                    <input type="radio" id="one" value="M" v-model="sexo" />
                    <label for="one">Masculino</label>
                    <input type="radio" id="two" value="F" v-model="sexo" />
                    <label for="two">Feminino</label>
                </div>
                <h5>CPF/CNPJ</h5>
                <input v-model="cpf_cnpj" v-mask="['###.###.###-##', '##.###.###/####-##']" placeholder="CPF ou CNPJ" />
                
                    <label for="">Data nascimento</label>
                </div> 
        </div>
        <ul v-if="tamanhoNomeInvalido">
            <li v-if="tamanhoNomeInvalido">Nome deve conter no minimo 3 caracteres</li>
            <li v-if="cpfInvalido">CFP/CNPJ incompleto</li>
            <li v-if="idadeInvalida">Idade inválida</li>
            <li v-if="sexoInvalido">Selecione o sexo do cliente</li>
            <li v-if="cpfExistente">CPF/CNPJ já cadastrado</li>
        </ul>
    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="salvaAlteracoes()">Salvar</button>
        <button type="button" class="btn btn-secondary" @click="fechaModal()">Sair sem salvar</button>
      </div>
    </div>
  </div>
</div>
   
</template>

<style scoped>
ul{
        color: red;
    }
    .campoInvalido{
        border-color: red;
    }
    .input-model{
        width: 100%;
    }
</style>