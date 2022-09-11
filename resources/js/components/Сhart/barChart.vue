<template>
    <div id="app2">
        <h3 class="page-title" style="margin-bottom: 20px;">Отчет по проектам </h3>
        <div class="tab-content border border-top-0 p-3" id="myTabContent">
            <div>
                <div class="col-12 grid-margin stretch-card">
                    <select id="company_filter" disabled>
                        <option value="0">Все</option>
                        <option v-for="company in this.companies_parsed" :value="company['id']">{{ company['name'] }}</option>
                    </select>
                </div>
            </div>
            <div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Воронка продаж</h6>
                            <div id="apexBar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<!--<script type="text/javascript" src="../assets/plugins/apexcharts/apexcharts.min.js"></script>-->
<script>

import axios from 'axios';
// require 'assets/plugins/apexcharts/apexcharts.min.js';

export default {
    components: {},
    name: "barChart",
    props: [
        'company_id',
        'user_id',
        'companies'
    ],
    data() {
        return {
            url : this.user_id === '1' ? "../project/getProjectAll" : `../project/getProjectByUser/${this.user_id}`,
            ReportStored: [],
            options : {},
            apexBarChart: {}
        }
    },
    mounted() {
        this.load()
        const plugin = document.createElement("script")
        plugin.setAttribute(
            "src",
            "/assets/plugins/apexcharts/apexcharts.min.js"
        )
        plugin.async = true;
        document.head.appendChild(plugin)
        this.filter()
    },
    methods: {
        load() {
            axios.post(this.url)
                .then(response => {
                    this.ReportStored = response.data
                })
        },
        filter() {
            let Report = [0,0,0,0,0]
            this.ReportStored.filter(p => {
                return this.company_id === '0' || parseInt(p.company_id) === parseInt(this.company_id)
            }).forEach(proj => {
                Report[proj.stage_id - 1] += parseInt(proj.price)
            })
            this.options = {
                chart: {
                    type: 'bar',
                    height: '400',
                    parentHeightOffset: 1
                },
                grid: {
                    borderColor: "rgba(77, 138, 240, .1)",
                    padding: {
                        bottom: -15
                    }
                },
                series: [{
                    name: 'м. руб',
                    data: Report
                }],
                xaxis: {
                    type: 'string',
                    categories: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан']
                }
            }
            if (Object.keys(this.apexBarChart).length !== 0)
                this.apexBarChart.destroy()
            this.apexBarChart = new ApexCharts(document.querySelector("#apexBar"), this.options);
            this.apexBarChart.render()
        }
    },
    computed: {
        companies_parsed() {
            return JSON.parse(this.companies)
        }
    }

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
