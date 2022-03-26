<?php

return [
    'layout' => [
        'hello'               => 'Hello !',
        'regards'             => 'Regards',
        'trouble'             => 'If you’re having trouble clicking the :action button, copy and paste the URL below into your web browser :',
        'all_rights_reserved' => 'All rights reserved.',
    ],
    'certificate_expiry' => [
        'subject' => 'Vessels\' certificates expiry notification',
    ],
    'company_registration_expiry' => [
        'subject' => 'Company Registration Expiry',
    ],
    'password_reset' => [
        'subject' => 'Password reset',
        'intro'   => 'You are receiving this email because we received a password reset request for your account.',
        'action'  => 'Reset Password',
        'outro'   => 'If you did not request a password reset, no further action is required.',
    ],
    'email_confirmation' => [
        'subject' => 'Email confirmation',
        'intro'   => 'Email confirmation is required in order to unleash your account.',
        'action'  => 'Confirm my email',
        'outro'   => 'Your account will be limited as long as your email remains not confirmed.',
    ],
    'contact' => [
        'subject'     => 'New contact message',
        'new_contact' => 'You\'ve got a new contact message. Submission detail :',
    ],
    'alert' => [
        'subject' => '[:app_name] Exception error',
        'message' => 'You\'ve got unexpected server exception error which message is : :message.',
        'full_message' => "You've got unexpected server exception error which message is : :message<br>User: :user<br>URL: :url",
        'trace'   => 'All trace detail :',
    ],
    'seafarer_contract_singing_request' => [
        'subject'       => 'New Seafarer Notification',
        'hello'         => 'Hello :vessel\'s Master!',
        'message'       => 'You got a new seafarer with the following information:',
        'sign_request'  => 'Please print the attached contract, ask seafarer to sing it and then attach the signed version to IKCP be clicking the bellow button',
        'seafarer_info' => 'Seafarer Information:',
    ],
    'new_task_assigned' => [
        'title'            => 'Title',
        'description'      => 'Description',
        'subject'          => 'New Task',
        'hello'            => 'Dear Sir/Madam :employee_name,',
        'message'          => 'A new task has been assigned to you with the following information:',
        'followup_request' => 'Please follow up this task by following this link:',
    ],
    'new_comment_assigned' => [
        'title'            => ':item Title',
        'description'      => 'Comment',
        'subject'          => 'New Comment',
        'hello'            => 'Dear Sir/Madam',
        'message'          => 'A new comment has been written by :commented to the following :commentable_type',
        'followup_request' => 'Please follow up this comment by following this link:',
    ],
    'imo_report' => [
        'subject' => 'IMO Report for vessel :vessel',
        'body'    => 'In the attached file you can find the generated IMO report for vessel :vessel',
    ],
    'workflowable_to_pass' => [
        'subject'          => 'New :workflowable',
        'hello'            => 'Dear Sir/Madam,',
        'requester'        => 'Requester',
        'message'          => 'You get a new :workflowable to pass with the following information:',
        'followup_request' => 'Please follow up this :workflowable by following this link:',
        'types'            => [
            'CashRequisition'	      => 'Cash Requisition Request',
            'PaymentRequisition'	   => 'Payment Requisition Request',
            'PurchaseRequisition'	  => 'Purchase Requisition Request',
            'FlightReservation'	    => 'Flight Reservation Request',
            'Leave'	                => 'Leave Request',
        ],
    ],
    'workflowable_passed' => [
        'subject'          => ':workflowable progress notification',
        'hello'            => 'Dear Sir/Madam,',
        'requester'        => 'Requester',
        'message'          => 'Your :workflowable passed to :status status.',
        'followup_request' => 'Please follow up this :workflowable by following this link:',
    ],
    'employee_updated' => [
        'subject'          => 'An Employee has Updated his Information',
        'hello'            => 'Dear Sir/Madam,',
        'Employee'         => 'Employee Name:',
        'message'          => 'An Employee has Updated his Information.',
    ],
    'trips_report' => [
        'subject'          => 'Monthly trips report.',
        'hello'            => 'Dear Sir/Madam,',
    ],  'announcement' => [
        'hello'            => 'Dear All,',
    ],
];
