<template>
    <div class="demo-date-picker">
      <div class="block">
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
  .demo-date-picker {
    display: flex;
    width: auto;
    padding: 0;
    flex-wrap: wrap;
  }
  
  .demo-date-picker .block {
    text-align: center;
    border-right: solid 1px var(--el-border-color);
    flex: 1;
  }
  
  .demo-date-picker .block:last-child {
    border-right: none;
  }
  
  .demo-date-picker .demonstration {
    display: block;
    font-size: 14px;
    font-weight: bold;
  }
  p{
    font-size: small;
    color: red;
  }
  </style>
  