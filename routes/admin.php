<?php

Route::get('fill_inventory', function(){
//    DB::transaction(function(){
        $items = \App\Models\InventoryItem::all();

        $Repo = app()->make('App\Repositories\Contracts\InventoryTransactionRepository');

        $items->each(function($item) use($Repo){
            $total_amount = 0;
//            if(!$item->variants) return;
            $count = mt_rand(20,50); // 10
            $period = round(180 / $count); // 18
            $cnt = 0;
            // 180 - (0*18) => 180 - (1*18) = 180 => 162
            // 180 - (1*18) => 180 - (2*18) = 162 => 162
            // 180 - (2*18) => 180 - (3*18)
//            echo "Initial ".$count."<br>";
            while(--$count > 0){
                $transaction_type = array_random(['check-in', 'check-out'], 1)[0];
                $variations = [];
//                echo "HI ".$count."<br>";
                $inventory = \App\Models\Inventory::all()->random(1)->first();
                if($item->variants) {
//                    $vars = array_random($item->variants,mt_rand(1,count($item->variants)));
                    $total_amount = 0;

                    $records_count = mt_rand(1, 5);
                    for($y = 0; $y < $records_count; $y++) {
                        $variants = [];
                        foreach ($item->variants as $var) {
                            $variants[] = ['name' => $var['name'], 'value' => array_random($var['options'], 1)[0]];
                        }

//                        array_push($variations, ['variations' => $variants]);

//                        array_walk($variants, function(&$item, $key){ $item = [$item['name']=> $item['value']]; });
                        $v = [];
                        array_map(function($item) use(&$v){ $v[$item['name']] = $item['value']; }, $variants);

                        $current_quantity = $Repo->check_quantity($item->id, $inventory->id, $v);
                        if($current_quantity == 0 && $transaction_type == 'check-out') continue;
                        do{
                            $q = $transaction_type == 'check-in' ? mt_rand(1,1000) : mt_rand(-1000,-1);
                        } while($current_quantity + $q < 0 || $q == 0);


                        array_push($variations,['quantity' => $q , 'variations' => $variants, 'id' => null]);
                        $total_amount += $q;
                    }
                    if(!count($variations)) continue;
                } else {
                    $current_quantity = $Repo->check_quantity($item->id, $inventory->id, []);
                    if($current_quantity == 0 && $transaction_type == 'check-out') continue;
                    do{
                        $total_amount = $transaction_type == 'check-in' ? mt_rand(1,1000) : mt_rand(-1000,-1);
//                        echo "Total: ". $total_amount. "<br>";
                    } while($current_quantity + $total_amount < 0 || $total_amount == 0);
                }

                $input = [
                    'transaction_type' => $transaction_type,
                    'inventory_item' => ['id' => $item->id],
                    'inventory' => ['id' => $inventory->id],
                    'variations' => $variations,
                    'quantity' => abs($total_amount),
                    'description' => $transaction_type,
//                    'transaction_date' => \Illuminate\Support\Carbon::now()->subDays(rand(1, 180))->format('Y-m-d')
                    'transaction_date' => \Illuminate\Support\Carbon::now()->subDays(rand(210 - (($cnt+1)*$period), 210 - ($cnt*$period)))->format('Y-m-d')
                ];
                $t = $Repo->Store($input);
//                dd($t, 180 - (($cnt+1)*$period), 180 - ($cnt*$period));
                $cnt++;
            }
        });

//    });
});

//Route::get('index/search', 'AjaxController@search')->name('search');

Route::get('refresh_csrf', static function () {
    return ['csrfToken' => csrf_token()];
})->name('refresh_csrf');

