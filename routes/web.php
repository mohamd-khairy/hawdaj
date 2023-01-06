<?php

use App\Models\Gallery;
use App\Models\Place;
use App\Models\Price;
use App\Services\UploadService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::get('cities', 'Dashboard\Settings\CityController@getCities')->name('cities');

Auth::routes(['verify' => false]);

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'maintanis']], function () {

    /*========================= Dashboard Routes ==============================*/
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'namespace' => 'Dashboard'], function () {

        //Global Routes
        Route::get('/', 'HomeController@index')->name('index');
        Route::get('/check-site', 'HomeController@checkSite')->name('check_site');
        Route::post('/check-site', 'HomeController@saveSite')->name('saveSite');

        Route::post('save-gallery', 'GalleryController@save')->name('SaveGallery');
        Route::post('save-img', 'GalleryController@saveImg')->name('SaveImg');
        Route::post('save-ceo', 'CeoController@save')->name('SaveCeo');
        Route::delete('delete-gallery/{id}', 'GalleryController@destroy')->name('deleteGallery');

        /************************************ places Request **********************************/
        Route::resource('places', 'PlacesController')->except('show');
        Route::post('related_places/{id}', 'PlacesController@related')->name('places.related');
        Route::post('near_stores/{id}', 'PlacesController@near')->name('places.near');
        Route::delete('destroy_selected', 'PlacesController@destroy_selected')->name('places.destroy_selected');
        Route::delete('force_destroy/{id}', 'PlacesController@force_destroy')->name('places.force_destroy');
        Route::get('restore/{id}', 'PlacesController@restore')->name('places.restore');
        Route::post('activation', 'PlacesController@activate')->name('places.active');
        Route::get('reviews/{id}', 'PlacesController@reviews')->name('places.reviews');
        Route::delete('reviews/{id}', 'PlacesController@destroy_reviews')->name('places.reviews.destroy_reviews');

        /************************************ end places     **********************************/

        /************************************ store Request **********************************/

        Route::resource('stores', 'StoreController')->except('show');
        Route::post('related_stores/{id}', 'StoreController@related')->name('stores.related');
        Route::post('activate_store', 'StoreController@activate')->name('stores.active');
        Route::delete('destroy_stores', 'StoreController@destroy_selected')->name('stores.destroy_selected');
        Route::post('near_places/{id}', 'StoreController@near')->name('stores.near');
        Route::get('restore_stores/{id}', 'StoreController@restore')->name('stores.restore');

        /************************************ end stores     **********************************/


        /************************************ zad_elgadel Request **********************************/

        Route::resource('zad_elgadels', 'ZadElgadelController')->except('show');
        Route::post('related_zad_elgadels/{id}', 'ZadElgadelController@related')->name('zad_elgadels.related');
        Route::post('activate_zad_elgadel', 'ZadElgadelController@activate')->name('zad_elgadels.active');
        Route::delete('destroy_zad_elgadels', 'ZadElgadelController@destroy_selected')->name('zad_elgadels.destroy_selected');
        Route::post('near_placess/{id}', 'ZadElgadelController@near')->name('zad_elgadels.near');
        Route::get('restore_zad_elgadels/{id}', 'ZadElgadelController@restore')->name('zad_elgadels.restore');

        /************************************ end zad_elgadels     **********************************/


        /**************************************** Users Routes *************************************/
        Route::group(['namespace' => 'Users'], function () {
            //Profile Routes
            Route::group(['prefix' => 'profile'], function () {
                Route::get('', 'ProfileController@index')->name('profile.index');
                Route::post('update', 'ProfileController@update')->name('profile.update');
                Route::group(['prefix' => 'changePassword'], function () {
                    Route::get('', 'ProfileController@changePassword')->name('profile.changePassword');
                    Route::post('update', 'ProfileController@updatePassword')->name('profile.changePassword.update');
                });
            });

            //Users && Permissions && Roles Routes
            Route::resource('users', 'UsersController')->except('show');
            Route::resource('roles', 'RolesController')->except('show');
            Route::resource('permissions', 'PermissionsController')->except('show');
            Route::get('permissions/{model}/edit', 'PermissionsController@edit')->name('permissions.edit');
            Route::put('permissions/{model}/update', 'PermissionsController@update')->name('permissions.update');
            Route::delete('permissions/{model}/destroy', 'PermissionsController@destroy')->name('permissions.destroy');
        });

        /************************************* Users Routes **************************************/

        /************************************* vendors Routes **************************************/
        Route::resource('vendors', 'VendorController')->except('show');
        /************************************* vendors Routes **************************************/

        /************************************* swalefs Routes **************************************/
        Route::resource('swalefs', 'SwalefController')->except('show');
        /************************************* swalefs Routes **************************************/

        /************************************* Caravans Routes **************************************/
        Route::resource('caravans', 'CaravanController')->except('show');
        /************************************* Caravans Routes **************************************/

        /************************************* Settings Routes **************************************/
        Route::group(['prefix' => 'setting', 'namespace' => 'Settings'], function () {
            Route::resource('categories', 'CategoryController')->except('show');
            Route::resource('features', 'FeatureController')->except('show');
            Route::resource('store-categories', 'CategoryOfStoreController')->except('show');
            Route::resource('zad_elgadel-categories', 'CategoryOfZadElgadelController')->except('show');
            Route::resource('regions', 'RegionController')->except('show');
            Route::resource('cities', 'CityController')->except('show');
            Route::resource('prices', 'PricesController')->except('show');
            Route::resource('sites', 'SiteController')->except('show');
            Route::resource('questions', 'HealthCheckController')->except('show');
            Route::resource('visit-types', 'VisitTypeController')->except('show');
            Route::resource('visit-reasons', 'VisitReasonController')->except('show');
            Route::resource('gates', 'GateController')->except('show');
            Route::resource('companies', 'CompanyController')->except('show');
            Route::resource('contract-types', 'ContractTypeController')->except('show');
            Route::get('/edit', 'SettingController@getAllSettings')->name('settings.edit');
            Route::post('/update-setting', 'SettingController@updateAllSettings')->name('settings.updateSetting');
            Route::get('get-cities', 'CityController@getCities')->name('getCities');
        });
        /************************************* Settings Routes **************************************/

        /************************************ Visits Request **********************************/
        Route::group(['namespace' => 'Visits'], function () {
            //Visits Requests
            Route::resource('visits', 'VisitRequestController')->except('show');
            Route::post('visits/{visit}/status', 'VisitRequestController@status')->name('visits.status');

            //Visit Activities
            Route::get('visits-activities/{id}/visitorData', 'VisitActivityController@visitorData')->name('visits-activities.visitorData');
            Route::post('visits-activities/storeVisitor', 'VisitActivityController@storeVisitor')->name('visits-activities.storeVisitor');
            Route::post('visits-activities/send-message', 'VisitActivityController@sendMessage')->name('visits-activities.sendMessage');
            Route::post('visits-activities/resend-link', 'VisitActivityController@resendLink')->name('visits-activities.resendLink');
            Route::put('visits-activities/{id}/action', 'VisitActivityController@takeAction')->name('visits-activities.takeAction');
            Route::post('visits-activities/storeVisit', 'VisitActivityController@storeVisit')->name('visits-activities.storeVisit');
            Route::get('visits-activities/newVisit', 'VisitActivityController@newVisit')->name('visits-activities.new');
            Route::get('visits-activities', 'VisitActivityController@index')->name('visits-activities');

            //Visitors Request
            Route::resource('visitors', 'VisitorController')->except('show');
            Route::get('visitors-by-id', 'VisitorController@getVisitorByIds');
            Route::get('get-visitor', 'VisitorController@getVisitor');
        });
        /************************************ Visits Request ********************************/

        /************************************ Materials Request **********************************/
        Route::group(['namespace' => 'Materials'], function () {
            //Materials Requests
            Route::resource('material-requests', 'MaterialRequestController')->except('show');
            Route::post('material-requests/{materialRequest}/material-status', 'MaterialRequestController@status')->name('materialRequests.status');
            Route::post('material-requests/{materialRequest}/approved-status', 'MaterialRequestController@approvedStatus')->name('materialRequests.approvedStatus');

            //Materials Activities
            Route::get('material-activities', 'MaterialActivityController@materialRequesterTasks')->name('materialActivities');
            Route::get('material-info/{id}', 'MaterialActivityController@getRequesterMaterialInfo')->name('getRequesterMaterialInfo');
            Route::put('material-action', 'MaterialActivityController@materialAction')->name('materialAction');

            //Materials Request
            Route::resource('materials', 'MaterialController')->except('show');
            Route::get('get-material', 'MaterialController@getMaterial');
            Route::get('materials-by-id', 'MaterialController@getMaterialByIds');
        });
        /************************************ Materials Request ********************************/

        /************************************ Contracts Request **********************************/
        Route::group(['namespace' => 'Contracts'], function () {
            //Contract Requests
            Route::resource('contract-requests', 'ContractRequestController')->except('show');

            //Contract Crud
            Route::get('get-supervisors/{company_id}', 'ContractController@getSupervisors')->name('getSupervisors');
            Route::get('get-contracts/{company_id}', 'ContractController@getContracts')->name('getContracts');
            Route::get('get-contract-dates/{contract_id}', 'ContractController@getContractDates')->name('getContractDates');
            Route::resource('settings/contracts', 'ContractController')->except('show');
        });
        /************************************ Contracts Request ********************************/

        /************************************ Cars Request **********************************/
        Route::group(['namespace' => 'Cars'], function () {
            //Car Requests
            Route::resource('car-requests', 'CarRequestController')->except('show');
            Route::post('car-requests/uploadExcel', 'CarRequestController@uploadExcel')->name('carRequests.uploadExcel');
            Route::post('car-requests/{carRequest}/approved-status', 'CarRequestController@approvedStatus')->name('carRequests.approvedStatus');

            //Car Request Details
            Route::get('search-car', 'CarRequestDetailsController@searchWithCar')->name('searchWithCar');
            Route::get('get-car-request/{id}', 'CarRequestDetailsController@getCarRequest')->name('getCarRequest');
            Route::post('car-request-action', 'CarRequestDetailsController@carAction')->name('carRequestAction');
        });
        /************************************ Cars Request ********************************/

        //Permissions
        Route::get('permission-meetings', 'PermissionController@index')->name('permissionMeetings');

        //Meeting info
        Route::get('meetings-invitation/{visit_id}/{visitor_id}/{secret_code}', 'MeetingController@index')->name('visitor-get-request');
        Route::get('contractor-meetings-invitation/{contract_request_id}/{contractor_id}/{secret_code}', 'MeetingController@contractRequest')->name('contractor-get-request');
        Route::get('material-meetings-invitation', 'MeetingController@materialRequest')->name('material-get-request');
        Route::post('meeting-confirmation', 'MeetingController@confirm');
        Route::post('contract-meeting-confirmation', 'MeetingController@confirmContractRequest');
        Route::get('meeting-cancel/{id}', 'MeetingController@cancel')->name('meeting-cancel');
        Route::get('contract-meeting-cancel/{id}', 'MeetingController@contractRequestcancel')->name('contract-meeting-cancel');

        //Guard
        Route::get('guard', 'GuardController@index')->name('guard.index');
        Route::get('guard/visit-scan', 'GuardController@scan')->name('guard.visit_scan');
        Route::get('guard/visit-data', 'GuardController@search')->name('guard.visit_search');
        Route::post('guard/action', 'GuardController@takeAction')->name('guard.action');
        Route::get('materialguard', 'GuardController@materialIndex')->name('materialguard.index');
        Route::get('materialguard/material-data', 'GuardController@materialSearch')->name('materialguard.material_search');
        Route::post('materialguard/action', 'GuardController@materialAction')->name('materialguard.action');
        Route::post('carguard/action', 'GuardController@carAction')->name('carguard.action');
        Route::get('carguard/car-data', 'GuardController@carSearch')->name('carguard.car_search');
        Route::get('guard/contract-visit-data', 'GuardController@ContractSearch')->name('guard.contract_visit_search');
        Route::post('guard/contractor-action', 'GuardController@takeContractorAction')->name('guard.contractVisitAction');

        //Tasks
        Route::get('tasks', 'TaskController@index')->name('tasks');
        Route::get('task/{id}', 'TaskController@getTaskInfo')->name('getTaskInfo');
        Route::put('task-action', 'TaskController@taskAction')->name('taskAction');
        Route::get('material-task/{id}', 'TaskController@getMaterialTaskInfo')->name('getMaterialTaskInfo');
        Route::put('material-task-action', 'TaskController@materialTaskAction')->name('materialTaskAction');
        Route::get('car-task/{id}', 'TaskController@getCarTaskInfo')->name('getCarTaskInfo');
        Route::put('car-task-action', 'TaskController@carTaskAction')->name('carTaskAction');
        Route::get('contractor-task/{id}', 'TaskController@getContractorTaskInfo')->name('getContractorTaskInfo');
        Route::put('contractor-task-action', 'TaskController@contractorTaskAction')->name('contractorTaskAction');

        //Notification Routes
        Route::get('notifications', 'NotificationController@index')->name('notifications');
        Route::delete('notifications/{id}/destroy', 'NotificationController@destroy')->name('notifications.destroy');
        Route::get('/update_notification', 'HomeController@updateNotification');

        //Mail Templates
        Route::resource('mail_template', 'MailTemplateController');
    });
    /*========================= Dashboard Routes ==============================*/

    /*========================= Front Routes ==============================*/
    Route::group(['as' => 'front.', 'namespace' => 'Front'], function () {
        Route::get('/', 'HomeController@index')->name('index');
        Route::post('/searchPlaces', 'HomeController@searchPlaces')->name('searchPlaces');
        Route::post('/getSubCategory', 'HomeController@getSubCategory')->name('getSubCategory');
        Route::post('/ratePlaces', 'HomeController@ratePlaces')->name('ratePlaces');
        Route::get('/place-details/{id}', 'HomeController@PlaceDetails')->name('PlaceDetails');
        Route::get('/store-details/{id}', 'HomeController@storeDetails')->name('storeDetails');
        Route::get('/zad-details/{id}', 'HomeController@zadDetails')->name('zadDetails');

        Route::get('/full-map', 'HomeController@getFullMap')->name('getFullMap');
        Route::get('/full-map-data', 'HomeController@getFullMapData')->name('getFullMapData');

        Route::get('/places', 'HomeController@Places')->name('Places');
        Route::get('/stores', 'HomeController@stores')->name('stores');
        Route::get('/zads', 'HomeController@zads')->name('zads');
        Route::post('send', 'HomeController@uploadMessage')->name('send');

        Route::get('sync_places_data', 'HomeController@sync_places_data');
        Route::get('selected_places', 'HomeController@selected_places');

        Route::post('action_selected_places', 'HomeController@action_selected_places')->name('action_selected_places');
        Route::get('view_trip/{id}', 'HomeController@view_trip')->name('view_trip')->middleware('auth');


        Route::post('/login', 'HomeController@login')->name('login');
        Route::post('/register', 'HomeController@register')->name('register');
        Route::get('/logout', 'HomeController@logout')->name('logout')->middleware('auth');
        Route::post('/save_trip', 'HomeController@save_trip')->name('save_trip')->middleware('auth');
        Route::get('/my_trips', 'HomeController@my_trips')->name('my_trips')->middleware('auth');

        Route::get('/swalefs', 'HomeController@get_all_swalefs')->name('swalefs');
        Route::get('/swalef/{id}', 'HomeController@one_page_swalefs')->name('swalefsonepage');
    });
    /*========================= Front Routes ==============================*/
});

Route::get('api/handle-emails', function () {
    Artisan::call('queue:work --tries=3');
});

//Auth System
Route::post('store-visitors', 'Dashboard\Visits\VisitorController@store');
