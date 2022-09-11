<template>
    <h4>Список компаний компаний</h4>
    <table class="table table-bordered" v-if="!loading">
        <thead>
        <tr>
            <th>Наименование</th>
            <th>ИНН</th>
            <th>Телефон</th>
            <th>Описание</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in filteredCompany">
            <td>{{ item.name }}</td>
            <td>{{ item.inn }}</td>
            <td>{{ item.phone }}</td>
            <td>{{ item.description }}</td>
        </tr>

        </tbody>
    </table>


    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>


</template>

<script>

import axios from 'axios';


export default {
    components: {},
    name: "Companylist",
    props: [
        'company_id',
    ],
    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            //
            success: false,
            error: false,
            loading: false,

            //  Данные формы
            info: '',
            name: '',
            phone: '',
            inn: '',
            description: '',

            company:[],
            filteredCompnay:[],
        }
    },
    mounted() {
        this.loadInfo();
    },
    methods: {
        loadInfo() {
            this.filteredCompany = [];
            this.company = [];
            this.loading = true
            let data = {
                company_id: this.company_id
            }
            axios.post(`../companies/parentList`, data)
                .then(response => {
                    for (let key in response.data) {
                        this.company.push(response.data[key]);
                        this.filteredCompany.push(response.data[key]);
                    }
                    this.loading = false
                })
        },

    },
    computed: {}

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
