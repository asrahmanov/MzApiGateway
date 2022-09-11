<template>
    <div>
        <div style="text-align: left">
            <label>Фильтр:</label><input v-model="searchTerm" @keyup="doSearch(false)"/>
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
    </div>
</template>

<script>

import axios from 'axios';
import inputSelect from "../other/selects/inputSelect";
import {createToaster} from "@meforma/vue-toaster";
import TableLite from "vue3-table-lite";
import {reactive, ref} from "vue";

export default {
    name: 'reportList',
    props: {
        user_id: {
            type: Number
        },
        project_id: {
            type: Number
        },
    },
    components: {
        inputSelect,
        createToaster,
        TableLite
    },
    setup(props) {
        const searchTerm = ref(""); // Search text
        let rows_unfiltered = []
        // Init Your table settings
        const table = reactive({
            isLoading: true,
            columns: [
                {
                    label: "1 КВАРТАЛ",
                    field: "quarter_1",
                    sortable: true,
                    isKey: true,
                },
                {
                    label: "2 КВАРТАЛ",
                    field: "quarter_2",
                    sortable: true,
                },
                {
                    label: "3 КВАРТАЛ",
                    field: "quarter_3",
                    sortable: true,
                },
                {
                    label: "4 КВАРТАЛ",
                    field: "quarter_4",
                    sortable: true,
                },
                {
                    label: "ИТОГО",
                    field: "quarter_sum",
                    sortable: true,
                },
                {
                    label: "ДАТА",
                    field: "report_date",
                    sortable: true,
                },
            ],
            rows: [],
            totalRecordCount: 0,
            sortable: {
                order: "report_date",
                sort: "asc",
            },
            messages: {
                pagingInfo: "Отображаются {0}-{1} из {2}",
                pageSizeChangeLabel: "Строк на экране:",
                gotoPageLabel: "Перейти на страницу:",
                noDataAvailable: "Нет данных",
            },
        });
        const doSearch = (reload) => {
            //console.log('Сработал doSearch', reload)
            if(reload){
                rows_unfiltered = []
                table.isLoading = true;
                let url = `../../project/getReport/${props.project_id}`;
                axios.get(url)
                    .then(response => {
                        table.rows = [];
                        response.data.forEach(row => {
                            row.quarter_sum  = parseFloat(row.quarter_1) + parseFloat(row.quarter_2) +
                                parseFloat(row.quarter_3) + parseFloat(row.quarter_4)
                            if(row.report_date){
                                row.report_date = row.report_date.split('-').reverse().join('.')
                            }
                            rows_unfiltered.push(row)
                            table.rows.push(row)
                        })
                        table.totalRecordCount = response.data.length;
                        table.isLoading = false;
                    });
                return {
                    doSearch,
                    searchTerm,
                    table,
                };
            } else{
                table.rows = rows_unfiltered.filter(
                    x => x.quarter_1.toString().toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                        x.quarter_2.toString().toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                        x.quarter_3.toString().toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                        x.quarter_4.toString().toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                        x.quarter_sum.toString().toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                        x.report_date.toString().toLowerCase().includes(searchTerm.value.toLowerCase())
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
    data: function () {
        return {
            reports: [],
            info: '',
            loading: false,
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
</style>
