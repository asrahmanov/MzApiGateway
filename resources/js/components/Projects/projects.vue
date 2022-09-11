<template>
    <div>
        <div style="text-align: left">
            <div class="row">
                <div class="col-4">
                    <label>Фильтр:</label>
                    <input v-model="searchTerm" @keyup="doSearch(false)" class="form-control mb-3"/>
                </div>
                <div class="col-1 v-question-block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
                         class="bi bi-question-circle v-question" viewBox="0 0 40 40">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path
                            d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                    </svg>
                    <p class="v-question-p">Для фильтрации данных по ключевому запросу введи текст в поле. Таблица будет
                        перестроена под запрос автоматически.</p>
                </div>
            </div>
        </div>
    </div>
    <table-lite
        :is-static-mode="true"
        :is-loading="table.isLoading"
        :columns="table.columns"
        :rows="table.rows"
        :total="table.totalRecordCount"
        :sortable="table.sortable"
        :messages="table.messages"
        @do-search="doSearch(false)"
        @is-finished="table.isLoading = false"
    ></table-lite>

</template>

<script>

import axios from 'axios';
import inputSelect from "../other/selects/inputSelect";
import {createToaster} from "@meforma/vue-toaster";
import TableLite from "vue3-table-lite";
import {reactive, ref} from "vue";


