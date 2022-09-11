<template>
    <div class="col-6">
        <!--        <div class="form-group">-->
        <!--            <label>Выберите ДЗО</label>-->
        <!--            <select class="form-control" v-model="company_id">-->
        <!--                <option value="0">Выберите ДЗО</option>-->
        <!--                <option v-for="item in filteredCompany" :value="item.id">{{ item.name }}</option>-->
        <!--            </select>-->
        <!--        </div>-->
    </div>

    <div class="col-6">
        <div class="form-group" style="display: none">
            <label>Выберите цех</label>
            <select class="form-control">
                <option value="0">Выберите цех</option>
            </select>
        </div>
    </div>

    <div class="col-6" style="display: none">
        <div class="form-group">
            <label>Выберите участок</label>
            <select class="form-control">
                <option value="0">Выберите участок</option>
            </select>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <label>Выберите месяц отчета</label>
            <input type="month" v-model="report_month" class="form-control" :placeholder="report_month"
                   @change="loadReports">
        </div>
    </div>
    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div class="col-12" v-show="!loading">
        <div class="row">
            <div class="col-6">
                <div class="v-chart-header">
                    <p>Выполнение плана запуска</p>
                    <p> {{ getProductionPlan }}%</p>
                </div>
                <apexchart width="100%" type="line" height="400px" :options="options" :series="series"></apexchart>
            </div>

            <div class="col-6">
                <div class="v-chart-header">
                    <p>Выполнение плана передачи в ОТК</p>
                    <p>{{ getOTKPlan }}%</p>
                </div>
                <apexchart width="100%" type="line" height="400px" :options="options2" :series="series2"></apexchart>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="v-chart-header">
                    <p>Передача продукции на склад</p>
                    <p>{{ getStockFact }} ШТ.</p>
                </div>
                <apexchart width="100%" type="line" height="400px" :options="options3" :series="series3"></apexchart>
            </div>

            <div class="col-6">
                <div class="v-chart-header">
                    <p>Процент выхода годной продукции</p>
                    <p>{{ getFactPercent }} % </p>
                </div>
                <apexchart width="100%" type="line" height="400px" :options="options4" :series="series4"></apexchart>
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
    name: "report_view",
    props: [
        'user_id'
    ],

    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,

            report_month: '',
            report_day_begin: null,//'2022-05-01',
            report_day_end: null,//'2022-05-31',
            company_id: 17,
            plan_of_transfer_to_otk: 0,
            filteredCompany: [],
            company: [],
            report: [],
            options: {
                labels: [],
                legend:{
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                },
                colors: ['#5597d3', '#45bb15', '#ed7d31', '#e8cc48'],
                chart: {
                    type: "line",
                    stacked: true,
                },
                markers: {
                    strokeWidth: 2,
                    size: [4, 4]
                },
                tooltip: {
                    y: {
                        formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                            if(seriesIndex === 1){
                                return val + series[0][dataPointIndex]
                            } else {
                                return val
                            }
                        }
                    }
                },
            },
            series: [
                {
                    name: 'План',
                    type: 'line',
                    data: []
                },
                {
                    name: 'План запуска По ССЗ',
                    type: 'line',
                    data: []
                },
                {
                    name: 'Факт',
                    type: 'column',
                    data: []
                },
                {
                    name: 'Факт опробования',
                    type: 'column',
                    data: []
                },
            ],
            options2: {
                labels: ['2022-05-01', '2022-05-02', '2022-05-03', '2022-05-04', '2022-05-05'],
                colors: ['#5597d3', '#ed7d31'],
                yaxis: {
                    min: 0,
                }
            },
            series2: [
                {
                    name: 'План передачи в ОТК',
                    type: 'line',
                    data: []
                },
                {
                    name: 'Факт передачи в ОТК',
                    type: 'column',
                    data: []
                },

            ],
            options3: {
                labels: [],
                legend:{
                    showForSingleSeries: true,
                },
                colors: ['#ed7d31'],
                markers: {
                    strokeWidth: 2,
                    size: [4, 4]
                }
            },
            series3: [{
                name: 'Передано на склад',
                type: 'column',
                data: []
            }
            ],
            options4: {
                labels: [],
                legend:{
                    showForSingleSeries: true,
                },
                colors: ['#5597d3'],
                yaxis: {
                    min: 0,
                }
            },
            series4: [{
                name: 'Процент выхода годной продукции',
                type: 'line',
                data: []
            }
            ],

        }
    },
    async mounted() {
        await this.setDefaultDate()
        await this.loadCompany()
        await this.loadReports()
    },
    methods: {

        loadCompany() {
            this.filteredCompany = [];
            this.company = [];
            let data = {
                company_id: 1
            }
            axios
                .get(`../../companies/getByUser/${this.user_id}`)
                .then((response) => {
                    this.company = response.data;
                    this.filteredCompany = response.data;
                })
        },
        setDefaultDate() {
            if (!this.report_month) {
                let d = new Date(),
                    month = '' + (d.getMonth() + 1),
                    year = d.getFullYear();

                if (month.length < 2)
                    month = '0' + month;
                this.report_month = year + '-' + month
            }
        },

        loadReports() {
            this.loading = true
            this.report = [];
            this.series[0].data = []
            this.series[1].data = []
            this.series[2].data = []
            this.series[3].data = []
            this.options.labels = []

            this.series2[0].data = []
            this.series2[1].data = []
            this.options2.labels = []

            this.series3[0].data = []
            this.options3.labels = []

            this.series4[0].data = []
            this.options4.labels = []
            //Берем первый и последний день месяца
            let currentDate = new Date(this.report_month + '-01')
            console.log('currentDate', currentDate)
            let y = currentDate.getFullYear(), m = currentDate.getMonth()
            this.report_day_begin = new Date(y, m, 2).toISOString().split('T')[0]
            this.report_day_end = new Date(y, m + 1, 1).toISOString().split('T')[0]
            axios
                .get(`../../production-report/get-by-report-between/${this.report_day_begin}/${this.report_day_end}/${this.company_id}`)
                .then((response) => {
                    this.report = response.data;
                    let fact_of_transfer_to_otk = 0
                    let labels = []
                    let lastReport = this.report[this.report.length - 1]
                    let plan_of_transfer_to_otk = lastReport.plan_of_transfer_to_otk
                    this.plan_of_transfer_to_otk = plan_of_transfer_to_otk
                    let fact_of_transfer_to_warehouse = 0
                    this.report.forEach(item => {
                        // productsPlan += parseInt(item.launch_plan)
                        // productsFact += item.launch_fact
                        this.series[0].data.push(item.launch_plan)
                        this.series[1].data.push(item.launch_plan_ssz - item.launch_plan) //из за стака
                        this.series[2].data.push(item.launch_fact)
                        this.series[3].data.push(item.sampling)
                        labels.push(item.report_day)

                        fact_of_transfer_to_otk += parseInt(item.fact_of_transfer_to_otk)

                        this.series2[0].data.push(item.plan_of_transfer_to_otk)
                        this.series2[1].data.push(fact_of_transfer_to_otk)


                        fact_of_transfer_to_warehouse += parseInt(item.fact_of_transfer_to_warehouse)
                        this.series3[0].data.push(fact_of_transfer_to_warehouse)
                        //
                        let series4Option = item.fact_of_transfer_to_otk * 100 / item.launch_previously
                        if(series4Option > 100){
                            series4Option = 100
                        }
                        series4Option = series4Option.toFixed(2)
                        this.series4[0].data.push(series4Option)
                    })
                    this.options = {
                        labels: labels
                    }
                    this.options2 = {
                        labels: labels
                    }
                    this.options3 = {
                        labels: labels
                    }
                    this.options4 = {
                        labels: labels
                    }
                    console.log('options', this.options)
                    this.loading = false;
                    window.dispatchEvent(new Event('resize'))
                })
        },
        countWorkDay(sDay, eDay) {
            console.log(sDay, eDay)
            let s = new Date(sDay), e = new Date(eDay);
            let s_t_w = s.getDay(), e_t_w = e.getDay() + 1;

            // Разница в днях
            let diffDay = (e - s) / (1000 * 60 * 60 * 24) + 1;
            let diffWeekDay = diffDay - (s_t_w == 0 ? 0 : 7 - s_t_w) + e_t_w;
            // Расчет состоит из нескольких полных недель
            let weeks = Math.floor(diffWeekDay / 7);
            if (weeks <= 0) {// На одной неделе, то есть время начала и окончания не может быть одновременно воскресеньем и субботой (воскресенье - первый день недели)
                return e_t_w - s_t_w + (s_t_w ? 1 : 0) + (e_t_w == 6 ? -1 : 0);
            } else {
                return weeks * 5 + (e_t_w == 6 ? 5 : e_t_w) + (s_t_w >= 1 && s_t_w <= 5 ? (6 - s_t_w) : 0);
            }
        }
    },
    computed: {
        getProductionPlan() {
            let result = 0
            if (this.report.length !== 0) {
                let launch_plan = 0
                let launch_fact = 0
                this.report.forEach(item => {
                    launch_plan += parseInt(item.launch_plan)
                    launch_fact += parseInt(item.launch_fact)
                })
                result = launch_fact * 100 / launch_plan
                result = result.toFixed(2)
            }
            return result
        },
        getOTKPlan() {
            let result = 0

            if (this.report.length !== 0) {
                let plan_of_transfer_to_otk = this.plan_of_transfer_to_otk
                let fact_of_transfer_to_otk = 0
                this.report.forEach(item => {
                    fact_of_transfer_to_otk += parseInt(item.fact_of_transfer_to_otk)
                })
                result = fact_of_transfer_to_otk * 100 / plan_of_transfer_to_otk
                result = result.toFixed(2)
            }
            return result
        },
        getStockFact() {
            let result = 0
            if (this.report.length !== 0) {
                let fact_of_transfer_to_warehouse = 0
                this.report.forEach(item => {
                    fact_of_transfer_to_warehouse += parseInt(item.fact_of_transfer_to_warehouse)
                })
                result = fact_of_transfer_to_warehouse
            }
            return result
        },
        getFactPercent() {
            let result = 0
            if (this.report.length !== 0) {
                let fact_of_transfer_to_otk = 0
                let launch_previously = 0
                this.report.forEach(item => {
                    fact_of_transfer_to_otk += parseInt(item.fact_of_transfer_to_otk)
                    launch_previously += parseInt(item.launch_previously)
                })
                result = fact_of_transfer_to_otk * 100/ launch_previously
                result = result.toFixed(2)
            }
            return result
        }
    },

}
</script>

<style scoped>
.v-chart-row {
    display: flex;
    justify-content: space-between;
}

.v-chart-header {
    display: flex;
    justify-content: space-between;
}
</style>
