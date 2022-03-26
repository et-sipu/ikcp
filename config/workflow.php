<?php

return [
    'activity_applying'   => [
        'type'          => 'workflow',
        'marking_store' => [
            'type'      => 'single_state',
            'arguments' => ['status'],
        ],
        'supports'      => [\App\Models\Activity::class],
        'places'        => ['pending', 'rejected', 'approved', 'done', 'closed'],
        'transitions'   => [
            'reject' => [
                'from' => 'pending',
                'to'   => 'rejected',
            ],
            'approve' => [
                'from' => 'pending',
                'to'   => 'approved',
            ],
            'achieve' => [
                'from' => 'approved',
                'to'   => 'done',
            ],
            'decline' => [
                'from' => 'done',
                'to'   => 'approved',
            ],
            'close' => [
                'from' => 'done',
                'to'   => 'closed',
            ],
        ],
    ],
    'task_applying'   => [
        'type'          => 'workflow',
        'marking_store' => [
            'type'      => 'single_state',
            'arguments' => ['status'],
        ],
        'supports'      => [\App\Models\Task::class],
        'places'        => ['pending', 'done', 'closed'],
        'transitions'   => [
            'achieve' => [
                'from' => 'pending',
                'to'   => 'done',
            ],
            'decline' => [
                'from' => 'done',
                'to'   => 'pending',
            ],
            'close' => [
                'from' => 'done',
                'to'   => 'closed',
            ],
        ],
    ],
    'purchase_process' => [
        'type'          => 'workflow',
        'marking_store' => [
            'type'      => 'single_state',
            'arguments' => ['status'],
        ],
        'supports'      => [\App\Models\PurchaseRequisition::class],
        'places'        => [
            'tech_approving', 'supper_approving', 'sourcing',
            'po_issuing', 'prf_issuing', 'fin_approving', 'md_approving',
            'ceo_approving', 'dec_1_approving', 'payment_preparing', 'dec_2_approving', 'payment_releasing',
            'buying', 'delivering', 'delivered', 'rejected',
        ],
        'transitions'   => [
            'tech_approved' => [
                'from' => 'tech_approving',
                'to'   => 'supper_approving',
            ],
            'tech_declined' => [
                'from' => 'tech_approving',
                'to'   => 'rejected',
            ],
            'supper_approved' => [
                'from' => 'supper_approving',
                'to'   => 'sourcing',
            ],
            'supper_declined' => [
                'from' => 'supper_approving',
                'to'   => 'rejected',
            ],
            'sourced' => [
                'from' => 'sourcing',
                'to'   => 'po_issuing',
            ],
            'po_issued' => [
                'from' => 'po_issuing',
                'to'   => 'prf_issuing',
            ],
            'prf_issued' => [
                'from' => 'prf_issuing',
                'to'   => 'fin_approving',
            ],
            'fin_approved' => [
                'from' => 'fin_approving',
                'to'   => 'md_approving',
            ],
            'fin_declined' => [
                'from' => 'fin_approving',
                'to'   => 'rejected',
            ],
            'md_approved' => [
                'from' => 'md_approving',
                'to'   => 'ceo_approving',
            ],
            'dm_declined' => [
                'from' => 'md_approving',
                'to'   => 'rejected',
            ],
            'ceo_approved' => [
                'from' => 'ceo_approving',
                'to'   => 'dec_1_approving',
            ],
            'ceo_declined' => [
                'from' => 'ceo_approving',
                'to'   => 'rejected',
            ],
            'dec_1_approved' => [
                'from' => 'dec_1_approving',
                'to'   => 'payment_preparing',
            ],
            'dec_1_declined' => [
                'from' => 'dec_1_approving',
                'to'   => 'rejected',
            ],
            'payment_prepared' => [
                'from' => 'payment_preparing',
                'to'   => 'dec_2_approving',
            ],
            'dec_2_approved' => [
                'from' => 'dec_2_approving',
                'to'   => 'payment_releasing',
            ],
            'paymet_released' => [
                'from' => 'payment_releasing',
                'to'   => 'buying',
            ],
            'bought' => [
                'from' => 'buying',
                'to'   => 'delivering',
            ],
            'delivered' => [
                'from' => 'delivering',
                'to'   => 'delivered',
            ],
        ],
    ],
    'cash_requisition_process' => [
        'type'          => 'workflow',
        'marking_store' => [
            'type'      => 'single_state',
            'arguments' => ['status'],
        ],
        'supports'      => [\App\Models\CashRequisition::class],
        'places'        => [
            'hod_approving', 'hr_approving', 'finance_approving', 'eco_approving', 'dec_approving', 'releasing', 'proofing', 'closing', 'closed', 'rejected',
        ],
        'happy_path' => [
            'hod_approving', 'hr_approving', 'finance_approving', 'eco_approving', 'dec_approving', 'releasing', 'proofing', 'closing', 'closed',
        ],
        'transitions'   => [
            'hod_approved' => [
                'from' => 'hod_approving',
                'to'   => 'hr_approving',
            ],
            'urgent_hod_approved' => [
                'from' => 'hod_approving',
                'to'   => 'finance_approving',
            ],
            'hod_declined' => [
                'from' => 'hod_approving',
                'to'   => 'rejected',
            ],
            'hr_approved' => [
                'from' => 'hr_approving',
                'to'   => 'finance_approving',
            ],
            'urgent_hr_approved' => [
                'from' => 'hr_approving',
                'to'   => 'finance_approving',
            ],
            'hr_declined' => [
                'from' => 'hr_approving',
                'to'   => 'rejected',
            ],
            'finance_approved' => [
                'from' => 'finance_approving',
                'to'   => 'eco_approving',
            ],
            'urgent_finance_approved' => [
                'from' => 'finance_approving',
                'to'   => 'dec_approving',
            ],
            'finance_declined' => [
                'from' => 'finance_approving',
                'to'   => 'rejected',
            ],
            'eco_approved' => [
                'from' => 'eco_approving',
                'to'   => 'dec_approving',
            ],
            'urgent_eco_approved' => [
                'from' => 'eco_approving',
                'to'   => 'dec_approving',
            ],
            'eco_declined' => [
                'from' => 'eco_approving',
                'to'   => 'rejected',
            ],
            'dec_approved' => [
                'from' => 'dec_approving',
                'to'   => 'releasing',
            ],
            'dec_declined' => [
                'from' => 'dec_approving',
                'to'   => 'rejected',
            ],
            'released' => [
                'from' => 'releasing',
                'to'   => 'proofing',
            ],
            'proofed' => [
                'from' => 'proofing',
                'to'   => 'closing',
            ],
            'close' => [
                'from' => 'closing',
                'to'   => 'closed',
            ],
        ],
    ],
    'payment_requisition_process' => [
        'type'          => 'workflow',
        'marking_store' => [
            'type'      => 'single_state',
            'arguments' => ['status'],
        ],
        'supports'      => [\App\Models\PaymentRequisition::class],
        'places'        => [
            'hod_approving', 'finance_approving', 'md_approving', 'ceo_approving', 'dec_approving', 'releasing', 'closed', 'rejected',
        ],
        'happy_path' => [
            'hod_approving', 'finance_approving', 'md_approving', 'ceo_approving', 'dec_approving', 'releasing', 'closed',
        ],
        'transitions'   => [
            'hod_approved' => [
                'from' => 'hod_approving',
                'to'   => 'finance_approving',
            ],
            'urgent_hod_approved' => [
                'from' => 'hod_approving',
                'to'   => 'finance_approving',
            ],
            'hod_declined' => [
                'from' => 'hod_approving',
                'to'   => 'rejected',
            ],
            'finance_approved' => [
                'from' => 'finance_approving',
                'to'   => 'md_approving',
            ],
            'urgent_finance_approved' => [
                'from' => 'finance_approving',
                'to'   => 'dec_approving',
            ],
            'finance_declined' => [
                'from' => 'finance_approving',
                'to'   => 'rejected',
            ],
            'md_approved' => [
                'from' => 'md_approving',
                'to'   => 'ceo_approving',
            ],
            'urgent_md_approved' => [
                'from' => 'md_approving',
                'to'   => 'dec_approving',
            ],
            'md_declined' => [
                'from' => 'md_approving',
                'to'   => 'rejected',
            ],
            'ceo_approved' => [
                'from' => 'ceo_approving',
                'to'   => 'dec_approving',
            ],
            'urgent_ceo_approved' => [
                'from' => 'ceo_approving',
                'to'   => 'dec_approving',
            ],
            'ceo_declined' => [
                'from' => 'ceo_approving',
                'to'   => 'rejected',
            ],
            'dec_approved' => [
                'from' => 'dec_approving',
                'to'   => 'releasing',
            ],
            'dec_declined' => [
                'from' => 'dec_approving',
                'to'   => 'rejected',
            ],
            'closing' => [
                'from' => 'releasing',
                'to'   => 'closed',
            ],
        ],
    ],
    'flight_reservation_process' => [
        'type'          => 'workflow',
        'marking_store' => [
            'type'      => 'single_state',
            'arguments' => ['status'],
        ],
        'supports'      => [\App\Models\FlightReservation::class],
        'places'        => [
//            'vessels_hod_approving', 'sites_hod_approving', 'ceo_approving', 'budgeting', 'prf_issuing', 'prf_approving', 'purchasing', 'deducting', 'closed', 'rejected',
            'vessels_hod_approving', 'sites_hod_approving', 'prf_issuing', 'purchasing', 'closed', 'rejected',
//            ['hod_approving' => ['vessels_hod_approving', 'sites_hod_approving'], 'prf_issuing', 'purchasing', 'closed', 'rejected',
        ],
        'happy_path'        => [
            'vessels_hod_approving', 'sites_hod_approving', 'prf_issuing', 'prf_approving', 'purchasing', 'closed',
//            ['hod_approving' => ['vessels_hod_approving', 'sites_hod_approving']], 'prf_issuing', 'prf_approving', 'purchasing', 'closed',
        ],
        'transitions'   => [
            'vessels_hod_approved' => [
                'from' => 'vessels_hod_approving',
                'to'   => 'prf_issuing',
            ],
            'vessels_hod_declined' => [
                'from' => 'vessels_hod_approving',
                'to'   => 'rejected',
            ],
            'sites_hod_approved' => [
                'from' => 'sites_hod_approving',
                'to'   => 'prf_issuing',
            ],
            'sites_hod_declined' => [
                'from' => 'sites_hod_approving',
                'to'   => 'rejected',
            ],
//            'ceo_approved' => [
//                'from' => 'ceo_approving',
//                'to'   => 'budgeting',
//            ],
//            'ceo_declined' => [
//                'from' => 'ceo_approving',
//                'to'   => 'rejected',
//            ],
//            'budgeted' => [
//                'from' => 'budgeting',
//                'to'   => 'prf_issuing',
//            ],
            'prf_issued' => [
                'from' => 'prf_issuing',
                'to'   => 'purchasing',
            ],
//            'prf_issued' => [
//                'from' => 'prf_issuing',
//                'to'   => 'prf_approving',
//            ],
//            'prf_approved' => [
//                'from' => 'prf_approving',
//                'to'   => 'purchasing',
//            ],
//            'prf_rejected' => [
//                'from' => 'prf_approving',
//                'to'   => 'rejected',
//            ],
//            'purchased' => [
//                'from' => 'purchasing',
//                'to'   => 'deducting',
//            ],
            'closing' => [
                'from' => 'purchasing',
                'to'   => 'closed',
            ],
        ],
    ],
    'leave_process' => [
        'type'          => 'workflow',
        'marking_store' => [
            'type'      => 'single_state',
            'arguments' => ['status'],
        ],
        'supports'      => [\App\Models\Leave::class],
        'places'        => [
            'hod_approving', 'dec_approving', 'hr_approving', 'approved', 'rejected',
        ],
        'transitions'   => [
            'hod_approved' => [
                'from' => 'hod_approving',
                'to'   => 'hr_approving',
            ],
            'hod_declined' => [
                'from' => 'hod_approving',
                'to'   => 'rejected',
            ],
            'hod_approved_long_leave' => [
                'from' => 'hod_approving',
                'to'   => 'dec_approving',
            ],
            'dec_approved' => [
                'from' => 'dec_approving',
                'to'   => 'hr_approving',
            ],
            'dec_declined' => [
                'from' => 'dec_approving',
                'to'   => 'rejected',
            ],
            'hr_approved' => [
                'from' => 'hr_approving',
                'to'   => 'approved',
            ],
            'hr_declined' => [
                'from' => 'hr_approving',
                'to'   => 'rejected',
            ],
        ],
    ],
];
