<template>
    <div>
        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <div v-else style="width: 100%; overflow-x: visible;">
        <div class="row">
            <div class="col-6 mb-2">
                <select name="" id="" v-model="company_id" @change="filter()">
                    <option value="0">Все</option>
                    <option v-for="item in companies" :value="item.id">{{ item.name}}</option>
                </select>
            </div>
            <label for="report_max_date" class="col-form-label">Дата по:</label>
            <div class="col-4 mb-2">
                <input type="date" class="form-control datepicker" id="report_max_date" v-model="max_report_date" @change="filter()">
            </div>
        </div>


        <table class="table table-bordered" id="datatable" >
            <thead>
            <tr>
                <th>ДЗО</th>
                <th>Тип</th>
                <th>Контрагенты</th>
                <th>Наименование проекта</th>
                <th>1 квартал</th>
                <th>2 квартал</th>
                <th>3 квартал</th>
                <th>4 квартал</th>
                <th>Итого</th>
                <th>Дата</th>
                <th>Этап</th>
                <th>Прогресс</th>
                <th>Текущие состояние</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="projects.length == 0">
                <td colspan="5">Нет данных</td>
            </tr>
            <tr v-for="(item, index) in projectsFilter" :key="item.id">
                <td>{{ searchCompanyName(item.company_id).name }}</td>
                <td>{{item.typeName}}</td>
                <td>{{ searchCounterpartiesName(item.counterparty_id).name }}</td>
                <td style="word-wrap: break-word; max-width: 250px; white-space: break-spaces !important;">{{ item.name }}</td>
                <td>{{ findProject(item.id, item.type).quarter_1 }}</td>
                <td>{{ findProject(item.id, item.type).quarter_2 }}</td>
                <td>{{ findProject(item.id, item.type).quarter_3 }}</td>
                <td>{{ findProject(item.id, item.type).quarter_4 }}</td>
                <td>{{ findProject(item.id, item.type).quarter_1 + findProject(item.id, item.type).quarter_2 + findProject(item.id, item.type).quarter_3 + findProject(item.id, item.type).quarter_4 }}</td>
                <td>{{ findProject(item.id, item.type).report_date }}</td>
                <td style="word-wrap: break-word; max-width: 250px; white-space: break-spaces !important;">{{ item.stage.name }}</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" :style="btnStyles(item.stage.id)" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </td>
                <td style="word-wrap: break-word; max-width: 250px; white-space: break-spaces !important;">{{ item.state_comment }}</td>
            </tr>
            <tr class="v-total-top">
                <td>Итоги</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{total().quarter_1}}</td>
                <td>{{total().quarter_2}}</td>
                <td>{{total().quarter_3}}</td>
                <td>{{total().quarter_4}}</td>
                <td>{{total().all}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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
    name: 'productReport',

    components: {
        inputSelect,
        createToaster
    },
    props: {
        user_id: {
            type: Number
        },
        typeReport:{
            type:String,
            default:null,
        },
        productName:{
            type:String,
            default:null
        }
    },
    computed: {

    },
    methods: {
        // Получение спика котрагентов

        loadCounterparties() {
            axios
                .get("/manuals/counterparties/list")
                .then((response) => {
                    this.counterparties = response.data;
                })
        },

        getReportByUser() {

            let url;
            let urlContracts;
            //console.log('user_id', this.user_id);
            if (this.user_id == 1) {
                url = "/project/getReportAll";
                urlContracts = "/contract/getReportAll";
            } else {
                url = `/project/getReportByUser/${this.user_id}`;
                urlContracts = `/contract/getReportByUser/${this.user_id}`;
            }
            if(!this.typeReport || this.typeReport === 'Projects') {
                axios
                    .get(url)
                    .then((response) => {
                        this.reports = response.data;
                    })
            }
            if(!this.typeReport || this.typeReport === 'Contracts') {
                axios
                    .get(urlContracts)
                    .then((response) => {
                        this.reportsContracts = response.data
                    })
            }
        },
        // TODO переделать на вычисляемое св-во
        searchCompanyName(id){
            return this.companies.find(el => {
                if (el.id == id) {
                    return el
                }
            });
        },
        // TODO переделать на вычисляемое св-во
        searchCounterpartiesName(id){
            return this.counterparties.find(el => {
                if (el.id == id) {
                    return el
                }
            });

        },

        findProject(id, type){
            let reportData=[]
            let reports=[]
            if(type==='project'){
                reportData = this.reports
                reports = reportData.filter(report=>{
                    if(report.project_id == id && (this.max_report_date >= report.report_date || this.max_report_date ==='')){
                        return true
                    } else {
                        return false
                    }
                })
            }
            if(type==='contract'){
                reportData = this.reportsContracts
                reports = reportData.filter(report=>{
                    if(report.contract_id == id && (this.max_report_date >= report.report_date || this.max_report_date ==='')){
                        return true
                    } else {
                        return false
                    }
                })
            }

            let maxDate = new Date(Math.max.apply(null, reports.map(function(e) {
                return new Date(e.report_date)
            })));
            //console.log('reports', reports, maxDate)
            let report = reports.find(report=>{
                let reportDate = new Date(report.report_date)
                if(reportDate.getFullYear() === maxDate.getFullYear() &&
                    reportDate.getMonth() === maxDate.getMonth() &&
                    reportDate.getDate()=== maxDate.getDate()){
                    return true
                } else{
                    return false
                }
            })

            if (typeof report == "undefined") {
                return {
                    quarter_1: 0,
                    quarter_2: 0,
                    quarter_3: 0,
                    quarter_4: 0,
                }

            } else {
                return report
            }

        },
        total(){
            let result ={
                quarter_1: 0,
                quarter_2: 0,
                quarter_3: 0,
                quarter_4: 0,
                all: 0,
            }
            this.projectsFilter.forEach(project=>{
                let report = this.findProject(project.id, project.type)
                result.quarter_1 += report.quarter_1
                result.quarter_2 += report.quarter_2
                result.quarter_3 += report.quarter_3
                result.quarter_4 += report.quarter_4
                result.all += report.quarter_1 + report.quarter_2 + report.quarter_3 + report.quarter_4
            })
            return result
        },

        loadCompanyByUser() {
            axios
                .get(`/companies/getByUser/${this.user_id}`)
                .then((response) => {
                    this.companies = response.data;
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
            if(this.user_id === 1) {
                url = "/project/getProjectAll";
                urlContracts = "/contract/getContractAll";
            } else {
                url = `/project/getProjectByUser/${this.user_id}`;
                urlContracts = `/contract/getContractByUser/${this.user_id}`;
            }
            if(!this.typeReport || this.typeReport === 'Projects') {
                axios
                    .get(url)
                    .then((response) => {
                        this.info = response;
                        response.data.forEach(project => {
                            project.type = 'project'
                            project.typeName = 'Проект'
                            if(project.product_name=== this.productName.toString()){
                                this.projects.push(project)
                                this.projectsFilter.push(project)
                            }
                        })
                        if(this.typeReport === 'Projects'){
                            this.loading = false
                        }
                    })
            }
            if(!this.typeReport || this.typeReport === 'Contracts'){
                axios
                    .get(urlContracts)
                    .then((response) => {
                        //this.info = response;
                        //console.log('Контракты', response.data)
                        response.data.forEach(contract=>{
                            contract.type = 'contract'
                            contract.typeName = 'Контракт'
                            if(contract.product_name===this.productName.toString()){
                                this.projects.push(contract)
                                this.projectsFilter.push(contract)
                            }
                        })
                        this.loading = false
                    })
            }
        },

        filter() {
            this.projectsFilter = this.projects.filter(el => {
                return (this.max_report_date === '' || this.max_report_date >= this.findProject(el.id, el.type).report_date ) &&
                    (parseInt(this.company_id) === 0 || this.company_id === el.company_id)
            })

            this.$parent.$refs.bar.filter(this.company_id, this.max_report_date)
            this.$parent.$refs.donut.filter(this.company_id, this.max_report_date)

        },
    },
    mounted() {
        this.getReportByUser();
        this.loadCompanyByUser();
        this.loadCounterparties();
        this.load();
    },
    data: function () {
        return {
            projects: [],
            projectsFilter: [],
            info: '',
            loading: false,
            companies: [],
            counterparties: [],
            reports: [],
            reportsContracts:[],
            company_id: 0,
            max_report_date: ''
        }
    },
}
</script>
<style>
.v-total-top{
    border-top: 2px solid black;
}
</style>
