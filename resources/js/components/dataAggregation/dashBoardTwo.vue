<template>
    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div class="col-12" v-show="!loading">
        <div class="row">
            <div class="col-6">
                <div class="v-chart-header">
                    <p>Контроль выполнения выручки опер. план</p>
                    <p></p>
                </div>
                <apexchart width="100%" ref="chart7" type="bar" height="500px" :options="options7"
                           :series="series7"></apexchart>
            </div>
            <div class="col-6">
                <div class="v-chart-header">
                    <p>Отклонение, %</p>
                    <p></p>
                </div>
                <apexchart width="100%" ref="chart8" type="bar" height="500px" :options="options8"
                           :series="series8"></apexchart>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="v-chart-header">
                    <p>Общая выручка</p>
                    <p></p>
                </div>
                <apexchart width="100%" type="bar" height="500px" :options="options9"
                           :series="series9"></apexchart>
            </div>
            <div class="col-6">
                <div class="v-chart-header">
                    <p>Выполнение текущей выручки относительно ожидаемой</p>
                    <p></p>
                </div>
                <apexchart width="100%" ref="chart10" type="bar" height="500px" :options="options10"
                           :series="series10"></apexchart>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="v-chart-header">
                    <p>анализ текущего выполнения выручки</p>
                    <p></p>
                </div>
                <apexchart width="100%" ref="chart11" type="bar" height="700px" :options="options11"
                           :series="series11"></apexchart>
            </div>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    components: {
        createToaster
    },
    name: "dataAggregation",

    data() {
        return {
            loading: false,
            contractAndFact: [],
            planContract: [],
            expectedRevenue: [],
            budget: [],
            operPlan: [],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            options7: {
                labels: [],
                legend: {
                    showForSingleSeries: true,
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                chart: {
                    type: "bar",
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter(value) {
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                        //return value.toLocaleString('en')
                    },
                    style: {
                        colors: ['#333']
                    },
                    offsetX: 40,
                },
                colors: ['#bdd7ee', '#1f4e79'],
                xaxis: {
                    categories: []
                },
                yaxis: {
                    categories: []
                },
            },
            series7: [
                {
                    name: 'Опер. план',
                    type: 'column',
                    data: []
                },
                {
                    name: 'Факт/ожид. факт',
                    type: 'column',
                    data: []
                }
            ],
            options8: {
                labels: [],
                legend: {
                    showForSingleSeries: true,
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                chart: {
                    type: "bar",
                    toolbar: {
                        show: false
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        dataLabels: {
                            position: 'center',
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter(value) {
                        return `${value}%`;
                    },
                },
                colors: [
                    function ({value, seriesIndex, dataPointIndex, w}) {
                        //let i = w.config.series[0].data[dataPointIndex]['x']
                        if (value < 0) {
                            return '#be0000'
                        } else {
                            return '#70ad47'
                        }
                    }
                ],
                grid: {
                    show: true,
                },
                xaxis: {
                    categories: []
                },
                yaxis: {
                    show: true,
                    min: -100,
                    max: 100,
                },
                tooltip: {
                    fixed: {
                        enabled: true,
                        position: 'topLeft',
                        offsetX: 0,
                        offsetY: 0,
                    },
                }
            },
            series8: [
                {
                    name: 'Отклонение, %',
                    type: 'column',
                    data: []
                },
            ],
            options9: {
                labels: [],
                legend: {
                    showForSingleSeries: true,
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                xaxis: {
                    categories: ['Контрактация', 'Опер. план', 'Факт/ожид. факт', 'Текущий факт',]
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            position: 'top',
                        }
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter(value) {
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                        //return value.toLocaleString('en')
                    },
                    style: {
                        colors: ['#333']
                    },
                    offsetY: -30,
                },
            },
            series9: [
                {
                    name: 'млн. руб.',
                    type: 'column',
                    data: [6666, 6000, 5458, 5458]
                }
            ],
            options10: {
                labels: [],
                legend: {
                    showForSingleSeries: true,
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                chart: {
                    type: "bar",
                    toolbar: {
                        show: false
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        dataLabels: {
                            position: 'center',
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter(value) {
                        return `${value}%`;
                    },
                },
                colors: [
                    function ({value, seriesIndex, dataPointIndex, w}) {
                        //let i = w.config.series[0].data[dataPointIndex]['x']
                        if (value < 100) {
                            return '#be0000'
                        } else {
                            return '#70ad47'
                        }
                    }
                ],
                grid: {
                    show: true,
                },
                xaxis: {
                    categories: []
                },
                yaxis: {
                    show: true,
                    min: -100,
                    max: 100,
                }
            },
            series10: [
                {
                    name: 'Выполнение, %',
                    type: 'column',
                    data: []
                },
            ],
            options11: {
                labels: [],
                legend: {
                    showForSingleSeries: true,
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                xaxis: {
                    categories: []
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            position: 'top',
                            orientation: 'vertical',
                        }
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter(value) {
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                        //return value.toLocaleString('en')
                    },
                    style: {
                        colors: ['#333']
                    },
                    offsetY: 10,
                },
            },
            series11: [
                {
                    name: 'Контрактация',
                    type: 'column',
                    data: []
                },
                {
                    name: 'Опер. план',
                    type: 'column',
                    data: []
                },
                {
                    name: 'Факт/ожид. факт',
                    type: 'column',
                    data: []
                },
                {
                    name: 'Текущий факт',
                    type: 'column',
                    data: []
                }
            ],
        }
    },
    async mounted() {
        await this.loadData()
    },
    methods: {
        async loadData() {
            this.loading = true
            await axios
                .get(`../../data-aggregation-contract-and-fact/getAll`)
                .then((response) => {
                    this.contractAndFact = response.data
                    this.contractAndFact.forEach(item => {
                        for (let i = 1; i < 13; i++) {
                            item['fact_' + i] = item['fact_' + i] / 1000
                            item['plan_' + i] = item['plan_' + i] / 1000
                        }
                    })
                })
            await axios
                .get(`../../data-aggregation-plan-contract/getAll`)
                .then((response) => {
                    this.planContract = response.data
                })
            await axios
                .get(`../../data-aggregation-expected-revenue/getAll`)
                .then((response) => {
                    this.expectedRevenue = response.data
                    this.expectedRevenue.forEach(item => {
                        item['sum'] = item['sum'] / 1000000
                    })
                })
            await axios
                .get(`../../data-aggregation-budget/getAll`)
                .then((response) => {
                    this.budget = response.data
                })
            await axios
                .get(`../../data-aggregation-operational-plan/getAll`)
                .then((response) => {
                    this.operPlan = response.data
                    this.operPlan.forEach(item => {
                        for (let i = 1; i < 13; i++) {
                            item['plan_' + i] = item['plan_' + i] / 1000000
                        }
                    })
                })
            this.setSeventhSeries()
            this.setEighthSeries()
            this.setNinthSeries()
            this.setTenthSeries()
            this.setElevensSeries()
            this.loading = false
            window.dispatchEvent(new Event('resize'))
        },
        setSeventhSeries(){
            let categories = []
            this.series7[0].data = [] //Опер план
            this.series7[1].data = [] //Факт/ожид факт
            this.operPlan.forEach(item => {
                categories.push(item.short_name)
                let operSum = 0
                for (let i = 1; i < 13; i++) {
                    operSum += item['plan_' + i]
                }
                //this.series7[0].data.push(operSum.toFixed(2))
                this.series7[0].data.push(Math.round(operSum))
                let contractAndFact = this.contractAndFact.filter(contract=>contract.company_name === item.company_name)
                let factSum = 0
                for (let i = 1; i < 13; i++) {
                    factSum += contractAndFact[0]['fact_' + i]
                }
                if(factSum !== 0){
                    //this.series7[1].data.push(factSum.toFixed(2))
                    this.series7[1].data.push(Math.round(factSum))
                } else{
                    let expectedRevenue = this.expectedRevenue.filter(exp => exp.company_name === item.company_name)
                    //console.log('expectedRevenue', expectedRevenue)
                    let sum = 0
                    expectedRevenue.forEach(exp => {
                        sum += exp['sum']
                    })
                    //this.series7[1].data.push(sum.toFixed(2))
                    this.series7[1].data.push(Math.round(sum))
                }
            })
            this.$refs.chart7.updateOptions({
                xaxis: {
                    categories: categories
                }
            })
            this.$refs.chart8.updateOptions({
                xaxis: {
                    categories: categories
                }
            })
        },
        setEighthSeries(){
            this.series8[0].data = []
            this.series7[1].data.forEach((item, index) => {
                let percent = (this.series7[1].data[index] / this.series7[0].data[index] - 1) * 100
                percent = Math.floor(percent * 100) / 100
                this.series8[0].data.push(percent)
            })
        },
        setNinthSeries(){
            this.series9[0].data = []
            let sumPlan = 0
            this.contractAndFact.forEach(item => {
                for (let i = 1; i < 13; i++) {
                    sumPlan += item['plan_' + i]
                }
            })
            //this.series9[0].data.push(sumPlan.toFixed(2))
            this.series9[0].data.push(Math.round(sumPlan))

            let operSum = 0
            this.operPlan.forEach(item => {
                for (let i = 1; i < 13; i++) {
                    operSum += item['plan_' + i]
                }
            })
            //this.series9[0].data.push(operSum.toFixed(2))
            this.series9[0].data.push(Math.round(operSum))

            let sumFactExp = 0
            this.contractAndFact.forEach(item => {
                for (let i = 1; i < 13; i++) {
                    if(item['fact_' + i] !==0){
                        sumFactExp += item['fact_' + i]
                    } else{ //Если нет данных, то берем ожидаемую прибыль
                        let expectedRevenue = this.expectedRevenue.filter(exp => exp.company_name === item.company_name)
                        expectedRevenue.forEach(exp=>{
                            sumFactExp += exp['sum']
                        })
                    }
                }
            })
            //this.series9[0].data.push(sumFactExp.toFixed(2))
            this.series9[0].data.push(Math.round(sumFactExp))


            let sumFact = 0
            this.contractAndFact.forEach(item => {
                for (let i = 1; i < 13; i++) {
                    sumFact += item['fact_' + i]
                }
            })
            //this.series9[0].data.push(sumFact.toFixed(2))
            this.series9[0].data.push(Math.round(sumFact))
        },
        setTenthSeries(){
            let categories = []
            this.series10[0].data = []

            this.contractAndFact.forEach(item => {
                categories.push(item.company_name)
                //categories.push('тест')
                let sumFact = 0
                for (let i = 1; i < 13; i++) {
                    sumFact += item['fact_' + i]
                }
                let sumExp = 0
                let expectedRevenue = this.expectedRevenue.filter(exp=>exp.company_name === item.company_name)
                expectedRevenue.forEach(exp=>{
                    sumExp += exp['sum']
                })
                if(sumExp === 0 ){
                    sumExp = sumFact
                }

                let percent = sumFact/sumExp * 100
                percent = Math.floor(percent * 100) / 100
                this.series10[0].data.push(percent)
            })
            this.$refs.chart10.updateOptions({
                xaxis: {
                    categories: categories
                }
            })
        },
        setElevensSeries(){
            let categories = []
            this.series11[0].data = []
            this.series11[1].data = []
            this.series11[2].data = []
            this.series11[3].data = []
            this.contractAndFact.forEach(item => {
                categories.push(item.company_name)
                let sumPlan = 0
                for (let i = 1; i < 13; i++) {
                    sumPlan += item['plan_' + i]
                }
                //this.series11[0].data.push(sumPlan.toFixed(2))
                this.series11[0].data.push(Math.round(sumPlan))

                let operPlan = this.operPlan.filter(oper=> oper.company_name === item.company_name)
                let operSum = 0
                operPlan.forEach(item => {
                    for (let i = 1; i < 13; i++) {
                        operSum += item['plan_' + i]
                    }
                })
                //this.series11[1].data.push(operSum.toFixed(2))
                this.series11[1].data.push(Math.round(operSum))

                let sumFactExp = 0
                for (let i = 1; i < 13; i++) {
                    sumFactExp += item['fact_' + i]
                }
                if(sumFact === 0){
                    let expectedRevenue = this.expectedRevenue.filter(exp=>exp.company_name === item.company_name)
                    expectedRevenue.forEach(exp=>{
                        sumFactExp += exp['sum']
                    })
                }
                //this.series11[2].data.push(sumFactExp.toFixed(2))
                this.series11[2].data.push(Math.round(sumFactExp))

                let sumFact = 0
                for (let i = 1; i < 13; i++) {
                    sumFact += item['fact_' + i]
                }
                //this.series11[3].data.push(sumFact.toFixed(2))
                this.series11[3].data.push(Math.round(sumFact))
            })

            this.$refs.chart11.updateOptions({
                xaxis: {
                    categories: categories
                }
            })
        }
    },
    computed: {},

}
</script>

<style scoped>

</style>
