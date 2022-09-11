<template>
    <div>
        <div class="row">
            <div class="col-6 mb-2">
                <select name="" id="" v-model="company_id" @change="filter()">
                    <option value="0">Все</option>
                    <option v-for="item in companies" :value="item.id">{{ item.name }}</option>
                </select>
            </div>
            <label for="report_max_date" class="col-form-label">Дата по:</label>
            <div class="col-4 grid-margin"><input type="date" class="form-control datepicker" id="report_max_date"
                                                  v-model="maxDate" @change="filter()"></div>
        </div>
        <apexchart width="100%" type="bar" height="400px" :options="options" :series="series"></apexchart>
    </div>
</template>

<script>
import axios from "axios";


export default {
    name: "dashBoardCurrent",
    props: [
        'user_id'
    ],
    data() {
        return {
            company_id: 0,
            companies: [],
            url: this.user_id == '1' ? "../project/getProjectAll" : `../project/getProjectByUser/${this.user_id}`,
            maxDate: null,
            ReportStored: [],
            reports: [],
            reportsFilter: [],
            options: {
                chart: {
                    id: 'vuechart-report'
                },
                xaxis: {
                    categories: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан']
                }
            },
            series: [{
                name: 'м. руб',
                data: []
            }],

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
        findReportsValue(id) {
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
                let dateCompare = new Date(this.maxDate)
                dateItem.setHours(1, 0, 0, 0)
                dateCompare.setHours(0, 0, 0, 0)
                if (dateItem > dateCompare && this.maxDate) {
                    result = false
                }
                if (p.type === 'project' && p.project_id !== id) {
                    return false
                }
                if (p.type === 'contract' && p.contract_id !== id) {
                    return false
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

        filter() {
            let Report = [0, 0, 0, 0, 0]
            this.series[0].data = []
            let projectStoredFilter = this.ReportStored.filter(p => {
                let result = true
                if (parseInt(p.company_id) !== parseInt(this.company_id)) {
                    result = false
                }
                if (parseInt(this.company_id) === 0) {
                    result = true
                }
                return result
            })
            projectStoredFilter.forEach(proj => {
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
                    // console.log('report', this.findReportsValue(proj.id))
                    Report[key] += this.findReportsValue(proj.id)
                }
            })

            Report.forEach(el => {
                this.series[0].data.push(el.toFixed(4))
            })
        }
    },

    async mounted() {
        await this.getReportByUser()
        await this.loadCompanyByUser()
        await this.load()
        this.filter()

    }

}
</script>

<style scoped>

</style>
