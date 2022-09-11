<template>

    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div class="alert alert-fill-success" role="alert" v-if="success">
        Успешно
    </div>

    <div class="alert alert-fill-danger" role="alert" v-if="error">
       Ошибка!
    </div>
    <div v-if="!loading">

        <div class="form-group">

            <label for="company_name">Наименование компании</label>
            <input type="text" class="form-control" id="company_name" v-model="name" autocomplete="off" placeholder="Наименование компании 2" v-if="!editForm" disabled />
            <input type="text" class="form-control" id="company_name"  v-model="name" autocomplete="off" placeholder="Наименование компании" v-else />
        </div>
        <div class="form-group">
            <label for="inn">ИНН</label>
            <input type="text" class="form-control" id="inn" placeholder="ИНН"   v-model="inn" v-if="!editForm" disabled>
            <input type="text" class="form-control" id="inn" placeholder="ИНН" v-model="inn" v-else>
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <input type="text" class="form-control" id="phone" autocomplete="off" placeholder="Телефон" v-model="phone"  v-if="!editForm" disabled>
            <input type="text" class="form-control" id="phone" autocomplete="off" placeholder="Телефон" v-model="phone" v-else>
        </div>

        <div class="form-group">
            <label for="phone">Описание</label>
            <textarea  class="form-control" id="description" autocomplete="off" placeholder="Описание" v-model="description" v-if="!editForm" disabled></textarea>
            <textarea  class="form-control" id="description" autocomplete="off" placeholder="Описание"  v-model="description" v-else ></textarea>
        </div>


        <button type="submit" class="btn btn-primary mr-2" @click="editForm = true" v-if="!editForm">Редактировать</button>
        <button type="submit" class="btn btn-success mr-2" @click="Save()" v-else >Сохранить</button>

    </div>

</template>

<script>
import inputSelect from "../other/selects/inputSelect";
import axios from 'axios';
export default {
    components:{
        inputSelect
    },
    name: "Companyinfo",
    props:[
        'company_id',
    ],
    data(){
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
        }
    },
    mounted() {
        this.loadInfo();
    },
    methods:{
        loadInfo(){
            this.loading = true
            axios.get(`../companies/get/${this.company_id}`)
                .then(response => {
                        this.name = response.data.name,
                        this.phone = response.data.phone,
                        this.inn = response.data.inn,
                        this.description = response.data.description
                        this.loading = false
                })
        },
        Save(){
            this.editForm = false
            this.loading = true
            let data = {
                id: this.company_id,
                name:this.name,
                phone:this.phone,
                inn:this.inn,
                description:this.description
            }
            axios.put(`../companies/save`,data)
                .then(response => {
                        if(response.data.result) {
                            this.success = true;
                            this.loading = false
                            setTimeout(() => {
                                this.success = false;
                            },1000)
                        } else {
                            this.error = true;
                            this.loading = false
                            setTimeout(() => {
                                this.error = false;
                            },1000)
                        }
                })
        },
    },
    computed:{

    }

}
</script>

<style scoped>
.nav-item{
cursor: pointer;
}
</style>