Route::get('expired_certificate', 'VesselController@expired_certificate')->name('expired_certificate');
Route::post('images/upload', 'AjaxController@imageUpload')->name('images.upload');
Route::get('get_list_of/{constant}', 'AjaxController@getListsOf')->name('get_list_of_constants');
Route::get('get_list_of_ports', 'AjaxController@getListsOfPorts')->name('get_list_of_ports');
Route::get('get_list_of_ranks', 'AjaxController@getListsOfRanks')->name('get_list_of_ranks');
Route::get('get_list_of_masters', 'AjaxController@getListsOfMasters')->name('get_list_of_masters');
Route::get('get_list_of_users', 'AjaxController@getListsOfUsers')->name('get_list_of_users');
Route::get('get_list_of_employees', 'AjaxController@getListsOfEmployees')->name('get_list_of_employees');
Route::get('get_list_of_employment_levels', 'AjaxController@getListsOfEmploymentLevels')->name('get_list_of_employment_levels');
Route::patch('users/update_profile', 'UserController@updateProfile')->name('users.update_profile');
Route::get('users/search', 'UserController@search')->name('users.search');
Route::get('users/search', 'UserController@search')->name('users.search');
Route::get('departments/search', 'DepartmentController@search')->name('departments.search');
Route::get('branches/search', 'BranchController@search')->name('branches.search');
Route::get('designations/search', 'DesignationController@search')->name('designations.search');
Route::get('doc_templates/search', 'DocTemplateController@search')->name('doc_templates.search');
Route::get('holidays/get_as_ranges', 'HolidayController@getAsRanges')->name('holidays.get_as_ranges');
Route::get('groups/get_groups', 'GroupController@search')->name('groups.search');
Route::get('equipments/get_brands', 'EquipmentController@brands')->name('equipments.brands');
Route::get('flight_reservations/mo_report', 'FlightReservationController@mo_report')->name('flight_reservations.mo_report');
Route::get('document_expiration', 'CompanyRegistrationsController@document_expiration')->name('document_expiration');
Route::get('vessel_insurance_expiration', 'VesselInsuranceController@vessel_insurance_expiration')->name('vessel_insurance_expiration');
Route::get('seafarer_contracts/getids', 'SeafarerContractController@getids')->name('seafarer_contracts/getids');
Route::get('Audits/{payment_requisition}/get_payment_requisition', 'AuditController@getPaymentRequisitionHistory')->name('audits.get_payment_requisition_history');
Route::get('Audits/{cash_requisition}/get_cash_requisition_history', 'AuditController@getCashRequisitionHistory')->name('audits.get_cash_requisition_history');
Route::get('get_current_date', static function () {
    return date('Y-m-d');
})->name('current_date');
Route::get('get_business_days_count/{start_date}/{end_date}', 'AjaxController@getBusinessDaysCount')->name('get_business_days_count');

Route::post('seafarer_contracts/preview', 'SeafarerContractController@contract_preview')->name('seafarer_contracts.preview');
Route::get('vessels/limited_search', 'VesselController@limited_search')->name('vessels.limited_search');

Route::get('employees/get_employees_emails', 'EmployeeController@getEmployeesEmails')->name('employees.get_employees_emails');

Route::get('comments/{controller_name}/{item}', static function (\Illuminate\Http\Request $request, $controller_name, $item) {
    $app = app();
    $controller = $app->make('\App\Http\Controllers\Backend\\'.$controller_name.'Controller');

    return $controller->callAction('getComments', ['request' => $request, 'commentable_id' => $item]);
})->name('get_comments');

Route::post('comments/{controller_name}/{item}', static function (\Illuminate\Http\Request $request, $controller_name, $item) {
    $app = app();
    $controller = $app->make('\App\Http\Controllers\Backend\\'.$controller_name.'Controller');

    return $controller->callAction('addComment', ['request' => $request, 'commentable_id' => $item]);
})->name('add_comment');

Route::get('history/{controller_name}/{item}', static function (\Illuminate\Http\Request $request, $controller_name, $item) {
    $app = app();
    $controller = $app->make('\App\Http\Controllers\Backend\\'.$controller_name.'Controller');

    return $controller->callAction('getHistory', ['request' => $request, 'auditable_id' => $item]);
})->name('get_history');

