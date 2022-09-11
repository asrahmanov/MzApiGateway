<template>

    <div class="col-6">
        <div class="form-group">
            <label>Выберите дату отчета</label>
            <input type="date" v-model="report_day" class="form-control">
        </div>
    </div>


    <div class="col-12">
        <hr_report_view :user_id="user_id" :report_day="report_day" :company_id="company_id" ></hr_report_view>
    </div>



</template>

<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";
import hr_report_view from "./hr_report_view";
export default {
    components: {
        createToaster,
        hr_report_view
    },
    name: "input_date",
    props: [
        'user_id'
    ],

    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            report_day: '',
            company_id: 1,
        }
    },

    mounted() {
    },
    methods: {

    },
    computed: {}

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
