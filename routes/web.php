<?php
use Illuminate\Support\Facades\Route;

//отключение чувствительности к регистру
use Illuminate\Routing\Route as IlluminateRoute;
use App\CaseInsensitiveUriValidator;
use Illuminate\Routing\Matching\UriValidator;


$validators = IlluminateRoute::getValidators();
$validators[] = new CaseInsensitiveUriValidator;
IlluminateRoute::$validators = array_filter($validators, function($validator) {
    return get_class($validator) != UriValidator::class;
});
//отключение чувствительности к регистру//


Route::group(['prefix' => 'auth'], function(){
    Route::get('login', function () { return view('auth.login'); })->name('auth.login.form');
    Route::post('login','Auth\LoginController@login')->name('auth.login');
    Route::get('register', 'Auth\RegisterController@form')->name('auth.registration.form');
    Route::post('register', 'Auth\RegisterController@registration')->name('auth.registration');
    Route::post('register-validator','Auth\RegisterController@registrationValidator')->name('register_validator');
    Route::get('logout','Auth\LoginController@logout')->name('auth.logout');
});

Route::group([
    'prefix'=>'companies',
    'as'=>'companies.'
],function (){
    Route::get('/', 'Companies\CompaniesController@index')->name('all')->middleware('isAuth');
    Route::get('/{id}', 'Companies\CompaniesController@show')->name('one')->middleware('isAuth');
    Route::post('/parentList', 'Companies\CompaniesController@parentList')->name('parentCompany')->middleware('isAuth');
    Route::get('/get/{id}', 'Companies\CompaniesController@get')->name('GetCompany')->middleware('isAuth');
    Route::put('/save', 'Companies\CompaniesController@update')->name('UpdateCompany')->middleware('isAuth');
    Route::post('/add', 'Companies\CompaniesController@insert')->name('InsertCompany')->middleware('isAuth');
    Route::get('/getByUser/{user_id}', 'Companies\CompaniesController@getByUser')->middleware('isAuth');
    Route::get('/getAllDzo', 'Companies\CompaniesController@getAllDzo')->middleware('isAuth');
});



Route::group([
    'prefix'=>'users',
    'as'=>'users.'
],function (){
    Route::get('/test', 'Users\UsersController@createView')->name('createView')->middleware('isAuth');
    Route::get('/createUser', 'Users\UsersController@createViewDzo')->name('createUser')->middleware('isAuth');
    Route::get('/', 'Users\UsersController@userView')->name('view')->middleware('isAuth');
    Route::get('/dzo', 'Users\UsersController@userViewDzo')->name('viewDzo')->middleware('isAuth');
    Route::get('/info/{id}', 'Users\UsersController@userInfo')->name('info')->middleware('isAuth');
    Route::get('/infoOne/{id}', 'Users\UsersController@userInfoOne')->name('one')->middleware('isAuth');
    Route::get('/{id}', 'Users\UsersController@getOne')->middleware('isAuth');
    Route::get('/getRole', 'Users\UsersController@getRole')->middleware('isAuth');
    Route::post('/add', 'Users\UsersController@insert')->name('InsertUser')->middleware('isAuth');
    Route::post('/list', 'Users\UsersController@list')->name('parentUsers')->middleware('isAuth');

    Route::post('/dzo', 'Users\UsersController@listDzo')->name('userDzo')->middleware('isAuth');
    Route::post('/edit', 'Users\UsersController@edit')->middleware('isAuth');


});


Route::group([
    'prefix'=>'manuals',
    'as'=>'manuals.'
],function (){
    Route::get('/counterparties/all', 'Manuals\Counterparties\CounterpartiesController@index')->name('counterparties.all')->middleware('isAuth');
    Route::get('/counterparties/list', 'Manuals\Counterparties\CounterpartiesController@list')->name('counterparties.list')->middleware('isAuth');
    Route::get('/counterparties/create', 'Manuals\Counterparties\CounterpartiesController@create')->name('counterparties.create')->middleware('isAuth');
    Route::post('/counterparties/checkinn/{inn}', 'Manuals\Counterparties\CounterpartiesController@checkinn')->middleware('isAuth');
    Route::post('/counterparties/add', 'Manuals\Counterparties\CounterpartiesController@insert')->middleware('isAuth');
});