Route::group(
    ['middleware' => ['can:view crew requests']],
    static function () {
        Route::get('crew_request/search', 'CrewRequestController@search')->name('crew_requests.search');
        Route::get('crew_request/{crew_request}/show', 'CrewRequestController@show')->name('crew_requests.show');
        Route::post('crew_request/{crew_request}/mark_as_done', 'CrewRequestController@mark_as_done')->name('crew_requests.mark_as_done');

        Route::resource('crew_requests', 'CrewRequestController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view own vessels' || 'can:view vessel certificates' || 'can:view ves sel forms']],
    static function () {
        Route::get('vessels/search', 'VesselController@search')->name('vessels.search');
        Route::get('vessels/{vessel}/show', 'VesselController@show')->name('vessels.show');
        Route::get('vessels/{vessel}/imo_report', 'VesselController@get_imo_reports')->name('vessels.imo_repo rt');
        Route::post('vessels/{vessel}/imo_report', 'VesselController@process_imo_report')->name('vessels.process_imo_report');
        Route::get('vessels/{vessel}/export_certificates', 'VesselController@export_certificates')->name('vessels.export_certificates');

        Route::resource('vessels', 'VesselController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view ports']],
    static function () {
        Route::get('ports/search', 'PortController@search')->name('ports.search');
        Route::get('ports/{port}/show', 'PortController@show')->name('ports.show');

        Route::resource('ports', 'PortController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view seafarers']],
    static function () {
        Route::get('seafarers/search', 'SeafarerController@search')->name('seafarers.search');
        Route::get('seafarers/{seafarer}/show', 'SeafarerController@show')->name('seafarers.show');
        Route::get('seafarers/onboard/{vessel}', 'SeafarerController@getOnBoardSeafarers')->name('seafarers.onboard');
        Route::post('seafarers/{seafarer}/blacklist', 'SeafarerController@blacklist')->name('seafarers.blacklist');
        Route::post('seafarers/{seafarer}/whitelist', 'SeafarerController@whitelist')->name('seafarers.whitelist');

        Route::resource('seafarers', 'SeafarerController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view own seafarer contracts']],
    static function () {
        Route::get('seafarer_contracts/search', 'SeafarerContractController@search')->name('seafarer_contracts.search');
        Route::get('seafarer_contracts/{seafarer_contract}/show', 'SeafarerContractController@show')->name('seafarer_contracts.show');
        Route::get('seafarer_contracts/{seafarer_contract}/print', 'SeafarerContractController@print')->name('seafarer_contracts.print');
        Route::get('seafarer_contracts/{seafarer_contract}/print_sea', 'SeafarerContractController@print_sea')->name('seafarer_contracts.print_sea');
        Route::get('seafarer_contracts/{seafarer_contract}/print_prop', 'SeafarerContractController@print_prop')->name('seafarer_contracts.print_prop');
        Route::post('seafarer_contracts/{seafarer_contract}/approve', 'SeafarerContractController@approve')->name('seafarer_contracts.approve');
        Route::post('seafarer_contracts/{seafarer_contract}/signons', 'SeafarerContractController@signons')->name('seafarer_contracts.signons');
        Route::post('seafarer_contracts/{seafarer_contract}/signons/{sign}', 'SeafarerContractController@approve_sign')->name('seafarer_contracts.approve_sign');
        Route::delete('seafarer_contracts/{seafarer_contract}/signons/{sign}', 'SeafarerContractController@delete_sign')->name('seafarer_contracts.delete_sign');

        Route::resource('seafarer_contracts', 'SeafarerContractController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
    );

Route::get('users/notifications/{latest?}', 'NotificationController@getNotifications')->name('notifications.get_notifications');
Route::get('users/unread_notifications_count', 'NotificationController@getUnreadNotificationsCount')->name('notifications.get_unread_notifications_count');
Route::get('users/unread_notifications_count_by_type', 'NotificationController@getUnreadNotificationsCountByType')->name('notifications.get_unread_notifications_count_by_type');
Route::post('users/notifications/{notification}/toggle_status/{mark_as}', 'NotificationController@toggleNotificationStatus')->name('notifications.toggle_notification_status');
Route::post('users/notifications/bulk_toggle_status/{mark_as}', 'NotificationController@bulkToggleNotificationStatus')->name('notifications.bulk_toggle_notification_status')->where('mark_as','read|unread');
Route::get('users/notifications/{notification}/get', 'NotificationController@getNotification')->name('notifications.get_notification');

Route::group(
    ['middleware' => ['can:view users']],
    static function () {
        Route::get('users/active_counter', 'UserController@getActiveUserCounter')->name('users.active.counter');
        Route::get('users/roles', 'UserController@getRoles')->name('users.get_roles');
        Route::get('users/get_permission', 'UserController@getPermission')->name('users.get_permission');

        Route::get('users/search', 'UserController@search')->name('users.search');
        Route::get('users/{user}/show', 'UserController@show')->name('users.show');

        Route::resource('users', 'UserController', [
            'only' => ['store', 'update', 'destroy'],
        ]);

        Route::post('users/batch_action', 'UserController@f')->name('users.batch_action');
        Route::post('users/{user}/active', 'UserController@activeToggle')->name('users.active');

        Route::get('users/{user}/impersonate', 'UserController@impersonate')->name('users.impersonate');
    }
);

Route::group(
    ['middleware' => ['can:view roles']],
    static function () {
        Route::get('roles/permissions', 'RoleController@getPermissions')->name('roles.get_permissions');

        Route::get('roles/search', 'RoleController@search')->name('roles.search');
        Route::get('roles/{role}/show', 'RoleController@show')->name('roles.show');

        Route::resource('roles', 'RoleController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view own tasks']],
    static function () {
        Route::get('tasks/search', 'TaskController@search')->name('tasks.search');
        Route::get('tasks/{task}/show', 'TaskController@show')->name('tasks.show');
        Route::post('tasks/{task}/change_status/{status}', 'TaskController@changeStatus')->name('tasks.change_status');
        Route::get('tasks/print', 'TaskController@print')->name('tasks.print');

        Route::resource('tasks', 'TaskController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view departments']],
    static function () {
        Route::get('departments/{department}/show', 'DepartmentController@show')->name('departments.show');

        Route::resource('departments', 'DepartmentController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);
Route::group(
    ['middleware' => ['can:view audits']],
    static function () {
        Route::get('Audits/search', 'AuditController@search')->name('Audits.search');
    }
);

Route::group(
    ['middleware' => ['can:view own activities']],
    static function () {
        Route::get('activities/search', 'ActivityController@search')->name('activities.search');
        Route::get('activities/{activity}/show', 'ActivityController@show')->name('activities.show');
        Route::post('activities/{activity}/approve', 'ActivityController@approve')->name('activities.approve');
        Route::post('activities/{activity}/change_status/{status}', 'ActivityController@changeStatus')->name('activities.change_status');
        Route::get('activities/print', 'ActivityController@print')->name('activities.print');

        Route::resource('activities', 'ActivityController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

//Route::group(
//    ['middleware' => ['can:view purchase requisitions']],
//    static function () {
//        Route::get('purchase_requisitions/search', 'PurchaseRequisitionController@search')->name('purchase_requisitions.search');
//        Route::get('purchase_requisitions/{purchase_requisition}/show', 'PurchaseRequisitionController@show')->name('purchase_requisitions.show');
//        Route::post('purchase_requisitions/{purchase_requisition}/change_status/{status}', 'PurchaseRequisitionController@changeStatus')->name('purchase_requisitions.change_status');
//
//        Route::resource('purchase_requisitions', 'PurchaseRequisitionController', [
//            'only' => ['store', 'update', 'destroy'],
//        ]);
//
//        Route::get('quotations/search', 'QuotationController@search')->name('quotations.search');
//        Route::get('quotations/{quotation}/show', 'QuotationController@show')->name('quotations.show');
//        Route::post('quotations/{quotation}/select', 'QuotationController@select')->name('quotations.select');
//
//        Route::resource('quotations', 'QuotationController', [
//            'only' => ['store', 'update', 'destroy'],
//        ]);
//
//    }
//);

Route::group(
    ['middleware' => ['can:view own cash requisitions']],
    static function () {
        Route::get('cash_requisitions/search', 'CashRequisitionController@search')->name('cash_requisitions.search');
        Route::get('cash_requisitions/export', 'CashRequisitionController@export')->name('cash_requisitions.export');
        Route::get('cash_requisitions/{cash_requisition}/show', 'CashRequisitionController@show')->name('cash_requisitions.show');
        Route::post('cash_requisitions/{cash_requisition}/change_status/{status}', 'CashRequisitionController@changeStatus')->name('cash_requisitions.change_status');
        Route::get('cash_requisitions/{cash_requisition}/print', 'CashRequisitionController@print')->name('cash_requisitions.print');
        Route::get('cash_requisitions/get_statuses', 'CashRequisitionController@getStatuses')->name('cash_requisitions.get_statuses');
        Route::get('cash_requisitions/get_happy_path', 'CashRequisitionController@getHappyPath')->name('cash_requisitions.get_happy_path');

        Route::resource('cash_requisitions', 'CashRequisitionController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view own payment requisitions']],
    static function () {
        Route::get('payment_requisitions/search', 'PaymentRequisitionController@search')->name('payment_requisitions.search');
        Route::get('payment_requisitions/export', 'PaymentRequisitionController@export')->name('payment_requisitions.export');
        Route::get('payment_requisitions/{payment_requisition}/show', 'PaymentRequisitionController@show')->name('payment_requisitions.show');
        Route::post('payment_requisitions/{payment_requisition}/change_status/{status}', 'PaymentRequisitionController@changeStatus')->name('payment_requisitions.change_status');
        Route::get('payment_requisitions/{payment_requisition}/print', 'PaymentRequisitionController@print')->name('payment_requisitions.print');
        Route::get('payment_requisitions/get_statuses', 'PaymentRequisitionController@getStatuses')->name('payment_requisitions.get_statuses');
        Route::get('payment_requisitions/get_happy_path', 'PaymentRequisitionController@getHappyPath')->name('payment_requisitions.get_happy_path');
        Route::post('payment_requisitions/{payment_requisition}/generate_payment_voucher', 'PaymentRequisitionController@generatePaymentVoucher')->name('payment_requisitions.generate_payment_voucher');

        Route::resource('payment_requisitions', 'PaymentRequisitionController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);


Route::group(
    ['middleware' => ['can:view own announcements']],
    static function () {
        Route::get('announcements/search', 'AnnouncementController@search')->name('announcements.search');
        Route::get('announcements/{announcement}/show', 'AnnouncementController@show')->name('announcements.show');
        Route::post('announcements/{announcement}/publish', 'AnnouncementController@publish')->name('announcements.publish');


        Route::resource('announcements', 'AnnouncementController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view own flight reservations']],
    static function () {
        Route::get('flight_reservations/search', 'FlightReservationController@search')->name('flight_reservations.search');
        Route::get('flight_reservations/budget', 'FlightReservationController@budgeting')->name('flight_reservations.budget');
        Route::get('flight_reservations/report', 'FlightReservationController@report')->name('flight_reservations.report');
        Route::get('flight_reservations/{flight_reservation}/show', 'FlightReservationController@show')->name('flight_reservations.show');
        Route::post('flight_reservations/{flight_reservation}/change_status/{status}', 'FlightReservationController@changeStatus')->name('flight_reservations.change_status');
//        Route::get('payment_requisitions/{payment_requisition}/print', 'PaymentRequisitionController@print')->name('payment_requisitions.print');
        Route::get('flight_reservations/get_statuses', 'FlightReservationController@getStatuses')->name('flight_reservations.get_statuses');
        Route::get('flight_reservations/get_happy_path', 'FlightReservationController@getHappyPath')->name('flight_reservations.get_happy_path');
        Route::post('flight_reservations/{flight_reservation}/generate_prf', 'FlightReservationController@generatePRF')->name('flight_reservations.generate_prf');
        Route::post('flight_reservations/bulk_generate_prf', 'FlightReservationController@bulkGeneratePRF')->name('flight_reservations.bulk_generate_prf');
//        Route::get('flight_reservations/bulk_generate_report', 'FlightReservationController@bulkGenerateReport')->name('flight_reservations.bulk_generate_report');
        Route::post('flight_reservations/bulk_generate_report', 'FlightReservationController@bulkGenerateReport')->name('flight_reservations.bulk_generate_report');
        Route::get('flight_reservations/{flight_reservation}/get_flight_items', 'FlightReservationController@getFlightItems')->name('flight_reservations.get_flight_items');

        Route::resource('flight_reservations', 'FlightReservationController', [
            'only' => ['store', 'update', 'destroy'],
        ]);

        Route::get('flight_reservation_quotations/search', 'FlightReservationQuotationController@search')->name('flight_reservation_quotations.search');
        Route::get('flight_reservation_quotations/{flight_reservation_quotation}/show', 'FlightReservationQuotationController@show')->name('flight_reservation_quotations.show');

        Route::resource('flight_reservation_quotations', 'FlightReservationQuotationController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::get('attendances/dept_movement', 'AttendanceController@get_dept_movement')->name('attendances.dept_movement');
Route::post('attendances/dept_movement', 'AttendanceController@save_dept_movement')->name('attendances.save_dept_movement');

Route::group(
    ['middleware' => ['can:view attendances']],
    static function () {
        Route::get('attendance/export', 'AttendanceController@export')->name('attendances.export');
        Route::get('attendances/search', 'AttendanceController@search')->name('attendances.search');
        Route::get('attendances/representative', 'AttendanceController@representative_search')->name('attendance.representativesearch');
        Route::get('attendances/{attendance}/show', 'AttendanceController@show')->name('attendances.show');
        Route::get('representative', 'AttendanceController@representativesearch')->name('representative');

        Route::post('attendances/store_represent', 'AttendanceController@store_represent')->name('attendances.store_represent');
        Route::post('attendances/{representative}/update_represent', 'AttendanceController@update_represent')->name('attendances.update_represent');
        Route::delete('attendances/{representative}/delete_represent', 'AttendanceController@delete_represent')->name('attendances.delete_represent');

        Route::resource('attendances', 'AttendanceController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view branches']],
    static function () {
        Route::get('branches/{branch}/show', 'BranchController@show')->name('branches.show');
//        Route::get('branches/search', 'BranchController@search')->name('branches.search');

        Route::resource('branches', 'BranchController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

//Route::group(
//    ['middleware' => ['can:view representatives']],
//    static function () {
//
//        Route::resource('representatives', 'AttendanceRepresentativeController', [
//            'only' => ['store', 'update', 'destroy'],
//        ]);
//    }
//);

Route::group(
    ['middleware' => ['can:view designations']],
    static function () {
        Route::get('designations/{designation}/show', 'DesignationController@show')->name('designations.show');

        Route::resource('designations', 'DesignationController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view own employees']],
    static function () {
        Route::get('employees/search', 'EmployeeController@search')->name('employees.search');
        Route::get('employees/get_contracting_info/{employee?}', 'EmployeeController@getContractingInfo')->name('employees.get_contracting_info');
        Route::get('employees/{employee}/show', 'EmployeeController@show')->name('employees.show');

        Route::resource('employees', 'EmployeeController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view fingerprints']],
    static function () {
        Route::get('fingerprints/search', 'FingerprintController@search')->name('fingerprints.search');
        Route::post('fingerprints/import', 'FingerprintController@import')->name('fingerprints.import');
    }
);

Route::group(
    ['middleware' => ['can:view own leaves']],
    static function () {
        Route::get('leaves/search', 'LeaveController@search')->name('leaves.search');
        Route::get('leaves/{leave}/show', 'LeaveController@show')->name('leaves.show');
        Route::post('leaves/{leave}/change_status/{status}', 'LeaveController@changeStatus')->name('leaves.change_status');
        Route::get('leaves/employee_leave_entitlements/{employee?}', 'LeaveController@leaveEntitlements')->name('leaves.employee_leave_entitlements');

        Route::resource('leaves', 'LeaveController', [
            'only' => ['store', 'update', 'destroy'],
        ])->parameters([
            'leaves' => 'leave',
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view holidays']],
    static function () {
        Route::get('holidays/search', 'HolidayController@search')->name('holidays.search');
        Route::get('holidays/per_year', 'HolidayController@perYear')->name('holidays.per_year');
        Route::get('holidays/{holiday}/show', 'HolidayController@show')->name('holidays.show');

        Route::resource('holidays', 'HolidayController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view doc templates']],
    static function () {
        Route::get('doc_templates/{doc_template}/show', 'DocTemplateController@show')->name('doc_templates.show');

        Route::resource('doc_templates', 'DocTemplateController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view vessel forms']],
    static function () {
        Route::get('vessel_forms/search', 'VesselFormController@search')->name('vessel_forms.search');
        Route::get('vessel_forms/{vessel_form}/show', 'VesselFormController@show')->name('vessel_forms.show');

        Route::resource('vessel_forms', 'VesselFormController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view doc audits']],
    static function () {
        Route::get('doc_audits/search', 'DocAuditController@search')->name('doc_audits.search');
        Route::get('doc_audits/{doc_audit}/show', 'DocAuditController@show')->name('doc_audits.show');

        Route::resource('doc_audits', 'DocAuditController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view company registrations']],
    function () {
        Route::get('company_registrations/search', 'CompanyRegistrationsController@search')->name('company_registrations.search');
        Route::get('company_registrations/{company_registration}/show', 'CompanyRegistrationsController@show')->name('company_registrations.show');

        Route::resource('company_registrations', 'CompanyRegistrationsController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view vessel insurances']],
    static function () {
        Route::get('vessel_insurances/search', 'VesselInsuranceController@search')->name('vessel_insurances.search');
        Route::get('vessel_insurances/{vessel_insurance}/show', 'VesselInsuranceController@show')->name('vessel_insurances.show');

        Route::resource('vessel_insurances', 'VesselInsuranceController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view inventory item categories']],
    function () {
        Route::get('inventory_item_categories/search', 'InventoryItemCategoryController@search')->name('inventory_item_categories.search');
        Route::get('inventory_item_categories/get_as_tree', 'InventoryItemCategoryController@getAsTree')->name('inventory_item_categories.get_as_tree');
        Route::get('inventory_item_categories/{inventory_item_category}/show', 'InventoryItemCategoryController@show')->name('inventory_item_categories.show');

        Route::resource('inventory_item_categories', 'InventoryItemCategoryController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view groups']],
    function () {
        Route::get('groups/search', 'GroupController@search')->name('groups.search');
        Route::get('groups/{group}/show', 'GroupController@show')->name('groups.show');

        Route::resource('groups', 'GroupController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view inventory items']],
    function () {
        Route::get('inventory_items/search', 'InventoryItemController@search')->name('inventory_items.search');
        Route::get('inventory_items/{inventory_item}/show', 'InventoryItemController@show')->name('inventory_items.show');
        Route::get('inventory_items/{inventory_item}/variants', 'InventoryItemController@getVariants')->name('inventory_items.get_variants');

        Route::resource('inventory_items', 'InventoryItemController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view own inventories']],
    function () {
        Route::get('inventories/search', 'InventoryController@search')->name('inventories.search');
        Route::get('inventories/{inventory}/show', 'InventoryController@show')->name('inventories.show');
        Route::post('inventories/{inventory}/check_quantity/{inventory_item}', 'InventoryController@checkQuantity')->name('inventories.check_quantity');

        Route::resource('inventories', 'InventoryController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view own rob report']],
    function () {
        Route::get('transactions/report', 'InventoryTransactionController@report')->name('transactions.report');
        Route::get('transactions/{inventory_item}{store_id}/chart', 'InventoryTransactionController@item_transaction_chart')->name('transactions.chart');
    }
);

Route::group(
    ['middleware' => ['can:view own inventory transactions']],
    function () {
        Route::get('inventory_transactions/export', 'InventoryTransactionController@export')->name('inventory_transactions.export');
        Route::get('inventory_transactions/search', 'InventoryTransactionController@search')->name('inventory_transactions.search');
        Route::get('inventory_transactions/{inventory_transaction}/show', 'InventoryTransactionController@show')->name('inventory_transactions.show');

        Route::resource('inventory_transactions', 'InventoryTransactionController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view own equipment']],
    function () {
        Route::get('equipment/search', 'EquipmentController@search')->name('equipment.search');
        Route::get('equipment/{equipment}/show', 'EquipmentController@show')->name('equipment.show');

        Route::resource('equipment', 'EquipmentController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::get('/{vue_capture?}', 'BackendController@index')
    ->where('vue_capture', '[\/\w\.-]*')
    ->name('home');
