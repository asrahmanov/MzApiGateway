<template>
    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div class="col-12" v-show="!loading">
        <div class="row">
            <div class="col-6">
                <div class="v-chart-header">
                    <p>Выручка млн. руб.</p>
                    <p></p>
                </div>
                <apexchart width="100%" type="bar" height="500px" :options="options" :series="series"></apexchart>
            </div>
            <div class="col-6">
                <div class="v-chart-header">
                    <p>Контроль выполнения выручки бюджет, в млн. руб.</p>
                    <p></p>
                </div>
                <apexchart width="100%" type="bar" height="350px" :options="options2" :series="series2"></apexchart>
                <apexchart width="100%" type="bar" height="150px" :options="options3" :series="series3"></apexchart>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="v-chart-header">
                    <p>Структура контрактации по подразделениям, млн. руб.</p>
                    <p></p>
                </div>
                <apexchart width="100%" ref="chart4" type="bar" height="500px" :options="options4"
                           :series="series4"></apexchart>
            </div>
            <div class="col-6">
                <div class="v-chart-header">
                    <p>Контроль выполнения выручки опер. план, в млн. руб.</p>
                    <p></p>
                </div>
                <apexchart width="100%" type="bar" height="350px" :options="options5" :series="series5"></apexchart>
                <apexchart width="100%" type="bar" height="150px" :options="options6" :series="series6"></apexchart>
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
            options: {
                labels: [],
                legend: {
                    showForSingleSeries: true,
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                xaxis: {
                    categories: ['Контрактация', 'Бюджет', 'Опер. план', 'Ожидаемый факт',]
                },
                yaxis: {
                    show: false,
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
            series: [
                {
                    name: 'млн. руб.',
                    type: 'column',
                    data: [6666, 6000, 5458, 5458]
                }
            ],
            options2: {
                labels: [],
                legend: {
                    showForSingleSeries: true,
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                colors: ['#bdd7ee', '#1f4e79'],
                xaxis: {
                    categories: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
                },
                yaxis: {
                    show: false,
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
            series2: [
                {
                    name: 'Бюджет',
                    type: 'column',
                    data: []
                },
                {
                    name: 'Факт',
                    type: 'column',
                    data: []
                }
            ],
            options3: {
                labels: [],
                legend: {
                    showForSingleSeries: false,
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                colors: [
                    function ({value, seriesIndex, dataPointIndex, w}) {
                        //let i = w.config.series[0].data[dataPointIndex]['x']
                        if (value !== 100) {
                            return '#be0000'
                        } else {
                            return '#70ad47'
                        }
                    }
                ],
                chart: {
                    toolbar: {
                        show: false
                    },
                },
                grid: {
                    show: false,
                },
                xaxis: {
                    categories: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']

                },
                yaxis: {
                    show: false,
                    min: -100,
                    max: 100,
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            position: 'bottom',
                        }
                    },
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        colors: ['#333']
                    },
                    formatter(value) {
                        return `${value}%`;
                    },
                    offsetY: -30,
                },
            },
            series3: [
                {
                    name: 'Отклонение от планового значения',
                    type: 'column',
                    data: []
                },
            ],
            options4: {
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
            series4: [
                {
                    name: 'Котрактация',
                    type: 'column',
                    data: []
                },
                {
                    name: 'В т.ч. оплачено',
                    type: 'column',
                    data: []
                }
            ],
            options5: {
                labels: [],
                legend: {
                    showForSingleSeries: true,
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                colors: ['#bdd7ee', '#1f4e79'],
                xaxis: {
                    categories: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
                },
                yaxis: {
                    show: false,
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
            series5: [
                {
                    name: 'Бюджет',
                    type: 'column',
                    data: []
                },
                {
                    name: 'Факт',
                    type: 'column',
                    data: []
                }
            ],
            options6: {
                labels: [],
                legend: {
                    showForSingleSeries: false,
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                colors: [
                    function ({value, seriesIndex, dataPointIndex, w}) {
                        //let i = w.config.series[0].data[dataPointIndex]['x']
                        if (value <= 0) {
                            return '#be0000'
                        } else {
                            return '#70ad47'
                        }
                    }
                ],
                chart: {
                    toolbar: {
                        show: false
                    },
                },
                grid: {
                    show: false,
                },
                xaxis: {
                    categories: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
                },
                yaxis: {
                    show: false,
                    min: -100,
                    max: 100,
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            position: 'bottom',
                        }
                    },
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        colors: ['#333']
                    },
                    formatter(value) {
                        return `${value}%`;
                    },
                    offsetY: -30,
                },
            },
            series6: [
                {
                    name: 'Отклонение от планового значения',
                    type: 'column',
                    data: []
                },
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
            this.setFirstSeries()
            this.setSecondSeries()
            this.setThirdSeries()
            this.setFourthSeries()
            this.setFifthsSeries()
            this.setSixthSeries()
            this.loading = false
            window.dispatchEvent(new Event('resize'))
        },
        setFirstSeries() {
            this.series[0].data = []
            let sumFact = 0
            let sumPlan = 0
            this.contractAndFact.forEach(item => {
                for (let i = 1; i < 13; i++) {
                    //sumFact += item['fact_' + i]
                    sumPlan += item['plan_' + i]
                }
            })
            //this.series[0].data.push(sumPlan.toFixed(2)) // Контрактация
            this.series[0].data.push(Math.round(sumPlan)) // Контрактация
            let budget = 0
            this.budget.forEach(item => {
                budget += item['sum']
            })
            //this.series[0].data.push(budget.toFixed(2)) //Бюджет
            this.series[0].data.push(Math.round(budget)) //Бюджет
            let sumOperPlan = 0
            this.operPlan.forEach(item => {
                for (let i = 1; i < 13; i++) {
                    sumOperPlan += item['plan_' + i]
                }
            })
            //this.series[0].data.push(sumOperPlan.toFixed(2))
            this.series[0].data.push(Math.round(sumOperPlan))
            let sumExpectFact = 0
            // console.log('response', response)
            this.expectedRevenue.forEach(item => {
                sumExpectFact += item['sum']
            })
            //this.series[0].data.push(sumExpectFact.toFixed(2))
            this.series[0].data.push(Math.round(sumExpectFact))
        },
        setSecondSeries() {
            this.series2[0].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,]
            this.series2[1].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,]

            //Бюджет TODO год
            this.budget.forEach(item => {
                let date = new Date(item.date_budget);
                let month = date.getMonth();
                this.series2[0].data[month] = item['sum']
            })
            this.series2[0].data.forEach((item, index) => {
                this.series2[0].data[index] = Math.round(item)//item.toFixed(2)
            })

            // this.contractAndFact.forEach(item => {
            //     for (let i = 1; i < 13; i++) {
            //         //this.series2[0].data[i - 1] += item['plan_' + i]
            //         this.series2[1].data[i - 1] += item['fact_' + i]
            //     }
            // })

            this.series2[1].data.forEach((item, index) => {
                if (this.series2[1].data[index] !== 0) {
                    //this.series2[1].data[index] = item.toFixed(2)
                    this.series2[1].data[index] = Math.round(item)
                } else { // если нет данных по фактической выручке, то собираем ожидаемую
                    let expectedRevenue = this.expectedRevenue.filter(exp => exp.mount === this.months[index])
                    //console.log('expectedRevenue', expectedRevenue)
                    let sum = 0
                    expectedRevenue.forEach(exp => {
                        sum += exp['sum']
                    })
                    this.series2[1].data[index] = Math.round(sum)//sum.toFixed(2)
                }
            })
        },
        setThirdSeries() {
            this.series3[0].data = []
            this.series2[1].data.forEach((item, index) => {
                let percent = (this.series2[1].data[index] / this.series2[0].data[index] - 1) * 100
                percent = Math.floor(percent * 100) / 100
                if (percent >= 0) {
                    percent = 100
                }
                this.series3[0].data.push(percent)
            })
        },
        setFourthSeries() {
            let categories = []
            this.series4[0].data = [] //контрактация
            this.series4[1].data = [] //в т.ч. оплачено
            this.contractAndFact.forEach(item => {
                categories.push(item.company_name)
                let sumFact = 0
                for (let i = 1; i < 13; i++) {
                    sumFact += item['fact_' + i]
                }
                sumFact = Math.round(sumFact)//sumFact.toFixed(2)
                this.series4[0].data.push(sumFact)
                let sumContract = 0
                let expectedRevenue = this.expectedRevenue.filter(exp => exp.company_name === item.company_name)
                expectedRevenue.forEach(exp => {
                    sumContract += exp['sum']
                })
                sumContract = Math.round(sumContract)//sumContract.toFixed(2)
                this.series4[1].data.push(sumContract)
            })
            this.$refs.chart4.updateOptions({
                xaxis: {
                    categories: categories
                }
            })
        },
        setFifthsSeries() {
            this.series5[0].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,]
            this.series5[1].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,]
            this.operPlan.forEach(item => {
                for (let i = 1; i < 13; i++) {
                    this.series5[0].data[i - 1] += item['plan_' + i]
                }
            })

            // this.contractAndFact.forEach(item => {
            //     for (let i = 1; i < 13; i++) {
            //         //this.series5[0].data[i - 1] += item['plan_' + i]
            //         this.series5[1].data[i - 1] += item['fact_' + i]
            //     }
            // })

            this.series5[0].data.forEach((item, index) => {
                this.series5[0].data[index] = Math.round(item)//item.toFixed(2)
            })
            this.series5[1].data.forEach((item, index) => {
                if (this.series5[1].data[index] !== 0) {
                    //this.series5[1].data[index] = item.toFixed(2)
                    this.series5[1].data[index] = Math.round(item)
                } else { // если нет данных по фактической выручке, то собираем ожидаемую
                    let expectedRevenue = this.expectedRevenue.filter(exp => exp.mount === this.months[index])
                    //console.log('expectedRevenue', expectedRevenue)
                    let sum = 0
                    expectedRevenue.forEach(exp => {
                        sum += exp['sum']
                    })
                    this.series5[1].data[index] = Math.round(sum)//sum.toFixed(2)
                }

            })

        },
        setSixthSeries() {
            this.series6[0].data = []
            this.series5[1].data.forEach((item, index) => {
                let percent = (this.series5[1].data[index] / this.series5[0].data[index] - 1) * 100
                percent = Math.floor(percent * 100) / 100
                // if (percent >= 0) {
                //     percent = 100
                // }
                this.series6[0].data.push(percent)
            })
        },
    },
    computed: {},

}
</script>

<style scoped>

</style>