Route::group([
    'prefix'=>'hr-report-types',
    'as'=>'hr-report-types.'
],function (){
    Route::get('/', 'Hr\HrReportTypeController@list')->name('all')->middleware('isAuth');
});

Route::group([
    'prefix'=>'hr-report',
    'as'=>'hr-report.'
],function (){
    Route::post('/', 'Hr\HrReportController@insert')->middleware('isAuth');


    Route::get('/index', 'Hr\HrReportController@index')->name('index')->middleware('isAuth');
    Route::get('/report_view', 'Hr\HrReportController@report_view')->name('view')->middleware('isAuth');
    Route::get('/report_covid', 'Hr\HrReportController@report_covid')->name('report_covid')->middleware('isAuth');
    Route::get('/get-by-report-day/{report_day}/{company_id}', 'Hr\HrReportController@getReportByReportDayandCopmanyID')->middleware('isAuth');
    Route::get('/ImportExel/{report_day}/{company_id}', 'Hr\HrReportController@ImportExel')->middleware('isAuth');
});

Route::group([
    'prefix'=>'hr-report-covid',
    'as'=>'hr-report-covid.'
],function (){
    Route::post('/', 'Hr\HrReportCovidController@insert')->middleware('isAuth');

    Route::get('/addReport', 'Hr\HrReportCovidController@addReport')->name('covid')->middleware('isAuth');
    Route::get('/viewReport', 'Hr\HrReportCovidController@report_covid')->name('report_covid')->middleware('isAuth');
    Route::get('/index', 'Hr\HrReportCovidController@index')->name('index')->middleware('isAuth');
    Route::get('/get-by-report-day/{report_day}/{company_id}', 'Hr\HrReportCovidController@getReportByReportDayandCopmanyID')->middleware('isAuth');
    Route::get('/ImportExel/{report_day}/{company_id}', 'Hr\HrReportCovidController@ImportExel')->middleware('isAuth');
});

Route::group([
    'prefix'=>'production-report',
    'as'=>'production-report.'
],function (){
    Route::post('/', 'Production\ProductionReportController@insert')->middleware('isAuth');

    Route::get('/addReport', 'Production\ProductionReportController@addReport')->name('addReport')->middleware('isAuth');
    Route::get('/index', 'Production\ProductionReportController@index')->name('report_view')->middleware('isAuth');
    Route::get('/dash-board', 'Production\ProductionReportController@dashBoard')->name('dashBoard')->middleware('isAuth');
    Route::get('/get-by-report-day/{report_day}/{company_id}', 'Production\ProductionReportController@getReportByReportDayandCopmanyID')->middleware('isAuth');
    Route::get('/get-by-report-between/{report_day_from}/{report_day_to}/{company_id}', 'Production\ProductionReportController@getByReportBetween')->middleware('isAuth');
    Route::get('/ImportExel/{report_day}/{company_id}', 'Production\ProductionReportController@ImportExel')->middleware('isAuth');
});

Route::group([
    'prefix'=>'interview-worksheets',
    'as'=>'interview-worksheets.'
],function (){
    Route::post('/', 'Interview\InterviewController@insert')->middleware('isAuth');
    Route::post('/edit', 'Interview\InterviewController@edit')->middleware('isAuth');
    Route::post('/create', 'Interview\InterviewController@createWorkSheetsforUser')->middleware('isAuth');
    Route::get('/view', 'Interview\InterviewController@viewReport')->name('view')->middleware('isAuth');
    Route::get('/all', 'Interview\InterviewController@viewReportAll')->name('all')->middleware('isAuth');
    Route::get('/', 'Interview\InterviewController@getReportAll')->middleware('isAuth');
    Route::get('/view_close', 'Interview\InterviewController@viewClose')->name('view_close')->middleware('isAuth');
    Route::get('/views/{id}', 'Interview\InterviewController@viewReportById')->middleware('isAuth');
    Route::get('/view/{id}', 'Interview\InterviewController@viewOne')->middleware('isAuth');
    Route::get('/getAll', 'Interview\InterviewController@addReport')->name('addReport')->middleware('isAuth');
    Route::get('/get-by-userId/{user_id}', 'Interview\InterviewController@getByUserId')->middleware('isAuth');
    Route::get('/get-by-id/{user_id}', 'Interview\InterviewController@getById')->middleware('isAuth');
    Route::get('/createWorkSheets', 'Interview\InterviewController@createWorkSheets')->name('createWorkSheets')->middleware('isAuth');
});


