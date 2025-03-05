<script>
import rotas from '../Assets/ArquivosConfiguracao/apiconfig'
import datePickerYear from '../Utils/datePickerYear.vue'
import CpfCNPJinput from '../utils/CpfCNPJinput.vue'
import inputText from '../Utils/inputText.vue'
import selectListOne from '../Utils/selectListOne.vue'
import inputFloatNumberManual from '../Utils/inputFloatNumberManual.vue'
export default{
    props:{
        Servicos: Array,
        Servico: Object
    },
    data(){
        return{
            descricao: this.Servico.descricao,
            descricaoInvalida: false,
            preco: this.Servico.preco,
            precoInvalido: false,
            loading: false,
            ativo: this.Servico.ativado
          }
    },
    components:{
        datePickerYear,
        selectListOne,
        inputText,
        inputFloatNumberManual
    },
    methods: {
        salvaAlteracoes(){
            if(this.validacoes())
            {

                this.loading= true
                const csrfToken = document.getElementsByName("_token")[0].value; // Get CSRF token

                    const servico = {
                        descricao: this.descricao,
                        preco: this.preco,
                        ativado: this.ativo     
                    };

                    fetch(rotas.servicos.editar(this.Servico.id), {
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

            if(this.descricao.length <= 3)
            {
                this.descricaoInvalida = true
                retorno = false
            }
            else 
                this.descricaoInvalida = false
            
            if(parseFloat(this.preco) <= 0)
            {
                this.precoInvalido = true
                retorno = false
            }
            else 
                this.precoInvalido = false

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
}



</script>

<template>
 <!-- Modal -->
<div class="modal fade show modal-dialog-centered " tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Serviço</h1>
      </div>
      <div class="modal-body">
        <div class="card border-dark mb-3" style="max-width: 18rem;" >
        <div class="card-body text-dark" >
            <inputText v-model="descricao" :maxLengh="30" :Label="'Descricao'"/>
            <p v-if="descricaoInvalida">Descricao deve ter no minimo 3 caracteres </p>
            <inputFloatNumberManual v-model="preco"/>
            <p v-if="precoInvalido">Preço deve ser maior que zero</p>
            <div>
                <input type="radio" id="true" :value="false" v-model="ativo" />
                <label for="true">Desativado</label>
                <input type="radio" id="false" :value="true" v-model="ativo" />
                <label for="false">Ativado</label>
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