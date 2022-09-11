<template>

    <button class="btn  btn-primary mb-3" @click="openModal()">Создать отчет</button>
    <div class="modal" tabindex="1" role="dialog" :style="{display:isOpenModal?'block':'none'}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Создание отчета</h5>
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
                        <h3>Планируемая выручка</h3>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="price">Планируемая выручка 1 первый квартал</label>
                                    <input type="number" min="0" class="form-control" id="price" v-model="quarter_1"
                                            placeholder="1 первый квартал">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="price">Планируемая выручка 2 первый квартал</label>
                                    <input type="number" min="0" class="form-control" id="price" v-model="quarter_2"
                                           placeholder="2 первый квартал">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="price">Планируемая выручка 3 первый квартал</label>
                                    <input type="number" min="0" class="form-control" id="price" v-model="quarter_3"
                                           placeholder="3 первый квартал">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="price">Планируемая выручка 4 первый квартал</label>
                                    <input type="number" min="0" class="form-control" id="price" v-model="quarter_4"
                                           placeholder="4 первый квартал">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="price">Фактическая выручка</label>
                                    <input type="number" min="0" class="form-control" id="price" v-model="actual_revenue"
                                           placeholder="Фактическая выручка">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="price">Текущая контрактация</label>
                                    <input type="number" min="0" class="form-control" id="price" v-model="current_contracting"
                                           placeholder="Текущая контрактация">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label >Дата отчета</label>
                                    <input type="date" min="0" class="form-control"  v-model="report_date"
                                           placeholder="Дата отчета">
                                </div>
                            </div>

                            <div class="col-6">

                            </div>
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

export default {
    name: "reportCreate",
    components: {
        createToaster,
        inputSelect
    },
    data() {
        return {
            isOpenModal: false,
            loading: false,

            quarter_4: 0, // 4 квартал
            quarter_3: 0, // 3 квартал
            quarter_2: 0, // 2 квартал
            quarter_1: 0, // 1 квартал
            actual_revenue: 0, //Фактическая выручка
            current_contracting: 0, //Текущая контрактация

            report_date: '',
        }
    },
    props: {
        user_id: {
            type: Number
        },
        contract_id: {
            type: Number
        },
    },
    mounted() {

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
                this.quarter_4 = 0;
                this.quarter_3 = 0;
                this.quarter_2 = 0;
                this.quarter_1 = 0;
                this.actual_revenue = 0
                this.current_contracting = 0
        },


        // создание отчета
        create() {

            const toaster = createToaster({position: 'top-right'});

                this.loading = true
                let data = {
                    user_id: this.user_id,
                    contract_id: this.contract_id,
                    quarter_1: this.quarter_1,
                    quarter_2: this.quarter_2,
                    quarter_3: this.quarter_3,
                    quarter_4: this.quarter_4,
                    actual_revenue: this.actual_revenue,
                    current_contracting: this.current_contracting,
                    report_date: this.report_date
                }


                axios.post(`../../contract/reportCreate`, data)
                    .then(response => {
                        this.info = response;
                        if (response.data == 1) {
                            this.loading = false
                            this.closeAllModal()
                            this.$parent.$refs.reportlist.doSearch(true)
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
</script>

<style scoped>
</style>
