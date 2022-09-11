import {createApp, defineAsyncComponent} from 'vue'
import mitt from 'mitt';

const emitter = mitt();
window.eventBus = emitter

import Companyinfo from './components/Company/Companyinfo';
import Companylist from './components/Company/Companylist';
import Companyadd from './components/Company/Companyadd';

import Useradd from './components/User/Useradd';
import Userlist from './components/User/Userlist';
import UserView from './components/User/UserView';
import UserUpdate from './components/User/UserUpdate'
import UserCreate from './components/User/UserCreate'
import UserOne from './components/User/UserOne'
import UserCreatedzo from './components/User/UserCreatedzo'
import UserDzo from './components/User/UserDzo'

import counterparties from './components/Manuals/Counterparties/counterparties';
import modalcreate from './components/Manuals/Counterparties/modalcreate';
import counterpartiescreate from './components/Manuals/Counterparties/counterpartiesСreate';

import projectmodalcreate from './components/Projects/projectmodalcreate';
import projects from './components/Projects/projects';
import product from './components/Projects/product';
import projectEdit from './components/Projects/projectEdit';
import projectStage from './components/Projects/projectStage';
import reportCreate from './components/Projects/reportCreate';
import reportList from './components/Projects/reportList';
import projectReport from './components/Projects/projectReport';
import projectReportDzo from "./components/Projects/projectReportDzo";
import projectReportDebt from "./components/Projects/projectReportDebt";

import contractmodalcreate from "./components/Contracts/contractmodalcreate";
import contracts from './components/Contracts/contracts';
import contractProduct from './components/Contracts/product';
import contractEdit from './components/Contracts/contractEdit';
import contractStage from './components/Contracts/contractStage';
import contractReportCreate from './components/Contracts/reportCreate';
import contractReportList from './components/Contracts/reportList';
import contractReport from './components/Contracts/contractReport';

import addEvent from './components/Security/addEvent';
import ViewEvent from './components/Security/ViewEvent';
import barChart from './components/Сhart/barChart'
import chart from './components/Сhart/chart';
import chartContracts from "./components/Сhart/chartContracts";
import donut from './components/Сhart/donut'
import donutContracts from "./components/Сhart/donutContracts";
import VueApexCharts from "vue3-apexcharts"
import importExel from "./components/other/import/importExel";
import productList from "./components/Projects/productList";
import productsReport from "./components/Projects/productsReport";

import dashBoardCurrent from './components/Сhart/dashBoard/dashBoardCurrent';
import dashBoardSales from './components/Сhart/dashBoard/dashBoardSales';
import dashBoardMovement from './components/Сhart/dashBoard/dashBoardMovement';

import redButton from "./components/Projects/redButton";
import hr_report from "./components/Hr/hr_report";
import hr_report_covid from "./components/Hr/hr_report_covid";
import hr_report_covid_view from "./components/Hr/hr_report_covid_view";
import hr_report_view from "./components/Hr/hr_report_view";
import input_date from "./components/Hr/input_date";

import add_report from "./components/Production/add_report";
import report_view from "./components/Production/report_view";
import productDashBoard from "./components/Production/dashBoard";



import interviewView from "./components/Interview/Worksheets/view";
import interviewViewOne from "./components/Interview/Worksheets/one";
import interviewViewAll from "./components/Interview/Worksheets/viewall";
import interviewFormAll from "./components/Interview/form/interview-form-all";
import interviewFormViewOne from "./components/Interview/form/interview-form-view-one";
import interviewFormCreateOne from "./components/Interview/form/interview-form-create-one.vue";
import worksheet from "./components/Interview/Worksheets/worksheet";

import dataAggregationDashboardOne from "./components/dataAggregation/dashBoardOne";
import dataAggregationDashboardTwo from "./components/dataAggregation/dashBoardTwo";


const app = createApp({
    components: {
        Companyinfo,
        Companylist,
        Companyadd,
// Пользователи
        Useradd,
        Userlist,
        UserUpdate,
        UserCreate,
        UserOne,
        UserCreatedzo,
        UserDzo,


        counterparties,
        modalcreate,
        projects,
        projectmodalcreate,
        projectEdit,
        projectStage,
        reportCreate,
        reportList,
        projectReport,
        counterpartiescreate,

        // Безопастность
        addEvent,
        ViewEvent,

        barChart,

        chart,
        chartContracts,
        donut,
        donutContracts,
        product,
        projectReportDzo,
        projectReportDebt,

        contractmodalcreate,
        contracts,
        contractProduct,
        contractEdit,
        contractStage,
        contractReportCreate,
        contractReportList,
        contractReport,
        importExel,
        productList,
        productsReport,
        dashBoardCurrent,
        dashBoardSales,
        dashBoardMovement,
        redButton,

        hr_report,
        hr_report_covid,
        hr_report_covid_view,
        hr_report_view,
        input_date,
        UserView,

        // Production
        add_report,
        report_view,
        productDashBoard,

        interviewView,
        interviewViewOne,
        interviewViewAll,


        interviewFormAll,
        interviewFormViewOne,
        interviewFormCreateOne,
        worksheet,
        dataAggregationDashboardOne,
        dataAggregationDashboardTwo




    }
},);
app.config.devtools = true;
app.use(VueApexCharts);
app.mount('#app2');

require('./bootstrap');
