<template>
    <div>
        <div class="row">
            <div class="col-6 mb-2">
                <select name="" id="" v-model="company_id" @change="filterAll">
                    <option value="0">Все</option>
                    <option v-for="item in companies" :value="item.id">{{ item.name }}</option>
                </select>
            </div>
        </div>
        <apexchart width="100%" type="bar" height="400px" :options="options" :series="series"></apexchart>
    </div>
</template>

<script>
import axios from "axios";


export default {
    name: "dashBoardMovement",
    props: [
        'user_id'
    ],
    data() {
        return {
            company_id: 0,
            companies: [],
            url: this.user_id == '1' ? "../project/getProjectAll" : `../project/getProjectByUser/${this.user_id}`,
            RDurl: this.user_id == '1' ? "../../project/getReportAll" : `../../project/getReportByUser/${this.user_id}`,
            ReportStored: [],
            reports:[],
            reportsFilter:[],
            options: {
                chart: {
                    id: 'vuechart-report'
                },
                xaxis: {
                    categories: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан']
                }
            },
            series: [{
                name: 'Текущая неделя м. руб',
                data: []
            },
                {
                    name: 'Прошлая неделя м. руб',
                    data: []
                }
            ],

        }
    },

    methods: {
        async load() {
            await axios.get(this.url)
                .then(response => {
                    for (let key in response.data) {
                        this.ReportStored.push(response.data[key]);
                    }
                })
        },
        async loadCompanyByUser() {
            axios
                .get(`../../companies/getByUser/${this.user_id}`)
                .then((response) => {
                    this.companies = response.data;
                    this.companiesFilter = response.data;
                })
        },
        getReportByUser() {

            let url;
            let urlContracts;
            //console.log('user_id', this.user_id);
            if (this.user_id == 1) {
                url = "../../project/getReportAll";
                urlContracts = "../../contract/getReportAll";
            } else {
                url = `../../project/getReportByUser/${this.user_id}`;
                urlContracts = `../../contract/getReportByUser/${this.user_id}`;
            }

            axios
                .get(url)
                .then((response) => {
                    response.data.forEach(project => {
                        project.type = 'project'
                        this.reports.push(project)
                    })
                })
            axios
                .get(urlContracts)
                .then((response) => {
                    response.data.forEach(contract => {
                        contract.type = 'contract'
                        this.reports.push(contract)
                    })
                    this.reportsFilter = this.reports
                })
        },
        findReportsValue(id, minDate, maxDate) {
            let values = {
                quarter_1: 0,
                quarter_2: 0,
                quarter_3: 0,
                quarter_4: 0,
                actual_revenue: 0,
                current_contracting: 0,
            }
            let ReportStoredFilter = this.reportsFilter.filter(p => {
                let result = true
                let dateItem = new Date(p.report_date)
                dateItem.setHours(1, 0, 0, 0)
                if (dateItem < minDate || dateItem > maxDate) {
                    result = false
                }
                if (p.type === 'project' && p.project_id !== id) {
                    result = false
                }
                if (p.type === 'contract' && p.contract_id !== id) {
                    result = false
                }
                return result
            })
            //Берем последний отчет на дату
            let maxReportDate = new Date(Math.max.apply(null, ReportStoredFilter.map(function (e) {
                return new Date(e.report_date)
            })));
            //console.log('reports', reports, maxDate)
            let report = ReportStoredFilter.find(report => {
                let reportDate = new Date(report.report_date)
                if (reportDate.getFullYear() === maxReportDate.getFullYear() &&
                    reportDate.getMonth() === maxReportDate.getMonth() &&
                    reportDate.getDate() === maxReportDate.getDate()) {
                    return true
                } else {
                    return false
                }
            })

            if (report) {
                values.quarter_1 += report.quarter_1
                values.quarter_2 += report.quarter_2
                values.quarter_3 += report.quarter_3
                values.quarter_4 += report.quarter_4
                values.actual_revenue += report.actual_revenue
                values.current_contracting += report.current_contracting
            }

            let result = values.quarter_1 + values.quarter_2 + values.quarter_3 + values.quarter_4
            return result
        },

        filterAll(){
            this.filter('current')
            this.filter('last')
        },

        filter(week) {
            let Report = [0, 0, 0, 0, 0]
            let minDate = new Date()
            let maxDate = new Date()
            if (week === 'current') {
                minDate.setDate(minDate.getDate() - 7);
                //minDate = minDate.toISOString().split('T')[0];
                this.series[0].data = []
            }
            if (week === 'last') {
                minDate.setDate(minDate.getDate() - 14);
                //minDate = minDate.toISOString().split('T')[0];
                maxDate.setDate(maxDate.getDate() - 7);
                //maxDate = maxDate.toISOString().split('T')[0];
                this.series[1].data = []
            }

            let ReportStoredFilter = this.ReportStored.filter(p => {
                let result = true
                if (parseInt(p.company_id) !== parseInt(this.company_id)) {
                    result = false
                }
                if (parseInt(this.company_id) === 0) {
                    result = true
                }
                return result
            })
            ReportStoredFilter.forEach(proj => {
                let key = 0
                switch (proj.stage.name) {
                    case 'Подготовка предложения':
                        key = 0
                        break
                    case 'Закупка объявлена':
                        key = 1
                        break
                    case 'Предложение подано':
                        key = 2
                        break
                    case 'Конкурс выигран':
                        key = 3
                        break
                    case 'Договор подписан':
                        key = 4
                        break
                    case 'НИОКР: подготовка документов':
                        key = 0
                        break
                    case 'НИОКР: участие в конкурсе':
                        key = 1
                        break
                    case 'НИОКР: согласование':
                        key = 2
                        break
                    case 'НИОКР: договор заключен':
                        key = 4
                        break
                    case 'Б/К: КП':
                        key = 2
                        break
                    case 'Б/К: договор':
                        key = 4
                        break
                    case 'Проект завершен':
                        key = 'none'
                        break
                }
                if (key !== 'none') {
                    Report[key] += this.findReportsValue(proj.id, minDate, maxDate)
                }
            })

            Report.forEach(el => {
                if (week === 'current') {
                    this.series[0].data.push(el.toFixed(4))
                }
                if (week === 'last') {
                    this.series[1].data.push(el.toFixed(4))
                }
            })
        }
    },

    async mounted() {
        await this.getReportByUser()
        await this.loadCompanyByUser()
        await this.load()
        this.filterAll()
    }

}
</script>

<style scoped>

</style>
