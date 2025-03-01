<template>
    <div class="select-list">
      <el-select
        :id="label"
        v-model="selection"
        collapse-tags
        filterable
        :placeholder="label"
        @change="atualizaValor"
        popper-class="custom-header"
        style="width: 100%; max-width: 250px"
      >
        <!-- Options -->
        <el-option
          v-for="option in options"
          :key="option.id"
          :label="option.descricao"
          :value="option.id"
        />
      </el-select>
    </div>
</template>
  
<script>
  import { ElSelect, ElOption, ElCheckbox } from "element-plus";
  
  export default {
    name: "SelectList",
    components: {
      ElSelect,
      ElOption,
      ElCheckbox,
    },
    props: {
      modelValue: {
        type: [Object, String, Number],
        default: null,
      },
      label: {
        type: String,
        default: "Selecione uma opção",
      },
      options: {
        type: Array,
        required: true,
      },
    },
    data() {
      return {
        selection: this.modelValue,
      };
    },
    methods: {
      atualizaValor() {
        this.$emit("update:modelValue",this.selection);
      },
    },
    watch: {
      // Watch for changes in modelValue from the parent and update selection
      modelValue(newValue) {
        this.selection = newValue;
      },
    },
  };
  </script>
  
  <style scoped>
  .select-list {
    width: 100%;
    max-width: 250px;
  }
  
  .custom-header .el-checkbox {
    display: flex;
    height: unset;
  }
  </style>