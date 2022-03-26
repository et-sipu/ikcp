<?php
return [
    'backend' =>	[
        'activities' =>		[
            'create' => 'Error on activity creation',
            'delete' => 'Error on activity deletion',
            'update' => 'Error on activity updating',
        ],

        'announcements' =>		[
            'create' => 'Error on announcement creation',
            'delete' => 'Error on announcement deletion',
            'update' => 'Error on announcement updating',
        ],

        'attendances' =>		[
            'create' => 'Error on attendance creation',
            'delete' => 'Error on attendance deletion',
            'update' => 'Error on attendance updating',
        ],

        'branches' =>		[
            'create' => 'Error on branch creation',
            'delete' => 'Error on branch deletion',
            'update' => 'Error on branch updating',
        ],

        'cash_requisitions' =>		[
            'create' => 'Error on cash requisition creation',
            'delete' => 'Error on cash requisition deletion',
            'update' => 'Error on cash requisition updating',
            'upload_receipts' => 'Upload receipts',
        ],

        'crew_requests' =>		[
            'create' => 'Error on crew request creation',
            'delete' => 'Error on crew request deletion',
            'update' => 'Error on crew request updating',
        ],

        'departments' =>		[
            'create' => 'Error on department creation',
            'delete' => 'Error on department deletion',
            'update' => 'Error on department updating',
        ],

        'designations' =>		[
            'create' => 'Error on designation creation',
            'delete' => 'Error on designation deletion',
            'update' => 'Error on designation updating',
        ],

        'doc_audits' =>		[
            'create' => 'Error on doc audit creation',
            'delete' => 'Error on doc audit deletion',
            'update' => 'Error on doc audit updating',
        ],

        'doc_templates' =>		[
            'create' => 'Error on doc template creation',
            'delete' => 'Error on doc template deletion',
            'update' => 'Error on doc template updating',
        ],

        'equipment' => [
            'create' => 'Error on equipment creation',
            'update' => 'Error on equipment updating',
            'delete' => 'Error on equipment deletion',
        ],

        'employees' =>		[
            'already_exist' => 'There is already an employee with the same info (passport number, NRIC OR name with surname with birth date]',
            'create' => 'Error on employee creation',
            'delete' => 'Error on employee deletion',
            'duplicate_staff_id' => 'One of thumbs id is duplicated',
            'update' => 'Error on employee updating',
        ],

        'fingerprints' =>		[
            'import' => 'Error on fingerprints importing',
        ],

        'flight_reservation_quotations' =>		[
            'create' => 'Error on flight reservation quotation creation',
            'delete' => 'Error on flight reservation quotation deletion',
            'update' => 'Error on flight reservation quotation updating',
        ],

        'flight_reservations' =>		[
            'create' => 'Error on flight reservation creation',
            'delete' => 'Error on flight reservation deletion',
            'insufficient_quote' => 'Insufficient quotations count, at least add :count',
            'invalid_trip_price' => 'Invalid trip price',
            'prf_not_generated' => 'PRF is not generated yet!',
            'update' => 'Error on flight reservation updating',
            'upload_receipts' => 'Upload receipts',
        ],

        'holidays' =>		[
            'create' => 'Error on holiday creation',
            'delete' => 'Error on holiday deletion',
            'update' => 'Error on holiday updating',
        ],

        'inventories' =>		[
            'create' => 'Error on Inventory creation',
            'delete' => 'Error on Inventory deletion',
            'update' => 'Error on Inventory updating',
        ],

        'inventory_item_categories' =>		[
            'create' => 'Error on inventory item category creation',
            'delete' => 'Error on inventory item category deletion',
            'id_same_parent' => 'Item can not be parent of its self',
            'update' => 'Error on inventory item category updating',
        ],

        'inventory_items' =>		[
            'create' => 'Error on inventory item creation',
            'delete' => 'Error on inventory item deletion',
            'update' => 'Error on inventory item updating',
        ],

        'inventory_transactions' =>		[
            'create' => 'Error on Inventory transaction creation',
            'delete' => 'Error on Inventory transaction deletion',
            'update' => 'Error on Inventory transaction updating',
            'insufficient_items' => 'Insufficient items count to be checked out',
        ],

        'leaves' =>		[
            'already_exist' => 'There is another leave which overlap with this one',
            'create' => 'Error on leave creation',
            'delete' => 'Error on leave deletion',
            'employee_on_probation' => 'you are on probationary period, you only entitled to apply for a leave when you get permanent',
            'insufficient_entitlement' => 'Insufficient entitlement',
            'update' => 'Error on leave updating',
        ],

        'payment_requisitions' =>		[
            'create' => 'Error on payment requisition creation',
            'delete' => 'Error on payment requisition deletion',
            'update' => 'Error on payment requisition updating',
            'upload_payment_voucher' => 'Upload payment voucher',
        ],

        'purchase_requisitions' =>		[
            'already_exist' => 'There is already a purchase requisition with the same number',
            'create' => 'Error on purchase requisition creation',
            'delete' => 'Error on purchase requisition deletion',
            'insufficient_quote' => 'Insufficient quotations count, at least add one',
            'no_quote_selected' => 'Select the most suitable quotation depend on specification, quality and price',
            'update' => 'Error on purchase requisition updating',
        ],

        'quotations' =>		[
            'create' => 'Error on quotation creation',
            'delete' => 'Error on quotation deletion',
            'update' => 'Error on quotation updating',
        ],

        'representative' =>		[
            'already_exist' => 'There is already a representative with the same name',
            'create' => 'Error on representative creation',
            'delete' => 'Error on representative deletion',
            'update' => 'Error on representative updating',
        ],

        'roles' =>		[
            'create' => 'Error on role creation',
            'delete' => 'Error on role deletion',
            'update' => 'Error on role updating',
        ],

        'seafarer_contracts' =>		[
            'already_exist' => 'There is already a contract for the same seafarer and going on',
            'approve' => 'Error on approving seafarer contract',
            'create' => 'Error on seafarer contract creation',
            'delete' => 'Error on seafarer contract deletion',
            'delete_sign_off_first' => 'Can\'t delete sign on without deleting sign off',
            'illegal_sign_off' => 'Can\'t add sign off for seafarer without sign on',
            'illegal_sign_off_approve' => 'Can\'t approve sign off for seafarer before approving sign on',
            'seafarer_blacklisted' => 'This seafarer is blacklisted, please whitelist him first',
            'sign_delete' => 'Error on sign deletion',
            'sign_must_be_pending' => 'Sign must be in pending state to be able to delete it',
            'sign_off_before_sign_in' => 'Sign off date must be after sign on date',
            'update' => 'Error on seafarer contract updating',
        ],

        'seafarers' =>		[
            'already_exist' => 'There is already a seafarer with the same info (passport number, NRIC OR name with surname with birth date]',
            'create' => 'Error on seafarer creation',
            'delete' => 'Error on seafarer deletion',
            'update' => 'Error on seafarer updating',
        ],

        'tasks' =>		[
            'create' => 'Error on task creation',
            'delete' => 'Error on task deletion',
            'update' => 'Error on task updating',
        ],

        'users' =>		[
            'cannot_set_superior_roles' => 'You cannot attribute roles superior to yours',
            'create' => 'Error on user creation',
            'delete' => 'Error on user deletion',
            'first_user_cannot_be_destroyed' => 'Super admin user cannot be deleted',
            'first_user_cannot_be_disabled' => 'Super admin user cannot be disabled',
            'first_user_cannot_be_edited' => 'You cannot edit super admin user',
            'first_user_cannot_be_impersonated' => 'Super admin user cannot be impersonated',
            'update' => 'Error on user updating',
        ],

        'vessel_forms' =>		[
            'create' => 'Error on vessel form creation',
            'delete' => 'Error on vessel form deletion',
            'update' => 'Error on vessel form updating',
        ],

        'vessel_insurances' =>		[
            'create' => 'Error on vessel insurance creation',
            'delete' => 'Error on vessel insurance deletion',
            'update' => 'Error on vessel insurance updating',
        ],

        'vessels' =>		[
            'already_exist' => 'There is already a vessel with the same name',
            'create' => 'Error on vessel creation',
            'delete' => 'Error on vessel deletion',
            'duplicate_certificate_summary' => 'There is a duplicate in certificates summary',
            'duplicate_certificates' => 'There is a duplicate in certificates list',
            'update' => 'Error on vessel updating',
        ],

        'groups' => [
            'create' => 'Error on group creation',
            'update' => 'Error on group updating',
            'delete' => 'Error on group deletion',
        ],
    ],

    'general' => 'Server exception',
    'not_found' => 'Item not found',
    'unauthorized' => 'Action not allowed',
];