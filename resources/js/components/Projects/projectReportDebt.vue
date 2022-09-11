<template>
    <div>
        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <div v-else class="v-report">
            <p>ДЗО у которых не сдан отчет</p>
            <div class="row">
                <div class="col-6 mb-2">
                    <select name="" id="" v-model="company_id" @change="filter()">
                        <option value="0">Все</option>
                        <option v-for="item in companies" :value="item.id">{{ item.name }}</option>
                    </select>
                </div>
                <div class="col-4 mb-2">
                    <label>Дата отчета не позднее: {{ daysCount }} {{ days() }}</label>
                    <input type="range" id="volume" name="volume" v-model="daysCount"
                           min="1" max="14" @change="rangeChange" class="form-control mb-3"/>
                </div>
            </div>


            <table class="table table-bordered" id="datatable">
                <thead>
                <tr>
                    <th>ДЗО</th>
                    <th>Дата отчета</th>
                    <th>Телефон</th>
                    <th>Описание</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="projects.length == 0">
                    <td colspan="5">Нет данных</td>
                </tr>
                <tr v-if="isLoading">
                    <td colspan="5">Загрузка...</td>
                </tr>
                <tr v-for="item in companiesFilter" :key="item.id" v-if="!isLoading">
                    <td>{{ item.name }}</td>
                    <td>{{ findReports(item.id).date }}</td>
                    <td>{{ item.phone }}</td>
                    <td>{{ item.description }}</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import inputSelect from "../other/selects/inputSelect";
import {createToaster} from "@meforma/vue-toaster";

export default {
    name: 'projectReportDebt',

    components: {
        inputSelect,
        createToaster
    },
    props: {
        user_id: {
            type: Number
        },
    },
    computed: {},
    methods: {
        rangeChange() {
            console.log('change')
            let now = new Date();
            let today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
            let value = today.valueOf() - 86400000 * this.daysCount
            this.dateToCompare = new Date(value);
            this.loadCompanyByUser();
        },

        // Получение спика котрагентов
        loadCounterparties() {
            axios
                .get("../../manuals/counterparties/list")
                .then((response) => {
                    this.counterparties = response.data;
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
        // TODO переделать на вычисляемое св-во
        searchCounterpartiesName(id) {
            return this.counterparties.find(el => {
                if (el.id == id) {
                    return el
                }
            });

        },

        findReports(id) {
            let projectsOfCompany = this.projects.filter(el => el.company_id === id)
            let projectsOfCompanyIds = []
            projectsOfCompany.forEach(item => {
                projectsOfCompanyIds.push(item.id)
            })
            let values = {
                quarter_1: 0,
                quarter_2: 0,
                quarter_3: 0,
                quarter_4: 0,
                actual_revenue: 0,
                date: null,
            }
            this.reportsFilter.forEach(item => {
                if (item.type === 'project' && projectsOfCompanyIds.includes(item.project_id)) {
                    values.quarter_1 += item.quarter_1
                    values.quarter_2 += item.quarter_2
                    values.quarter_3 += item.quarter_3
                    values.quarter_4 += item.quarter_4
                    values.actual_revenue += item.actual_revenue
                    if (!values.date) {
                        values.date = item.report_date
                    }
                    if (values.date && item.report_date > values.date) {
                        values.date = item.report_date
                    }
                }
                if (item.type === 'contract' && projectsOfCompanyIds.includes(item.contract_id)) {
                    values.quarter_1 += item.quarter_1
                    values.quarter_2 += item.quarter_2
                    values.quarter_3 += item.quarter_3
                    values.quarter_4 += item.quarter_4
                    values.actual_revenue += item.actual_revenue
                    if (!values.date) {
                        values.date = item.report_date
                    }
                    if (values.date && item.report_date > values.date) {
                        values.date = item.report_date
                    }
                }

            })
            if (!values.date) {
                values.date = 'Нет отчета'
            }
            return values
        },

        loadCompanyByUser() {
            this.isLoading = true
            this.companies = []
            this.companiesFilter = []
            axios
                .get(`../../companies/getByUser/${this.user_id}`)
                .then((response) => {
                    response.data.forEach(item => {
                        if (new Date(this.findReports(item.id).date) < new Date(this.dateToCompare) || this.findReports(item.id).date === 'Нет отчета') {
                            this.companies.push(item)
                            this.companiesFilter.push(item)
                        }
                    })
                    this.isLoading = false
                })
        },

        btnStyles(stage_id) {
            let width = (20 * stage_id) + 1 + '%';
            return {
                width: width
            }
        },
        load() {
            this.loading = true;

            let url;
            let urlContracts;
            if (this.user_id === 1) {
                url = "../project/getProjectAll";
                urlContracts = "../contract/getContractAll";
            } else {
                url = `../project/getProjectByUser/${this.user_id}`;
                urlContracts = `../contract/getContractByUser/${this.user_id}`;
            }
            axios
                .get(url)
                .then((response) => {
                    this.info = response;
                    response.data.forEach(project => {
                        project.type = 'project'
                        project.typeName = 'Проект'
                    })
                    this.projects = response.data;
                    this.projectsFilter = response.data;
                })
            axios
                .get(urlContracts)
                .then((response) => {
                    //this.info = response;
                    //console.log('Контракты', response.data)
                    response.data.forEach(contract => {
                        contract.type = 'contract'
                        contract.typeName = 'Контракт'
                        this.projects.push(contract)
                    })
                    this.loading = false
                })
        },

        filter() {
            if (this.company_id === '0') {
                this.companiesFilter = this.companies
            } else {
                this.companiesFilter = this.companies.filter(company => company.id === this.company_id)
            }

            if (this.max_report_date === '') {
                this.reportsFilter = this.reports
            } else {
                this.reportsFilter = this.reports.filter(report => report.report_date <= this.max_report_date)
            }
            //Что то будем делать
        },
        days() {
            let result = null
            let words = ['день', 'дня', 'дней']
            if ((this.daysCount % 100 > 9 && this.daysCount % 100 < 20) || (this.daysCount % 10 > 4 && this.daysCount % 10 < 10) || (this.daysCount % 10 == 0)) {
                result = words[2]
            } else if (this.daysCount % 10 === 1) {
                result = words[0]
            } else {
                result = words[1]
            }
            return result
        }
    },
    mounted() {
        let now = new Date();
        let today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
        let value = today.valueOf() - 86400000 * this.daysCount
        this.dateToCompare = new Date(value);
        this.getReportByUser();
        this.loadCounterparties();
        this.load();
        this.loadCompanyByUser();
    },
    data: function () {
        return {
            projects: [],
            projectsFilter: [],
            info: '',
            loading: false,
            companies: [],
            companiesFilter: [],
            counterparties: [],
            reports: [],
            reportsFilter: [],
            company_id: '0',
            max_report_date: '',
            dateToCompare: null,
            daysCount: 7,
            isLoading: false,
        }
    },
}
</script>
<style>
.v-report {
    width: 100%;
    overflow: scroll;
    max-height: 75vh;
}

th {
    position: sticky;
    top: -1px;
    background: #f9fafb;
}
</style>
<style>
.v-report {
    width: 100%;
    overflow: scroll;
    max-height: 75vh;
}

th {
    position: sticky;
    top: -1px;
    background: #f9fafb;
}
</style>
