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
                <th>Контагент</th>
                <th>Наименование контракта</th>
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
            <tr v-if="contracts.length == 0">
                <td colspan="5">Нет данных</td>
            </tr>
            <tr v-for="(item, index) in contractsFilter" :key="item.id">
                <td>{{ searchCompanyName(item.company_id).name }}</td>
                <td>{{ searchCounterpartiesName(item.counterparty_id).name }}</td>
                <td style="word-wrap: break-word; max-width: 250px; white-space: break-spaces !important;">{{ item.name }}</td>
                <td>{{ findContract(item.id).quarter_1 }}</td>
                <td>{{ findContract(item.id).quarter_2 }}</td>
                <td>{{ findContract(item.id).quarter_3 }}</td>
                <td>{{ findContract(item.id).quarter_4 }}</td>
                <td>{{ findContract(item.id).quarter_1 + findContract(item.id).quarter_2 + findContract(item.id).quarter_3 + findContract(item.id).quarter_4 }}</td>
                <td>{{ findContract(item.id).report_date }}</td>
                <td style="word-wrap: break-word; max-width: 250px; white-space: break-spaces !important;">{{ item.stage.name }}</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" :style="btnStyles(item.stage.id)" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </td>
                <td style="word-wrap: break-word; max-width: 250px; white-space: break-spaces !important;">{{ item.state_comment }}</td>
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
    name: 'contractReport',

    components: {
        inputSelect,
        createToaster
    },
    props: {
        user_id: {
            type: Number
        },
    },
    computed: {

    },
    methods: {
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
            console.log('user_id', this.user_id);
            if (this.user_id == 1) {
                url = "../../contract/getReportAll";
            } else {
                url = `../../contract/getReportByUser/${this.user_id}`;
            }

            axios
                .get(url)
                .then((response) => {
                    this.reports = response.data;
                })
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

        findContract(id){
            let obj = this.reports.find(el => {
                if (el.contract_id == id) {
                    return el
                }
            });

            if (typeof obj == "undefined") {
                return {
                    quarter_1: 0,
                    quarter_2: 0,
                    quarter_3: 0,
                    quarter_4: 0,
                }

            } else {
                return obj
            }

        },

        loadCompanyByUser() {
            axios
                .get(`../../companies/getByUser/${this.user_id}`)
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
            if(this.user_id === 1) {
                url = "../contract/getContractAll";
            } else {
                url = `../contract/getContractByUser/${this.user_id}`;
            }
            axios
                .get(url)
                .then((response) => {
                    this.info = response;
                    this.contracts = response.data;
                    this.contractsFilter = response.data;
                    this.loading = false
                })
        },

        filter() {
            this.contractsFilter = this.contracts.filter(el => {
                return (this.max_report_date === '' || this.max_report_date >= this.findContract(el.id).report_date ) &&
                    (this.company_id === 0 || this.company_id === el.company_id)
            })

            this.$parent.$refs.bar.filter(this.company_id)
            this.$parent.$refs.donut.filter(this.company_id)

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
            contracts: [],
            contractsFilter: [],
            info: '',
            loading: false,
            companies: [],
            counterparties: [],
            reports: [],
            company_id: 0,
            max_report_date: ''
        }
    },
}
</script>
