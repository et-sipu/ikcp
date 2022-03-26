export default {
    "en": {
        "auth": {
            "failed": "These credentials do not match our records.",
            "throttle": "Too many login attempts. Please try again in {seconds} seconds."
        },
        "mails": {
            "layout": {
                "hello": "Hello !",
                "regards": "Regards",
                "trouble": "If you’re having trouble clicking the {action} button, copy and paste the URL below into your web browser :",
                "all_rights_reserved": "All rights reserved."
            },
            "certificate_expiry": {
                "subject": "Vessels' certificates expiry notification"
            },
            "password_reset": {
                "subject": "Password reset",
                "intro": "You are receiving this email because we received a password reset request for your account.",
                "action": "Reset Password",
                "outro": "If you did not request a password reset, no further action is required."
            },
            "email_confirmation": {
                "subject": "Email confirmation",
                "intro": "Email confirmation is required in order to unleash your account.",
                "action": "Confirm my email",
                "outro": "Your account will be limited as long as your email remains not confirmed."
            },
            "contact": {
                "subject": "New contact message",
                "new_contact": "You've got a new contact message. Submission detail :"
            },
            "alert": {
                "subject": "[{app_name}] Exception error",
                "message": "You've got unexpected server exception error which message is : {message}.",
                "full_message": "You've got unexpected server exception error which message is : {message}<br>User: {user}<br>URL: {url}",
                "trace": "All trace detail :"
            },
            "seafarer_contract_singing_request": {
                "subject": "New Seafarer Notification",
                "hello": "Hello {vessel}'s Master!",
                "message": "You got a new seafarer with the following information:",
                "sign_request": "Please print the attached contract, ask seafarer to sing it and then attach the signed version to IKCP be clicking the bellow button",
                "seafarer_info": "Seafarer Information:"
            },
            "new_task_assigned": {
                "title": "Title",
                "description": "Description",
                "subject": "New Task",
                "hello": "Dear Sir/Madam {employee_name},",
                "message": "A new task has been assigned to you with the following information:",
                "followup_request": "Please follow up this task by following this link:"
            },
            "new_comment_assigned": {
                "title": "{item} Title",
                "description": "Comment",
                "subject": "New Comment",
                "hello": "Dear Sir/Madam",
                "message": "A new comment has been written by {commented} to the following {commentable_type}",
                "followup_request": "Please follow up this comment by following this link:"
            },
            "imo_report": {
                "subject": "IMO Report for vessel {vessel}",
                "body": "In the attached file you can find the generated IMO report for vessel {vessel}"
            },
            "workflowable_to_pass": {
                "subject": "New {workflowable}",
                "hello": "Dear Sir/Madam,",
                "requester": "Requester",
                "message": "You get a new {workflowable} to pass with the following information:",
                "followup_request": "Please follow up this {workflowable} by following this link:",
                "types": {
                    "CashRequisition": "Cash Requisition Request",
                    "PaymentRequisition": "Payment Requisition Request",
                    "PurchaseRequisition": "Purchase Requisition Request",
                    "FlightReservation": "Flight Reservation Request",
                    "Leave": "Leave Request"
                }
            },
            "workflowable_passed": {
                "subject": "{workflowable} progress notification",
                "hello": "Dear Sir/Madam,",
                "requester": "Requester",
                "message": "Your {workflowable} passed to {status} status.",
                "followup_request": "Please follow up this {workflowable} by following this link:"
            },
            "employee_updated": {
                "subject": "An Employee has Updated his Information",
                "hello": "Dear Sir/Madam,",
                "Employee": "Employee Name:",
                "message": "An Employee has Updated his Information."
            },
            "trips_report": {
                "subject": "Monthly trips report.",
                "hello": "Dear Sir/Madam,"
            },
            "announcement": {
                "hello": "Dear All,"
            }
        },
        "permissions": {
            "access": {
                "backend": {
                    "description": "Can access to administration pages.",
                    "display_name": "Backoffice access"
                },
                "change_password": {
                    "description": "Can change his own password",
                    "display_name": "Change own password"
                }
            },
            "blacklist": {
                "description": "Can blacklist Seafarer",
                "display_name": "Blacklist"
            },
            "categories": {
                "access": "Access",
                "activities": "Activities",
                "admin_forms": "Admin Forms",
                "crew": "Crew",
                "vessels": "Vessels",
                "departments": "Departments",
                "doc_audits": "Document Of Compliance Audit",
                "finance": "Finance",
                "hr": "Human Resources",
                "procurement": "Procurement",
                "setting": "Setting",
                "tasks": "Tasks",
                "work_management": "Work Management",
                "inventories": "Inventories"
            },
            "create": {
                "activities": {
                    "description": "Can create activities.",
                    "display_name": "Create activities"
                },
                "announcements": {
                    "description": "Can create announcements.",
                    "display_name": "Create announcements"
                },
                "equipment": {
                    "display_name": "Create equipment",
                    "description": "Can create equipment."
                },
                "attendances": {
                    "description": "Can create attendances.",
                    "display_name": "Create attendances"
                },
                "branches": {
                    "description": "Can create branches.",
                    "display_name": "Create branches"
                },
                "cash_requisitions": {
                    "description": "Can create cash requisitions.",
                    "display_name": "Create cash requisitions"
                },
                "crew_requests": {
                    "description": "Can create crew requests.",
                    "display_name": "Create crew requests"
                },
                "departments": {
                    "description": "Can create departments.",
                    "display_name": "Create departments"
                },
                "designations": {
                    "description": "Can create designations.",
                    "display_name": "Create designations"
                },
                "doc_audits": {
                    "description": "Can create doc audits.",
                    "display_name": "Create doc audits"
                },
                "doc_templates": {
                    "description": "Can create doc templates.",
                    "display_name": "Create doc templates"
                },
                "employees": {
                    "description": "Can create employees.",
                    "display_name": "Create employees"
                },
                "flight_reservations": {
                    "description": "Can create flight reservations.",
                    "display_name": "Create flight reservations"
                },
                "holidays": {
                    "description": "Can create holidays.",
                    "display_name": "Create holidays"
                },
                "inventories": {
                    "description": "Can create inventories.",
                    "display_name": "Create inventories"
                },
                "inventory_item_categories": {
                    "description": "Can create inventory item categories.",
                    "display_name": "Create inventory item categories"
                },
                "inventory_items": {
                    "description": "Can create inventory items.",
                    "display_name": "Create inventory items"
                },
                "inventory_transactions": {
                    "description": "Can create inventory transactions.",
                    "display_name": "Create inventory transactions"
                },
                "leaves": {
                    "description": "Can create leaves.",
                    "display_name": "Create leaves"
                },
                "payment_requisitions": {
                    "description": "Can create payment requisitions.",
                    "display_name": "Create payment requisitions"
                },
                "ports": {
                    "description": "Can create ports.",
                    "display_name": "Create ports"
                },
                "purchase_requisitions": {
                    "description": "Can create purchase requisitions.",
                    "display_name": "Create purchase requisitions"
                },
                "roles": {
                    "description": "Can create roles.",
                    "display_name": "Create roles"
                },
                "seafarer_contracts": {
                    "description": "Can create seafarer contracts.",
                    "display_name": "Create seafarer contracts"
                },
                "seafarers": {
                    "description": "Can create seafarers.",
                    "display_name": "Create seafarers"
                },
                "tasks": {
                    "description": "Can create tasks.",
                    "display_name": "Create tasks"
                },
                "users": {
                    "description": "Can create users.",
                    "display_name": "Create users"
                },
                "vessel_certificates": {
                    "description": "Can create vessel certificates.",
                    "display_name": "Create vessel certificates"
                },
                "vessel_forms": {
                    "description": "Can create vessel reports.",
                    "display_name": "Create vessel reports"
                },
                "vessel_insurances": {
                    "description": "Can create vessel insurances.",
                    "display_name": "Create vessel insurances"
                },
                "vessels": {
                    "description": "Can create vessels.",
                    "display_name": "Create vessels"
                },
                "groups": {
                    "display_name": "Create groups",
                    "description": "Can create groups."
                }
            },
            "delete": {
                "activities": {
                    "description": "Can delete activities.",
                    "display_name": "Delete activities"
                },
                "equipment": {
                    "display_name": "Delete equipment",
                    "description": "Can delete equipment."
                },
                "announcements": {
                    "description": "Can delete announcements.",
                    "display_name": "Delete announcements"
                },
                "branches": {
                    "description": "Can delete branches.",
                    "display_name": "Delete branches"
                },
                "cash_requisitions": {
                    "description": "Can delete cash requisitions.",
                    "display_name": "Delete cash requisitions"
                },
                "crew_requests": {
                    "description": "Can delete crew requests.",
                    "display_name": "Delete crew requests"
                },
                "departments": {
                    "description": "Can delete departments.",
                    "display_name": "Delete departments"
                },
                "designations": {
                    "description": "Can delete designations.",
                    "display_name": "Delete designations"
                },
                "doc_audits": {
                    "description": "Can delete doc audits.",
                    "display_name": "Delete doc audits"
                },
                "doc_templates": {
                    "description": "Can delete doc templates.",
                    "display_name": "Delete doc templates"
                },
                "employees": {
                    "description": "Can delete employees.",
                    "display_name": "Delete employees"
                },
                "flight_reservations": {
                    "description": "Can delete flight reservations.",
                    "display_name": "Delete flight reservations"
                },
                "holidays": {
                    "description": "Can delete holidays.",
                    "display_name": "Delete holidays"
                },
                "inventories": {
                    "description": "Can delete inventories.",
                    "display_name": "Delete inventories"
                },
                "inventory_item_categories": {
                    "description": "Can delete inventory item categories.",
                    "display_name": "Delete inventory item categories"
                },
                "inventory_items": {
                    "description": "Can delete inventory items.",
                    "display_name": "Delete inventory items"
                },
                "inventory_transactions": {
                    "description": "Can delete inventory transactions.",
                    "display_name": "Delete inventory transactions"
                },
                "leaves": {
                    "description": "Can delete leaves.",
                    "display_name": "Delete leaves"
                },
                "payment_requisitions": {
                    "description": "Can delete payment requisitions.",
                    "display_name": "Delete payment requisitions"
                },
                "ports": {
                    "description": "Can delete ports.",
                    "display_name": "Delete ports"
                },
                "purchase_requisitions": {
                    "description": "Can delete purchase requisitions.",
                    "display_name": "Delete purchase requisitions"
                },
                "roles": {
                    "description": "Can delete roles.",
                    "display_name": "Delete roles"
                },
                "seafarer_contracts": {
                    "description": "Delete create seafarer contracts.",
                    "display_name": "Delete seafarer contracts"
                },
                "seafarers": {
                    "description": "Can delete seafarers.",
                    "display_name": "Delete seafarers"
                },
                "tasks": {
                    "description": "Can delete tasks.",
                    "display_name": "Delete tasks"
                },
                "users": {
                    "description": "Can delete users.",
                    "display_name": "Delete users"
                },
                "vessel_certificates": {
                    "description": "Can delete vessel certificates.",
                    "display_name": "Delete vessel certificates"
                },
                "vessel_forms": {
                    "description": "Can delete vessel reports.",
                    "display_name": "Delete vessel reports"
                },
                "vessel_insurances": {
                    "description": "Can delete vessel insurances.",
                    "display_name": "Delete vessel insurances"
                },
                "vessels": {
                    "description": "Can delete vessels.",
                    "display_name": "Delete vessels"
                },
                "groups": {
                    "display_name": "Delete groups",
                    "description": "Can delete groups."
                }
            },
            "edit": {
                "activities": {
                    "description": "Can edit activities.",
                    "display_name": "Edit activities"
                },
                "equipment": {
                    "display_name": "Edit equipment",
                    "description": "Can edit equipment."
                },
                "announcements": {
                    "description": "Can edit announcements.",
                    "display_name": "Edit announcements"
                },
                "attendances": {
                    "description": "Can edit attendances.",
                    "display_name": "Edit attendances"
                },
                "branches": {
                    "description": "Can edit branches.",
                    "display_name": "Edit branches"
                },
                "cash_requisitions": {
                    "description": "Can edit cash requisitions.",
                    "display_name": "Edit cash requisitions"
                },
                "crew_requests": {
                    "description": "Can edit crew requests.",
                    "display_name": "Edit crew requests"
                },
                "departments": {
                    "description": "Can edit departments.",
                    "display_name": "Edit departments"
                },
                "designations": {
                    "description": "Can edit designations.",
                    "display_name": "Edit designations"
                },
                "doc_audits": {
                    "description": "Can edit doc audits.",
                    "display_name": "Edit doc audits"
                },
                "doc_templates": {
                    "description": "Can edit doc templates.",
                    "display_name": "Edit doc templates"
                },
                "employees": {
                    "description": "Can edit employees.",
                    "display_name": "Edit employees"
                },
                "flight_reservations": {
                    "description": "Can edit flight reservations.",
                    "display_name": "Edit flight reservations"
                },
                "holidays": {
                    "description": "Can edit holidays.",
                    "display_name": "Edit holidays"
                },
                "inventory_item_categories": {
                    "description": "Can edit inventory item categories.",
                    "display_name": "Edit inventory item categories"
                },
                "inventory_items": {
                    "description": "Can edit inventory items.",
                    "display_name": "Edit inventory items"
                },
                "inventories": {
                    "description": "Can update inventories.",
                    "display_name": "Update inventories"
                },
                "inventory_transactions": {
                    "description": "Can update inventory transactions.",
                    "display_name": "Update inventory transactions"
                },
                "leaves": {
                    "description": "Can edit leaves.",
                    "display_name": "Edit leaves"
                },
                "groups": {
                    "display_name": "Edit groups",
                    "description": "Can edit groups."
                },
                "own": {
                    "cash_requisitions": {
                        "description": "Can edit own cash requisitions",
                        "display_name": "Edit own cash requisitions"
                    },
                    "employees": {
                        "description": "Can edit his own employment info",
                        "display_name": "Edit own employment info"
                    },
                    "flight_reservations": {
                        "description": "Can edit own flight reservations",
                        "display_name": "Edit own flight reservations"
                    },
                    "leaves": {
                        "description": "Can edit his own leaves",
                        "display_name": "Edit own leaves"
                    },
                    "payment_requisitions": {
                        "description": "Can edit own payment requisitions",
                        "display_name": "Edit own payment requisitions"
                    },
                    "purchase_requisitions": {
                        "description": "Can edit own purchase requisitions",
                        "display_name": "Edit own purchase requisitions"
                    },
                    "seafarer_contracts": {
                        "description": "Can edit own seafarer contracts",
                        "display_name": "Edit own seafarer contracts"
                    },
                    "seafarers": {
                        "description": "Can edit own seafarers who are not on approved contract or the approved contract is on master's vessel",
                        "display_name": "Edit own seafarers"
                    },
                    "vessels": {
                        "description": "Can edit own vessels",
                        "display_name": "Edit own vessels"
                    },
                    "equipment": {
                        "description": "Can edit own Equipment",
                        "display_name": "Edit own Equipment"
                    },
                    "inventories": {
                        "description": "Can edit own inventories",
                        "display_name": "Edit own inventories"
                    },
                    "inventory_transactions": {
                        "description": "Can edit own inventory transactions",
                        "display_name": "Edit own inventory transactions"
                    }
                },
                "payment_requisitions": {
                    "description": "Can edit payment requisitions.",
                    "display_name": "Edit payment requisitions"
                },
                "ports": {
                    "description": "Can edit ports.",
                    "display_name": "Edit ports"
                },
                "purchase_requisitions": {
                    "description": "Can edit purchase requisitions.",
                    "display_name": "Edit purchase requisitions"
                },
                "roles": {
                    "description": "Can edit roles.",
                    "display_name": "Edit roles"
                },
                "seafarer_contracts": {
                    "description": "Edit seafarer contracts.",
                    "display_name": "Edit seafarer contracts"
                },
                "seafarers": {
                    "description": "Can edit seafarers.",
                    "display_name": "Edit seafarers"
                },
                "tasks": {
                    "description": "Can edit tasks.",
                    "display_name": "Edit tasks"
                },
                "users": {
                    "description": "Can edit users.",
                    "display_name": "Edit users"
                },
                "vessel_certificates": {
                    "description": "Can edit vessel certificates.",
                    "display_name": "Edit vessel certificates"
                },
                "vessel_forms": {
                    "description": "Can edit vessel reports.",
                    "display_name": "Edit vessel reports"
                },
                "vessel_insurances": {
                    "description": "Can edit vessel insurances.",
                    "display_name": "Edit vessel insurances"
                },
                "vessels": {
                    "description": "Can edit vessels.",
                    "display_name": "Edit vessels"
                }
            },
            "impersonate": {
                "description": "Can take ownership of others user identities. Useful for tests.",
                "display_name": "Impersonate user"
            },
            "others": {
                "activities": {
                    "print": {
                        "description": "Can print activities list per selected filters",
                        "display_name": "Print Activities"
                    }
                },
                "announcements": {
                    "publish": {
                        "description": "Can publish announcements",
                        "display_name": "Publish announcements"
                    }
                },
                "attendances": {
                    "export": {
                        "description": "Can export daily attendance as excel file",
                        "display_name": "Export attendance"
                    }
                },
                "cash_requisitions": {
                    "closing": {
                        "description": "Can close cash requisitions after getting invoice from requester side",
                        "display_name": "Closing cash requisitions"
                    },
                    "dec_approving": {
                        "description": "Pass cash requisitions in DEC approving stage",
                        "display_name": "Approve cash requisitions (DEC]"
                    },
                    "eco_approving": {
                        "description": "Pass cash requisitions in ECO approving stage",
                        "display_name": "Approve cash requisitions (ECO]"
                    },
                    "export": {
                        "description": "Can export cash requisitions as excel file",
                        "display_name": "Export cash requisitions"
                    },
                    "finance_approving": {
                        "description": "Pass cash requisitions in finance approving stage",
                        "display_name": "Approve cash requisitions (finance]"
                    },
                    "hr_approving": {
                        "description": "Pass cash requisitions in HR approving stage",
                        "display_name": "Approve cash requisitions (HR]"
                    },
                    "print": {
                        "description": "Can print specific cash requisitions when reaching DEC Approving status",
                        "display_name": "Print cash requisitions"
                    },
                    "releasing": {
                        "description": "Can releasing money for cash requisitions",
                        "display_name": "Releasing cash requisitions"
                    },
                    "urgent": {
                        "description": "Pass cash requisitions in an urgent way so no need to wait for long approves",
                        "display_name": "Pass cash requisitions urgently"
                    }
                },
                "flight_reservations": {
                    "generate_report": {
                        "description": "Generate flight reservations' report for bank",
                        "display_name": "Generate flight reservations' report"
                    },
                    "prf_issuing": {
                        "description": "Issue the PRF depending on flight reservations budgets",
                        "display_name": "Issuing flight reservations PRF"
                    },
                    "purchasing": {
                        "description": "Purchase tickets for flight reservations",
                        "display_name": "Purchase flight reservations' tickets"
                    },
                    "sites_hod_approving": {
                        "description": "Pass flight reservations in ECo approving stage (Sites]",
                        "display_name": "Approve flight reservations (ECO Sites]"
                    },
                    "vessels_hod_approving": {
                        "description": "Pass flight reservations in ECO approving stage (Vessels]",
                        "display_name": "Approve flight reservations (ECO Vessels]"
                    }
                },
                "leaves": {
                    "hr_approving": {
                        "description": "Pass leaves in HR approving stage",
                        "display_name": "Approve leaves (HR]"
                    }
                },
                "own": {
                    "seafarer_contracts": {
                        "add_signs": {
                            "description": "Can add signon and sign off to his own seafarer",
                            "display_name": "Sign own seafarer on and off"
                        }
                    },
                    "vessels": {
                        "view_imo": {
                            "description": "Can view and preview own IMO reports",
                            "display_name": "View own IMO Report"
                        }
                    },
                    "inventory_transactions": {
                        "rob": {
                            "description": "Can view own ROB report",
                            "display_name": "View own ROB Report"
                        }
                    }
                },
                "payment_requisitions": {
                    "ceo_approving": {
                        "description": "Pass payment requisitions in CEO approving stage",
                        "display_name": "Approve payment requisitions (CEO)"
                    },
                    "dec_approving": {
                        "description": "Pass payment requisitions in DEC approving stage",
                        "display_name": "Approve payment requisitions (DEC]"
                    },
                    "export": {
                        "description": "Can export payment requisitions as excel file",
                        "display_name": "Export payment requisitions"
                    },
                    "finance_approving": {
                        "description": "Pass payment requisitions in finance approving stage",
                        "display_name": "Approve payment requisitions (finance]"
                    },
                    "md_approving": {
                        "description": "Pass payment requisitions in Managing Director approving stage",
                        "display_name": "Approve payment requisitions (MD]"
                    },
                    "print": {
                        "description": "Can print specific payment requisitions when reaching DEC Approving status",
                        "display_name": "Print payment requisitions"
                    },
                    "releasing": {
                        "description": "Can releasing money for payment requisitions",
                        "display_name": "Releasing payment requisitions"
                    },
                    "urgent": {
                        "description": "Pass payment requisitions in an urgent way so no need to wait for long approves",
                        "display_name": "Pass payment requisitions urgently"
                    }
                },
                "purchase_requisitions": {
                    "buying": {
                        "description": "Execute buying order to satisfy purchase requistions",
                        "display_name": "Execute purchase requisitions"
                    },
                    "ceo_approving": {
                        "description": "Pass purchase requisitions in CEO approving stage",
                        "display_name": "Approve purchase requisitions (CEO]"
                    },
                    "dec_approving": {
                        "description": "Pass purchase requisitions in DEC_1 & DEC_2 approving stage",
                        "display_name": "Approve purchase requisitions (DEC]"
                    },
                    "delivered": {
                        "description": "Close purchase requisitions by marking it as delivered",
                        "display_name": "Close purchase requisitions"
                    },
                    "delivering": {
                        "description": "Deliver purchase requisitions",
                        "display_name": "Deliver purchase requisitions"
                    },
                    "fin_approving": {
                        "description": "Pass purchase requisitions in financial approving stage",
                        "display_name": "Approve purchase requisitions (Financial]"
                    },
                    "md_approving": {
                        "description": "Pass purchase requisitions in Managing Director approving stage",
                        "display_name": "Approve purchase requisitions (MD]"
                    },
                    "payment_preparing": {
                        "description": "Prepare payment for purchase requisitions",
                        "display_name": "Prepare payment for purchase requisitions"
                    },
                    "payment_releasing": {
                        "description": "Release payment for purchase requisitions",
                        "display_name": "Release payment for purchase requisitions"
                    },
                    "po_issuing": {
                        "description": "Issue purchase order for purchase requisitions",
                        "display_name": "PO Issuing for purchase requisitions"
                    },
                    "prf_issuing": {
                        "description": "Issue PRF for purchase requisitions",
                        "display_name": "PRF Issuing for purchase requisitions"
                    },
                    "sourcing": {
                        "description": "Source purchase requisitions by adding many quatation",
                        "display_name": "Sourcing purchase requisitions"
                    },
                    "supper_approving": {
                        "description": "Pass purchase requisitions in supper approving stage",
                        "display_name": "Approve purchase requisitions (Super Technical]"
                    },
                    "tech_approving": {
                        "description": "Verify purchase requisitions technically",
                        "display_name": "Verify purchase requisitions (Technical]"
                    }
                },
                "representative": {
                    "description": "Can be HR representative",
                    "display_name": "Attendance Representative"
                },
                "seafarer_contracts": {
                    "add_signs": {
                        "description": "Can add signon and sign off to seafarer",
                        "display_name": "Sign seafarer on and off"
                    },
                    "approve": {
                        "description": "Can approve seafarer contract",
                        "display_name": "Approve seafarer contract"
                    },
                    "approve_signs": {
                        "description": "Can approve seafarer signs on and off",
                        "display_name": "Approve seafarer signs"
                    },
                    "delete_signs": {
                        "description": "Can delete seafarer pending signs",
                        "display_name": "Delete seafarer pending signs"
                    },
                    "preview": {
                        "description": "Can preview seafarer contract without the ability to save it but only print it",
                        "display_name": "Preview seafarer contract"
                    }
                },
                "tasks": {
                    "print": {
                        "description": "Can print tasks list per selected filters",
                        "display_name": "Print Tasks"
                    }
                },
                "inventory_transactions": {
                    "rob": {
                        "description": "Can see ROB report",
                        "display_name": "ROB Report"
                    }
                },
                "vessels": {
                    "generate_imo": {
                        "description": "Can generate IMO reports for own vessels",
                        "display_name": "Generate IMO Report"
                    },
                    "view_imo": {
                        "description": "Can view and preview IMO reports for all vessels",
                        "display_name": "View IMO Report"
                    }
                }
            },
            "show": {
                "seafarers": {
                    "description": "Can show individual seafarers without the ability to edit.",
                    "display_name": "Show seafarer"
                }
            },
            "view": {
                "activities": {
                    "description": "Can view activities.",
                    "display_name": "View activities"
                },
                "announcements": {
                    "description": "Can view announcements.",
                    "display_name": "View announcements"
                },
                "attendances": {
                    "description": "Can view attendances.",
                    "display_name": "View attendances"
                },
                "audits": {
                    "description": "Can view audits.",
                    "display_name": "View Audits"
                },
                "branches": {
                    "description": "Can view branches.",
                    "display_name": "View branches"
                },
                "cash_requisitions": {
                    "description": "Can view cash requisitions.",
                    "display_name": "View cash requisitions"
                },
                "crew_requests": {
                    "description": "Can view crew requests.",
                    "display_name": "View crew requests"
                },
                "departments": {
                    "description": "Can view departments.",
                    "display_name": "View departments"
                },
                "designations": {
                    "description": "Can view designations.",
                    "display_name": "View designations"
                },
                "doc_audits": {
                    "description": "Can view doc audits.",
                    "display_name": "View doc audits"
                },
                "doc_templates": {
                    "description": "Can view doc templates.",
                    "display_name": "View doc templates"
                },
                "employees": {
                    "description": "Can view employees.",
                    "display_name": "View employees"
                },
                "equipment": {
                    "display_name": "View equipment",
                    "description": "Can view equipment."
                },
                "fingerprints": {
                    "description": "Can view fingerprints.",
                    "display_name": "View fingerprints"
                },
                "flight_reservations": {
                    "description": "Can view flight reservations.",
                    "display_name": "View flight reservations"
                },
                "holidays": {
                    "description": "Can view holidays.",
                    "display_name": "View holidays"
                },
                "inventories": {
                    "description": "Can view inventories.",
                    "display_name": "View inventories"
                },
                "inventory_item_categories": {
                    "description": "Can view inventory item categories.",
                    "display_name": "View inventory item categories"
                },
                "inventory_items": {
                    "description": "Can view inventory items.",
                    "display_name": "View inventory items"
                },
                "inventory_transactions": {
                    "description": "Can view inventory transactions.",
                    "display_name": "View inventory transactions"
                },
                "leaves": {
                    "description": "Can view leaves.",
                    "display_name": "View leaves"
                },
                "groups": {
                    "display_name": "View groups",
                    "description": "Can view groups."
                },
                "own": {
                    "activities": {
                        "description": "Can view own activity",
                        "display_name": "View own activity"
                    },
                    "equipment": {
                        "display_name": "View own Equipment",
                        "description": "Can view own Equipment"
                    },
                    "announcements": {
                        "description": "Can view own announcements.",
                        "display_name": "View own announcements"
                    },
                    "cash_requisitions": {
                        "description": "Can view own cash requisitions",
                        "display_name": "View own cash requisitions"
                    },
                    "employees": {
                        "description": "Can view his own employment info",
                        "display_name": "View own employment info"
                    },
                    "flight_reservations": {
                        "description": "Can view own flight reservations.",
                        "display_name": "View own flight reservations"
                    },
                    "leaves": {
                        "description": "Can view his own leaves",
                        "display_name": "View own leaves"
                    },
                    "payment_requisitions": {
                        "description": "Can view own payment requisitions.",
                        "display_name": "View own payment requisitions"
                    },
                    "purchase_requisitions": {
                        "description": "Can view own purchase requisitions.",
                        "display_name": "View own purchase requisitions"
                    },
                    "seafarer_contracts": {
                        "description": "Can view own seafarer contracts",
                        "display_name": "View own seafarer contracts"
                    },
                    "tasks": {
                        "description": "Can view own task",
                        "display_name": "View own task"
                    },
                    "vessels": {
                        "description": "Can view own vessel",
                        "display_name": "View own vessel"
                    },
                    "inventory_transactions": {
                        "description": "Can view own inventory transactions.",
                        "display_name": "View own inventory transactions"
                    },
                    "inventories": {
                        "description": "Can view own inventories.",
                        "display_name": "View own inventories"
                    }
                },
                "payment_requisitions": {
                    "description": "Can view payment requisitions.",
                    "display_name": "View payment requisitions"
                },
                "ports": {
                    "description": "Can view ports.",
                    "display_name": "View ports"
                },
                "purchase_requisitions": {
                    "description": "Can view purchase requisitions.",
                    "display_name": "View purchase requisitions"
                },
                "roles": {
                    "description": "Can view roles.",
                    "display_name": "View roles"
                },
                "seafarer_contracts": {
                    "description": "Can view seafarer contracts.",
                    "display_name": "View seafarer contracts"
                },
                "seafarers": {
                    "description": "Can view seafarers.",
                    "display_name": "View seafarers"
                },
                "tasks": {
                    "description": "Can view tasks.",
                    "display_name": "View tasks"
                },
                "users": {
                    "description": "Can view users.",
                    "display_name": "View users"
                },
                "vessel_certificates": {
                    "description": "Can view vessel certificates.",
                    "display_name": "View vessel certificates"
                },
                "vessel_forms": {
                    "description": "Can view vessel reports.",
                    "display_name": "View vessel reports"
                },
                "vessel_insurances": {
                    "description": "Can view vessel insurances.",
                    "display_name": "View vessel insurances"
                },
                "vessels": {
                    "description": "Can view vessels.",
                    "display_name": "View vessels"
                }
            },
            "whitelist": {
                "description": "Can whitelist Seafarer",
                "display_name": "Whitelist"
            }
        },
        "labels": {
            "actions": "Actions",
            "add_new": "Add",
            "add_option": "Add new options",
            "alerts": {
                "login_as": "You are actually logged as <strong>{name}</strong>, you can logout as <a href=\"{route}\" data-turbolinks=\"false\">{admin}</a>.",
                "not_confirmed": "Your account will be in limited mode as long as your email remains not confirmed. <a href=\"{route}\">Click here</a> in order to resend mail confirmation."
            },
            "all_rights_reserved": "All rights reserved.",
            "anonymous": "Anonymous",
            "are_you_sure": "Are you sure ?",
            "auth": {
                "disabled": "Your account has been disabled."
            },
            "author": "Author",
            "author_id": "Author ID",
            "backend": {
                "Audits": {
                    "titles": {
                        "index": "Audit list",
                        "main": "Audits management"
                    }
                },
                "activities": {
                    "titles": {
                        "create": "Activity creation",
                        "edit": "Activity modification",
                        "index": "Activity list",
                        "main": "Activity management"
                    }
                },
                "announcements": {
                    "titles": {
                        "create": "Announcement creation",
                        "edit": "Announcement modification",
                        "index": "Announcement list",
                        "main": "Announcements"
                    }
                },
                "attendances": {
                    "statuses": {
                        "A": "Absent",
                        "AL": "Annual Leave",
                        "CL": "Compassionate",
                        "ET": "External Task",
                        "MC": "Medical Leave",
                        "ML": "Maternity",
                        "OB": "On-board",
                        "P": "Present",
                        "PL": "Paternity",
                        "RL": "Replacement Leave",
                        "SL": "Site Leave",
                        "UL": "Unpaid Leave"
                    },
                    "titles": {
                        "List": "Attendance Representative List",
                        "create": "Attendance creation",
                        "daily_attendance": "Daily Attendance",
                        "dept_movement": "Department Movement",
                        "edit": "Attendance modification",
                        "fingerprints": {
                            "import": "Import Fingerprints",
                            "index": "Finger Prints",
                            "main": "Finger Prints"
                        },
                        "index": "Daily Attendance",
                        "main": "Attendance Management",
                        "representative": "HR Representative"
                    }
                },
                "branches": {
                    "titles": {
                        "create": "Branch creation",
                        "edit": "Branch modification",
                        "index": "Branch list",
                        "main": "Branch management"
                    }
                },
                "cash_requisitions": {
                    "states": {
                        "closed": "Closed",
                        "closing": "Closing",
                        "dec_approving": "DEC Approving",
                        "eco_approving": "ECO Confirmation",
                        "finance_approving": "Finance Recording",
                        "hod_approving": "HOD Checking",
                        "hr_approving": "HR Recording",
                        "proofing": "Proofing",
                        "rejected": "Rejected",
                        "releasing": "Releasing"
                    },
                    "titles": {
                        "create": "Cash requisition creation",
                        "edit": "Cash requisition modification",
                        "index": "Cash requisition list",
                        "main": "CRF management"
                    }
                },
                "company_registrations": {
                    "titles": {
                        "create": "Company registration creation",
                        "edit": "Company registration modification",
                        "index": "Company registration list",
                        "main": "Company registration management"
                    }
                },
                "dashboard": {
                    "active_users": "Active users",
                    "all_posts": "All posts",
                    "form_submissions": "Submissions",
                    "last_new_posts": "Last new posts",
                    "last_pending_posts": "Last pending posts",
                    "last_posts": "Last posts",
                    "last_published_posts": "Last publications",
                    "new_posts": "New posts",
                    "pending_posts": "Pending posts",
                    "published_posts": "Published posts"
                },
                "departments": {
                    "titles": {
                        "create": "Department creation",
                        "edit": "Department modification",
                        "index": "Department list",
                        "main": "Department management"
                    }
                },
                "designations": {
                    "titles": {
                        "create": "Designation creation",
                        "edit": "Designation modification",
                        "index": "Designation list",
                        "main": "Designation management"
                    }
                },
                "doc_audits": {
                    "titles": {
                        "create": "Doc audit creation",
                        "edit": "Doc audit modification",
                        "index": "Doc audit list",
                        "main": "Doc audit management",
                        "title": "Doc Audit List"
                    }
                },
                "equipment": {
                    "titles": {
                        "main": "Equipment management",
                        "index": "Equipment list",
                        "create": "Equipment creation",
                        "edit": "Equipment modification"
                    }
                },
                "doc_templates": {
                    "template_types": {
                        "ISM": "ISM Form",
                        "Registrations": "COMPANY REGISTRATIONS",
                        "Reports": "Vessel Reports",
                        "SMS": "SMS",
                        "Training": "Training Manual"
                    },
                    "titles": {
                        "create": "Doc template creation",
                        "edit": "Doc template modification",
                        "index": "Doc template list",
                        "main": "Doc template management"
                    }
                },
                "employees": {
                    "employment_status": {
                        "on_probation": "On Probation",
                        "permanent": "Permanent",
                        "resigned": "Resigned",
                        "terminated": "Terminated"
                    },
                    "titles": {
                        "academic_qualifications": "Academic Qualifications",
                        "acd_qualifications": "Academic Qualifications",
                        "children_info": "Particulars of children",
                        "contact_info": "Contact Information",
                        "create": "Employee creation",
                        "document_info": "Documents Information",
                        "edit": "Employee modification",
                        "financial_info": "Financial Information",
                        "index": "Employee list",
                        "job_info": "Job Information",
                        "main": "Employee management",
                        "parents_info": "Particulars of parents",
                        "personal_info": "Personal Information",
                        "prof_qualifications": "Professional Qualifications",
                        "professional_qualifications": "Professional Qualifications",
                        "spouse_info": "Particulars of spouse"
                    }
                },
                "flight_reservation_quotations": {
                    "titles": {
                        "create": "Flight reservation quotation creation",
                        "edit": "Flight reservation quotation modification",
                        "index": "Flight reservation quotation list",
                        "main": "Flight reservation quotation management"
                    }
                },
                "flight_reservations": {
                    "actions": {
                        "generate_prf": "Generate PRF",
                        "generate_report": "Generate Report"
                    },
                    "flight_types": {
                        "ONEWAY": "One Way",
                        "RETURN": "Return"
                    },
                    "form_types": {
                        "SITE": "Site",
                        "VESSEL": "Vessel"
                    },
                    "states": {
                        "budgeting": "Budgeting",
                        "ceo_approving": "CEO Approving",
                        "closed": "Closed",
                        "hod_approving": "HOD Checking",
                        "prf_approving": "PRF Approving",
                        "prf_issuing": "PRF Issuing",
                        "purchasing": "Purchasing",
                        "deducting": "Deducting",
                        "rejected": "Rejected",
                        "sites_hod_approving": "HR Approving (SITE)",
                        "vessels_hod_approving": "ECO Approving (VESSEL)"
                    },
                    "titles": {
                        "create": "Flight reservation creation",
                        "edit": "Flight reservation modification",
                        "flight": "Flight Information",
                        "hotel": "Hotel Information",
                        "index": "Flight reservation list",
                        "main": "Flight reservation",
                        "passengers": "Passengers Information",
                        "transport": "Transport Information",
                        "transportation": "Transportation Information"
                    }
                },
                "forms": {
                    "titles": {
                        "annual_leave": "Annual Leave",
                        "ar": "Appraisal for Executive & Above",
                        "are": "Appraisal for Executive Manager & Above",
                        "arf": "Email & Intranet Requisition Form",
                        "arn": "Appraisal for non Executive /Technical staff",
                        "atf": "Authorized special task form",
                        "bunker_declaration": "Bunker Declaration",
                        "carf": "Cash Advance Requisition Form",
                        "carf_old": "Cash Advance Requisition Form (OLD]",
                        "crf": "Cash Requisition Form",
                        "det": "Declaration of daily external task",
                        "eaf": "Employment Application Form",
                        "ecf": "Exit Clearance Form",
                        "ela": "Employee leave application",
                        "erf": "Employee Requisition Form",
                        "flight_reservation": "Flight Reservation",
                        "fuel_requestion": "Fuel Requisition",
                        "iee": "Interview evaluation (Executive & Above]",
                        "ien": "Interview evaluation form (Non-Executive]",
                        "prf": "Payment Requisition Form",
                        "rer": "Re-employment requisition form",
                        "rf": "Requisition Form",
                        "rl": "Replacement Leave",
                        "sis": "Staff Information System",
                        "tof": "Time-off form",
                        "vehicle_maintenance_check_list": "Vehicle Maintenance Check List",
                        "vrf": "Vehicle requisition form",
                        "dodet": "DECLARATION OF DAILY EXTERNAL TASK"
                    }
                },
                "general_documents": {
                    "IKSB_GROUP_COVER_2019": "IKSB GROUP COVER 2019",
                    "IKSB_IMW_SMS_GROUP_Profile_2019": "IKSB IMW SMS GROUP Profile 2019"
                },
                "groups": {
                    "titles": {
                        "create": "Group creation",
                        "edit": "Group modification",
                        "index": "Group list",
                        "main": "Group management"
                    }
                },
                "holidays": {
                    "titles": {
                        "create": "Holiday creation",
                        "edit": "Holiday modification",
                        "index": "Holiday list",
                        "main": "Holiday management"
                    }
                },
                "inventories": {
                    "titles": {
                        "create": "Inventory creation",
                        "edit": "Inventory modification",
                        "index": "Inventory list",
                        "main": "Inventory management"
                    }
                },
                "inventory_item_categories": {
                    "titles": {
                        "create": "Inventory item category creation",
                        "edit": "Inventory item category modification",
                        "index": "Inventory item category list",
                        "main": "Items' categories",
                        "variant_options": "Type one option then press enter to add and type more"
                    }
                },
                "inventory_items": {
                    "titles": {
                        "create": "Inventory item creation",
                        "edit": "Inventory item modification",
                        "index": "Inventory item list",
                        "main": "Inventory item"
                    }
                },
                "inventory_transactions": {
                    "ROB": {
                        "main": "ROB Inventory Transactions"
                    },
                    "titles": {
                        "create": "Inventory transaction creation",
                        "edit": "Inventory transaction modification",
                        "index": "Inventory transaction list",
                        "main": "Transaction"
                    },
                    "transaction_types": {
                        "check-in": "Check In",
                        "check-out": "Check Out"
                    }
                },
                "leaves": {
                    "leave_types": {
                        "AL": "Annual Leave",
                        "CL": "Compassionate Leave",
                        "EL": "Emergency Leave",
                        "HP": "Hospitality Leave",
                        "MA": "Marriage Leave",
                        "MC": "Medical Leave",
                        "ML": "Maternity Leave",
                        "PL": "Paternity Leave",
                        "RL": "Replacement Leave",
                        "SL": "Site Leave",
                        "SPL": "Special Leave",
                        "UL": "Unpaid Leave"
                    },
                    "periods": {
                        "AN": "Afternoon",
                        "F": "Full Day",
                        "M": "Morning"
                    },
                    "states": {
                        "approved": "Approved",
                        "dec_approving": "DEC Approving",
                        "hod_approving": "HOD Checking",
                        "hr_approving": "HR Recording",
                        "rejected": "Rejected"
                    },
                    "titles": {
                        "create": "Leave creation",
                        "edit": "Leave modification",
                        "index": "Leave list",
                        "leave_entitlements": "Leave Entitlements",
                        "main": "Leave management"
                    }
                },
                "new_menu": {
                    "role": "New role",
                    "seafarer": "New seafarer",
                    "user": "New user"
                },
                "payment_requisitions": {
                    "states": {
                        "ceo_approving": "CEO Confirmation",
                        "closed": "Closed",
                        "dec_approving": "DEC Approving",
                        "finance_approving": "Finance Verification",
                        "hod_approving": "HOD Checking",
                        "md_approving": "MD Verification",
                        "rejected": "Rejected",
                        "releasing": "Releasing"
                    },
                    "titles": {
                        "create": "Payment requisition creation",
                        "edit": "Payment requisition modification",
                        "index": "Payment requisition list",
                        "main": "PR management"
                    }
                },
                "ports": {
                    "actions": {
                        "destroy": "Delete selected port",
                        "disable": "Disable selected port",
                        "enable": "Enable selected port"
                    },
                    "titles": {
                        "create": "Port creation",
                        "edit": "Port modification",
                        "index": "Port List",
                        "main": "Port management",
                        "menu": "Ports Management"
                    }
                },
                "purchase_requisitions": {
                    "states": {
                        "buying": "buying",
                        "ceo_approving": "CEO Approving",
                        "dec_1_approving": "DEC 1th Approving",
                        "dec_2_approving": "DEC 2th Approving",
                        "delivered": "Delivered",
                        "delivering": "Delivering",
                        "dm_approving": "Managing Director Approving",
                        "fin_approving": "Finance Approving",
                        "payment_preparing": "Payment Preparing",
                        "payment_releasing": "Payment Releasing",
                        "po_issuing": "PO Issuing",
                        "prf_issuing": "PRF Issuing",
                        "rejected": "Rejected",
                        "sourcing": "Sourcing",
                        "supper_approving": "Technical Supper Approving",
                        "tech_approving": "Tech Approving"
                    },
                    "titles": {
                        "create": "Purchase requisition creation",
                        "edit": "Purchase requisition modification",
                        "index": "Purchase requisition list",
                        "main": "PR management"
                    }
                },
                "quotations": {
                    "titles": {
                        "create": "Quotation creation",
                        "edit": "Quotation modification",
                        "index": "Quotation list",
                        "main": "Quotation management"
                    }
                },
                "roles": {
                    "titles": {
                        "create": "Role creation",
                        "edit": "Role modification",
                        "index": "Role list",
                        "main": "Role management"
                    }
                },
                "seafarer_contracts": {
                    "actions": {
                        "destroy": "Delete selected contracts",
                        "disable": "Disable selected contracts",
                        "enable": "Enable selected contracts"
                    },
                    "text": {
                        "approve_contract_msg": "By approving the contract, an email with seafarer contract will be sent to vessel email address to be signed and you will not be able to modify any information related to this contract.",
                        "approve_sign_off_msg": "By approving this sign the seafarer will considered offboard and the contract will be finished and archived.",
                        "approve_sign_on_msg": "By approving this sign the seafarer will considered onboard.",
                        "delete_sign": "Are you sure you want to delete this sign",
                        "mark_as_done_msg": "Are you sure you want to mark this request as done"
                    },
                    "titles": {
                        "contract_info": "Contract Information",
                        "create": {
                            "contracts": "Seafarer contract creation",
                            "crew_requests": "Crew request creation"
                        },
                        "crew_request": "Crew Request",
                        "edit": {
                            "contracts": "Seafarer contract modification",
                            "crew_requests": "Crew request modification"
                        },
                        "index": {
                            "contracts": "Seafarer contract list",
                            "crew_requests": "Crew request list"
                        },
                        "insurance_info": "Insurance Information",
                        "joining_info": "Joining Information",
                        "main": {
                            "contracts": "Contract management",
                            "crew_requests": "Crew Requests"
                        },
                        "menu": "Contract management"
                    }
                },
                "seafarers": {
                    "actions": {
                        "blacklist": "Blacklist",
                        "destroy": "Delete selected seafarers",
                        "disable": "Disable selected seafarers",
                        "enable": "Enable selected seafarers",
                        "whitelist": "Unblacklist"
                    },
                    "text": {
                        "are_you_sure_blacklist": "Are you sure you want to add him to the blacklist?",
                        "are_you_sure_whitelist": "Are you sure you want to remove him from the blacklist?",
                        "please_add_your_comments": "Please add your comments"
                    },
                    "titles": {
                        "capabilities_info": "Capabilities Information",
                        "contact_info": "Contact Information",
                        "create": "Seafarer creation",
                        "document_info": "Documents Information",
                        "edit": "Seafarer modification",
                        "financial_info": "Financial Information",
                        "index": "Seafarer list",
                        "last_drawn_salary": "Last Drawn Salary",
                        "main": "Seafarer management",
                        "medical_info": "Medical Information",
                        "personal_info": "Personal Information",
                        "view": "View Seafarer"
                    }
                },
                "sidebar": {
                    "access": "Access management",
                    "admin_forms": "Admin Forms",
                    "attendance": "Attendance Management",
                    "content": "Content management",
                    "crew": "Crew Management",
                    "finance": "Finance Management",
                    "forms": "Form management",
                    "hierarchy": "Hierarchy management",
                    "hr": "HR Management",
                    "purchases": "Purchases Management",
                    "settings": "Settings",
                    "vessel_inventory": "Vessel Invertory",
                    "work": "Work Management"
                },
                "tasks": {
                    "titles": {
                        "create": "Task creation",
                        "edit": "Task modification",
                        "index": "Task list",
                        "main": "Task management"
                    }
                },
                "notifications": {
                    "titles": {
                        "main": "Notification list"
                    },
                    "types": {
                        "App\\Notifications\\ApproveRequired": "Approvals",
                        "App\\Notifications\\CommentAdded": "Comments",
                        "App\\Notifications\\RequestProgressed": "Requests Progression",
                        "App\\Notifications\\ExceptionOccurred": "Server Errors",
                        "App\\Notifications\\EmployeeInfoUpdated": "Employee Info Updated",
                        "App\\Notifications\\AnnouncementPublished": "Announcement Published"
                    },
                    "icons": {
                        "App\\Notifications\\ApproveRequired": "stamp",
                        "App\\Notifications\\CommentAdded": "comment-dots",
                        "App\\Notifications\\RequestProgressed": "step-forward",
                        "App\\Notifications\\ExceptionOccurred": "bug",
                        "App\\Notifications\\EmployeeInfoUpdated": "user-edit",
                        "App\\Notifications\\AnnouncementPublished": "bullhorn"
                    }
                },
                "titles": {
                    "administration": "Administration",
                    "dashboard": "Dashboard"
                },
                "users": {
                    "actions": {
                        "destroy": "Delete selected users",
                        "disable": "Disable selected users",
                        "enable": "Enable selected users"
                    },
                    "titles": {
                        "create": "User creation",
                        "edit": "User modification",
                        "index": "User list",
                        "main": "User management",
                        "profile": "My profile"
                    }
                },
                "vessel_forms": {
                    "titles": {
                        "create": "Vessel form creation",
                        "edit": "Vessel form modification",
                        "index": "Vessel form list",
                        "main": "Vessel form management"
                    }
                },
                "vessel_insurances": {
                    "titles": {
                        "covered_vessels": "Covered Vessels",
                        "create": "Vessel insurance creation",
                        "edit": "Vessel insurance modification",
                        "index": "Vessel insurance list",
                        "main": "Vessel insurance"
                    }
                },
                "vessels": {
                    "actions": {
                        "destroy": "Delete selected vessel",
                        "disable": "Disable selected vessel",
                        "enable": "Enable selected vessel"
                    },
                    "text": {
                        "approve_generate_msg": "By generating the IMO report, an email with stamped and signed IMO report will be generated ,saved and sent to the authorized persons."
                    },
                    "titles": {
                        "GA_Plans": "GA Plans",
                        "certificates": "Vessel certificates",
                        "certificates_summary": "Certificate Summary",
                        "create": "Vessel creation",
                        "edit": "Vessel modification",
                        "imo_report": "IMO Report",
                        "in_vessel_insurance": "Vessel insurance",
                        "index": "Vessel list",
                        "info": "Vessel information",
                        "main": "Vessel management",
                        "port_clearance": "Port Clearance",
                        "reports": "Vessel Reports"
                    }
                }
            },
            "by": "by",
            "choose_file": "Choose File",
            "comments": "Comments",
            "cookieconsent": {
                "dismiss": "Got it !",
                "message": "This website uses cookies to ensure you get the best experience on our website."
            },
            "created_at": "Created at",
            "datatables": {
                "entries_per_page": "entries per page",
                "infos": "Showing {offset_start} to {offset_end} of {total} entries",
                "no_matched_results": "No matched results available",
                "no_results": "No results available",
                "search": "Search",
                "show_per_page": "Show"
            },
            "delete_image": "Delete image",
            "deleted_at": "Deleted at",
            "descriptions": {
                "action_not_reversible": "Please note that this action is not reversible so please make sure",
                "allowed_documents_types": "Allowed file types: png jpg jpeg pdf.",
                "allowed_image_types": "Allowed types: png gif jpg jpeg.",
                "allowed_pay_leave_values": "0 value means no pay leave<br>1-30 means number of days<br>more than 30 means fixed value"
            },
            "execute": "Execute",
            "export": "Export",
            "filters": "Filters",
            "general": "General",
            "http": {
                "403": {
                    "title": "Access Denied",
                    "description": "Sorry, but you do not have permissions to access this page."
                },
                "404": {
                    "title": "Page Not Found",
                    "description": "Sorry, but the page you were trying to view does not exist."
                },
                "500": {
                    "title": "Server Error",
                    "description": "Sorry, but the server has encountered a situation it doesn't know how to handle. We'll fix this as soon as possible."
                }
            },
            "language": "Language",
            "last_access_at": "Last access at",
            "localization": {
                "en": "English",
                "es": "Spanish",
                "fr": "French"
            },
            "more_info": "More info",
            "morphs": {
                "post": "Post, identity {id}",
                "user": "User, identity {id}"
            },
            "no": "No",
            "no_file_chosen": "No file chosen",
            "no_results": "No results available",
            "no_value": "No value",
            "placeholders": {
                "route": "Select a valid internal route",
                "tags": "Select or create a tag"
            },
            "published_at": "Published at",
            "search": "Search",
            "updated_at": "Updated at",
            "upload_image": "Upload image",
            "user": {
                "account": "My account",
                "account_delete": "<p>This action will delete entirely your account from this site as well as all associated data.</p>",
                "account_deleted": "Account successfully deleted",
                "administration": "Administration",
                "avatar": "Avatar",
                "change_password": "Change my password",
                "dashboard": "Dashboard",
                "delete": "Delete my account",
                "edit_profile": "Edit my profile",
                "email_confirmation_sended": "Mail confirmation sended.",
                "email_confirmed": "Email successfully confirmed.",
                "login": "Login",
                "logout": "Logout",
                "member_since": "Member since {date}",
                "online": "Online",
                "password_forgot": "Forget password ?",
                "password_reset": "Password Reset",
                "password_updated": "Password successfully updated.",
                "profile": "My profile",
                "profile_updated": "Profile successfully updated.",
                "register": "Register",
                "remember": "Remember",
                "send_password_link": "Send reset password link",
                "settings": "Settings",
                "space": "My space",
                "super_admin": "Super administrateur",
                "titles": {
                    "account": "My account",
                    "space": "My space"
                }
            },
            "validate": "Validate",
            "with": "with",
            "yes": "Yes",
            "notifications_count": "You have {count} unread notifications"
        },
        "pagination": {
            "previous": "&laquo; Previous",
            "next": "Next &raquo;"
        },
        "exceptions": {
            "backend": {
                "activities": {
                    "create": "Error on activity creation",
                    "delete": "Error on activity deletion",
                    "update": "Error on activity updating"
                },
                "announcements": {
                    "create": "Error on announcement creation",
                    "delete": "Error on announcement deletion",
                    "update": "Error on announcement updating"
                },
                "attendances": {
                    "create": "Error on attendance creation",
                    "delete": "Error on attendance deletion",
                    "update": "Error on attendance updating"
                },
                "branches": {
                    "create": "Error on branch creation",
                    "delete": "Error on branch deletion",
                    "update": "Error on branch updating"
                },
                "cash_requisitions": {
                    "create": "Error on cash requisition creation",
                    "delete": "Error on cash requisition deletion",
                    "update": "Error on cash requisition updating",
                    "upload_receipts": "Upload receipts"
                },
                "crew_requests": {
                    "create": "Error on crew request creation",
                    "delete": "Error on crew request deletion",
                    "update": "Error on crew request updating"
                },
                "departments": {
                    "create": "Error on department creation",
                    "delete": "Error on department deletion",
                    "update": "Error on department updating"
                },
                "designations": {
                    "create": "Error on designation creation",
                    "delete": "Error on designation deletion",
                    "update": "Error on designation updating"
                },
                "doc_audits": {
                    "create": "Error on doc audit creation",
                    "delete": "Error on doc audit deletion",
                    "update": "Error on doc audit updating"
                },
                "doc_templates": {
                    "create": "Error on doc template creation",
                    "delete": "Error on doc template deletion",
                    "update": "Error on doc template updating"
                },
                "equipment": {
                    "create": "Error on equipment creation",
                    "update": "Error on equipment updating",
                    "delete": "Error on equipment deletion"
                },
                "employees": {
                    "already_exist": "There is already an employee with the same info (passport number, NRIC OR name with surname with birth date]",
                    "create": "Error on employee creation",
                    "delete": "Error on employee deletion",
                    "duplicate_staff_id": "One of thumbs id is duplicated",
                    "update": "Error on employee updating"
                },
                "fingerprints": {
                    "import": "Error on fingerprints importing"
                },
                "flight_reservation_quotations": {
                    "create": "Error on flight reservation quotation creation",
                    "delete": "Error on flight reservation quotation deletion",
                    "update": "Error on flight reservation quotation updating"
                },
                "flight_reservations": {
                    "create": "Error on flight reservation creation",
                    "delete": "Error on flight reservation deletion",
                    "insufficient_quote": "Insufficient quotations count, at least add {count}",
                    "invalid_trip_price": "Invalid trip price",
                    "prf_not_generated": "PRF is not generated yet!",
                    "update": "Error on flight reservation updating",
                    "upload_receipts": "Upload receipts"
                },
                "holidays": {
                    "create": "Error on holiday creation",
                    "delete": "Error on holiday deletion",
                    "update": "Error on holiday updating"
                },
                "inventories": {
                    "create": "Error on Inventory creation",
                    "delete": "Error on Inventory deletion",
                    "update": "Error on Inventory updating"
                },
                "inventory_item_categories": {
                    "create": "Error on inventory item category creation",
                    "delete": "Error on inventory item category deletion",
                    "id_same_parent": "Item can not be parent of its self",
                    "update": "Error on inventory item category updating"
                },
                "inventory_items": {
                    "create": "Error on inventory item creation",
                    "delete": "Error on inventory item deletion",
                    "update": "Error on inventory item updating"
                },
                "inventory_transactions": {
                    "create": "Error on Inventory transaction creation",
                    "delete": "Error on Inventory transaction deletion",
                    "update": "Error on Inventory transaction updating",
                    "insufficient_items": "Insufficient items count to be checked out"
                },
                "leaves": {
                    "already_exist": "There is another leave which overlap with this one",
                    "create": "Error on leave creation",
                    "delete": "Error on leave deletion",
                    "employee_on_probation": "you are on probationary period, you only entitled to apply for a leave when you get permanent",
                    "insufficient_entitlement": "Insufficient entitlement",
                    "update": "Error on leave updating"
                },
                "payment_requisitions": {
                    "create": "Error on payment requisition creation",
                    "delete": "Error on payment requisition deletion",
                    "update": "Error on payment requisition updating",
                    "upload_payment_voucher": "Upload payment voucher"
                },
                "purchase_requisitions": {
                    "already_exist": "There is already a purchase requisition with the same number",
                    "create": "Error on purchase requisition creation",
                    "delete": "Error on purchase requisition deletion",
                    "insufficient_quote": "Insufficient quotations count, at least add one",
                    "no_quote_selected": "Select the most suitable quotation depend on specification, quality and price",
                    "update": "Error on purchase requisition updating"
                },
                "quotations": {
                    "create": "Error on quotation creation",
                    "delete": "Error on quotation deletion",
                    "update": "Error on quotation updating"
                },
                "representative": {
                    "already_exist": "There is already a representative with the same name",
                    "create": "Error on representative creation",
                    "delete": "Error on representative deletion",
                    "update": "Error on representative updating"
                },
                "roles": {
                    "create": "Error on role creation",
                    "delete": "Error on role deletion",
                    "update": "Error on role updating"
                },
                "seafarer_contracts": {
                    "already_exist": "There is already a contract for the same seafarer and going on",
                    "approve": "Error on approving seafarer contract",
                    "create": "Error on seafarer contract creation",
                    "delete": "Error on seafarer contract deletion",
                    "delete_sign_off_first": "Can't delete sign on without deleting sign off",
                    "illegal_sign_off": "Can't add sign off for seafarer without sign on",
                    "illegal_sign_off_approve": "Can't approve sign off for seafarer before approving sign on",
                    "seafarer_blacklisted": "This seafarer is blacklisted, please whitelist him first",
                    "sign_delete": "Error on sign deletion",
                    "sign_must_be_pending": "Sign must be in pending state to be able to delete it",
                    "sign_off_before_sign_in": "Sign off date must be after sign on date",
                    "update": "Error on seafarer contract updating"
                },
                "seafarers": {
                    "already_exist": "There is already a seafarer with the same info (passport number, NRIC OR name with surname with birth date]",
                    "create": "Error on seafarer creation",
                    "delete": "Error on seafarer deletion",
                    "update": "Error on seafarer updating"
                },
                "tasks": {
                    "create": "Error on task creation",
                    "delete": "Error on task deletion",
                    "update": "Error on task updating"
                },
                "users": {
                    "cannot_set_superior_roles": "You cannot attribute roles superior to yours",
                    "create": "Error on user creation",
                    "delete": "Error on user deletion",
                    "first_user_cannot_be_destroyed": "Super admin user cannot be deleted",
                    "first_user_cannot_be_disabled": "Super admin user cannot be disabled",
                    "first_user_cannot_be_edited": "You cannot edit super admin user",
                    "first_user_cannot_be_impersonated": "Super admin user cannot be impersonated",
                    "update": "Error on user updating"
                },
                "vessel_forms": {
                    "create": "Error on vessel form creation",
                    "delete": "Error on vessel form deletion",
                    "update": "Error on vessel form updating"
                },
                "vessel_insurances": {
                    "create": "Error on vessel insurance creation",
                    "delete": "Error on vessel insurance deletion",
                    "update": "Error on vessel insurance updating"
                },
                "vessels": {
                    "already_exist": "There is already a vessel with the same name",
                    "create": "Error on vessel creation",
                    "delete": "Error on vessel deletion",
                    "duplicate_certificate_summary": "There is a duplicate in certificates summary",
                    "duplicate_certificates": "There is a duplicate in certificates list",
                    "update": "Error on vessel updating"
                },
                "groups": {
                    "create": "Error on group creation",
                    "update": "Error on group updating",
                    "delete": "Error on group deletion"
                }
            },
            "general": "Server exception",
            "not_found": "Item not found",
            "unauthorized": "Action not allowed"
        },
        "logs": {
            "backend": {
                "users": {
                    "created": "User ID {user} created",
                    "updated": "User ID {user} updated",
                    "deleted": "User ID {user} deleted"
                },
                "seafarers": {
                    "deleted": "Seafarer ID {seafarer} deleted"
                },
                "form_submissions": {
                    "created": "Form submission ID {form_submission} created"
                }
            }
        },
        "alerts": {
            "backend": {
                "actions": {
                    "invalid": "Invalid action"
                },
                "activities": {
                    "created": "Activity created",
                    "deleted": "Activity deleted",
                    "status_changed": "Activity status changed",
                    "updated": "Activity updated"
                },
                "announcements": {
                    "created": "Announcement created",
                    "deleted": "Announcement deleted",
                    "published": "Announcement published",
                    "updated": "Announcement updated"
                },
                "attendances": {
                    "created": "Attendance created",
                    "deleted": "Attendance deleted",
                    "saved": "Attendance saved",
                    "updated": "Attendance updated"
                },
                "branches": {
                    "created": "Branch created",
                    "deleted": "Branch deleted",
                    "updated": "Branch updated"
                },
                "cash_requisitions": {
                    "created": "Cash requisition created",
                    "deleted": "Cash requisition deleted",
                    "status_changed": "Cash requisition status changed",
                    "updated": "Cash requisition updated"
                },
                "company_registrations": {
                    "created": "Company registration created",
                    "deleted": "Company registration deleted",
                    "updated": "Company registration updated"
                },
                "crew_requests": {
                    "created": "Crew request created",
                    "deleted": "Crew request deleted",
                    "updated": "Crew request updated"
                },
                "departments": {
                    "created": "Department created",
                    "deleted": "Department deleted",
                    "updated": "Department updated"
                },
                "designations": {
                    "created": "Designation created",
                    "deleted": "Designation deleted",
                    "updated": "Designation updated"
                },
                "doc_audits": {
                    "created": "Doc audit created",
                    "deleted": "Doc audit deleted",
                    "updated": "Doc audit updated"
                },
                "doc_templates": {
                    "created": "Doc template created",
                    "deleted": "Doc template deleted",
                    "updated": "Doc template updated"
                },
                "employees": {
                    "created": "Employee created",
                    "deleted": "Employee deleted",
                    "updated": "Employee updated"
                },
                "equipment": {
                    "created": "Equipment created",
                    "deleted": "Equipment deleted",
                    "updated": "Equipment updated"
                },
                "fingerprints": {
                    "imported": "Fingerprints imported"
                },
                "flight_reservation_quotations": {
                    "created": "Flight reservation quotation created",
                    "deleted": "Flight reservation quotation deleted",
                    "updated": "Flight reservation quotation updated"
                },
                "flight_reservations": {
                    "created": "Flight reservation created",
                    "deleted": "Flight reservation deleted",
                    "prf_generated": "PRF generated for Flight reservation",
                    "status_changed": "Flight reservation status changed",
                    "updated": "Flight reservation updated"
                },
                "groups": {
                    "created": "Group created",
                    "deleted": "Group deleted",
                    "updated": "Group updated"
                },
                "holidays": {
                    "created": "Holiday created",
                    "deleted": "Holiday deleted",
                    "updated": "Holiday updated"
                },
                "inventories": {
                    "created": "Inventory created",
                    "deleted": "Inventory deleted",
                    "updated": "Inventory updated"
                },
                "inventory_item_categories": {
                    "created": "Inventory item category created",
                    "deleted": "Inventory item category deleted",
                    "updated": "Inventory item category updated"
                },
                "inventory_items": {
                    "created": "Inventory item created",
                    "deleted": "Inventory item deleted",
                    "updated": "Inventory item updated"
                },
                "inventory_transactions": {
                    "created": "Inventory transaction created",
                    "deleted": "Inventory transaction deleted",
                    "updated": "Inventory transaction updated"
                },
                "leaves": {
                    "created": "Leave created",
                    "deleted": "Leave deleted",
                    "status_changed": "Leave status changed",
                    "updated": "Leave updated"
                },
                "others": {
                    "comment_created": "Comment created"
                },
                "payment_requisitions": {
                    "created": "Payment requisition created",
                    "deleted": "Payment requisition deleted",
                    "status_changed": "Payment requisition status changed",
                    "updated": "Payment requisition updated"
                },
                "purchase_requisitions": {
                    "created": "Purchase requisition created",
                    "deleted": "Purchase requisition deleted",
                    "status_changed": "Purchase requisition status changed",
                    "updated": "Purchase requisition updated"
                },
                "quotations": {
                    "created": "Quotation created",
                    "deleted": "Quotation deleted",
                    "updated": "Quotation updated"
                },
                "representative": {
                    "created": "Representative created",
                    "deleted": "Representative deleted",
                    "saved": "Representative saved",
                    "updated": "Representative updated"
                },
                "roles": {
                    "created": "Role created",
                    "deleted": "Role deleted",
                    "updated": "Role updated"
                },
                "seafarer_contracts": {
                    "approved": "Seafarer contract approved",
                    "created": "Seafarer contract created",
                    "deleted": "Seafarer contract deleted",
                    "signs": {
                        "approved": "Sign approved",
                        "created": "Sign created",
                        "deleted": "Sign deleted"
                    },
                    "updated": "Seafarer contract updated"
                },
                "seafarers": {
                    "blacklisted": "Seafarer Blacklisted",
                    "created": "Seafarer created",
                    "deleted": "Seafarer deleted",
                    "updated": "Seafarer updated",
                    "whitelisted": "Seafarer Unblacklisted"
                },
                "tasks": {
                    "created": "Task created",
                    "deleted": "Task deleted",
                    "status_changed": "Task status changed",
                    "updated": "Task updated"
                },
                "users": {
                    "bulk_destroyed": "Selected users deleted",
                    "bulk_disabled": "Selected users disabled",
                    "bulk_enabled": "Selected users enabled",
                    "created": "User created",
                    "deleted": "User deleted",
                    "profile_updated": "Your profile updated",
                    "status_updated": "User status updated",
                    "updated": "User updated"
                },
                "vessel_forms": {
                    "created": "Vessel form created",
                    "deleted": "Vessel form deleted",
                    "updated": "Vessel form updated"
                },
                "vessel_insurances": {
                    "created": "Vessel insurance created",
                    "deleted": "Vessel insurance deleted",
                    "updated": "Vessel insurance updated"
                },
                "vessels": {
                    "created": "Vessel created",
                    "deleted": "Vessel deleted",
                    "imo_generated": "IMO report generated and sent",
                    "updated": "Vessel updated"
                }
            }
        },
        "buttons": {
            "activities": {
                "create": "Create activity"
            },
            "add_attachment": "Add Attachment",
            "add_more": "Add More",
            "announcements": {
                "create": "Create announcement"
            },
            "apply": "Apply",
            "approve": "Approve",
            "attendances": {
                "create": "Create attendance",
                "create_representative": "Create representative"
            },
            "back": "Back",
            "branches": {
                "create": "Create branch"
            },
            "cancel": "Cancel",
            "cash_requisitions": {
                "create": "Create cash requisition",
                "requester_crf_history": "Requester cash requisition history"
            },
            "close": "Close",
            "company_registrations": {
                "create": "Create company registration"
            },
            "confirm": "Confirm",
            "create": "Create",
            "crew_requests": {
                "convert_to_contract": "Convert to contract",
                "create": "Create crew request"
            },
            "decline": "Decline",
            "delete": "Delete",
            "delete_sign": "Delete sign",
            "departments": {
                "create": "Create department"
            },
            "designations": {
                "create": "Create designation"
            },
            "doc_audits": {
                "create": "Create doc audit"
            },
            "doc_templates": {
                "create": "Create doc template"
            },
            "drop_file": "Drop File",
            "edit": "Edit",
            "edit_and_approve": "Edit & Approve",
            "employees": {
                "add_child": "Add child",
                "add_qualifications": "Add qualifications",
                "create": "Create employee",
                "drop_child": "Drop child",
                "drop_qualification": "Drop qualifications"
            },
            "execute": "Execute",
            "fingerprints": {
                "import": "Import"
            },
            "flight_reservation_quotations": {
                "create": "Create flight reservation quotation"
            },
            "flight_reservations": {
                "add_passenger": "Add Passenger",
                "create": "Create flight reservation",
                "drop_passenger": "Drop Passenger",
                "drop_trip": "Drop Trip",
                "expand_passenger": "Passenger details",
                "expand_trip": "Trip details",
                "generate_prf": "Generate PRF",
                "generate_report": "Generate Report",
                "view_prf": "View PRF"
            },
            "forward": "Forward",
            "generate": "Generate",
            "history": "History",
            "holidays": {
                "create": "Create holiday"
            },
            "imo": "Generate IMO Report",
            "import": "Import",
            "inventories": {
                "create": "Create inventory"
            },
            "equipment": {
                "create": "Create equipment"
            },
            "inventory_item_categories": {
                "create": "Create inventory item category"
            },
            "inventory_items": {
                "create": "Create inventory item"
            },
            "inventory_transactions": {
                "create": "Create inventory transaction"
            },
            "leaves": {
                "create": "Create leave"
            },
            "login-as": "Login as {name}",
            "mark_as_done": "Mark As Done",
            "mark_as_read": "Mark As Read",
            "mark_as_unread": "Mark As Unread",
            "no": "No",
            "payment_requisitions": {
                "create": "Create payment requisition",
                "requester_prf_history": "Requester payment requisition history"
            },
            "ports": {
                "create": "Create Port"
            },
            "preview": "Preview",
            "print": "Print",
            "publish": "Publish",
            "purchase_requisitions": {
                "create": "Create purchase requisition"
            },
            "quotations": {
                "create": "Create quotation"
            },
            "refresh": "Refresh",
            "reject": "Reject",
            "reset": "Reset",
            "roles": {
                "create": "Create role"
            },
            "save": "Save",
            "save_and_approve": "Save & Approve",
            "seafarer_contracts": {
                "create": "Create seafarer contract"
            },
            "seafarers": {
                "blacklisted": "Blacklist",
                "create": "Create seafarer",
                "whitelist": "Remove From The Blacklist"
            },
            "search": "Search",
            "select": "Select",
            "select_all": "Select All",
            "unselect_all": "Unselect All",
            "send": "Send",
            "show": "Show",
            "show_hide_columns": "Show/Hide Columns",
            "show_hide_filters": "Show/Hide Filters",
            "sign_off": "Sign Off",
            "sign_on": "Sign On",
            "tasks": {
                "create": "Create task"
            },
            "update": "Update",
            "urgent_pass": "Urgent Pass",
            "users": {
                "create": "Create user"
            },
            "vessel_forms": {
                "create": "Create vessel form"
            },
            "vessel_insurances": {
                "create": "Create vessel insurance"
            },
            "vessels": {
                "add_certificate": "Add Certificate",
                "add_certificate_summary": "Add Certificate Summary",
                "add_port_clearance": "Add Port Clearance",
                "create": "Create vessel",
                "drop_certificate": "Drop Certificate",
                "export_certificates": "Export Certificates"
            },
            "view": "View",
            "view_content": "View Content",
            "view_signed_contract": "View Signed Contract",
            "yes": "Yes",
            "groups": {
                "create": "Create group",
                "show_members": "Show Members"
            },
            "first": "First",
            "last": "Last",
            "next": "Next",
            "previous": "Previous"
        },
        "passwords": {
            "password": "Passwords must be at least six characters and match the confirmation.",
            "reset": "Your password has been reset!",
            "sent": "We have e-mailed your password reset link!",
            "token": "This password reset token is invalid.",
            "user": "We can't find a user with that e-mail address."
        },
        "validation": {
            "accepted": "The {attribute} must be accepted.",
            "active_url": "The {attribute} is not a valid URL.",
            "after": "The {attribute} must be a date after {date}.",
            "after_or_equal": "The {attribute} must be a date after or equal to {date}.",
            "alpha": "The {attribute} may only contain letters.",
            "alpha_dash": "The {attribute} may only contain letters, numbers, and dashes.",
            "alpha_num": "The {attribute} may only contain letters and numbers.",
            "array": "The {attribute} must be an array.",
            "before": "The {attribute} must be a date before {date}.",
            "before_or_equal": "The {attribute} must be a date before or equal to {date}.",
            "between": {
                "numeric": "The {attribute} must be between {min} and {max}.",
                "file": "The {attribute} must be between {min} and {max} kilobytes.",
                "string": "The {attribute} must be between {min} and {max} characters.",
                "array": "The {attribute} must have between {min} and {max} items."
            },
            "boolean": "The {attribute} field must be true or false.",
            "confirmed": "The {attribute} confirmation does not match.",
            "date": "The {attribute} is not a valid date.",
            "date_format": "The {attribute} does not match the format {format}.",
            "different": "The {attribute} and {other} must be different.",
            "digits": "The {attribute} must be {digits} digits.",
            "digits_between": "The {attribute} must be between {min} and {max} digits.",
            "dimensions": "The {attribute} has invalid image dimensions.",
            "distinct": "The {attribute} field has a duplicate value.",
            "email": "The {attribute} must be a valid email address.",
            "exists": "The selected {attribute} is invalid.",
            "file": "The {attribute} must be a file.",
            "filled": "The {attribute} field must have a value.",
            "image": "The {attribute} must be an image.",
            "in": "The selected {attribute} is invalid.",
            "in_array": "The {attribute} field does not exist in {other}.",
            "integer": "The {attribute} must be an integer.",
            "ip": "The {attribute} must be a valid IP address.",
            "ipv4": "The {attribute} must be a valid IPv4 address.",
            "ipv6": "The {attribute} must be a valid IPv6 address.",
            "json": "The {attribute} must be a valid JSON string.",
            "max": {
                "numeric": "The {attribute} may not be greater than {max}.",
                "file": "The {attribute} may not be greater than {max} kilobytes.",
                "string": "The {attribute} may not be greater than {max} characters.",
                "array": "The {attribute} may not have more than {max} items."
            },
            "mimes": "The {attribute} must be a file of type: {values}.",
            "mimetypes": "The {attribute} must be a file of type: {values}.",
            "min": {
                "numeric": "The {attribute} must be at least {min}.",
                "file": "The {attribute} must be at least {min} kilobytes.",
                "string": "The {attribute} must be at least {min} characters.",
                "array": "The {attribute} must have at least {min} items."
            },
            "not_in": "The selected {attribute} is invalid.",
            "not_regex": "The {attribute} format is invalid.",
            "numeric": "The {attribute} must be a number.",
            "present": "The {attribute} field must be present.",
            "regex": "The {attribute} format is invalid.",
            "required": "The {attribute} field is required.",
            "required_if": "The {attribute} field is required when {other} is {value}.",
            "required_unless": "The {attribute} field is required unless {other} is in {values}.",
            "required_with": "The {attribute} field is required when {values} is present.",
            "required_with_all": "The {attribute} field is required when {values} is present.",
            "required_without": "The {attribute} field is required when {values} is not present.",
            "required_without_all": "The {attribute} field is required when none of {values} are present.",
            "same": "The {attribute} and {other} must match.",
            "size": {
                "numeric": "The {attribute} must be {size}.",
                "file": "The {attribute} must be {size} kilobytes.",
                "string": "The {attribute} must be {size} characters.",
                "array": "The {attribute} must contain {size} items."
            },
            "string": "The {attribute} must be a string.",
            "timezone": "The {attribute} must be a valid zone.",
            "unique": "The {attribute} has already been taken.",
            "uploaded": "The {attribute} failed to upload.",
            "url": "The {attribute} format is invalid.",
            "custom": {
                "attribute-name": {
                    "rule-name": "custom-message"
                },
                "seafarer_contract_id": {
                    "unique": "The seafarer already has a sign of same type for this contract"
                },
                "new_outstanding_invoices": {
                    "required": "The Payment field is required"
                }
            },
            "attributes": {
                "id": "ID",
                "no": "NO",
                "name": "Name",
                "display_name": "Display name",
                "username": "Pseudo",
                "email": "Email",
                "brand": "Brand",
                "model": "Model",
                "serial_num": "Serial NO",
                "first_name": "Firstname",
                "last_name": "Lastname",
                "password": "Password",
                "inventories": "Inventories",
                "items": "Items",
                "password_confirmation": "Confirm password",
                "old_password": "Old password",
                "new_password": "New password",
                "new_password_confirmation": "Confirm new password",
                "postal_code": "Postal code",
                "city": "City",
                "country": "Country",
                "address": "Address",
                "phone": "Phone",
                "mobile": "Mobile",
                "age": "Age",
                "sex": "Sex",
                "gender": "Gender",
                "day": "Day",
                "month": "Month",
                "year": "Year",
                "hour": "Hour",
                "minute": "Minute",
                "second": "Second",
                "title": "Title",
                "content": "Content",
                "description": "Description",
                "summary": "Summary",
                "excerpt": "Excerpt",
                "date": "Date",
                "time": "Time",
                "available": "Available",
                "size": "Size",
                "roles": "Roles",
                "permissions": "Permissions",
                "active": "Active",
                "confirmed": "Confirmed",
                "message": "Message",
                "g-recaptcha-response": "Captcha",
                "locale": "Localization",
                "route": "Route",
                "url": "URL alias",
                "form_data": "Form data",
                "recipients": "Recipients",
                "source_path": "Original path",
                "target_path": "Target path",
                "redirect_type": "Redirect type",
                "timezone": "Timezone",
                "order": "Display order",
                "image": "Image",
                "status": "Status",
                "statuses": "Statuses",
                "pinned": "Pinned",
                "promoted": "Promoted",
                "body": "Body",
                "tags": "Tags",
                "published_at": "Published at",
                "unpublished_at": "Unpublished at",
                "metable_type": "Entity",
                "port_name": "Port Name",
                "departure_port": "Departure Port",
                "port_clearance_file": "Port Clearance File",
                "certificate_summary_file": "Certificate Summary File",
                "comment": "Comment",
                "value_covered": "HULL Value",
                "vessel_covered": "Vessel",
                "type": "Type",
                "imo_number": "IMO Number",
                "call_sign": "Call Sign",
                "official_number": "Official Number",
                "port_of_registry": "Port Of Registry",
                "flag": "Flag",
                "piloted_by": "Piloted By",
                "code": "Code",
                "passport_no": "Passport No.",
                "passport_info": "Passport Info",
                "smc_reg_no": "Seaman Reg No.",
                "personal_info": {
                    "id": "ID",
                    "surname": "Surname",
                    "name": "Name",
                    "sex": "Sex",
                    "marital_status": "Marital Status",
                    "address": "Address",
                    "nationality": "Nationality",
                    "religion": "Religion",
                    "race": "Race",
                    "nric_no": "NRIC Number",
                    "date_of_birth": "Date Of Birth",
                    "place_of_birth": "Place Of Birth",
                    "date_and_place_of_birth": "Date & Place Of Birth",
                    "date_of_join": "Date First Join",
                    "blood_type": "Blood Type",
                    "photo": "Photo",
                    "signature": "Signature"
                },
                "contact_info": {
                    "personal_hp": "Personal Hand Phone",
                    "personal_email": "Personal Email",
                    "next_of_kin_name": "Nex Of Kin",
                    "next_of_kin_hp": "Next Of Kin Hand Phone",
                    "next_of_kin_address": "Next Of Kin Address",
                    "next_of_kin_relationship": "Next Of Kin Relationship"
                },
                "financial_info": {
                    "payment_method": "Payment Method",
                    "bank": "Bank",
                    "account_no": "Account Number",
                    "swift_code": "Swift Code",
                    "country_of_origin": "Country Of Origin",
                    "income_tax_no": "Income Tax No",
                    "epf": "E.P.F",
                    "socso_no": "Socso No",
                    "zakat": "Zakat"
                },
                "spouse_info": {
                    "name": "Spouse's name",
                    "nric_no": "NRIC No",
                    "occupation": "Occupation",
                    "employer_name": "Employer's name",
                    "employer_address": "Employer's address",
                    "hp": "Hand phone",
                    "dom": "Date of marriage"
                },
                "parents_info": {
                    "father_name": "Father Name",
                    "father_status": "Father Status",
                    "mother_name": "Mother Name",
                    "mother_status": "Mother Status"
                },
                "qualifications_info": {
                    "university": "University / College / Polytechnic / School",
                    "qualification": "Qualification",
                    "institute": "Institute",
                    "specialization": "Specialization",
                    "year": "Year",
                    "file": "File"
                },
                "children_info": {
                    "name": "Name",
                    "icno": "IC NO",
                    "bcno": "BC NO",
                    "dob": "DOB",
                    "gender": "Gender",
                    "marital_status": "Marital Status"
                },
                "capabilities_info": {
                    "rank": "Rank",
                    "coc_certificate_type": "COC Type",
                    "coc_certificate_class": "COC Class",
                    "security_course": "Security Course",
                    "other_courses": "Other Courses"
                },
                "documents_info": {
                    "PASSPORT": {
                        "label": "Passport",
                        "no": "No.",
                        "expiry": "Expiry",
                        "passport_expiry": "Passport Expiry",
                        "file": "Passport Photo"
                    },
                    "WORK_PERMIT": {
                        "label": "Work Permit",
                        "no": "No.",
                        "expiry": "Expiry",
                        "file": "Work Permit  / Latest Immigration"
                    },
                    "MEDICAL_CERT": {
                        "label": "Medical Certificate",
                        "no": "No.",
                        "expiry": "Expiry",
                        "file": "Medical Certificate Photo"
                    },
                    "COC_CERT": {
                        "label": "COC Certificate",
                        "no": "No.",
                        "expiry": "Expiry",
                        "coc_cert_expiry": "COC Certificate Expiry",
                        "file": "COC Certificate Photo"
                    },
                    "SMC": {
                        "label": "Seaman Card",
                        "no": "Reg No.",
                        "expiry": "Expiry",
                        "file": "Seaman Card Photo"
                    },
                    "DISCHARGE_BOOK": {
                        "label": "Discharge Book",
                        "no": "No.",
                        "expiry": "Expiry",
                        "file": "Discharge Book Photo"
                    },
                    "GOC_GMDSS": {
                        "label": "GOC GMDSS",
                        "no": "No.",
                        "expiry": "Expiry",
                        "file": "GOC GMDSS Photo"
                    },
                    "MG_COR": {
                        "label": "Mongolian COR",
                        "no": "No.",
                        "expiry": "Expiry",
                        "file": "Mongolian COR Photo"
                    },
                    "MY_COR": {
                        "label": "Malaysian COR",
                        "no": "No.",
                        "expiry": "Expiry",
                        "file": "Malaysian COR Photo"
                    },
                    "COE": {
                        "label": "COE",
                        "no": "No.",
                        "expiry": "Expiry",
                        "file": "COE Photo"
                    }
                },
                "seniority": "Seniority",
                "vessel": "Vessel",
                "seafarer": "Seafarer",
                "duration_of_service": "Duration Of Service",
                "basic_salary": "Basic Salary",
                "scheduled_sign_on_date": "Scheduled Sign On",
                "sign_on_date": "Sign On Date",
                "sign_on_port": "Sing On Port",
                "sign_type": "Sign Type",
                "sign_off_date": "Sign Off Date",
                "pay_leave": "Pay leave",
                "last_ship_joined": "Last Ship Joined",
                "last_sign_off_date": "Last Sign Off Date",
                "signed_contract": "Signed Contract",
                "vessel_specs": "Vessel Specs",
                "tested_by": "Seafarer Tested By",
                "insurance_company": "Insurance Company",
                "insurance_type": "Insurance Type",
                "insurance_issue_date": "Insurance Issue Date",
                "insurance_expiry_date": "Insurance Expiry Date",
                "expiry_date": "Expiry Date",
                "replacement_of": "Replacement Of",
                "suggested_seafarer": "Suggested Seafarer",
                "rank": "Rank",
                "remarks": "Remarks",
                "movement_type": "Movement Type",
                "movement_date": "Movement Date",
                "last_port": "Last Port",
                "port_of": "Port of",
                "stamp": "Stamp",
                "certificates_info": {
                    "label": "Vessel Certificate",
                    "type": "Certificate Type",
                    "number": "Certificate No.",
                    "remarks": "Remarks",
                    "expiry": "Expiry",
                    "file": "File"
                },
                "request_date": "Request Date",
                "start_date": "Start Date",
                "end_date": "End Date",
                "due_date": "Due Date",
                "completion_date": "Completion Date",
                "duration": "Duration",
                "cost": "Cost",
                "approved_cost": "Approved Cost",
                "hod": "Head Of Department",
                "department": "Department",
                "departments": "Departments",
                "branch": "Branch",
                "branches": "Branches",
                "designation": "Designation",
                "designations": "Designations",
                "action_by": "Action By",
                "action_from": "Action From",
                "assigned_user": "Assigned User",
                "assigned_department": "Assigned Department",
                "assigned_branch": "Assigned Branch",
                "assigned_departments": "Assigned Departments",
                "creation_date": "Creation Date",
                "pr_number": "PR Number",
                "requester": "Requester",
                "pr_form": "PR Form",
                "cash_advance": "Cash Advance",
                "reimbursement": "Reimbursement",
                "deposit": "Deposit",
                "request_type": "Request Type",
                "amount": "Amount",
                "approved_amount": "Approved Amount",
                "purpose": "Purpose",
                "cash_advance_taken": "Cash Advance Taken",
                "cash_advance_taken_date": "Cash Advance Taken Date",
                "total_receipt_returned": "Total Receipt Returned",
                "total_receipt_returned_date": "Total Receipt Returned Date",
                "outstanding_ca": "Outstanding CA",
                "attachments": "Attachments",
                "attachment": "Attachment",
                "project": "Project",
                "supplier": "Supplier",
                "payment": "Payment",
                "proposed_payment": "Proposed Payment",
                "approved_payment": "Approved Payment",
                "released_to": "Released To",
                "details": "Details",
                "last_pv_no": "Last PV NO.",
                "last_payment_amount": "Last Payment Amount",
                "last_payment_date": "Last Payment Date",
                "old_outstanding_invoices": "Old Outstanding Invoices",
                "new_outstanding_invoices": "New Outstanding Invoices",
                "cheque_no": "Cheque No",
                "cheque_bank": "Cheque Bank",
                "cheque_date": "Cheque Date",
                "auditable_type": "Auditable Type",
                "modifications": "Modifications",
                "price": "Price",
                "budget": "Budget",
                "deduction": "Deduction",
                "final_price": "Final Price",
                "note": "Note",
                "currency": "Currency",
                "invoice": "Invoice",
                "receipts": "Receipts",
                "receipt": "Receipt",
                "payment_voucher": "Payment Voucher",
                "form_type": "Form Type",
                "form_target": "Vessel / Site",
                "flight_type": "Flight Type",
                "departure_date": "Departure Date",
                "check_in": "Check In",
                "quarter_date": "Quarter Date",
                "departure_period": "Departure Period",
                "departure_from": "Departure From",
                "departure_to": "Departure To",
                "departure_luggage": "Departure Luggage",
                "return_date": "Return Date",
                "return_period": "Return Period",
                "return_from": "Return From",
                "return_to": "Return To",
                "return_luggage": "Return Luggage",
                "transport_type": "Transport Type",
                "trip_type": "Trip Type",
                "transport_from": "Transport From",
                "transport_to": "Transport To",
                "transportation_cost": "Transportation Cost",
                "location": "Location",
                "hotel": "Hotel",
                "nights": "Nights",
                "passengers": "Passengers",
                "passenger_name": "Passenger Name",
                "passengers_info": {
                    "label": "Vessel Certificate",
                    "passport": "MyKad/Passport",
                    "name": "Name",
                    "designation": "Designation",
                    "nationality": "Nationality",
                    "dob": "Date Of Birth",
                    "hp": "Handphone No",
                    "expiry": "Expiry",
                    "file": "File"
                },
                "flight_time": "Time",
                "heading": "Heading",
                "airlines": "Air Lines",
                "website": "Website",
                "employee": "Employee",
                "employees": "Employees",
                "attendance_date": "Date",
                "dept_movement": "Dept Movement",
                "log_book": "Log Book HR",
                "thumbprint": "Thumbprint",
                "hr_review": "HR Review",
                "ey_query": "EY Query",
                "logged_in_time": "IN",
                "logged_out_time": "OUT",
                "qualifications_info.academic_qualifications.*.year": "Year",
                "qualifications_info.academic_qualifications.*.authority": "University",
                "qualifications_info.academic_qualifications.*.degree": "Qualification",
                "qualifications_info.academic_qualifications.*.file": "File",
                "qualifications_info.professional_qualifications.*.year": "Year",
                "qualifications_info.professional_qualifications.*.authority": "Institute",
                "qualifications_info.professional_qualifications.*.degree": "Specialization",
                "qualifications_info.professional_qualifications.*.file": "File",
                "trips.*.trip_type": "Trip Type",
                "trips.*.trip_attributes.departure_date": "Departure Date",
                "trips.*.trip_attributes.transport_from": "Transport From",
                "trips.*.trip_attributes.transport_to": "Transport To",
                "trips.*.trip_attributes.departure_from": "Departure From",
                "trips.*.trip_attributes.departure_to": "Departure To",
                "trips.*.trip_attributes.flight_type": "Flight Type",
                "trips.*.trip_attributes.hotel": "Hotel Name",
                "trips.*.trip_attributes.nights": "Nights",
                "trips.*.trip_attributes.location": "Location",
                "trips.*.trip_attributes.check_in": "Check In",
                "trips.*.trip_attributes.price": "Price",
                "children_info.*.name": "Name",
                "children_info.*.dob": "DOB",
                "children_info.*.gender": "Gender",
                "job_info.email": "Email",
                "staff_id": "Staff ID",
                "destination.to": "Destination",
                "job_info.thumbs": "Thumb IDs",
                "job_info.employment_status": "Employment Status",
                "job_info.report_to": "Report To",
                "report_to": "Report To",
                "employment_status": "Employment Status",
                "job_info.probationary_period": "Probationary Period",
                "probationary_period": "Probationary Period",
                "thumbs": "Thumb IDs",
                "staff_name": "Staff Name",
                "clocking": "Clocking",
                "terminal": "Terminal",
                "fingerprints_file": "Finger prints file",
                "leave_type": "Leave Type",
                "period": "Period",
                "days_count": "Days Count",
                "reason": "Reason",
                "level": "Level",
                "trips.*.attributes.form_type": "Form Type",
                "trips.*.attributes.form_target": "Vessel / Site",
                "trips.*.attributes.flight_type": "Flight Type",
                "trips.*.attributes.quarter_date": "Quarter Date",
                "trips.*.attributes.departure_period": "Departure Period",
                "trips.*.attributes.departure_from": "Departure From",
                "trips.*.attributes.departure_to": "Departure To",
                "trips.*.attributes.departure_luggage": "Departure Luggage",
                "trips.*.attributes.return_date": "Return Date",
                "trips.*.attributes.return_period": "Return Period",
                "trips.*.attributes.return_from": "Return From",
                "trips.*.attributes.return_to": "Return To",
                "trips.*.attributes.return_luggage": "Return Luggage",
                "occasion": "Occasion",
                "CC": "CC:",
                "cc": "CC",
                "subject": "Subject:",
                "subjectList": "Subject",
                "destination": "Destination",
                "destination_list": "Destination",
                "template": "Template",
                "template_type": "Template type",
                "template_file": "Template file",
                "form_file": "Form file",
                "template_name": "Template Name",
                "doc_template": "Template Name",
                "templates": "Templates",
                "nc": "NC",
                "obs": "OBS",
                "audit_date": "Audit Date",
                "audit_file": "Audit File",
                "registration_file": "Registration File",
                "parent": "Parent",
                "members": "Members",
                "part_no": "Part No",
                "unit": "Unit",
                "category": "Category",
                "usage": "Usage",
                "variants": "Variants",
                "variant_name": "Variant Name ( EX: Color )",
                "variant_options": "Variant Options ( EX: Red, Blue, Black... )",
                "transaction_type": "Transaction type",
                "inventory_item": "Inventory Item",
                "variations": "Variations",
                "quantity": "Quantity",
                "inventory": "Inventory",
                "expired_at": "Expired at",
                "transaction_date": "Transaction Date",
                "company": "Company",
                "grade": "Grade",
                "validity_from": "Validity From",
                "validity_to": "Validity To",
                "company_registrations": {
                    "registration": "Company Registration",
                    "registration_no": "Registration Number",
                    "0": ""
                },
                "users": "Users",
                "groups": "Groups"
            }
        }
    }
}
