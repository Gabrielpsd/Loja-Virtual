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
        style="width: auto;"
        :size="Size"
        :formatter="(value) => `${value}`.replace(/[^0-9]/g, '')"
      />
    </div>
  </template>
  
  <script>
  
  export default {

    props: {
      modelValue: {
        type: String,
      },
      maxLengh: {
        type: Number,
        default: 4,
      },
      label: {
        type: [Text, String],
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
      };
    },
    methods: {
      onInput() {
        this.$emit('update:modelValue', this.numero);
      },
    },
  };
  </script>

  