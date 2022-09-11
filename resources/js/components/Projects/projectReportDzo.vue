<template>
    <div>
        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <div v-else class="v-report">
            <p>Это отчет ДЗО</p>
            <div class="row">
                <div class="col-6 mb-2">
                    <select name="" id="" v-model="company_id" @change="filter()">
                        <option value="0">Все</option>
                        <option v-for="item in companies" :value="item.id">{{ item.name }}</option>
                    </select>
                </div>
                <label for="report_max_date" class="col-form-label">Дата по:</label>
                <div class="col-4 mb-2">
                    <input type="date" class="form-control datepicker" id="report_max_date" v-model="max_report_date"
                           @change="filter()">
                </div>
            </div>


            <table class="table table-bordered" id="datatable">
                <thead>
                <tr>
                    <th>ДЗО</th>
                    <th>Фактическая выручка</th>
                    <th>Текущая контрактация</th>
                    <th>Прогноз по выручке на 1 квартал</th>
                    <th>Прогноз по выручке на 2 квартал</th>
                    <th>Прогноз по выручке на 3 квартал</th>
                    <th>Прогноз по выручке на 4 квартал</th>
                    <th>Прогноз на год</th>
                    <!--                <th>Фактическая выручка на дату отчёта</th>-->
                    <th>План по выручке на текущий год</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="projects.length == 0">
                    <td colspan="5">Нет данных</td>
                </tr>
                <tr v-for="(item, index) in companiesFilter" :key="item.id">
                    <td>{{ item.name }}</td>
                    <td>{{ findReports(item.id).actual_revenue.toFixed(4) }}</td>
                    <td>{{ findReports(item.id).current_contracting.toFixed(4) }}</td>
                    <td>{{ findReports(item.id).quarter_1.toFixed(4) }}</td>
                    <td>{{ findReports(item.id).quarter_2.toFixed(4) }}</td>
                    <td>{{ findReports(item.id).quarter_3.toFixed(4) }}</td>
                    <td>{{ findReports(item.id).quarter_4.toFixed(4) }}</td>
                    <td>{{
                            (findReports(item.id).quarter_1 + findReports(item.id).quarter_2 + findReports(item.id).quarter_3 + findReports(item.id).quarter_4).toFixed(4)
                        }}
                    </td>
                    <!--                <td></td>-->
                    <td>{{ item.planValue }}</td>
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
    name: 'projectReportDzo',

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
                current_contracting: 0,
            }
            this.reportsFilter.forEach(item => {
                if (item.type === 'project' && projectsOfCompanyIds.includes(item.project_id)) {
                    values.quarter_1 += item.quarter_1
                    values.quarter_2 += item.quarter_2
                    values.quarter_3 += item.quarter_3
                    values.quarter_4 += item.quarter_4
                    values.actual_revenue += item.actual_revenue
                    values.current_contracting += item.current_contracting
                }
                if (item.type === 'contract' && projectsOfCompanyIds.includes(item.contract_id)) {
                    values.quarter_1 += item.quarter_1
                    values.quarter_2 += item.quarter_2
                    values.quarter_3 += item.quarter_3
                    values.quarter_4 += item.quarter_4
                    values.actual_revenue += item.actual_revenue
                    values.current_contracting += item.current_contracting
                }
            })
            return values
        },

        loadCompanyByUser() {
            axios
                .get(`../../companies/getByUser/${this.user_id}`)
                .then((response) => {
                    response.data.forEach(item => {
                        let plan = this.companiesPlan.find(plan => plan.id === item.id)
                        item.planValue = '0'
                        if(plan){
                            item.planValue = plan.value
                        }

                    })
                    this.companies = response.data;
                    this.companiesFilter = response.data;
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
            companiesFilter: [],
            counterparties: [],
            reports: [],
            reportsFilter: [],
            company_id: '0',
            max_report_date: '',
            companiesPlan: [
                {
                    "id": 1,
                    "inn": "7710277994",
                    "name": "АО «Росэлектроника»",
                    "phone": "+7 (495) 229-03-60, +7 (495) 357-09-04",
                    "description": "121357, МОСКВА ГОРОД, УЛИЦА ВЕРЕЙСКАЯ, ДОМ 29, СТРОЕНИЕ 141, КАБИНЕТ 322 \nosandreeva@ruselectronics.ru",
                    "parent_id": 0,
                    "created_at": null,
                    "updated_at": "2022-03-30T18:44:10.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 1
                    },
                    value: '0',
                },
                {
                    "id": 14,
                    "inn": "7802144144",
                    "name": "АО \"НИИ \"Гириконд\"",
                    "phone": "+7 (812) 247-15-63, +7 (812) 247-14-50",
                    "description": "194223, САНКТ-ПЕТЕРБУРГ ГОРОД, УЛИЦА КУРЧАТОВА, 10\n5526057@giricond.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:23:43.000000Z",
                    "updated_at": "2022-03-30T18:23:43.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 14
                    },
                    value:'370,00',
                },
                {
                    "id": 15,
                    "inn": "7710070622",
                    "name": "АО \"НИИРК\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:24:06.000000Z",
                    "updated_at": "2022-03-30T18:24:06.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 15
                    },
                    value: '0',
                },
                {
                    "id": 16,
                    "inn": "7743592991",
                    "name": "АО \"ВО \"ЭЛЕКТРОНИНТОРГ\"",
                    "phone": "+7 (495) 601-20-26, +7 (495) 988-78-11",
                    "description": "121059, МОСКВА Г., Б-Р УКРАИНСКИЙ, Д. 8, СТР. 1\nevartemov@electronintorg.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:24:33.000000Z",
                    "updated_at": "2022-03-30T18:24:33.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 16
                    },
                    value:'242,70',
                },
                {
                    "id": 17,
                    "inn": "7719800954",
                    "name": "АО \"ГЗ \"Пульсар\"",
                    "phone": "+7 (495) 369-48-62",
                    "description": "105187, МОСКВА ГОРОД, ПРОЕЗД ОКРУЖНОЙ, 27",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:25:01.000000Z",
                    "updated_at": "2022-03-30T18:25:01.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 17
                    },
                    value: '488,80',
                },
                {
                    "id": 18,
                    "inn": "3435000717",
                    "name": "АО \"Завод \"Метеор\"",
                    "phone": "+7 (8443) 34-26-94, +7 (8443) 34-22-88, +7 (8443) 34-22-58, +7 (8443) 34-15-95",
                    "description": "404122, ВОЛГОГРАДСКАЯ ОБЛАСТЬ, ГОРОД ВОЛЖСКИЙ, УЛИЦА ГОРЬКОГО, 1\nmazinayv@meteor.su",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:25:24.000000Z",
                    "updated_at": "2022-03-30T18:25:24.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 18
                    },
                    value:'110,00',
                },
                {
                    "id": 19,
                    "inn": "7709877563",
                    "name": "АО \"Интернавигация\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:25:47.000000Z",
                    "updated_at": "2022-03-30T18:25:47.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 19
                    },
                    value: '116,10',
                },
                {
                    "id": 20,
                    "inn": "7813487947",
                    "name": "АО \"Информакустика\"",
                    "phone": "+7 (812) 777-78-84, +7 (812) 320-01-56",
                    "description": "194021, САНКТ-ПЕТЕРБУРГ ГОРОД, УЛ. ПОЛИТЕХНИЧЕСКАЯ, Д.22, ЛИТ.А\ngda@forso.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:26:11.000000Z",
                    "updated_at": "2022-03-30T18:26:11.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 20
                    },
                    value:'70,01',
                },
                {
                    "id": 21,
                    "inn": "7704731673",
                    "name": "АО \"Концерн \"Орион\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:26:30.000000Z",
                    "updated_at": "2022-03-30T18:26:30.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 21
                    },
                    value: '0',
                },
                {
                    "id": 22,
                    "inn": "7735000722",
                    "name": "АО \"Логика\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:26:47.000000Z",
                    "updated_at": "2022-03-30T18:26:47.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 22
                    },
                    value: '0',
                },
                {
                    "id": 23,
                    "inn": "7712008203",
                    "name": "АО \"Мосэлектронпроект\"",
                    "phone": "+7 (495) 708-27-09, +7 (495) 708-27-17",
                    "description": "127299, МОСКВА ГОРОД, УЛИЦА КОСМОНАВТА ВОЛКОВА, ДОМ 12, ЭТ 5 ПОМ X КОМ 22\nereschenko_tv@mosep.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:27:08.000000Z",
                    "updated_at": "2022-03-30T18:27:08.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 23
                    },
                    value:'400,22',
                },
                {
                    "id": 24,
                    "inn": "5321144204",
                    "name": "АО \"НИИПТ \"РАСТР\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:27:24.000000Z",
                    "updated_at": "2022-03-30T18:27:24.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 24
                    },
                    value:'150,00',
                },
                {
                    "id": 25,
                    "inn": "5052023047",
                    "name": "АО \"НИИ \"Платан\" с заводом при НИИ\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:27:42.000000Z",
                    "updated_at": "2022-03-30T18:27:42.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 25
                    },
                    value:'66,00',
                },
                {
                    "id": 26,
                    "inn": "7802774001",
                    "name": "АО \"НИИ \"Телевидения\"",
                    "phone": "+7 (812) 297-41-67, +7 (812) 247-41-67",
                    "description": "194021, САНКТ-ПЕТЕРБУРГ ГОРОД, УЛИЦА ПОЛИТЕХНИЧЕСКАЯ, ДОМ 22\nniitv@niitv.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:28:05.000000Z",
                    "updated_at": "2022-03-30T18:28:05.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 26
                    },
                    value:'1 199,64',
                },
                {
                    "id": 27,
                    "inn": "6230005886",
                    "name": "АО \"ПЛАЗМА\"",
                    "phone": "+7 (4912) 24-90-02, +7 (4912) 24-90-79, +7 (4912) 24-90-89",
                    "description": "390023, РЯЗАНСКАЯ ОБЛАСТЬ, Г. РЯЗАНЬ, УЛ. ЦИОЛКОВСКОГО, Д. 24\nplasma@plasmalabs.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:28:32.000000Z",
                    "updated_at": "2022-03-30T18:28:32.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 27
                    },
                    value: '500,00',
                },
                {
                    "id": 28,
                    "inn": "4026008516",
                    "name": "АО \"НИИМЭТ\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:28:50.000000Z",
                    "updated_at": "2022-03-30T18:28:50.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 28
                    },
                    value: '0',
                },
                {
                    "id": 29,
                    "inn": "7017084932",
                    "name": "АО \"НИИПП\"",
                    "phone": "+7 (3822) 55-66-96, +7 (3822) 28-82-63, +7 (3822) 28-82-88",
                    "description": "634034, ТОМСКАЯ ОБЛАСТЬ, ГОРОД ТОМСК, УЛИЦА КРАСНОАРМЕЙСКАЯ, 99А\nniipp@niipp.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:29:38.000000Z",
                    "updated_at": "2022-03-30T18:29:38.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 29
                    },
                    value:'986,39',
                },
                {
                    "id": 30,
                    "inn": "5031099373",
                    "name": "АО \"НИИЭИ\"",
                    "phone": "+7 (499) 270-64-11, +7 (495) 702-93-12, +7 (965) 136-85-14",
                    "description": "142455, МОСКОВСКАЯ ОБЛАСТЬ, ГОРОД НОГИНСК, ГОРОД ЭЛЕКТРОУГЛИ, ПЕРЕУЛОК ГОРКИ, ДОМ 1\nsbyt@niiei.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:30:16.000000Z",
                    "updated_at": "2022-03-30T18:30:16.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 30
                    },
                    value:'169,94',
                },
                {
                    "id": 31,
                    "inn": "5834054179",
                    "name": "АО \"НИИЭМП\"",
                    "phone": "+7 (8412) 94-58-25, +7 (8412) 47-71-01",
                    "description": "440600, ПЕНЗЕНСКАЯ ОБЛАСТЬ, ГОРОД ПЕНЗА, УЛИЦА КАРАКОЗОВА, 44\nkoms@niiemp.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:30:47.000000Z",
                    "updated_at": "2022-03-30T18:30:47.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 31
                    },
                    value:'208,04',
                },
                {
                    "id": 32,
                    "inn": "5405441299",
                    "name": "АО \"НЗР \"Оксид\"",
                    "phone": "+7 (383) 266-11-50",
                    "description": "630102, НОВОСИБИРСКАЯ ОБЛАСТЬ, Г НОВОСИБИРСК, УЛ КИРОВА, 82\nklimenko@nzroksid.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:31:13.000000Z",
                    "updated_at": "2022-03-30T18:31:13.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 32
                    },
                    value:'187,00',
                },
                {
                    "id": 33,
                    "inn": "6453119615",
                    "name": "АО \"НПП \"АЛМАЗ\"",
                    "phone": "+7 (8452) 63-52-57, +7 (8452) 63-35-58",
                    "description": "410033, САРАТОВСКАЯ ОБЛАСТЬ, ГОРОД САРАТОВ, УЛИЦА ИМ ПАНФИЛОВА И.В., 1\ndubrovskyiy@almaz-rpe.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:31:50.000000Z",
                    "updated_at": "2022-03-30T18:31:50.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 33
                    },
                    value:'679,99',
                },
                {
                    "id": 34,
                    "inn": "5050108496",
                    "name": "АО \"НПП \"ИСТОК\" ИМ. ШОКИНА\"",
                    "phone": "+7 (495) 465-86-83, +7 (916) 266-60-43, +7 (495) 465-88-77",
                    "description": "141190, МОСКОВСКАЯ ОБЛАСТЬ, Г. ФРЯЗИНО, УЛ. ВОКЗАЛЬНАЯ, Д. 2А, К. 1, КОМ. 65, ЭТАЖ 2\ninfo@istokmw.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:32:13.000000Z",
                    "updated_at": "2022-03-30T18:32:13.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 34
                    },
                    value: '2 803,00',
                },
                {
                    "id": 35,
                    "inn": "6453097665",
                    "name": "АО \"НПП \"КОНТАКТ\"",
                    "phone": "+7 (8452) 35-76-55, +7 (8452) 63-33-52, +7 (8452) 35-76-01, +7 (8452) 35-77-01",
                    "description": "410086, САРАТОВСКАЯ ОБЛАСТЬ, ГОРОД САРАТОВ, УЛИЦА СПИЦЫНА, ДОМ 1\noffice@kontakt-saratov.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:32:37.000000Z",
                    "updated_at": "2022-03-30T18:32:37.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 35
                    },
                    value: '990,26',
                },
                {
                    "id": 36,
                    "inn": "7719846490",
                    "name": "АО \"НПП \"Пульсар\"",
                    "phone": "+7 (499) 369-03-33, +7 (495) 366-51-01",
                    "description": "105187, МОСКВА ГОРОД, ПР-Д ОКРУЖНОЙ, Д.27\nadministrator@pulsarnpp.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:33:02.000000Z",
                    "updated_at": "2022-03-30T18:33:02.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 36
                    },
                    value: '623,00'
                },
                {
                    "id": 37,
                    "inn": "5261079332",
                    "name": "АО \"НПП \"Салют\"",
                    "phone": "+7 (831) 466-15-10, +7 (831) 211-40-10",
                    "description": "603107, НИЖЕГОРОДСКАЯ ОБЛАСТЬ, ГОРОД НИЖНИЙ НОВГОРОД, УЛИЦА ЛАРИНА, ДОМ 7, КОРПУС 4, ОФИС 4264 \nsalut@salut.nnov.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:33:29.000000Z",
                    "updated_at": "2022-03-30T18:33:29.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 37
                    },
                    value:'800,06',
                },
                {
                    "id": 38,
                    "inn": "5261019855",
                    "name": "АО \"НПП \"Салют-25\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:33:43.000000Z",
                    "updated_at": "2022-03-30T18:33:43.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 38
                    },
                    value:'0',
                },
                {
                    "id": 39,
                    "inn": "7728328640",
                    "name": "АО \"НПП \"ТОРИЙ\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:33:58.000000Z",
                    "updated_at": "2022-03-30T18:33:58.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 39
                    },
                    value: '0',
                },
                {
                    "id": 40,
                    "inn": "7810098300",
                    "name": "АО \"НПП \"Электронстандарт\"",
                    "phone": "+7 (812) 676-28-81, +7 (812) 373-50-65",
                    "description": "196084, САНКТ-ПЕТЕРБУРГ ГОРОД, УЛИЦА ЦВЕТОЧНАЯ, ДОМ 25, КОРПУС 3 ЛИТЕР С, ПОМЕЩЕНИЕ 187\nv.i.shemyagina@es-npp.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:34:26.000000Z",
                    "updated_at": "2022-03-30T18:34:26.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 40
                    },
                    value: '180,00',
                },
                {
                    "id": 41,
                    "inn": "7107033763",
                    "name": "АО \"Октава\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:34:43.000000Z",
                    "updated_at": "2022-03-30T18:34:43.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 41
                    },
                    value: '0',
                },
                {
                    "id": 42,
                    "inn": "7017275172",
                    "name": "АО \"Омега\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:34:55.000000Z",
                    "updated_at": "2022-03-30T18:34:55.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 42
                    },
                    value:'21,01',
                },
                {
                    "id": 43,
                    "inn": "7719019691",
                    "name": "АО \"Оптрон\"",
                    "phone": "+7 (495) 366-14-47, +7 (495) 366-92-59",
                    "description": "105187, МОСКВА ГОРОД, УЛИЦА ЩЕРБАКОВСКАЯ, ДОМ 53, КОРПУС 7, КАБИНЕТ 37\nn.trofimova@optron.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:35:18.000000Z",
                    "updated_at": "2022-03-30T18:35:18.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 43
                    },
                    value:'65,00',
                },
                {
                    "id": 44,
                    "inn": "7734682448",
                    "name": "АО \"РЗМ Технологии\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:35:34.000000Z",
                    "updated_at": "2022-03-30T18:35:34.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 44
                    },
                    value:'861,45',
                },
                {
                    "id": 45,
                    "inn": "7810196298",
                    "name": "АО \"РНИИ \"ЭЛЕКТРОНСТАНДАРТ\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:35:47.000000Z",
                    "updated_at": "2022-03-30T18:35:47.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 45
                    },
                    value:'34,69',
                },
                {
                    "id": 46,
                    "inn": "6230006400",
                    "name": "АО \"РЗМКП\"",
                    "phone": "+7 (4912) 44-42-06, +7 (4912) 24-01-54, +7 (4912) 24-97-57",
                    "description": "390027, РЯЗАНСКАЯ ОБЛАСТЬ, ГОРОД РЯЗАНЬ, УЛИЦА НОВАЯ, ДОМ 51 \"В\"\ninfo@rmcip.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:36:08.000000Z",
                    "updated_at": "2022-03-30T18:36:08.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 46
                    },
                    value:'1 697,81',
                },
                {
                    "id": 47,
                    "inn": "7802309269",
                    "name": "АО \"Светлана-Рост\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:36:21.000000Z",
                    "updated_at": "2022-03-30T18:36:21.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 47
                    },
                    value: '0',
                },
                {
                    "id": 48,
                    "inn": "7730658356",
                    "name": "АО \"Сетевые технологии\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:36:32.000000Z",
                    "updated_at": "2022-03-30T18:36:32.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 48
                    },
                    value:'0',
                },
                {
                    "id": 49,
                    "inn": "6027075580",
                    "name": "АО \"СКБ ВТ\"",
                    "phone": "+7 (8112) 44-35-22, +7 (910) 425-11-77",
                    "description": "180007, ПСКОВСКАЯ ОБЛАСТЬ, ГОРОД ПСКОВ, УЛИЦА МАКСИМА ГОРЬКОГО, 1\nsokolova@skbvt.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:36:53.000000Z",
                    "updated_at": "2022-03-30T18:36:53.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 49
                    },
                    value:'9,22',
                },
                {
                    "id": 50,
                    "inn": "5321095589",
                    "name": "АО \"СКТБ РТ\"",
                    "phone": "+7 (8162) 62-17-35, +7 (8162) 62-90-06",
                    "description": "173021, НОВГОРОДСКАЯ ОБЛАСТЬ, ГОРОД ВЕЛИКИЙ НОВГОРОД, УЛИЦА НЕХИНСКАЯ, ДОМ 55\noffice@sktbrt.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:38:10.000000Z",
                    "updated_at": "2022-03-30T18:38:10.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 50
                    },
                    value:'350,00',
                },
                {
                    "id": 51,
                    "inn": "7713752430",
                    "name": "АО \"Спецмагнит\"",
                    "phone": "+7 (495) 482-00-08",
                    "description": "127238, МОСКВА ГОРОД, ШОССЕ ДМИТРОВСКОЕ, 58\ns-magnet@mail.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:38:43.000000Z",
                    "updated_at": "2022-03-30T18:38:43.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 51
                    },
                    value:'60,00',
                },
                {
                    "id": 52,
                    "inn": "7729033871",
                    "name": "АО \"ЦНИИ \"ЭЛЕКТРОНИКА\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:38:56.000000Z",
                    "updated_at": "2022-03-30T18:38:56.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 52
                    },
                    value: '0',
                },
                {
                    "id": 53,
                    "inn": "7731014611",
                    "name": "АО \"ЦНИТИ \"ТЕХНОМАШ\"",
                    "phone": "+7 (495) 144-75-15",
                    "description": "121108, МОСКВА ГОРОД, УЛИЦА ИВАНА ФРАНКО, 4 \ncnititm@cnititm.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:39:20.000000Z",
                    "updated_at": "2022-03-30T18:39:20.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 53
                    },
                    value:'335,10',
                },
                {
                    "id": 54,
                    "inn": "7722599844",
                    "name": "АО \"ЦКБ РМ\"",
                    "phone": "+7 (495) 361-45-04, +7 (915) 479-19-94",
                    "description": "117587, МОСКВА ГОРОД, ШОССЕ ВАРШАВСКОЕ, ДОМ 125Б\n sales@ckbrm.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:39:49.000000Z",
                    "updated_at": "2022-03-30T18:39:49.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 54
                    },
                    value:'68,00',
                },
                {
                    "id": 55,
                    "inn": "7718159209",
                    "name": "АО \"ЦНИИ \"Циклон\"",
                    "phone": "+7 (495) 460-48-00, +7 (495) 465-86-66",
                    "description": "107207, МОСКВА ГОРОД, ШОССЕ ЩЁЛКОВСКОЕ, 77\ne.terekhina@cyclone-jsc.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:40:14.000000Z",
                    "updated_at": "2022-03-30T18:40:14.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 55
                    },
                    value: '38,59',
                },
                {
                    "id": 56,
                    "inn": "7802144666",
                    "name": "АО \"ЦНИИ \"ЭЛЕКТРОН\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:40:29.000000Z",
                    "updated_at": "2022-03-30T18:40:29.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 56
                    },
                    value: '0',
                },
                {
                    "id": 57,
                    "inn": "5044028517",
                    "name": "АО \"ЦСМ\"",
                    "phone": "+7 (495) 994-00-71, +7 (905) 762-00-62",
                    "description": "141503, МОСКОВСКАЯ ОБЛАСТЬ, ГОРОД СОЛНЕЧНОГОРСК, УЛИЦА КРАСНАЯ, ДОМ 161, КОРПУС 1, ПОМЕЩЕНИЕ 1\nkochetkova.a@rt-hpc.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:40:50.000000Z",
                    "updated_at": "2022-03-30T18:40:50.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 57
                    },
                    value:'182,79',
                },
                {
                    "id": 58,
                    "inn": "7802362079",
                    "name": "АО \"ЭЛЕКТРОН-ОПТРОНИК\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:41:05.000000Z",
                    "updated_at": "2022-03-30T18:41:05.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 58
                    },
                    value: '0',
                },
                {
                    "id": 59,
                    "inn": "9703014282",
                    "name": "АО \"ЭЛЕМЕНТ\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:41:17.000000Z",
                    "updated_at": "2022-03-30T18:41:17.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 59
                    },
                    value: '0',
                },
                {
                    "id": 60,
                    "inn": "7719813840",
                    "name": "АО ВО \"Машприборинторг\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:41:28.000000Z",
                    "updated_at": "2022-03-30T18:41:28.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 60
                    },
                    value:'0,00',
                },
                {
                    "id": 61,
                    "inn": "7810245940",
                    "name": "АО \"НИИ \"Феррит-Домен\"",
                    "phone": "+7 (812) 676-29-29",
                    "description": "196006, САНКТ-ПЕТЕРБУРГ ГОРОД, УЛИЦА ЦВЕТОЧНАЯ, ДОМ 25, КОРПУС 3 ЛИТЕР Б, ПОМЕЩЕНИЕ 417\na.r.oganesjan@domen.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:41:57.000000Z",
                    "updated_at": "2022-03-30T18:41:57.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 61
                    },
                    value:'140,00',
                },
                {
                    "id": 62,
                    "inn": "5052022886",
                    "name": "АО НПП \"Циклон-Тест\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:42:12.000000Z",
                    "updated_at": "2022-03-30T18:42:12.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 62
                    },
                    value:'122,00',
                },
                {
                    "id": 63,
                    "inn": "7710563353",
                    "name": "ООО \"РК-Развитие\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:42:22.000000Z",
                    "updated_at": "2022-03-30T18:42:22.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 63
                    },
                    value: '0',
                },
                {
                    "id": 64,
                    "inn": "7731395798",
                    "name": "ООО \"Росэл-СТ\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:42:32.000000Z",
                    "updated_at": "2022-03-30T18:42:32.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 64
                    },
                    value: '0',
                },
                {
                    "id": 65,
                    "inn": "7701894662",
                    "name": "ООО \"Скантроник Системс\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:42:42.000000Z",
                    "updated_at": "2022-03-30T18:42:42.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 65
                    },
                    value: '0',
                },
                {
                    "id": 66,
                    "inn": "7718809369",
                    "name": "ООО \"ТОПЭ\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:42:53.000000Z",
                    "updated_at": "2022-03-30T18:42:53.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 66
                    },
                    value: '0',
                },
                {
                    "id": 67,
                    "inn": "0700000133",
                    "name": "ПАО \"ТЕЛЕМЕХАНИКА\"",
                    "phone": null,
                    "description": null,
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:43:01.000000Z",
                    "updated_at": "2022-03-30T18:43:01.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 67
                    },
                    value: '0',
                },
                {
                    "id": 68,
                    "inn": "9731001422",
                    "name": "ООО «РЭ-технологии»",
                    "phone": "+7 (495) 229-03-60, +7 (495) 357-09-04",
                    "description": "121357, МОСКВА Г, ВЕРЕЙСКАЯ УЛ, ДОМ 29, СТРОЕНИЕ 141, КАБИНЕТ 201\n info@roseltec.ru",
                    "parent_id": 1,
                    "created_at": "2022-03-30T18:43:24.000000Z",
                    "updated_at": "2022-03-30T18:43:24.000000Z",
                    "deleted": 0,
                    "pivot": {
                        "user_id": 1,
                        "company_id": 68
                    },
                    value:'1 500,00',
                }
            ]
        }
    },
}
</script>
<style>
.v-report{
    width: 100%;
    overflow: scroll;
    max-height: 75vh;
}
th{
    position: sticky;
    top: -1px;
    background: #f9fafb;
}
</style>
<style>
.v-report{
    width: 100%;
    overflow: scroll;
    max-height: 75vh;
}
th{
    position: sticky;
    top: -1px;
    background: #f9fafb;
}
</style>
