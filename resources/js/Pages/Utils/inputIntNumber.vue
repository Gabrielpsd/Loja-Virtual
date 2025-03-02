<script>
import ElInputNumber from 'element-plus'
export default{
    props: {
        modelValue: {
            type: [String, Number],
            default: '0',
        },
        maxAmount: {
        type: Number,
        default: 100,
        },
        Label: {
            type: [Text, String],
            default: "Preço"
        }
    },
    components:{
        ElInputNumber
    },
    methods:{
        updateNumber(event){
            this.$emit('update:modelValue', this.inputNumber);
        },
    },
    data(){
        return{
            inputNumber: this.modelValue
        }
    },
}

</script>

<template>
    <el-input-number ref="quantidade" v-model="inputNumber" :min="1" :max="maxAmount" @change="updateNumber" size="small" />
</template>