Route::group([
    'prefix'=>'data-aggregation',
    'as'=>'data-aggregation.'
],function (){
    Route::get('/dashboardOne', 'DataAggregation\ViewController@dashboardOne')->name('dashboardOne')->middleware('isAuth');
    Route::get('/dashboardTwo', 'DataAggregation\ViewController@dashboardTwo')->name('dashboardTwo')->middleware('isAuth');
});


Route::group([
    'prefix'=>'mail',
    'as'=>'mail.'
],function (){
    Route::post('/send', 'Mail\MailController@send')->middleware('isAuth');
});


Route::group([
    'prefix'=>'data-aggregation-contract-and-fact',
    'as'=>'data-aggregation-contract-and-fact.'
],function (){
    Route::post('/', 'DataAggregation\ContractAndFactController@insert')->middleware('isAuth');
    Route::post('/edit', 'DataAggregation\ContractAndFactController@edit')->middleware('isAuth');
    Route::get('/getAll', 'DataAggregation\ContractAndFactController@list')->middleware('isAuth');
    Route::get('/get-by-id/{id}', 'DataAggregation\ContractAndFactController@getById')->middleware('isAuth');
    Route::get('/get-by-name/{company_name}', 'DataAggregation\ContractAndFactController@getByCompanyName')->middleware('isAuth');
});

Route::group([
    'prefix'=>'data-aggregation-plan-contract',
    'as'=>'data-aggregation-plan-contract.'
],function (){
    Route::post('/', 'DataAggregation\PlanContractController@insert')->middleware('isAuth');
    Route::post('/edit', 'DataAggregation\PlanContractController@edit')->middleware('isAuth');
    Route::get('/getAll', 'DataAggregation\PlanContractController@list')->middleware('isAuth');
    Route::get('/get-by-id/{id}', 'DataAggregation\PlanContractController@getById')->middleware('isAuth');
    Route::get('/get-by-name/{company_name}', 'DataAggregation\PlanContractController@getByCompanyName')->middleware('isAuth');
    Route::get('/getGroup', 'DataAggregation\PlanContractController@getGroup')->middleware('isAuth');
});


Route::group([
    'prefix'=>'data-aggregation-budget',
    'as'=>'data-aggregation-budget.'
],function (){
    Route::post('/', 'DataAggregation\BudgetController@insert')->middleware('isAuth');
    Route::post('/edit', 'DataAggregation\BudgetController@edit')->middleware('isAuth');
    Route::get('/getAll', 'DataAggregation\BudgetController@list')->middleware('isAuth');
    Route::get('/get-by-id/{id}', 'DataAggregation\BudgetController@getById')->middleware('isAuth');
});

Route::group([
    'prefix'=>'data-aggregation-operational-plan',
    'as'=>'data-aggregation-operational-plan.'
],function (){
    Route::post('/', 'DataAggregation\OperationalPlanController@insert')->middleware('isAuth');
    Route::post('/edit', 'DataAggregation\OperationalPlanController@edit')->middleware('isAuth');
    Route::get('/getAll', 'DataAggregation\OperationalPlanController@list')->middleware('isAuth');
    Route::get('/get-by-id/{id}', 'DataAggregation\OperationalPlanController@getById')->middleware('isAuth');
    Route::get('/get-by-name/{company_name}', 'DataAggregation\OperationalPlanController@getByCompanyName')->middleware('isAuth');
});



Route::group([
    'prefix'=>'data-aggregation-expected-revenue',
    'as'=>'data-aggregation-expected-revenue.'
],function (){
    Route::post('/', 'DataAggregation\ExpectedRevenueController@insert')->middleware('isAuth');
    Route::post('/edit', 'DataAggregation\ExpectedRevenueController@edit')->middleware('isAuth');
    Route::get('/getAll', 'DataAggregation\ExpectedRevenueController@list')->name('addReport')->middleware('isAuth');
    Route::get('/get-by-id/{id}', 'DataAggregation\ExpectedRevenueController@getById')->middleware('isAuth');
    Route::get('/get-by-name/{company_name}', 'DataAggregation\ExpectedRevenueController@getByCompanyName')->middleware('isAuth');
    Route::get('/getGroup', 'DataAggregation\ExpectedRevenueController@getGroup')->middleware('isAuth');
});




