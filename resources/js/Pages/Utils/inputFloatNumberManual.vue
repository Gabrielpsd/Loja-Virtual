<script>
    export default{
        props:{
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
                default: 10000
            },
            size:{
                type: [String, Text],
                default: 'small'
            }
        },
        data(){
            return{
                inputNumber: new Intl
                    .NumberFormat(
                        'pt-BR',
                        {minimumFractionDigits: 2})
                    .format(this.modelValue),
                numeroMaximo: false,
                valorZerado: false
            }
        },
        methods:{
            updateNumber(number){
                this.$emit('update:modelValue',number)
                this.$emit('change');
            },
        
            formataParaBrasil(number){
                number  = number + ''
                number  = parseFloat(number.replace('.','').replace(',','.'))

                console.log("Comparando: ",number > this.maxAmount)

                if(number > this.maxAmount)
                {
                    this.numeroMaximo = true
                    number = this.maxAmount
                }
                else
                    this.numeroMaximo = false

                if(number === 0)
                    this.valorZerado = true
                else
                    this.valorZerado = false

                this.updateNumber(number)
                /*formata o numero para o padrão brasileiro */ 
                let numeroFormatado =  new Intl
                    .NumberFormat(
                        'pt-BR',
                        {minimumFractionDigits: 2})
                    .format(number)

                return numeroFormatado
            },

            verificaDigito(key) { 

                if(key.code == "Tab")
                    return

                if(parseInt(key.key) >= 0 && parseInt(key.key) <= 9)
                {   
                    let numbers = (this.inputNumber+'').split(',')
                    numbers = numbers[0]+numbers[1].charAt(0) + ',' + numbers[1].charAt(1) + key.key
                    this.inputNumber = this.formataParaBrasil(numbers)
                }

                if(key.code == 'Backspace')
                {
                    key.preventDefault()

                    if(parseFloat((this.inputNumber).replace(/[\.,]/,'')) <= 0.00)
                    {
                        return
                    } 

                    /* divide a strin entre parte inteira e centavos depois remonta a string */ 
                    let number = (this.inputNumber + '').split(',')
                    if(number[0].length <= 1)
                        number = '0' + ',' + number[0].slice(-1) + number[1].charAt(0)
                    else
                        number = number[0].slice(0,-1) + ',' + number[0].slice(-1) + number[1].charAt(0)
                    
                    this.inputNumber = this.formataParaBrasil(number)
                    return
                }

                if([37,38,39,40].indexOf(key.key) != -1)
                {
                    return
                }

                key.preventDefault()
                key.stopPropagation()
            },
        }
     
    }

</script>

<template>
     <label>
        <el-input 
        :size="size" 
        :placeholder="inputNumber" 
        :value="inputNumber" 
        @input="updateNumber" 
        v-on:keydown="verificaDigito"
        :disabled="disabled"
        >
        <template #prepend>R$</template>
    </el-input>
    <p v-if="numeroMaximo" style="color: red;">Máximo: R${{ (maxAmount).toFixed(2) }}</p>
    <p v-if="valorZerado" style="color: red;">Valor Zerado</p>
    
    </label>
</template>

<style scoped>
p {
  font-size: x-small;
}
</style>

