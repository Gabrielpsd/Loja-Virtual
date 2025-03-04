<template>
    <div class="demo-date-picker">
      <div class="block">
        <span class="demonstration">{{Label}}</span>
        <el-date-picker
          v-model="date"
          type="week"
          :placeholder="Label"
          :size="size"
          format="YYYY"
           @change="atualizaData"
          value-format="YYYY"
          />
      </div>
    </div>
  </template>

<script>
export default {
    name: 'DatePicker',
    props:{
      Label:{
        default: "Selecione a Data",
        type: [Text, String]
      },
      modelValue: {
        type: [String],
        default: null
      },
    },
    methods: {
        atualizaData()
        {
            this.$emit('update:modelValue', this.date)
        },
       
    },
    data() {
      return {
        size: 'small' , 
        date: this.modelValue
      }
    },
    mounted(){
      if(this.modelValue)
        this.date = String(new Date(this.modelValue,0,1).getFullYear())
    }
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
  