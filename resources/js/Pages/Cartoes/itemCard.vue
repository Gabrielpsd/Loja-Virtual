
<script>
import inputIntNumber from '../utils/inputIntNumber.vue';
import inputFloatNumber from '../utils/inputFloatNumber.vue';

export default{
    name: "Item",
    props: {
        produto: Object
    },
    components:{
        inputIntNumber,
        inputFloatNumber
    },
    data(){
        return{
            descricao: this.produto.descricao,
            preco: this.produto.preco,
            quantidade: this.produto.quantidade,
            precoTotal: 0,
            valorMaximo: 9999999
        }
    },
    watch: {
        preco: 'atualizaPrecoTotal',
        quantidade: 'atualizaPrecoTotal'
    },
    methods:{
        atualizaPrecoTotal() {
            console.log("Dentro do componente- preço: ", this.preco)
            console.log("Dentro do componente quant: ",this.quantidade)
            const resultado = (parseFloat(this.preco) || 0) * (parseInt(this.quantidade) || 0);
            this.precoTotal = resultado.toFixed(2);
        },
    },

}
</script>   

<template>
    <div class="outside">
        <div class="fw-bold "></div>   
            <div class="d-flex justify-content-between">
            <div class="padding" >
                Quantidade:
                <inputIntNumber v-model="this.quantidade"/>
            </div> 

                <inputFloatNumber v-model="this.preco"/>
                <inputFloatNumber v-model="this.precoTotal" :maxAmount="this.valorMaximo" disable/>

        </div>
        
    </div>
    
</template>

<style scoped>
    .flex{
        padding: 2px;
        margin: 2px;
    }
    .padding{
        padding: 3px;
        margin: 3px;
        display: flex;
        flex-direction: column;
        border-radius: 10px;
        justify-content: center;
        align-items: center;
    }
    h5{
        text-align: justify;
    }
    .outside{
        display: flex;
        flex-direction: row;
    }
</style>