Route::group([
    'prefix'=>'interview-form',
    'as'=>'interview-form.'
],function (){
    Route::get('/get-by-id/{id}', 'Interview\InterviewFormController@getById')->middleware('isAuth');
    Route::post('/', 'Interview\InterviewFormController@insert')->middleware('isAuth');
    Route::post('/edit', 'Interview\InterviewFormController@edit')->middleware('isAuth');
    Route::get('/view', 'Interview\InterviewFormController@viewReport')->name('view')->middleware('isAuth');
    Route::get('/all', 'Interview\InterviewFormController@viewReportAll')->name('all')->middleware('isAuth');
    Route::get('/create', 'Interview\InterviewFormController@createViewForm')->name('create')->middleware('isAuth');
    Route::get('/', 'Interview\InterviewFormController@getReportAll')->middleware('isAuth');
    Route::get('/view_close', 'Interview\InterviewFormController@viewClose')->name('view_close')->middleware('isAuth');
    Route::get('/views/{id}', 'Interview\InterviewFormController@viewReportById')->middleware('isAuth');
    Route::get('/view/{id}', 'Interview\InterviewFormController@viewOne')->middleware('isAuth');
    Route::delete('/{id}', 'Interview\InterviewFormController@delete')->middleware('isAuth');
    Route::get('/getAll', 'Interview\InterviewFormController@addReport')->name('addReport')->middleware('isAuth');
    Route::get('/get-by-userId/{user_id}', 'Interview\InterviewFormController@getByUserId')->middleware('isAuth');
});




Route::group([
    'prefix'=>'security-report',
    'as'=>'security-report.'
],function (){
    Route::get('/create', 'Security\SecurityController@index')->name('index')->middleware('isAuth');
    Route::get('/viewReport', 'Security\SecurityController@view')->name('viewReport')->middleware('isAuth');
    Route::get('/get-by-report-day/{event_day}/{company_id}', 'Security\SecurityController@get_by_report_day')->middleware('isAuth');
    Route::post('/', 'Security\SecurityController@insert')->middleware('isAuth');

});

Route::group([
    'prefix'=>'security-event',
    'as'=>'security-event.'
],function (){
    Route::get('/', 'Security\SecurityEventController@list')->name('getEvent')->middleware('isAuth');
});





Route::group([
    'prefix'=>'project',
    'as'=>'project.'
],function (){
    Route::get('/', 'Project\ProjectController@index')->name('all')->middleware('isAuth');
    Route::get('/product', 'Project\ProjectController@product')->name('product')->middleware('isAuth');
    Route::get('/productList', 'Project\ProjectController@productList')->name('productList')->middleware('isAuth');
    Route::get('/productReport/{name}', 'Project\ProjectController@productReport')->name('productReport')->middleware('isAuth');
    Route::get('/info/{id}', 'Project\ProjectController@show')->name('show')->middleware('isAuth');
    Route::get('/graph', 'Project\ProjectController@graph')->name('graph')->middleware('isAuth');
    Route::get('/report', 'Project\ProjectController@report')->name('report')->middleware('isAuth');
    Route::get('/dashboards', 'Project\ProjectController@dashboards')->name('dashboards')->middleware('isAuth');
    Route::get('/getType', 'Project\ProjectController@getType')->middleware('isAuth');
    Route::get('/getAppointment', 'Project\ProjectController@getAppointment')->middleware('isAuth');
    Route::post('/create', 'Project\ProjectController@insert')->middleware('isAuth');
    Route::post('/reportCreate', 'Project\ProjectController@reportCreate')->middleware('isAuth');
    Route::get('/stage', 'Project\ProjectController@getStageView')->name('stage')->middleware('isAuth');
    Route::get('/status', 'Project\ProjectController@getStatusView')->name('status')->middleware('isAuth');
    Route::get('/type', 'Project\ProjectController@getTypeView')->name('type')->middleware('isAuth');
    Route::post('/update', 'Project\ProjectController@update')->middleware('isAuth');
    Route::get('/getProjectByUser/{user_id}', 'Project\ProjectController@getProjectByUser')->middleware('isAuth');
    Route::get('/getProjectAll', 'Project\ProjectController@getProjectAll')->middleware('isAuth');
    Route::get('/getProjectById/{project_id}', 'Project\ProjectController@getProjectById')->middleware('isAuth');
    Route::get('/getStatus', 'Project\ProjectController@getProjectStatus')->middleware('isAuth');
    Route::get('/getStage', 'Project\ProjectController@getStage')->middleware('isAuth');
    Route::get('/getReport/{project_id}', 'Project\ProjectController@getReport')->middleware('isAuth');
    Route::get('/getReportByUser/{user_id}', 'Project\ProjectController@getReportByUser')->middleware('isAuth');
    Route::get('/getReportAll', 'Project\ProjectController@getReportAll')->middleware('isAuth');
    Route::get('/getLastReport/{project_id}', 'Project\ProjectController@getLastReport')->middleware('isAuth');
});



