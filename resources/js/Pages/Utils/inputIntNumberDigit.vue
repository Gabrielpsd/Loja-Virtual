<template>
    <div class="floating-label-container">
      <el-input
        v-model="numero"
        :maxlength="maxLengh"
        :placeholder="label"
        show-word-limit
        clearable
        type="text"
        @input="onInput"
        :size="Size"
        :formatter="(value) => `${value}`.replace(/[^0-9]/g, '')"
      />
      <p v-if="isOverLimit && !disable" style="color: red;">Maximo: {{ (maxAmount) }}</p>
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
        default: 4,
      },
      label: {
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
        numero : this.modelValue,
        isOverLimit: false
      };
    },
    methods: {
      onInput() {
        this.$emit('update:modelValue', this.numero);
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
  