<template>
    <div class="floating-label-container">
      <el-input
        v-model="texto"
        :placeholder="Label"
        show-word-limit
        clearable
        v-mask="['###.###.###-##', '##.###.###/####-##']" 
        type="text"
        @input="onInput"
        @change="validaValor"
        style="width: auto;"
        :size="Size"
        :disabled="disable"
      />
      <p v-if="cpfInvalido">CPF inválido</p>
      <p v-if="cnpjIncorreto">CNPJ inválido</p>
      <p v-if="cadastroIncompleto">Cadastro incompleto</p>
      <p v-if="cpfExistente">CPF/CNPJ já cadastrado</p>
    </div>
  </template>
  
  <script>
  import { ElInput } from 'v-money';
  
  export default {
    components:{
      ElInput
    },
    props: {
      modelValue: {
        type: String,
      },
      Label: {
        type: [Text, String],
        default: "Descrição"
      },
      Size: {
        type: String,
        default: 'large'
      },
      disable: {
        type: Boolean,
        default: false,
        },
      Cpfs:{
          type: Array
        }

    },
    data() {
      return {
        texto : this.modelValue,
        cpfInvalido: false,
        cnpjIncorreto: false,
        cadastroIncompleto: false,
        cpfExistente: false
      };
    },
    methods: {
      onInput(event) {
        this.$emit('update:modelValue', this.texto);
      },
      validaValor(){

        console.log("to aqui")
        let valor = this.texto.replace(/\D/g,'')
        let cadastroValido = true
        if(valor.length != 11 &&  valor.length != 14)
        {
          this.cadastroIncompleto = true
          cadastroValido = false
        }else{
          this.cadastroIncompleto = false 
        }

        if(!this.validateCPF(valor) && valor.length == 11)
        {
            this.cpfInvalido = true
            cadastroValido = false
        }
        else
        {
          this.cpfInvalido = false
        }

        this.cpfExistente = false
        this.Cpfs.forEach((item)=>{
                if(item == valor)
                {
                    this.cpfExistente = true
                    cadastroValido = false
                }
            })

        if(!this.validateCNPJ(valor) && valor.length == 14)
        {
            this.cnpjIncorreto = true
            cadastroValido = false
        }
        else
          this.cnpjIncorreto = false

        this.$emit('validaDados', cadastroValido);
      },

      validateCPF(cpf) {
          // Check if all digits are the same (invalid CPF)
          if (/^(\d)\1{10}$/.test(cpf)) {
              return false;
          }

          // Validate first verifier digit
          let sum = 0;
          for (let i = 0; i < 9; i++) {
              sum += parseInt(cpf.charAt(i)) * (10 - i);
          }
          let remainder = (sum * 10) % 11;
          if (remainder === 10) remainder = 0;
          if (remainder !== parseInt(cpf.charAt(9))) {
              return false;
          }

          // Validate second verifier digit
          sum = 0;
          for (let i = 0; i < 10; i++) {
              sum += parseInt(cpf.charAt(i)) * (11 - i);
          }
          remainder = (sum * 10) % 11;
          if (remainder === 10) remainder = 0;
          if (remainder !== parseInt(cpf.charAt(10))) {
              return false;
          }

          return true;
      },
      validateCNPJ(cnpj) {
          // Check if all digits are the same (invalid CNPJ)
          if (/^(\d)\1{13}$/.test(cnpj)) {
              return false;
          }

          // Validate first verifier digit
          let weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
          let sum = 0;
          for (let i = 0; i < 12; i++) {
              sum += parseInt(cnpj.charAt(i)) * weights[i];
          }
          let remainder = sum % 11;
          let verifierDigit = remainder < 2 ? 0 : 11 - remainder;
          if (verifierDigit !== parseInt(cnpj.charAt(12))) {
              return false;
          }

          // Validate second verifier digit
          weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
          sum = 0;
          for (let i = 0; i < 13; i++) {
              sum += parseInt(cnpj.charAt(i)) * weights[i];
          }
          remainder = sum % 11;
          verifierDigit = remainder < 2 ? 0 : 11 - remainder;
          if (verifierDigit !== parseInt(cnpj.charAt(13))) {
              return false;
          }

          return true;
      }
    },
  };
  </script>
  
  <style scoped>
  el-input{
    width: 10%;
    max-width: 240px;
  }
  p{
    color: red;
    font-size: small;
}
  </style>
  