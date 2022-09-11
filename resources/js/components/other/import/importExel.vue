<template>
    <div class="v-import">
        <div class="v-import-input">
            <div class="col-md-4" style="display: none;">
                <p>Дата загрузки</p>
                <input type="date" min="0" class="form-control" v-model="postData.upload_date"
                       placeholder="Дата загрузки">
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 v-mt">
                <p>Файл</p>
                <input type="file" ref="file" class="v-file-input" @change="changeFile()">

                <div v-if="loading != ''" class="alert alert-secondary  mt-3" role="alert">
                    Загрузка
                </div>

                <div v-if="loadingReportText != ''" class="alert alert-secondary mt-3" role="alert">
                    {{ loadingReportText }}
                </div>




            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 v-mt">
                <button @click="startImport" class="btn btn-primary">Загрузить</button>
                <button @click="startReport" class="btn btn-secondary ml-2 mb-1 mb-md-0">Данные не изменились</button>
            </div>
        </div>
        <p v-if="load">Загружено успешно</p>
        <div v-if="errors.length" v-for="error in errors" class="v-error">
            <p>Cтрока: {{ error.row }}</p>
            <p>{{ error.text }}</p>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "importExel",
    props: {
        user_id: {
            type: Number
        },
    },
    data() {
        return {
            postData: {
                user_id: this.user_id,
                file: null,
                upload_date: new Date().toISOString().slice(0, 10)
            },
            loading: false,
            load: false,
            errors: [],
            loadingReportText: '',
        }
    },
    methods: {
        changeFile() {
            this.postData.file = this.$refs.file.files[0]
        },
        startReport() {
            this.loadingReportText = 'Данные формирутеся не закрывайте страницу...'
            setTimeout(() => {
                this.loadingReportText = ''
            }, 2000)


        },

        async startImport() {
            this.loading = true
            this.load = false
            this.errors = []
            let formData = new FormData();
            formData.append('user_id', this.postData.user_id);
            formData.append('upload_date', this.postData.upload_date);
            formData.append('file', this.postData.file);
            console.log('Начинаем загрузку', formData)
            await axios.post(`../../import-from-excel`, formData)
                .then(response => {
                    console.log(response.data)
                    if (response.data.errors && response.data.errors.length) {
                        this.errors = response.data.errors
                    } else {
                        this.load = true
                    }
                })
            this.loading = false
        }
    }
}
</script>

<style scoped>
.v-import {
    /*display: flex;*/
}

.v-import-input {
    margin-bottom: 20px;
}

.v-error {
    margin-top: 20px;
    color: red;
}

.v-mt {
    margin-top: 10px;
}
</style>
