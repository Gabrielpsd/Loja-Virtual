<template>
    <div class="select-list">
      <el-select
        :id="label"
        v-model="selection"
        multiple
        collapse-tags
        filterable
        :placeholder="label"
        @change="atualizaValor"
        popper-class="custom-header"
        style="width: 100%; max-width: 250px"
      >
        <!-- Header with "Select All" checkbox -->
        <template #header>
          <el-checkbox
            v-model="checkAll"
            @change="handleCheckAll"
            :indeterminate="indeterminate"
          >
            Select All
          </el-checkbox>
        </template>
  
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
        selection: null,
        checkAll: true,
        indeterminate: false
      };
    },
    watch: {
        selection(val) {
            if (val.length === 0) {
                this.checkAll = false
                this.indeterminate = false
            } else if (val.length === this.options.length) {
                this.checkAll = true
                this.indeterminate = false
            } else {
                this.indeterminate = true
            }
      },
    },
    methods: {
      atualizaValor() {
        console.log(this.selection)
        if(this.selection.length == 0 )
        {
            this.$emit("update:modelValue", null);
            return
        }
        
        this.$emit("update:modelValue",this.selection);
      },
      handleCheckAll(val) {
        console.log("val", val)
        this.indeterminate = false
        if (val) {
          // Select the first option when "Select All" is checked
          this.selection = this.options.map((value)=>value.id);
        } else {
          // Clear selection when "Select All" is unchecked
          this.selection = [];
        }

        this.atualizaValor(); // Emit the updated selection
      },
    },
    mounted(){
        this.handleCheckAll(true)
    }
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