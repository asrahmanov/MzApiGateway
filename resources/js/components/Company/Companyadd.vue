<template>
    <div class="mb-5">
    <button type="submit" class="btn btn-primary mr-2" @click="viewForm = true " v-if="!viewForm">Добавить компанию</button>
    <button type="submit" class="btn btn-light mr-2" @click="viewForm = false" v-else>Отменить</button>
    </div>
    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div class="alert alert-fill-success" role="alert" v-if="success">
        Успешно
    </div>

    <div class="alert alert-fill-danger" role="alert" v-if="error!=''">
        {{ error }}!
    </div>
    <div v-if="viewForm">

        <div class="form-group">

            <label for="company_name">Наименование компании</label>
            <input type="text" class="form-control" id="company_name" v-model="name" autocomplete="off"
                   placeholder="Наименование компании"/>
        </div>
        <div class="form-group">
            <label for="inn">ИНН</label>
            <input type="text" class="form-control" id="inn" placeholder="ИНН" v-model="inn">
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <input type="text" class="form-control" id="phone" autocomplete="off" placeholder="Телефон" v-model="phone">
        </div>

        <div class="form-group">
            <label for="phone">Описание</label>
            <textarea class="form-control" id="description" autocomplete="off" placeholder="Описание"
                      v-model="description"></textarea>
        </div>

        <button type="submit" class="btn btn-success mr-2" @click="Save()" v-if="!loading">Создать</button>

    </div>

</template>

<script>
import inputSelect from "../other/selects/inputSelect";
import axios from 'axios';
import { createToaster } from "@meforma/vue-toaster";
export default {
    components: {
        inputSelect,
        createToaster
    },
    name: "Companyadd",
    props: [
        'company_id',
    ],
    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            //
            success: false,
            error: '',
            loading: false,
            viewForm: false,

            //  Данные формы
            info: '',
            name: '',
            phone: '',
            inn: '',
            description: '',
        }
    },
    mounted() {

    },
    methods: {

        Save() {

            const toaster = createToaster({ position: 'top-right' });
            if (this.name == '') {
                toaster.error(`Заполните поле Наименование`);
            } else if (this.inn.length != 10) {
                toaster.error(`ИНН должен содержать 10 цифр`);
            } else if (this.phone == '') {
                toaster.error(`Заполните поле телефон`);
            } else {
                this.loading = true
                let data = {
                    parent_id: this.company_id,
                    name: this.name,
                    phone: this.phone,
                    inn: this.inn,
                    description: this.description
                }
            axios.post(`../companies/add`, data)
                .then(response => {
                    if (response.data.result) {

                        this.loading = false
                        this.viewForm = false
                        this.$parent.$refs.Companylist.loadInfo()
                        this.$parent.$refs.userAdd.loadCompany()
                        toaster.success(`Успешно`);

                        this.company_id = '';
                        this.name = '';
                        this.phone = '';
                        this.inn = '';
                        this.description = '';


                    } else {
                        toaster.error(`Ошибка сервера`);
                        this.loading = false

                    }
                })

            }
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
