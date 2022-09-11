<template>
    <div>
        <div style="text-align: left">
            <label>Фильтр:</label><input v-model="searchTerm" @keyup="doSearch"/>
        </div>
        <table-lite
            :is-loading="table.isLoading"
            :columns="table.columns"
            :rows="table.rows"
            :total="table.totalRecordCount"
            :sortable="table.sortable"
            :messages="table.messages"
            @do-search="doSearch"
            @is-finished="table.isLoading = false"
        ></table-lite>
    </div>
</template>

<script>

import axios from 'axios';
import inputSelect from "../other/selects/inputSelect";
import {createToaster} from "@meforma/vue-toaster";
import TableLite from "vue3-table-lite";
import {reactive, ref} from "vue";

export default {
    name: 'product',
    components: {
        inputSelect,
        createToaster,
        TableLite
    },
    data: function () {
        return {
            company_id: 0,
            contracts: [],
            contractsFilter: [],
            info: '',
            loading: false,
            companies: [],
            product: [],
            product_name: '',
            counterparties: [],
        }
    },
    setup(props) {
        const searchTerm = ref(""); // Search text
        const rows_unfiltered = []
        // Fake data
        let counterpartiesById = {}, companiesById = {}
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
                    label: "Наименование контракта",
                    field: "name",
                    sortable: true,
                },
                {
                    label: "Наименование продукта",
                    field: "product_name",
                    sortable: true,
                },
                {
                    label: "Стоимость",
                    field: "price",
                    sortable: true,
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
                        return (`<a href="../../contract/info/${row.id}" target="_blank" class="btn btn-success">Карточка продукта</a>`);
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
        const doSearch = () => {
            table.rows = rows_unfiltered.filter(
                x => x.dzo.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                    x.counterparty.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                    x.status_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                    x.stage_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                    x.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                    // x.product_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                    x.price.toLowerCase().includes(searchTerm.value.toLowerCase())
            );
            table.totalRecordCount = table.rows.length;
        };
        axios
            .get("../../manuals/counterparties/list")
            .then(response => {
                response.data.forEach(row => {
                    counterpartiesById[row.id] = row.name
                });
                axios
                    .get(`../../companies/getByUser/${props.user_id}`)
                    .then(response => {
                        response.data.forEach(row => {
                            companiesById[row.id] = row.name
                        });
                        let uId = props.user_id, url = uId === 1 ? "../contract/getContractAll" : `../contract/getContractByUser/${props.user_id}`;
                        axios.get(url)
                            .then(response => {
                                table.rows = [];
                                response.data.forEach(row => {
                                    row.dzo = companiesById[row.company_id];
                                    row.counterparty = counterpartiesById[row.counterparty_id];
                                    row.status_name = row.status.name
                                    row.stage_name = row.stage.name;
                                    row.progress = row.stage.id;
                                    table.rows.push(row);
                                    rows_unfiltered.push(row)
                                })
                                table.totalRecordCount = response.data.length;
                                table.isLoading = false;
                            });
                        return {
                            doSearch,
                            searchTerm,
                            table,
                        };
                        // doSearch()
                    })
            })

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
</style>
