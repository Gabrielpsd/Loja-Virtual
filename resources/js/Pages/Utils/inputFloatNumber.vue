<template>
    <div class="floating-label-container">
        <el-input
        v-model="displayValue"
        @input="handleInput"
        @blur="handleBlur"
        placeholder="Digite o Valor"
        :disabled="disabled"
        :size="size"
        >
        <template #prepend>R$</template>
        </el-input>

        <p v-if="isOverLimit" style="color: red;">Máximo: R${{ (maxAmount).toFixed(2) }}</p>
    </div>
  </template>
  
  <script>
  import { ElInput } from 'element-plus';
  
  export default {
    components: {
      ElInput,
    },
    props: {
      modelValue: {
        type: [String, Number],
        default: '',
      },
      disabled: {
        type: Boolean,
        default: false,
      },
      maxAmount:{
        type: Number,
        default: 1000000
      },
      size:{
            type: [String, Text],
            default: 'small'
        }
    },
    data() {
      return {
        displayValue: this.formatCurrency(this.modelValue * 100),
        isOverLimit: false
      };
    },
    watch: {
      modelValue(newValue) {
        this.displayValue = this.formatCurrency(newValue);
      },
    },
    methods: {
      // Format the value as currency (e.g., 1000 => 1.000,00)
      formatCurrency(value) {
        if (!value) return '';
        const numericValue = parseFloat(value.toString().replace(/[^0-9]/g, ''));
        if (isNaN(numericValue)) return '';
        return (numericValue / 100).toLocaleString('pt-BR', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        });
      },
      // Parse the formatted value back to a raw number (e.g., 1.000,00 => 1000)
      parseCurrency(formattedValue) {
        if (!formattedValue) return 0;
        const numericString = formattedValue
          .replace(/[^0-9]/g, '') // Remove all non-numeric characters
          .padStart(3, '0'); // Ensure at least 3 digits (for cents)
        return parseInt(numericString, 10);
      },
      // Handle input event
      handleInput(value) {
        const parsedValue = this.parseCurrency(value);
        // Check if the parsed value exceeds the maximum limit

        if (parsedValue > this.maxAmount) {
            this.isOverLimit = true; // Set over-limit flag
            const cappedValue = this.maxAmount; // Cap the value at the maximum limit
            this.displayValue = this.formatCurrency(cappedValue);
            this.$emit('update:modelValue', cappedValue);
        } else {
            this.isOverLimit = false; // Reset over-limit flag
            this.displayValue = this.formatCurrency(parsedValue);
            this.$emit('update:modelValue', (parsedValue/100));
        }
      },
      // Handle blur event to ensure proper formatting
      handleBlur() {
        if(this.isOverLimit)
            return
        this.displayValue = this.formatCurrency(this.modelValue);
      },
    },
  };
  </script>
  
  <style scoped>
  .el-input {
    width: 100%;
  }
  .floating-label-container {
  position: relative;
  margin-top: 20px;
}

.floating-label-container input {
  width: 100%;
  padding: 10px 10px 10px 5px;
  font-size: 16px;
  border: none;
  border-bottom: 1px solid #757575;
  outline: none;
}

.floating-label-container label {
  color: #999;
  font-size: 16px;
  position: absolute;
  pointer-events: none;
  left: 5px;
  top: 10px;
  transition: 0.2s ease all;
}

.floating-label-container input:focus ~ label,
.floating-label-container label.active {
  top: -10px;
  font-size: 12px;
  color: #5264ae;
}

.floating-label-container input:focus {
  border-bottom: 2px solid #5264ae;
}

p {
  font-size: x-small;
}
  </style>