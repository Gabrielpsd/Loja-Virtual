<template>
    <div class="floating-label-container">
      <input
        id="floatNumber"
        :value="formattedValue"
        v-money="moneyConfig"
        type="text"
        @input="onInput"
        ref="moneyInput"
        :size="Size"
        @focus="isFocused = true"
        @blur="handleBlur"
        :disabled="disable"
      />
      <label :class="{ active: isFocused || formattedValue }" for="floatNumber">
        {{this.Label}}
      </label>
      <p v-if="isOverLimit && !disable" style="color: red;">Maximo: R${{ (maxAmount-0.01).toFixed(2) }}</p>
    </div>
  </template>
  
  <script>
  import { VMoney } from 'v-money';
  
  export default {
    directives: { money: VMoney },
    props: {
      modelValue: {
        type: [String, Number],
        default: '0.00',
      },
      maxAmount: {
        type: Number,
        default: 10000,
      },
      Label: {
        type: Text,
        default: "Preço"
      },
      Size: {
        type: Number,
        default: 10
      },
        disable: {
        type: Boolean,
        default: false,
        },
    },
    data() {
      return {
        isOverLimit: false,
        isFocused: false,
        moneyConfig: {
          decimal: ',',
          thousands: '.',
          prefix: '$ ',
          suffix: '',
          precision: 2,
          masked: false,
        },
      };
    },
    computed: {
      formattedValue() {
        const value = parseFloat(this.modelValue || '0.00');
        if (isNaN(value)) return '';
        return value.toFixed(2).replace('.', ',');
      },
    },
    methods: {
      onInput(event) {
        const rawValue = event.target.value;
        const numericValue = parseFloat(rawValue.replace(/[^0-9,]/g, '').replace(/[,]/g,'.'));
        if (rawValue === '') {
            this.isOverLimit = false;
            this.$emit('update:modelValue', '');
            return;
        }
        
        console.log(numericValue)
        console.log(typeof(numericValue))
        if (numericValue >= this.maxAmount - 0.01)  {
            console.log("Estou aqui (1)")
            this.isOverLimit = true;
            const maxValue = (this.maxAmount - 0.01).toFixed(this.moneyConfig.precision);
            this.$emit('update:modelValue', maxValue);
            this.$refs.moneyInput.value = maxValue.replace(/[$.]/g,'').replace(/[,]/,'.');
        } else {
            console.log("Estou aqui (2)")
            this.isOverLimit = false;
            this.$emit('update:modelValue', rawValue.replace(/[$.]/g,'').replace(/[,]/,'.'));
        }
      },
      handleBlur() {
        if (!this.formattedValue) {
          this.isFocused = false;
        }
      },
    },
  };
  </script>
  
  <style scoped>
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
  