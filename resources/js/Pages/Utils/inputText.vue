<template>
    <div class="floating-label-container">
      <el-input
        v-model="texto"
        :maxlength="maxLengh"
        :placeholder="Label"
        show-word-limit
        clearable
        type="text"
        @input="onInput"
        :size="Size"
      />
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
      maxLengh: {
        type: Number,
        default: 20,
      },
      Label: {
        type: Text,
        default: "Descrição"
      },
      Size: {
        type: String,
        default: 'large'
      },

    },
    data() {
      return {
        texto : this.modelValue
      };
    },
    methods: {
      onInput(event) {
        this.$emit('update:modelValue', this.texto);
      },
    },
  };
  </script>
  
  <style scoped>
  el-input{
    width: 100%;
    max-width: 240px;
  }
  </style>
  