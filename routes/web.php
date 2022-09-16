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


//Route::group(['prefix' => 'auth'], function(){
//    Route::get('login', function () { return view('auth.login'); })->name('auth.login.form');
//    Route::post('login','Auth\LoginController@login')->name('auth.login');
//    Route::get('register', 'Auth\RegisterController@form')->name('auth.registration.form');
//    Route::post('register', 'Auth\RegisterController@registration')->name('auth.registration');
//    Route::post('register-validator','Auth\RegisterController@registrationValidator')->name('register_validator');
//    Route::get('logout','Auth\LoginController@logout')->name('users.logout');
//});

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
    Route::get('/login/{login}/{password}', 'Users\UsersController@login');
    Route::get('/logout', 'Users\UsersController@logout')->name('logout');
    Route::get('login_view', function () { return view('auth.login'); })->name('auth');

});






Route::group([
    'prefix'=>'mail',
    'as'=>'mail.'
],function (){
    Route::post('/send', 'Mail\MailController@send')->middleware('isAuth');
});















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
