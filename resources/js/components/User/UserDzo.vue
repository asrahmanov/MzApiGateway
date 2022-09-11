<template>
    <table class="vtl-table table table-hover table-bordered table-responsive-sm mt-4" v-if="!loading">
        <thead>
        <tr>
            <th class="wordspace">№</th>
            <th class="wordspace">ДЗО</th>
            <th class="wordspace">Фамилия</th>
            <th class="wordspace">Имя, Очество</th>
            <th class="wordspace">Должность</th>
            <th class="wordspace">email</th>
            <th class="wordspace">Телефон</th>
<!--            <th class="wordspace">Роль</th>-->

        </tr>
        </thead>
        <tbody>
        <tr v-for="( item, index ) in filteredUsers">
            <td class="wordspace">{{ index + 1 }}</td>
            <td class="wordspace"><span v-for="company in item.company"> {{ company.name }}<br></span></td>
            <td class="wordspace">{{ item.last_name }}</td>
            <td class="wordspace">{{ item.first_name }}</td>
            <td class="wordspace">{{ item.job_title }}</td>
            <td class="wordspace">{{ item.email }}</td>
            <td class="wordspace">{{ item.phone }}</td>
<!--            <td class="wordspace">{{ item.role.name }}</td>-->
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
    name: "UserDzo",
    props: [
        'company_id',
    ],
    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            //
            info: "",
            success: false,
            error: false,
            loading: false,

            users:[],
            filteredUsers:[],
        }
    },
    mounted() {
        this.loadInfo();
    },
    methods: {
        loadInfo() {

            this.filteredUsers = [];
            this.users = [];
            this.loading = true
            let data = {
                company_id: this.company_id
            }
            axios.post(`../../users/dzo`, data)
                .then(response => {
                    this.info = response;
                    for (let key in response.data) {
                        this.users.push(response.data[key]);
                        this.filteredUsers.push(response.data[key]);
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
