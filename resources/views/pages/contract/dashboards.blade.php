@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"/>
@endpush
@section('content')
    <div id="app2">
        <h3 class="page-title" style="margin-bottom: 20px;">Отчет по контрактам </h3>
        <div class="tab-content border border-top-0 p-3" id="myTabContent">
            <div class="row">
                <div class="col-6">
                    <select id="company_filter" disabled>
                        <option value="0">Все</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company['id'] }}">{{ $company['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <label for="report_max_date" class="col-form-label">Дата по:</label>
                <div class="col-4 grid-margin"><input type="date" class="form-control datepicker" id="report_max_date"></div>
            </div>
            <div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Воронка продаж</h6>
                            <div id="apexBar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Воронка продаж 2</h6>
                            <div id="apexDonut"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/progressbar-js/progressbar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>


@endpush




@push('custom-scripts')

    <script>
        let user_id = `{{ $user['id'] }}`, bigdata = [], bigCategories = [],
            url = user_id === '1' ? "../contract/getContractAll?_=1" : `../contract/getContractByUser/${user_id}?_=1`,
            ReportStored, options, options1, apexBarChart, apexDonutChart, ReportDatesByProjId = {},
            RDurl = user_id === '1' ? "../../contract/getReportAll" : `../../contract/getReportByUser/${this.user_id}`


        async function load () {
            await axios
                .get(RDurl)
                .then((response) => {
                    response.data.forEach(rep => {
                        ReportDatesByProjId[rep.contract_id] = rep['report_date']
                    })

                })
            return await axios
                .get(url)
                .then((response) => {
                    let Report = [0,0,0,0,0]
                    ReportStored = response.data
                    response.data.forEach((proj, i, arr) => {
                        Report[proj.stage_id - 1] += parseInt(proj.price)
                        response.data[i]['report_date'] = ReportDatesByProjId[proj.id]
                    })
                    return Report
                })
        }


        async function getContractByUser() {
            return await axios
                .get(url)
                .then((response) => {
                    return response.data
                })
        }

        getContractByUser().then(data => {
            console.log('data', data)

            data.forEach(element => {
                // if(bigCategories.find(el => {
                //     if(el.stage.name == element.)
                // }))
            })
        })

        document.addEventListener("DOMContentLoaded", () => {

            load().then(data => {
                options = {
                    chart: {
                        type: 'bar',
                        height: '400',
                        parentHeightOffset: 1
                    },
                    grid: {
                        borderColor: "rgba(77, 138, 240, .1)",
                        padding: {
                            bottom: -15
                        }
                    },
                    series: [{
                        name: 'м. руб',
                        data: data
                    }],
                    xaxis: {
                        type: 'string',
                        categories: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан']
                    }
                }
                apexBarChart = new ApexCharts(document.querySelector("#apexBar"), options);
                apexBarChart.render()

                options1 = {
                    chart: {
                        height: 400,
                        type: 'donut'
                    },
                    series: data,
                    labels: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан'],
                    legend: {
                        position: 'top',
                        horizontalAlign: 'center'
                    },
                    dataLabels: {
                        enabled: true
                    }
                }
                apexDonutChart = new ApexCharts(document.querySelector("#apexDonut"), options1);
                apexDonutChart.render()

                document.getElementById('company_filter').disabled = false
            });

            function filterAndRedraw () {
                let companyFilterVal = document.getElementById('company_filter').value,
                    maxReportDate = document.getElementById('report_max_date').value
                let Report = [0,0,0,0,0]
                ReportStored.filter(p => {
                    console.log(p, p.report_date)
                    return (companyFilterVal === '0' || parseInt(p.company_id) === parseInt(companyFilterVal)) &&
                        (maxReportDate >= p.report_date || maxReportDate === '')
                }).forEach(proj => {
                    Report[proj.stage_id - 1] += parseInt(proj.price)
                })
                options = {
                    chart: {
                        type: 'bar',
                        height: '400',
                        parentHeightOffset: 1
                    },
                    grid: {
                        borderColor: "rgba(77, 138, 240, .1)",
                        padding: {
                            bottom: -15
                        }
                    },
                    series: [{
                        name: 'м. руб',
                        data: Report
                    }],
                    xaxis: {
                        type: 'string',
                        categories: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан']
                    }
                }
                apexBarChart.options = options
                apexBarChart.destroy()
                apexBarChart = new ApexCharts(document.querySelector("#apexBar"), options);
                apexBarChart.render()
                options1 = {
                    chart: {
                        height: 400,
                        type: 'donut'
                    },
                    series: Report,
                    labels: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан'],
                    legend: {
                        position: 'top',
                        horizontalAlign: 'center'
                    },
                    dataLabels: {
                        enabled: true
                    }
                }
                apexDonutChart.options = options1
                apexDonutChart.destroy()
                apexDonutChart = new ApexCharts(document.querySelector("#apexDonut"), options1);
                apexDonutChart.render()
            }

            document.getElementById('company_filter').addEventListener("change", filterAndRedraw)
            document.getElementById('report_max_date').addEventListener("change", filterAndRedraw)
            // document.getElementById('company_filter').addEventListener("change", e => {
            //     let companyFilterVal = document.getElementById('company_filter').value
            //     let Report = [0,0,0,0,0]
            //     ReportStored.filter(p => {
            //         return companyFilterVal === '0' || parseInt(p.company_id) === parseInt(companyFilterVal)
            //     }).forEach(proj => {
            //         Report[proj.stage_id - 1] += parseInt(proj.price)
            //     })
            //     options = {
            //         chart: {
            //             type: 'bar',
            //             height: '400',
            //             parentHeightOffset: 1
            //         },
            //         grid: {
            //             borderColor: "rgba(77, 138, 240, .1)",
            //             padding: {
            //                 bottom: -15
            //             }
            //         },
            //         series: [{
            //             name: 'м. руб',
            //             data: Report
            //         }],
            //         xaxis: {
            //             type: 'string',
            //             categories: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан']
            //         }
            //     }
            //     apexBarChart.options = options
            //     apexBarChart.destroy()
            //     apexBarChart = new ApexCharts(document.querySelector("#apexBar"), options);
            //     apexBarChart.render()
            //     options1 = {
            //         chart: {
            //             height: 400,
            //             type: 'donut'
            //         },
            //         series: Report,
            //         labels: ['Подготовка предложения', 'Закупка объявлена', 'Предложение подано', 'Конкурс выигран', 'Договор подписан'],
            //         legend: {
            //             position: 'top',
            //             horizontalAlign: 'center'
            //         },
            //         dataLabels: {
            //             enabled: true
            //         }
            //     }
            //     apexDonutChart.options = options1
            //     apexDonutChart.destroy()
            //     apexDonutChart = new ApexCharts(document.querySelector("#apexDonut"), options1);
            //     apexDonutChart.render()
            // })



            // })

        });

    </script>

@endpush
