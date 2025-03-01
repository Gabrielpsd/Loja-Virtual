
<script>
import selectListOne from '../utils/selectListOne.vue'

export default{
    name: "Item",
    components:{
        selectListOne
    },
    props:{
        Produtos: Array
    },
    data(){
        return{
            itemSelecionado: null,
            itemInvalido: false,
            id: 1
        }
    },
    methods:{
        salvar(){

            if(this.itemSelecionado === null)
            {
                this.itemInvalido = true
                return
            }

            let objetoInserir = this.Produtos.find(item=>item.id == this.itemSelecionado)
            this.resetaComponente
            this.itemSelecionado= null
            this.itemInvalido = false
            this.$emit("insereObjeto",objetoInserir)
        }
    },
    watch:{
        itemSelecionado(){
            this.itemInvalido = false
        }
    }
}
</script>

<template>
    <div class="d-flex justify-content-between margin">
            <h5>Adicionar Item</h5>
            <button type="button" class="btn btn-secondary btn-sm" @click="salvar" >Adicionar <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
            </svg>
        </button>
    </div>
    <p v-if="itemInvalido" style="color: red;">Selecione um item valido</p>
    <div class="margin">
        <selectListOne id="item" v-model="itemSelecionado" :options="this.Produtos" :label="'Produtos'"/>
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
    .margin{
        margin: 1px;
        display: flex;
        justify-content: center;
    }
    .bloqueado{
        cursor: not-allowed; /* Change cursor to indicate unavailability */
        pointer-events: none; /* Disable all mouse events */
    }
    select{
        width: 80%;
    }

    @media(max-width: 758px)
    {
        *{
            font-size: small ;
        }
    }
</style>