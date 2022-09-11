<template>
    <div>
        <div class="row">
            <div class="col-6 mb-2">
                <select name="" id="" v-model="company_id" @change="filter()">
                    <option value="0">Все</option>
                    <option v-for="item in companies" :value="item.id">{{ item.name }}</option>
                </select>
            </div>
            <div class="v-date-filter" :class="{active: currentDateValue==='month'}" @click="setDate('month')">месяц
            </div>
            <div class="v-date-filter" :class="{active: currentDateValue==='3month'}" @click="setDate('3month')">3
                месяца
            </div>
            <div class="v-date-filter" :class="{active: currentDateValue==='6month'}" @click="setDate('6month')">6
                месяцев
            </div>
            <div class="v-date-filter" :class="{active: currentDateValue==='12month'}" @click="setDate('12month')">год
            </div>
        </div>
        <apexchart width="100%" type="bar" height="400px" :options="options" :series="series"></apexchart>
    </div>
</template>

<script>
import axios from "axios";


export default {
    name: "dashBoardSales",
    props: [
        'user_id'
    ],
    data() {
        return {
            company_id: 0,
            companies: [],
            currentDateValue: 'month',
            url: this.user_id == '1' ? "../project/getProjectAll" : `../project/getProjectByUser/${this.user_id}`,
            RDurl: this.user_id == '1' ? "../../project/getReportAll" : `../../project/getReportByUser/${this.user_id}`,
            ReportStored: [],
            reports:[],
            reportsFilter:[],
            minDate: null,
            maxDate: null,
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
        setDate(value) {
            this.currentDateValue = value
            let minDate = new Date();
            let maxDate = new Date();
            this.maxDate = maxDate
            //this.maxDate = maxDate.toISOString().split('T')[0];
            switch (value) {
                case 'month':
                    minDate.setDate(minDate.getDate() - 30);
                    this.minDate = minDate
                    //this.minDate = minDate.toISOString().split('T')[0];
                    break
                case '3month':
                    minDate.setDate(minDate.getDate() - 90);
                    this.minDate = minDate
                    //this.minDate = minDate.toISOString().split('T')[0];
                    break
                case '6month':
                    minDate.setDate(minDate.getDate() - 180);
                    this.minDate = minDate
                    //this.minDate = minDate.toISOString().split('T')[0];
                    break
                case '12month':
                    minDate.setDate(minDate.getDate() - 365);
                    this.minDate = minDate
                    //this.minDate = minDate.toISOString().split('T')[0];
                    break
            }
            this.filter()
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
                // let minDate = new Date(this.minDate)
                // let maxDate = new Date(this.maxDate)
                dateItem.setHours(1, 0, 0, 0)
                //maxDate.setHours(0, 0, 0, 0)
                if (dateItem < this.minDate || dateItem > this.maxDate) {
                    result = false
                }
                return result
            })
            // console.log('ReportStoredFilter', ReportStoredFilter)
            ReportStoredFilter.forEach(item => {
                if (item.type === 'project' && item.project_id ===id) {
                    values.quarter_1 += item.quarter_1
                    values.quarter_2 += item.quarter_2
                    values.quarter_3 += item.quarter_3
                    values.quarter_4 += item.quarter_4
                    values.actual_revenue += item.actual_revenue
                    values.current_contracting += item.current_contracting
                }
                if (item.type === 'contract' && item.contract_id === id) {
                    values.quarter_1 += item.quarter_1
                    values.quarter_2 += item.quarter_2
                    values.quarter_3 += item.quarter_3
                    values.quarter_4 += item.quarter_4
                    values.actual_revenue += item.actual_revenue
                    values.current_contracting += item.current_contracting
                }
            })
            let result = values.quarter_1 + values.quarter_2 + values.quarter_3 + values.quarter_4
            return result
        },

        filter() {
            let Report = [0, 0, 0, 0, 0]
            this.series[0].data = []
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
        this.setDate('month')
        //Эта часть для перерисовки графиков
        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            window.dispatchEvent(new Event('resize'));
        });
    }

}
</script>

<style scoped>
.v-date-filter {
    padding: 10px;
    border: 1px solid black;
    border-radius: 4px;
    margin-left: 10px;
    cursor:pointer;
}

.v-date-filter.active {
    background-color: #007bff4a;
}
</style>
