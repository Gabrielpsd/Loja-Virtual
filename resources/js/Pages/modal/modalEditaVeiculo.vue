<script>
import rotas from '../Assets/ArquivosConfiguracao/apiconfig'
import datePickerYear from '../Utils/datePickerYear.vue'
import CpfCNPJinput from '../utils/CpfCNPJinput.vue'
import inputText from '../Utils/inputText.vue'
import selectListOne from '../Utils/selectListOne.vue'

export default{
    props:{
        Veiculo: Object,
        Clientes: Array,
        Marcas: Array,
        Cores: Array,
        ocultarCliente: {
            type: Boolean,
            default: false
        },
    },
    data(){
        return{
            placa: this.Veiculo.placa,
            placaInvalida: false,
            id_proprietario: this.Veiculo.id_proprietario,
            proprietarioinvalido: false,
            cor: this.Veiculo.id_cor,
            corInvalida: false,
            ano_fabricacao: this.Veiculo.ano_fabricacao + '',
            anoFabricacaoInvalido: false, 
            ano_modelo: this.Veiculo.ano_modelo + '',
            anoModeloInvalido: false,
            marca: this.Veiculo.id_marca,
            marcaInvalida: false,
            loading: false,
          }
    },
    components:{
        datePickerYear,
        selectListOne,
        inputText
    },
    methods: {
        salvaAlteracoes(){
            if(this.validacoes())
            {

                this.loading= true
                const csrfToken = document.getElementsByName("_token")[0].value; // Get CSRF token

                    const pessoa = {
                        id_proprietario: this.id_proprietario,
                        cor: this.cor,
                        marca: this.marca,
                        ano_fabricacao: this.ano_fabricacao,
                        ano_modelo: this.ano_modelo,
                    };

                    fetch(rotas.veiculos.editar(this.Veiculo.id), {
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


            if(this.placa.length != 7)
            {
                this.placaInvalida = true
                retorno = false
            }
            else 
                this.placaInvalida = false

            if(this.id_proprietario == null)
            {
                this.proprietarioinvalido = true
                retorno = false
                
            }
            else
                this.proprietarioinvalido = false

            if(this.cor == null)
            {
                this.corInvalida = true
                retorno =  false
                
            }
            else
                this.corInvalida = false

            if(this.marca == null)
            {
                this.marcaInvalida = true
                retorno =  false
                
            }
            else
                this.marcaInvalida = false

            if(this.ano_fabricacao > new Date().getFullYear() || this.ano_fabricacao == null)
            {
                this.anoFabricacaoInvalido = true
                retorno =  false
            }
            else
                this.anoFabricacaoInvalido = false

            if(this.ano_modelo == null)
            {
                this.anoModeloInvalido = true
                retorno =  false
            }
            else
                this.anoModeloInvalido = false

            return retorno 
        },

        cancelaAdicao(){
            this.placa= '',
            this.placaInvalida= false,
            this.id_proprietario= null,
            this.proprietarioinvalido= false,
            this.id_cor= null,
            this.corInvalida= false,
            this.ano_fabricacao= null,
            this.anoFabricacaoInvalido= false, 
            this.ano_modelo= null,
            this.anoModeloInvalido= false,
            this.marca= null,
            this.marcaInvalida= false
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edita Veiculo</h1>
      </div>
      <div class="modal-body">
        <div class="card border-dark mb-3" style="max-width: 18rem;" >
        <div class="card-body text-dark" >
            <inputText v-model="placa" :maxLengh="7" :Label="'Placa'"/>
            <p v-if="placaInvalida">Placa deve conter 7 caracteres </p>
            <div>
                <h6 v-if="!ocultarCliente">Proprietario</h6>
                <selectListOne v-if="!ocultarCliente" v-model="id_proprietario" :options="this.Clientes" :label="'Proprietario'" :disabled="BloquearCliente"/>
                <p v-if="proprietarioinvalido">Selecione um proprietario </p>
                <h6>Cor</h6>
                <selectListOne v-model="cor" :options="Cores" :label="'Cor'"/>
                <p v-if="corInvalida">Selecione uma cor </p>
                <h6>Marca</h6>
                <selectListOne v-model="marca" :options="Marcas" :label="'Marca'"/>
                <p v-if="marcaInvalida">Selecione uma marca </p>
                <datePickerYear v-model="ano_fabricacao" :Label="'Ano Fabricacao'" />
                <p v-if="anoFabricacaoInvalido">Ano de fabricacao invalida </p>
                <datePickerYear v-model="ano_modelo" :Label="'Ano Modelo'" />
                <p v-if="anoModeloInvalido">Selecione uma data</p>
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
        <button type="button" class="btn btn-secondary":disabled="loading" @click="fechaModal()">Sair sem salvar</button>
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