Route::group([
    'prefix'=>'contract',
    'as'=>'contract.'
],function (){
    Route::get('/', 'Contract\ContractController@index')->name('all')->middleware('isAuth');
    Route::get('/product', 'Contract\ContractController@product')->name('product')->middleware('isAuth');
    Route::get('/info/{id}', 'Contract\ContractController@show')->name('show')->middleware('isAuth');
    Route::get('/graph', 'Contract\ContractController@graph')->name('graph')->middleware('isAuth');
    Route::get('/report', 'Contract\ContractController@report')->name('report')->middleware('isAuth');
    Route::get('/dashboards', 'Contract\ContractController@dashboards')->name('dashboards')->middleware('isAuth');
    Route::get('/getType', 'Contract\ContractController@getType')->middleware('isAuth');
    Route::get('/getAppointment', 'Contract\ContractController@getAppointment')->middleware('isAuth');
    Route::post('/create', 'Contract\ContractController@insert')->middleware('isAuth');
    Route::post('/reportCreate', 'Contract\ContractController@reportCreate')->middleware('isAuth');
    Route::get('/stage', 'Contract\ContractController@getStageView')->name('stage')->middleware('isAuth');
    Route::get('/status', 'Contract\ContractController@getStatusView')->name('status')->middleware('isAuth');
    Route::get('/type', 'Contract\ContractController@getTypeView')->name('type')->middleware('isAuth');
    Route::post('/update', 'Contract\ContractController@update')->middleware('isAuth');
    Route::get('/getContractByUser/{user_id}', 'Contract\ContractController@getContractByUser')->middleware('isAuth');
    Route::get('/getContractAll', 'Contract\ContractController@getContractAll')->middleware('isAuth');
    Route::get('/getContractById/{Contract_id}', 'Contract\ContractController@getContractById')->middleware('isAuth');
    Route::get('/getStatus', 'Contract\ContractController@getContractStatus')->middleware('isAuth');
    Route::get('/getStage', 'Contract\ContractController@getStage')->middleware('isAuth');
    Route::get('/getReport/{contract_id}', 'Contract\ContractController@getReport')->middleware('isAuth');
    Route::get('/getReportAll', 'Contract\ContractController@getReportAll')->middleware('isAuth');
    Route::get('/getReportByUser/{user_id}', 'Contract\ContractController@getReportByUser')->middleware('isAuth');
    Route::get('/getLastReport/{contract_id}', 'Contract\ContractController@getLastReport')->middleware('isAuth');
});


Route::match(['get','post'],'/import-from-excel','ImportFromExcelController@importProjectsAndContracts')->name('import-from-excel');


//Route::get('/', 'Companies\CompaniesController@index')->name('all')->middleware('isAuth');
//Route::get('/', function () { return view('pages.mainPage'); })->name('mainPage')->middleware('isAuth');
Route::get('/', 'Controller@main')->name('mainPage')->middleware('isAuth');



Route::get('/example', function () {
    return view('example.dashboard');
})->middleware('isAuth');

Route::group(['prefix' => 'email'], function(){
    Route::get('inbox', function () { return view('pages.email.inbox'); });
    Route::get('read', function () { return view('pages.email.read'); });
    Route::get('compose', function () { return view('pages.email.compose'); });
});

Route::group(['prefix' => 'apps'], function(){
    Route::get('chat', function () { return view('pages.apps.chat'); });
    Route::get('calendar', function () { return view('pages.apps.calendar'); });
});

