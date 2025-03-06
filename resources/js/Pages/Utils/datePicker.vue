<template>
    <div >
      <div class="d-flex flex-column align-items justify-content-center">
        <span class="demonstration">{{Label}}</span>
        <el-date-picker
          v-model="date"
          type="date"
          :placeholder="Label"
          :size="size"
          @change="atualizaData"
          format="DD/MM/YYYY"
          value-format="YYYY-MM-DD"
          />
          <p v-if="dataInvalida"> Data não pode ser posterior a data atual</p>
        </div>
    </div>
  </template>

<script>
import utils from '../Assets/ArquivosConfiguracao/apiconfig'
  export default {
    name: 'DatePicker',
    props:{
      Label:{
        default: "Selecione a Data",
        type: [Text, String]
      },
      modelValue: {
        type: [String, Number],
        default: '0.00',
      }

    },
    methods: {
        atualizaData()
        {
            this.dataInvalida = false

            if(utils.calculateAge(this.date) > utils.DIASMAXIMOS || utils.calculateAge(this.date) <0)
            {
                this.dataInvalida = true
                return
            }
              
            this.$emit('update:modelValue', this.date)
        },
       
    },
    data() {
      return {
        size: 'small' , 
        date: this.modelValue,
        dataInvalida: false
      }
    },
  }
  </script>
  
  <style scoped>
 

  p{
    font-size: small;
    color: red;
  }
  </style>
  