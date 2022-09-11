<template>
    <button class="btn  btn-primary mb-3" @click="openModal()">Создание проекта</button>
    <div class="modal" tabindex="1" role="dialog" :style="{display:isOpenModal?'block':'none'}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Создать проект</h5>
                    <button @click="closeAllModal()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="spinner-border text-primary" role="status" v-if="loading">
                        <span class="sr-only">Loading...</span>
                    </div>

                    <div v-else>
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">

                                    <inputSelect
                                        title="Контрагент"
                                        :options="counterparties"
                                        idField="id"
                                        nameField="name"
                                        @change="counterpartyEmit($event)"
                                    >
                                    </inputSelect>
                                </div>
                            </div>
                            <div class="-col-4">
                                <div class="form-group">
                                    <label for="code"></label><br>
                                    <counterparties></counterparties>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <inputSelect
                                title="Выберите компанию"
                                :options="companies"
                                idField="id"
                                nameField="name"
                                @change="companyEmit($event)"
                            >
                            </inputSelect>
                        </div>

                        <div class="form-group" style="display: none;">
                            <label for="code">Код проекта</label>
                            <input type="text" class="form-control" id="code" v-model="code" disabled
                                   placeholder="Код проекта">
                        </div>

                        <div class="form-group">
                            <label for="project_name">НАИМЕНОВАНИЕ</label>
                            <input type="text" class="form-control" id="project_name" v-model="name"
                                   placeholder="НАИМЕНОВАНИЕ">
                        </div>

                        <div class="row" style="display: none">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="price">Прогнозируемая выручка, МЛН.РУБ.</label>
                                    <input type="number" min="0" class="form-control" id="price" v-model="price"
                                           @input="calculationNds()" placeholder="СТОИМОСТЬ">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="price_probable">СТОИМОСТЬ, c УЧ. ВЕР-ТИ. РУБ </label>
                                    <input type="number" min="0" class="form-control" id="price_probable"
                                           v-model="price_probable"
                                           placeholder="СТОИМОСТЬ">
                                </div>
                            </div>
                        </div>


                        <div class="row" >
                            <div class="col-6" style="display: none">
                                <div class="form-group">
                                    <label for="price_nds">НДС, РУБ </label>
                                    <input type="text" class="form-control" id="price_nds" v-model="price_nds"
                                           placeholder="НДС">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="probability">Вероятность, %</label>
                                    <input type="number" class="form-control" id="probability" v-model="probability"
                                           placeholder="Вероятность" min="0" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6" style="display: none">
                                <div class="form-group">
                                    <label for="type_id">Тип проекта</label>
                                    <select name="project_type" id="type_id" class="form-control" v-model="type_id">
                                        <option value="0">Выберите тип проекта</option>
                                        <option :value="item.id" v-for="item in projectType">{{ item.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Тип направления проекта</label>
                                    <select class="form-control" v-model="appointment_id">
                                        <option value="0">Выберите тип направления проекта</option>
                                        <option :value="item.id" v-for="item in projectAppointment">{{
                                                item.name
                                            }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="probability">Наименование продукта</label>
                            <input type="text" class="form-control" v-model="product_name"
                                   placeholder="Наименование продукта">
                        </div>


                        <div class="form-group">
                            <label for="description">Описание</label>
                            <textarea class="form-control" id="description" v-model="description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="state_comment">Текущие состояние</label>
                            <textarea class="form-control" id="state_comment"
                                      v-model="state_comment"></textarea>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeAllModal()">
                        Отменить
                    </button>
                    <button type="button" class="btn btn-primary" @click="create()">Создать</button>
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";
import inputSelect from '../other/selects/inputSelect';
import counterparties from "../Manuals/Counterparties/modalcreate";

export default {
    name: "projectmodalcreate",
    components: {
        createToaster,
        inputSelect,
        counterparties
    },
    data() {
        return {
            isOpenModal: false,
            loading: false,
            stage_id: 1, // Этап проекта
            code: '', // Код проекта
            name: '', // Наименование проекта
            price_probable: 10, // СТОИМОСТЬ, c УЧ. ВЕР-ТИ. РУБ
            price: 10, // СТОИМОСТЬ, РУБ
            price_nds: 10, // НДС, РУБ
            probability: 25, // Вероятность
            type_id: 1, // Тип проекта
            status_id: '', // Статус проекта
            description: '', // Описание проекта
            state_comment: '', // Описание состоятиня


            counterparties: [], // Массив контагенов
            counterparty_id: 0, // Выбранный контагент

            projectAppointment: [], // Массив типо навправлений проектов
            appointment_id: 0, // Выбраннное направлене

            companies: [], // Массив компаний
            company_id: 0, // Выбранная компания для кого создается проект

            projectType: [], // Массив типов проектов
            product_name: '', // Наименование продокта


        }
    },
    props: {
        user_id: {
            type: Number
        },
    },
    mounted() {
        this.loadCounterparties();
        this.loadCompanyByUser();
        this.loadProjectType();
        this.loadProjectAppointment();

    },
    methods: {
        openModal() {
            this.isOpenModal = true;
        },
        closeAllModal() {
            this.isOpenModal = false;
            this.clear();
        },

        clear() {
            this.counterparty_id = 0;
            this.company_id = 0;
            this.name = '';
            this.price_probable = 0;
            this.price = 0;
            this.price_nds = 0;
            this.probability = 25;
            this.type_id = 0;
            this.state_comment = '';
            this.description = '';
            this.appointment_id = 0;
        },


        // Получение выбранного контрагента
        counterpartyEmit(id) {
            this.counterparty_id = id
        },

        // Получение выбранной компании
        companyEmit(id) {
            this.company_id = id
        },

        calculationNds() {
            return this.price_nds = Math.floor(this.price * 20 / 100)
        },


        // Получения спика компаний для данного юзера
        loadCompanyByUser() {
            axios
                .get(`../../companies/getByUser/${this.user_id}`)
                .then((response) => {
                    this.companies = response.data;
                })
        },

        // Получение спика котрагентов
        loadCounterparties() {
            axios
                .get("../../manuals/counterparties/list")
                .then((response) => {
                    this.counterparties = response.data;
                })
        },

        // Получение Типов проектов
        loadProjectType() {
            axios
                .get("../../project/getType")
                .then((response) => {
                    this.projectType = response.data;
                })
        },

        // Получение Типов проектов
        loadProjectAppointment() {
            axios
                .get("../../project/getAppointment")
                .then((response) => {
                    this.projectAppointment = response.data;
                })
        },


        // создание проекта
        create() {

            const toaster = createToaster({position: 'top-right'});
            if (this.counterparty_id == 0) {
                toaster.error(`Выберите контагента`);
            } else if (this.company_id == 0) {
                toaster.error(`Выберите компанию`);
            } else if (this.name < 3) {
                toaster.error(`Наименование проекта должно быть больше 3 символов`);
            } else if (this.probability == 0) {
                toaster.error(`Вероятность, % не может быть "0 %"`);
            } else if (this.type_id == 0) {
                toaster.error(`Выберите тип проекта"`);
            } else {
                this.loading = true
                let data = {
                    user_id: this.user_id,
                    counterparty_id: this.counterparty_id,
                    company_id: this.company_id,
                    name: this.name,
                    price_probable: this.price_probable,
                    price: this.price,
                    price_nds: this.price_nds,
                    probability: this.probability,
                    type_id: this.type_id,
                    state_comment: this.state_comment,
                    description: this.description,
                    status_id: 1,
                    stage_id: this.stage_id,
                    code: '-',
                    product_name: this.product_name,
                    appointment_id: this.appointment_id,
                }
                axios.post(`../project/create`, data)
                    .then(response => {
                        this.info = response;
                        if (response.data.id > 0) {

                            this.loading = false
                            this.closeAllModal()
                            this.$parent.$refs.projectslist.doSearch(true)

                            toaster.success(`Успешно`);
                            this.clear();


                        } else {
                            toaster.error(`Ошибка сервера`);
                            this.loading = false

                        }

                    })

            }
        }
    }
}
</script>

<style scoped>
</style>
