<template>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label>Выберите категорию </label>
                <select class="form-control" v-model="event_id" @change="type_event_id = 0">
                    <option value="0">Выберите тип события</option>
                    <option v-for="item in TypeEvents" :value="item.id" v-show="item.parent_id === 0">{{
                            item.name
                        }}
                    </option>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label>Выберите событие </label>
                <select class="form-control" v-model="type_event_id">
                    <option value="0">Выберите событие</option>
                    <option v-for="item in TypeEvents" :value="item.id" v-show="item.parent_id === event_id">
                        {{ item.name }}
                    </option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">

                <label>Дата</label>
                <input type="date" class="form-control" v-model="event_date" autocomplete="off"
                       placeholder="Дата"/>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label>Время</label>
                <input type="time" class="form-control" placeholder="Время" v-model="event_time">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Описание мероприятия</label>
        <textarea name="" id="" cols="30" rows="10" v-model="description" class="form-control"></textarea>
    </div>


    <button type="submit" class="btn btn-success mr-2" @click="Save()">Создать</button>

</template>

<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    components: {
        createToaster
    },
    name: "addEvent",
    props: [
        'company_id',
        'user_id',
    ],


    data() {
        return {
            event_date: '',
            event_time: '',
            description: '',
            event_id: 0,
            type_event_id: 0,
            company_id: 17,
            TypeEvents: [],
            loading: false,
        }
    },
    mounted() {
        this.loadEvent();
    },
    methods: {
        loadEvent() {
            this.TypeEvents = [];
            this.loading = true

            axios
                .get(`../../security-event`)
                .then((response) => {
                    this.TypeEvents = response.data;
                })
        },

        Save() {
            const toaster = createToaster({position: 'top-right'});

            if (this.event_id == 0) {
                toaster.error(`Выберите категорию события`);
            } else if (this.type_event_id == 0) {
                toaster.error(`Выберите событие`);
            } else if (this.event_date == '') {
                toaster.error(`Заполните дату`);
            } else if (this.event_time == '') {
                toaster.error(`Заполните время`);
            } else if (this.description == '') {
                toaster.error(`Заполните описание`);
            } else {


                let data = {
                    event_id: this.type_event_id,
                    event_date: this.event_date,
                    event_time: this.event_time,
                    description: this.description,
                    company_id: this.company_id,
                    user_id: this.user_id,
                }
                axios.post(`../../security-report`, data)
                    .then(response => {
                        if (response.data.id > 0) {
                            toaster.success(`Успешно`);
                            this.loading = false
                            this.event_id = 0
                            this.type_event_id = 0
                            this.event_date = ''
                            this.event_time = ''
                            this.description = ''
                        } else {
                            toaster.error('Ошибка сервера');
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
