<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// user access formhub
Route::get('users/redirect/{id}', 'UsersAuthController');
Route::get('test', function () {
    // return view('test');
    die('testing');
    // $user = Users::get()->last()->id;
    // dd( $user);
});



/**
 * admin routes
 */

Route::group(['namespace' => 'Auth'], function () {
    Route::match(['POST', 'GET'], 'login', 'LoginController@login')->name('login');
    Route::get('supplier/login', 'LoginController@supplier_login')->name('supplier.login');
    Route::get('logout', 'LoginController@logout')->name('logout');
    Route::match(['POST', 'GET'], 'forgot_password', 'LoginController@forgot_password')->name('forgot_password');
    Route::match(['POST', 'GET'], 'reset_password/{id}/{token}', 'LoginController@reset_password')->name('reset_password');
});


Route::middleware(['auth', 'userisredirected'])->group(function () {
    // Route::get('/', function () {
    //     return view('welcome');
    // });
   

    Route::get('/', function () {
        return redirect('admin/');
    });;

    Route::group(['prefix' => 'admin'], function () {
        Route::get('users/redirects', 'UsersAuthController@redirects')->name('users.redirects');
        Route::post('users/check_role_has_user', 'UserController@check_role_has_user')->name('users.check_role_has_user');

        // Dashboard routes
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::post('form_data', 'DashboardController@showformData')->name('form_data');
        #Notifications routes
        Route::get('notifications', 'NotificationController')->name('notifications');
        Route::put('notifications/mark-read/{id}', 'NotificationController@update')->name('notifications.update'); 
       
        Route::post('getactionchat', 'ActionsController@getactionchat')->name('action.getactionchat');
        Route::post('get-notifications-by-id', 'NotificationController@get_chat_notification')->name('action.get_chat_notification');


        // Templates routes
        Route::middleware(['permissions:Create Template'])->group(function () {
            Route::get('templates', 'TemplateController@index')->name('templates');
            Route::get('create-template', 'TemplateController@createTemplate')->name('create-template');
            Route::post('save-template', 'TemplateController@saveTemplate')->name('save-template');
            Route::post('saveimage', 'TemplateController@saveimage')->name('saveimage');
            Route::get('getThumnails', 'TemplateController@getThumnails')->name('getThumnails');
            Route::post('archive_template', 'TemplateController@archive_template')->name('archive_template');
            Route::post('delete_question', 'TemplateController@deleteQuestion')->name('delete_question');
            Route::post('delete_section', 'TemplateController@deleteSection')->name('delete_section');
            Route::post('delete_scope', 'TemplateController@deleteScope')->name('delete_scope');
            Route::get('archive_template', 'TemplateController@getArchiveListing')->name('archive_template');
            Route::post('restore_template', 'TemplateController@restore_template')->name('restore_template');
            Route::get('edit_template/{id}', 'TemplateController@editTemplate')->name('edit-template');
            Route::match(['POST', 'GET'], 'share_template', 'TemplateController@share_template')->name('share_template');
            Route::post('update_template', 'TemplateController@updateTemplate')->name('update_template');
            Route::post('unshare_template', 'TemplateController@unshare_template')->name('unshare_template');
            Route::post('share_template_with', 'TemplateController@share_template_with')->name('share_template_with');
            Route::get('testing', 'TemplateController@testing')->name('testing');
            Route::get('document_library', 'TemplateController@document_library')->name('document_library');
            Route::post('dropdown_options', 'TemplateController@dropdown_options')->name('dropdown_options');
            Route::post('saveQuestion', 'TemplateController@saveQuestion')->name('saveQuestion');
            Route::post('updateQuestion', 'TemplateController@updateQuestion')->name('updateQuestion');
           
            
        });

        Route::middleware(['permissions:Complete Form'])->group(function () {
            // completed formsss routes
            Route::get('archive', 'CompletedFormController@getArchiveListing')->name('archive');
            Route::get('completed_forms', 'CompletedFormController@index')->name('completed_forms');
            Route::get('edit_form/{id}', 'CompletedFormController@edit')->name('edit_form');
            Route::get('edit-form/chat/{id}/{question_id}', 'CompletedFormController@formChat')->name('form.chat');
            Route::put('edit-form/chat/{id}', 'CompletedFormController@updateFormChat')->name('update.form.chat');
            Route::post('chatting', 'FirebaseController@chat');
            Route::get('save_as/{id}', 'CompletedFormController@saveAs')->name('save_as');
            Route::get('get_template_type', 'CompletedFormController@geTemplateType')->name('get_template_type');
            Route::get('archive', 'CompletedFormController@getArchiveListing')->name('archive');
            Route::get('show/{id}', 'CompletedFormController@show')->name('show');
            Route::post('archive', 'CompletedFormController@archive')->name('archive');
            Route::post('restore', 'CompletedFormController@restore')->name('restore');
            Route::match(['POST'], 'completed_forms', 'CompletedFormController@update')->name('completed_forms');
            Route::post('update_answer', 'CompletedFormController@update_answer')->name('update_answer');
            Route::post('save_comments', 'CompletedFormController@save_comments')->name('save_comments');
            Route::get('google_map', 'CompletedFormController@google_map')->name('google_map');
            Route::get('report/{id}', 'CompletedFormController@report')->name('report');
            Route::match(['POST', 'GET'], 'report/{id}', 'CompletedFormController@report')->name('report');
            Route::post('report_pdf/{id}', 'CompletedFormController@report_pdf')->name('report_pdf');
            Route::post('delete-evidence', 'CompletedFormController@delete_evidence')->name('delete-evidence');
            Route::post('upload-evidence', 'CompletedFormController@uploadEvidence');
        });
        // action routes
        Route::middleware(['permissions:Manage Actions'])->group(function () {
            Route::get('actions', 'ActionsController@index')->name('actions');
            Route::get('create-action', 'ActionsController@createAction')->name('create_action');
            Route::post('save-action', 'ActionsController@saveAction')->name('save_action');
            Route::get('edit-action/{id}', 'ActionsController@editAction')->name('edit_action');
            Route::get('actions/view/{id}', 'ActionsController@view')->name('actions.view');
            Route::post('update-action', 'ActionsController@updateAction')->name('update_action');
            Route::post('show_data', 'ActionsController@show_data')->name('show_data');
            Route::post('close_action', 'ActionsController@close_action')->name('close_action');
            Route::get('archive_actions', 'ActionsController@getArchiveListing')->name('archive_actions');
            Route::post('restore_action', 'ActionsController@restore_action')->name('restore_action');
            Route::get('chats', 'FirebaseController@index')->name('retrieve_chats');
            Route::post('create_action_chat', 'FirebaseController@initailize_chat')->name('create_action_chat');
            Route::post('edit_action_chat', 'FirebaseController@update_chat')->name('edit_action_chat');
            Route::post('update_action_status', 'ActionsController@updateActionStatus')->name('update_status');
            Route::get('destroy', 'ActionsController@destroy')->name('destroy');
            Route::post('remove_recurring', 'ActionsController@remove_recurring')->name('remove_recurring');
            Route::put('accept_reject/{id}', 'ActionsController@accept_reject')->name('accept_reject');
        });
        // Document routes
        Route::middleware(['permissions:Document Library'])->group(function () {
            Route::get('documents', 'DocumentsController@index')->name('documents');
            Route::post('archive-document', 'DocumentsController@archive_document')->name('archive-document');
            Route::get('archive_document_listing', 'DocumentsController@getArchiveListing')->name('archive_document_listing');
            Route::post('create_category', 'DocumentsController@create_category')->name('create_category');
            Route::post('check/category/name', 'DocumentsController@checkCategory');
            Route::post('check/unique/category', 'DocumentsController@validateCompanyName');
            Route::post('edit_category', 'DocumentsController@edit_category')->name('edit_category');
            Route::post('delete_category', 'DocumentsController@delete_category')->name('delete_category');
            

            Route::post('check/document/name', 'DocumentsController@checkDocument');
            Route::post('restore_document', 'DocumentsController@restore_document')->name('restore_document');
            Route::post('activity_log', 'DocumentsController@activity_log')->name('activity_log');
            Route::post('create_document', 'DocumentsController@create_document')->name('create_document');
            Route::get('edit_document', 'DocumentsController@edit_document')->name('edit_document');
            Route::post('update_document', 'DocumentsController@update_document')->name('update_document');
            Route::post('/update_document/{id}', 'DocumentsController@update');
           
            Route::post('view_document', 'DocumentsController@view_document')->name('view_document');
            Route::get('supplier/document', 'DocumentsController@supplierDocuments')->name('supplier.document');
            Route::put('update/{id}', 'DocumentsController@update');
            Route::post('show_bu_data', 'DocumentsController@BU_data')->name('show_bu_data');
            Route::post('show_states', 'DocumentsController@country_data')->name('show_states');
            // Route::group(['namespace' => 'Admin','prefix' => 'folders'], function(){
            Route::get('/folders/{id?}', 'FolderController@index')->name('folders');

            Route::get('/archive_folders/{id?}', 'FolderController@archive_folders')->name('archive_folders');
            Route::post('/restore_folder/{id?}', 'FolderController@restore_folder')->name('restore_folder');
            
            Route::post('/save_folder', 'FolderController@save')->name('save_folder');
            Route::post('/check/folder/name', 'FolderController@checkFolderName');
            Route::post('/save_sub_folder', 'FolderController@save_sub_folder')->name('save_sub_folder');
            Route::put('/rename_folder/{id}', 'FolderController@rename_folder')->name('rename_folder');
            Route::delete('/delete_folder/{id}', 'FolderController@delete_folder')->name('delete_folder');
            Route::post('/update/{id}', 'FolderController@update')->name('update_folder');
            Route::get('/download/document/{id}', 'DocumentsController@download_document')->name('download_document');
        });

        // });
        Route::group(['prefix' => 'cms'], function () {
            // Business unit routes
            Route::middleware(['permissions:Manage Business Unit'])->group(function () {
                Route::get('business-units/archived', 'BusinessUnitController@archived')->name('business-units.archived');
                Route::post('business-units/restore', 'BusinessUnitController@restore')->name('business-units.restore');
                Route::resource('business-units', BusinessUnitController::class);
                Route::post('check/business/department', 'BusinessUnitController@checkBusinessDepartment');
            });

            // Projects Routes
            Route::middleware(['permissions:Manage Projects'])->group(function () {
                Route::get('projects/archived', 'ProjectController@archived')->name('projects.archived');
                Route::post('projects/restore', 'ProjectController@restore')->name('projects.restore');
                Route::post('projects/open_close_project', 'ProjectController@open_close_project')->name('projects.open_close_project');
                Route::resource('projects', ProjectController::class);
            });

            // Departments Routes
            Route::middleware(['permissions:Manage Departments'])->group(function () {
                Route::get('departments/archived', 'DepartmentController@archived')->name('departments.archived');
                Route::post('departments/restore', 'DepartmentController@restore')->name('departments.restore');
                Route::resource('departments', DepartmentController::class);
            });

            // location Routes
            Route::middleware(['permissions:Manage Locations'])->group(function () {
                Route::get('location/archived', 'LocationController@archived')->name('location.archived');
                Route::post('location/restore', 'LocationController@restore')->name('location.restore');
                Route::resource('location', LocationController::class);
            });

            // level Routes
            Route::middleware(['permissions:Manage Levels'])->group(function () {
                Route::resource('level', LevelController::class);
            });

            // Groups Routes
            Route::middleware(['permissions:Manage Groups'])->group(function () {
                Route::get('groups/archived', 'GroupController@archived')->name('groups.archived');
                Route::post('groups/restore', 'GroupController@restore')->name('groups.restore');
                Route::resource('groups', GroupController::class);
            });

            // Roles Routes
            Route::middleware(['permissions:Manage Roles'])->group(function () {
                Route::get('roles/is_account_payable_exists', 'RoleController@check_account_payable_exists')->name('roles.check_account_payable_exists');
                Route::get('roles/update_account_payable', 'RoleController@update_account_payable')->name('roles.update_account_payable');

                Route::get('roles/is_supplier_approver_exists', 'RoleController@check_supplier_approver_exists')->name('roles.check_supplier_approver_exists');
                Route::get('roles/update_supplier_approver', 'RoleController@update_supplier_approver')->name('roles.update_supplier_approver');

                Route::get('roles/is_system_administrator_exists', 'RoleController@check_system_administrator_exists')->name('roles.check_system_administrator_exists');
                Route::get('roles/update_system_administrator', 'RoleController@update_system_administrator')->name('roles.update_system_administrator');

                Route::get('roles/is_alternative_supplier_approver_exist', 'RoleController@check_alternative_supplier_approver_exist')->name('roles.check_alternative_supplier_approver_exist');
                Route::get('roles/update_alternative_supplier_approver', 'RoleController@update_alternative_supplier_approver')->name('roles.update_alternative_supplier_approver');

                Route::get('roles/getrole', 'RoleController@getrole')->name('roles.getrole');
                Route::post('roles/getrole_by_business_department', 'RoleController@getrole_by_business_department')->name('roles.getrole_by_business_department');
                
                Route::post('roles/ajax_create_role', 'RoleController@ajax_create_role')->name('roles.ajax_create_role');
                Route::post('roles/update_user_role', 'RoleController@update_user_role')->name('roles.update_user_role');
              
                Route::post('roles/getUpgradeDowngradeRole', 'RoleController@getUpgradeDowngradeRole')->name('roles.getUpgradeDowngradeRole');
                Route::get('roles/archived', 'RoleController@archived')->name('roles.archived');
                Route::post('roles/restore', 'RoleController@restore')->name('roles.restore');
                Route::resource('roles', RoleController::class);
            });

            // Users Routes
            Route::middleware(['permissions:Manage User'])->group(function () {
               
                Route::post('users/check_role_avliable', 'UserController@check_role_avliable')->name('users.check_role_avliable');    
                Route::get('users/archived', 'UserController@archived')->name('users.archived');
                Route::post('users/restore', 'UserController@restore')->name('users.restore');
                Route::post('users/emailcheck', 'UserController@checkEmail')->name('users.emailcheck');
                Route::get('users/assignRevokeProject/{id}', 'UserController@assignRevokeProject')->name('users.assignRevokeProject');
                Route::put('users/saveProject/{id}', 'UserController@saveProject')->name('users.saveProject');
                Route::post('users/toggle_status', 'UserController@toggle_status')->name('users.toggle_status');
                Route::resource('users', UserController::class);
            });

            // Suppliers Routes
            Route::middleware(['permissions:Manage User'])->group(function () {
                Route::post('suppliers/approved_supplier', 'SuppliersController@approved_supplier')->name('suppliers.approved_supplier');
                Route::get('suppliers/supplier_approve_alert', 'SuppliersController@supplier_approve_alert')->name('suppliers.supplier_approve_alert');
                Route::get('suppliers/archived', 'SuppliersController@archived')->name('suppliers.archived');
                Route::post('suppliers/restore', 'SuppliersController@restore')->name('suppliers.restore');
                Route::post('suppliers/toggle_status', 'SuppliersController@toggle_status')->name('suppliers.toggle_status');
                Route::resource('suppliers', SuppliersController::class);
            });
        });
        // Faq routes
        Route::get('/faq', 'FaqController@index')->name('faq');
        Route::post('/add_faq', 'FaqController@add_faq')->name('add_faq');
        Route::post('/edit_faq', 'FaqController@edit_faq')->name('edit_faq');
        Route::post('/update_faq', 'FaqController@update_faq')->name('update_faq');
        Route::post('/delete_faq', 'FaqController@delete_faq')->name('delete_faq');
        Route::get('/terms_condition', 'FaqController@terms_condition')->name('terms_condition');
        Route::post('/add_terms_condition', 'FaqController@add_term_condition')->name('add_terms_condition');
        Route::get('/privacy_policy', 'FaqController@privacy_policy')->name('privacy_policy');
        Route::post('/add_privacy_policy', 'FaqController@add_privacy_policy')->name('add_privacy_policy');
        Route::match(['POST', 'GET'], '/email_setting', 'FaqController@email_setting')->name('email_setting');
    });
});
//view faq routs for mobile app
Route::get('/view_faq', 'FaqController@view_faq')->name('view_faq');
