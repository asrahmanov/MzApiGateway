<template>
    <apexchart width="100%"   height="400px" type="donut" :options="options" :series="series"></apexchart>
</template>

<script>
import axios from "axios";

export default {
    name: "donut",
    props: [
        'user_id'
    ],
    data() {
        return {

            Report: [],
            company_id: 0,
            url : this.user_id == '1' ? "../project/getProjectAll" : `../project/getProjectByUser/${this.user_id}`,
            ReportStored: [],
            options : {
                chart: {
                    id: 'vuechart-report'
                },
                xaxis: {
                    categories: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан']
                },
                labels: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан'],
            },
            series: [],

        }
    },

    methods: {
      async  load() {
          await  axios.get(this.url)
                .then(response => {
                    for (let key in response.data) {
                        this.ReportStored.push(response.data[key]);
                    }
                })
        },

        filter(company_id, max_date) {
            let Report = [0,0,0,0,0]
            this.series = []
            let ReportStoredFilter = this.ReportStored.filter(p => {
                let result = true
                if(parseInt(p.company_id) !== parseInt(company_id)){
                    result = false
                }
                if(parseInt(company_id) === 0){
                    result = true
                }
                let dateItem = new Date(p.created_at)
                let dateCompare = new Date(max_date)
                dateItem.setHours(1,0,0,0)
                dateCompare.setHours(0,0,0,0)
                if(dateItem > dateCompare && max_date !==''){
                    result = false
                }
                return result
                //return  parseInt(company_id) === 0 || parseInt(p.company_id) === parseInt(company_id)
            })

            ReportStoredFilter.forEach(proj => {
                Report[proj.stage_id - 1] += parseInt(proj.price)
            })

            Report.forEach(el => {
                this.series.push(el)
            })
        }
    },

    mounted() {
        this.load().then(() => {
            this.filter(this.company_id)
        })

    }

}
</script>

<style scoped>

</style>