export default {
    name: 'projects',
    components: {
        inputSelect,
        createToaster,
        TableLite,
    },
    setup(props) {
        const searchTerm = ref(""); // Search text
        let rows_unfiltered = []
        // Fake data
        let counterpartiesById = {}, companiesById = {}
        let reports = []
        let projects = []
        let findReports = function (company_id) {
            let projectsOfCompany = projects.filter(el => el.company_id === company_id)
            let projectsOfCompanyIds = []
            projectsOfCompany.forEach(item => {
                projectsOfCompanyIds.push(item.id)
            })
            let filteredReports = reports.filter(report=>{
                return projectsOfCompanyIds.includes(report.project_id);
            })
            let values = {
                quarter_1: 0,
                quarter_2: 0,
                quarter_3: 0,
                quarter_4: 0,
                actual_revenue: 0,
                current_contracting: 0,
            }

            let maxReportDate = new Date(Math.max.apply(null, filteredReports.map(function (e) {
                return new Date(e.report_date)
            })));
            //console.log('reports', reports, maxDate)
            let report = filteredReports.find(report => {
                let reportDate = new Date(report.report_date)
                if (reportDate.getFullYear() === maxReportDate.getFullYear() &&
                    reportDate.getMonth() === maxReportDate.getMonth() &&
                    reportDate.getDate() === maxReportDate.getDate()) {
                    return true
                } else {
                    return false
                }
            })
            if(report){
                values.quarter_1 += report.quarter_1
                values.quarter_2 += report.quarter_2
                values.quarter_3 += report.quarter_3
                values.quarter_4 += report.quarter_4
                values.actual_revenue += report.actual_revenue
                values.current_contracting += report.current_contracting
            }

            let result = values.quarter_1 + values.quarter_2 + values.quarter_3 + values.quarter_4
            return result
        }
        const table = reactive({
            isLoading: true,
            columns: [
                {
                    label: "ДЗО",
                    field: "dzo",
                    sortable: true,
                    isKey: true,
                },
                {
                    label: "Контрагент",
                    field: "counterparty",
                    sortable: true,
                },
                {
                    label: "Наименование проекта",
                    field: "name",
                    sortable: true,
                },
                {
                    label: "Прогнозируемая выручка, МЛН.РУБ.",
                    field: "price",
                    sortable: true,
                    display: function (row) {
                        return (
                            `${parseInt((row.price * 100)) / 100}`
                        );
                    },
                },
                {
                    label: "Статус",
                    field: "status_name",
                    sortable: true,
                    display: function (row) {
                        return (
                            '<span class="badge badge-primary" style="font-size: 14px;">' + row.status_name + '</span>'
                        );
                    },
                },
                {
                    label: "Этап",
                    field: "stage_name",
                    sortable: true,
                },
                {
                    label: "Прогресс",
                    field: "progress",
                    sortable: true,
                    display: function (row) {
                        let width = (20 * row.stage_id) + 1 + '%';
                        return (
                            `<div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    role="progressbar" style="width: ${width}" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                                </div>`
                        );
                    },
                },
                {
                    label: "Действие",
                    field: "id",
                    sortable: true,
                    display: function (row) {
                        return (`<a href="../../project/info/${row.id}" target="_blank" class="btn btn-success">Карточка проекта</a>`);
                    },
                },
            ],
            rows: [],
            totalRecordCount: 0,
            sortable: {
                order: "dzo",
                sort: "asc",
            },
            messages: {
                pagingInfo: "Отображаются {0}-{1} из {2}",
                pageSizeChangeLabel: "Строк на экране:",
                gotoPageLabel: "Перейти на страницу:",
                noDataAvailable: "Нет данных",
            },
        });
        const doSearch = async (reload) => {
            //console.log('Сработал doSearch', reload)
            if (reload) {
                table.isLoading = true
                rows_unfiltered = []
                await axios
                    .get("../../manuals/counterparties/list")
                    .then(response => {
                        response.data.forEach(row => {
                            counterpartiesById[row.id] = row.name
                        });
                    })
                await axios
                    .get(`../../companies/getByUser/${props.user_id}`)
                    .then(response => {
                        response.data.forEach(row => {
                            companiesById[row.id] = row.name
                        });
                    })
                let reportUrl;
                if (props.user_id == 1) {
                    reportUrl = "../../project/getReportAll";
                } else {
                    reportUrl = `../../project/getReportByUser/${props.user_id}`;
                }

                await axios
                    .get(reportUrl)
                    .then((response) => {
                        response.data.forEach(report => {
                            report.type = 'project'
                            reports.push(report)
                        })
                    })
                let uId = props.user_id,
                    url = uId === 1 ? "../project/getProjectAll" : `../project/getProjectByUser/${props.user_id}`;
                await axios.get(url)
                    .then(response => {
                        table.rows = [];
                        projects = response.data
                        response.data.forEach(row => {
                            row.dzo = companiesById[row.company_id];
                            row.counterparty = counterpartiesById[row.counterparty_id];
                            row.status_name = row.status.name
                            row.stage_name = row.stage.name;
                            row.progress = row.stage.id;
                            row.price = findReports(row.company_id)
                            table.rows.push(row);
                            rows_unfiltered.push(row)
                        })
                        table.totalRecordCount = response.data.length;
                        table.isLoading = false
                    });
                return {
                    doSearch,
                    searchTerm,
                    table,
                };
                // doSearch()
            } else {
                table.rows = rows_unfiltered.filter(
                    x => x.dzo.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                        x.counterparty.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                        x.status_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                        x.stage_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                        x.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                        x.price.toLowerCase().includes(searchTerm.value.toLowerCase())
                );
                table.totalRecordCount = table.rows.length;
            }
        };
        doSearch(true)

        return {
            doSearch,
            searchTerm,
            table,
        };
    },
    props: {
        user_id: {
            type: Number
        },
    },
    data: function () {
        return {
            company_id: 0,
            projects: [],
            projectsFilter: [],
            info: '',
            loading: false,
            companies: [],
            counterparties: [],
            reports: [],
            reportsFilter: [],
        }
    },
}
</script>
<style scoped>
::v-deep(.vtl-table .vtl-thead .vtl-thead-th) {
    color: #fff;
    background-color: #10b759;
    border-color: #10b759;
}

::v-deep(.vtl-table td),
::v-deep(.vtl-table tr) {
    border: none;
}

::v-deep(.vtl-paging-info) {
    color: #727cf5;
}

::v-deep(.vtl-paging-count-label),
::v-deep(.vtl-paging-page-label) {
    color: #727cf5;
}

::v-deep(.vtl-paging-pagination-page-link) {
    border: none;
}

::v-deep(.vtl-paging-count-dropdown),
::v-deep(.vtl-paging-page-dropdown) {
    display: inline-block;
}

.v-question-block {
    position: relative;
}

.v-question {
    margin-top: 35px;
    cursor: pointer;
}

.v-question-p {
    background: white;
    position: absolute;
    display: none;
    width: 400px;
    top: 3px;
    left: 40px;
    padding: 5px;
    border-radius: 4px;
}

.v-question:hover ~ .v-question-p {
    display: block;
}
</style>
