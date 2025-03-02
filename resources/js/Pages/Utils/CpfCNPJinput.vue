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
        style="width: auto;"
        :size="Size"
        :disabled="disable"
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
    width: 10%;
    max-width: 240px;
  }
  </style>
  