Route::group(['prefix' => 'ui-components'], function(){
    Route::get('alerts', function () { return view('pages.ui-components.alerts'); });
    Route::get('badges', function () { return view('pages.ui-components.badges'); });
    Route::get('breadcrumbs', function () { return view('pages.ui-components.breadcrumbs'); });
    Route::get('buttons', function () { return view('pages.ui-components.buttons'); });
    Route::get('button-group', function () { return view('pages.ui-components.button-group'); });
    Route::get('cards', function () { return view('pages.ui-components.cards'); });
    Route::get('carousel', function () { return view('pages.ui-components.carousel'); });
    Route::get('collapse', function () { return view('pages.ui-components.collapse'); });
    Route::get('dropdowns', function () { return view('pages.ui-components.dropdowns'); });
    Route::get('list-group', function () { return view('pages.ui-components.list-group'); });
    Route::get('media-object', function () { return view('pages.ui-components.media-object'); });
    Route::get('modal', function () { return view('pages.ui-components.modal'); });
    Route::get('navs', function () { return view('pages.ui-components.navs'); });
    Route::get('navbar', function () { return view('pages.ui-components.navbar'); });
    Route::get('pagination', function () { return view('pages.ui-components.pagination'); });
    Route::get('popovers', function () { return view('pages.ui-components.popovers'); });
    Route::get('progress', function () { return view('pages.ui-components.progress'); });
    Route::get('scrollbar', function () { return view('pages.ui-components.scrollbar'); });
    Route::get('scrollspy', function () { return view('pages.ui-components.scrollspy'); });
    Route::get('spinners', function () { return view('pages.ui-components.spinners'); });
    Route::get('tabs', function () { return view('pages.ui-components.tabs'); });
    Route::get('tooltips', function () { return view('pages.ui-components.tooltips'); });
});

Route::group(['prefix' => 'advanced-ui'], function(){
    Route::get('cropper', function () { return view('pages.advanced-ui.cropper'); });
    Route::get('owl-carousel', function () { return view('pages.advanced-ui.owl-carousel'); });
    Route::get('sweet-alert', function () { return view('pages.advanced-ui.sweet-alert'); });
});

Route::group(['prefix' => 'forms'], function(){
    Route::get('basic-elements', function () { return view('pages.forms.basic-elements'); });
    Route::get('advanced-elements', function () { return view('pages.forms.advanced-elements'); });
    Route::get('editors', function () { return view('pages.forms.editors'); });
    Route::get('wizard', function () { return view('pages.forms.wizard'); });
});

Route::group(['prefix' => 'charts'], function(){
    Route::get('apex', function () { return view('pages.charts.apex'); });
    Route::get('chartjs', function () { return view('pages.charts.chartjs'); });
    Route::get('flot', function () { return view('pages.charts.flot'); });
    Route::get('morrisjs', function () { return view('pages.charts.morrisjs'); });
    Route::get('peity', function () { return view('pages.charts.peity'); });
    Route::get('sparkline', function () { return view('pages.charts.sparkline'); });
});

Route::group(['prefix' => 'tables'], function(){
    Route::get('basic-tables', function () { return view('pages.tables.basic-tables'); });
    Route::get('data-table', function () { return view('pages.tables.data-table'); });
});

Route::group(['prefix' => 'icons'], function(){
    Route::get('feather-icons', function () { return view('pages.icons.feather-icons'); });
    Route::get('flag-icons', function () { return view('pages.icons.flag-icons'); });
    Route::get('mdi-icons', function () { return view('pages.icons.mdi-icons'); });
});

Route::group(['prefix' => 'general'], function(){
    Route::get('blank-page', function () { return view('pages.general.blank-page'); });
    Route::get('faq', function () { return view('pages.general.faq'); });
    Route::get('invoice', function () { return view('pages.general.invoice'); });
    Route::get('profile', function () { return view('pages.general.profile'); });
    Route::get('pricing', function () { return view('pages.general.pricing'); });
    Route::get('timeline', function () { return view('pages.general.timeline'); });
});



Route::group(['prefix' => 'error','as'=>'error.'], function(){
    Route::get('403', function () { return view('pages.error.403'); })->name(403);
    Route::get('404', function () { return view('pages.error.404'); })->name(404);
    Route::get('500', function () { return view('pages.error.500'); })->name(500);
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/red-button', 'Project\ProjectController@redButton')->middleware('isAuth');
Route::post('/red-button', 'Project\ProjectController@deleteAll')->middleware('isAuth');


// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.error.404');
})->where('page','.*');
