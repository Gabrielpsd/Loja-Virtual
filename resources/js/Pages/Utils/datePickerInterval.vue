<template>
    <div class="demo-date-picker">
      <div class="block">
        <span class="demonstration">{{Label}}</span>
        <el-date-picker
          v-model="date"
          type="daterange"
          range-separator="até"
          start-placeholder="Data inicial"
          end-placeholder="Data final"
          :size="size"
          unlink-panels
          @change="atualizaData"
          format="DD/MM/YYYY"
          :shortcuts="shortCut"
          value-format="YYYY-MM-DD"
          />
        </div>
    </div>
  </template>

<script>

  export default {
    name: 'DatePicker',
    props:{
      Label:{
        default: "Selecione a data",
        type: [Text, String]
      },
      size:{
        default: "small",
        type: [Text, String]
      }
    },
    methods: {
        atualizaData()
        {
            this.$emit('update:modelValue', this.date)
        },

    },
    data() {
      return {
        date: '',
        shortCut:[
            {
              text: 'Ultima Semana',
              value: () => {
                const end = new Date()
                const start = new Date()
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
                return [start, end]
              },
            },
            {
              text: 'Ulitimo mes ',
              value: () => {
                const end = new Date()
                const start = new Date()
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
                return [start, end]
              },
            },
            {
              text: 'ultimos 6 meses',
              value: () => {
                const end = new Date()
                const start = new Date()
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 180)
                return [start, end]
              },
            },
            {
              text: 'ultimos 1 ano',
              value: () => {
                const end = new Date()
                const start = new Date()
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 360)
                return [start, end]
              },
            },
          ]
      }
    },
  }
  </script>
  
  <style scoped>
  .demo-date-picker {
    display: flex;
    width: auto;
    padding: 0;
    flex-wrap: wrap;
  }
  
  .demo-date-picker .block {
    text-align: center;
    border-right: solid 1px var(--el-border-color);
    flex: 1;
  }
  
  .demo-date-picker .block:last-child {
    border-right: none;
  }
  
  .demo-date-picker .demonstration {
    display: block;
    font-size: 14px;
    font-weight: bold;
  }
  </style>
  