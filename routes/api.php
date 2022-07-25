<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
   

Route::prefix('v1')->namespace('Api\V1')->group(function () {
  
    
    Route::middleware(['auth_api'])->group(function () {

        // Route::get('completed_forms', 'CompletedFormController@index')->name('completed_forms');

        Route::get('completed_forms/{status}/{title?}', 'CompletedFormController@index')->name('completed_forms')->where('status', '[0-9]+');
        Route::get('completed_forms/maps', 'CompletedFormController@getMapData');
        Route::get('completedForm_detail/{id}', 'CompletedFormController@show')->name('completedForm_detail');
        Route::post('save_form', 'CompletedFormController@save_form')->name('save_form');
        Route::post('upload_file', 'CompletedFormController@upload_file')->name('upload_file');
        Route::post('edit_form', 'CompletedFormController@edit_form')->name('edit_form');
        Route::get('share_form/{id}', 'CompletedFormController@share_form')->name('share_form');
        Route::post('delete_media', 'CompletedFormController@delete_media')->name('delete_media');
        Route::get('form-data', 'CompletedFormController@formData');
        Route::delete('form-delete/{id}', 'CompletedFormController@deleteForm');
        Route::get('form_action_data/{form_id}', 'CompletedFormController@form_action_data')->name('form_action_data');

        Route::get('documents/{name?}', 'DocumentsController@index')->name('documents');
        Route::post('open_document', 'DocumentsController@open_document')->name('open_document');

        Route::get('templates/{name?}', 'TemplatesController@index')->name('templates');
        Route::get('template_detail/{id}', 'TemplatesController@show')->name('template_detail');

        /**
         * actions routes
         */
        Route::post('actions/{name?}', 'ActionsController@index');
        Route::post('action_detail/{id}', 'ActionsController@show');
        Route::post('add_action', 'ActionsController@add_action');
        Route::post('close_action', 'ActionsController@close_action');
        Route::post('change_action_status', 'ActionsController@change_action_status');
        Route::post('upcoming_actions_and_templates', 'ActionsController@upcoming_actions_and_templates');
        Route::get('assign_action/{id}', 'ActionsController@assign_action');
        Route::prefix('actions')->group(function () {
            Route::put('update/{id}', 'ActionsController@update');
        });
        Route::post('assign-action-notification', 'ActionsController@create_assign_action_notification');
        Route::post('close-action-notification', 'ActionsController@close_action_notification');
        Route::post('getAssignedUserDeviceToken', 'ActionsController@getAssignedUserDeviceToken');
       
        
        
        /** notifications routes */
        Route::get('notifications/count', 'NotificationsController@countUnread');
        Route::get('notifications', 'NotificationsController@index');
        Route::post('notifications/{id?}', 'NotificationsController@update');
        Route::get('notifications/type/{name?}', 'NotificationsController@makeread');
        Route::post('send/push/notifications', 'NotificationsController@sendPushNotification');
        Route::post('newMesgNotificationCreate/{actionId?}/{title?}/{body?}/{notifyType?}', 'ActionsController@newMesgNotificationCreate');
    
        /**Setting routes */
        Route::get('terms_conditions', 'SettingController@terms_condition');
        Route::get('privacy_policy', 'SettingController@privacy_policy');
        /**
         * Users routes
         */

       
        Route::prefix('users')->group(function () {
            Route::get('permissions', 'UsersController@permissions');
            Route::post('updateprofile', 'UsersController@updateprofile');
            Route::post('get_profile_image', 'UsersController@get_profile_image');
        });
    });

        /**
         * Users routes
         */
        Route::prefix('users')->group(function () {
            Route::post('p2b_login', 'UsersController@p2b_login');
        });
});
