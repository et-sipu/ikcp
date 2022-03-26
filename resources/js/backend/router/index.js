import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Full from '../containers/Full'

Vue.use(Router)

export function createRouter(base, i18n, route) {
  const router = new Router({
    mode: 'history',
    base: base,
    linkActiveClass: 'open active',
    scrollBehavior: () => ({ y: 0 }),
    routes: [
      {
        path: '/',
        redirect: '/dashboard',
        name: 'home',
        component: Full,
        meta: {
          label: i18n.t('labels.backend.titles.administration')
        },
        children: [
          {
            path: 'search',
            name: 'search',
            component: () => import('../views/Search'),
            meta: {
              label: i18n.t('labels.search')
            }
          },
          {
            path: 'dashboard',
            name: 'dashboard',
            component: () => import('../views/Dashboard'),
            meta: {
              label: i18n.t('labels.backend.titles.dashboard')
            }
          },
          {
            path: 'notifications',
            name: 'notifications',
            component: () => import('../views/NotificationList'),
            meta: {
              label: i18n.t('labels.backend.notifications.titles.main')
            }
          },
          {
            path: 'user_profile',
            name: 'user_profile',
            component: () => import('../views/UserProfile'),
            meta: {
              label: i18n.t('labels.backend.users.titles.profile')
            }
          },
          {
            path: 'Crew/Settings/ports',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.ports.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'ports',
                component: () => import('../views/PortList'),
                meta: {
                  label: i18n.t('labels.backend.ports.titles.index'),
                  permission: 'view own vessels'
                }
              },
              {
                path: 'create',
                name: 'ports_create',
                component: () => import('../views/PortForm'),
                meta: {
                  label: i18n.t('labels.backend.ports.titles.create'),
                  permission: 'create ports'
                }
              },
              {
                path: ':id/edit',
                name: 'ports_edit',
                component: () => import('../views/PortForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.ports.titles.edit'),
                  permission: 'edit ports'
                }
              }
            ]
          },
          {
            path: 'VM/vessels',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.vessels.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'vessels',
                component: () => import('../views/VesselList'),
                meta: {
                  label: i18n.t('labels.backend.vessels.titles.index'),
                  permission: 'view own vessels'
                }
              },
              {
                path: 'create',
                name: 'vessels_create',
                component: () => import('../views/VesselForm'),
                meta: {
                  label: i18n.t('labels.backend.vessels.titles.create'),
                  permission: 'create vessels'
                }
              },
              {
                path: ':id/edit',
                name: 'vessels_edit',
                component: () => import('../views/VesselForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.vessels.titles.edit'),
                  permission: app => {
                    return (
                      app.user.can('view own vessel') ||
                      app.user.can('view vessel certificates') ||
                      app.user.can('view vessel forms')
                    )
                  }
                }
              },
              {
                path: ':id/imo',
                name: 'imo_report',
                component: () => import('../views/VesselIMOReport'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.vessels.titles.imo_report'),
                  permission: 'edit own vessels'
                }
              }
            ]
          },
          {
            path: 'Crew/seafarers',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.seafarers.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'seafarers',
                component: () => import('../views/SeafarerList'),
                meta: {
                  label: i18n.t('labels.backend.seafarers.titles.index'),
                  permission: 'view seafarers'
                }
              },
              {
                path: 'create',
                name: 'seafarers_create',
                component: () => import('../views/SeafarerForm'),
                meta: {
                  label: i18n.t('labels.backend.seafarers.titles.create'),
                  permission: 'create seafarers'
                }
              },
              {
                path: ':id/edit',
                name: 'seafarers_edit',
                component: () => import('../views/SeafarerForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.seafarers.titles.edit'),
                  permission: 'edit own seafarers'
                }
              },
              {
                path: ':id/view',
                name: 'seafarers_view',
                component: () => import('../views/SeafarerForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.seafarers.titles.view'),
                  permission: 'show seafarer'
                }
              },
              {
                path: ':seafarerId/create_contract',
                name: 'seafarer_contracts_create',
                component: () => import('../views/SeafarerContractForm'),
                props: true,
                meta: {
                  label: i18n.t(
                    'labels.backend.seafarer_contracts.titles.create.contracts'
                  ),
                  permission: app => {
                    return (
                      app.user.can('create seafarer contracts') ||
                      app.user.can('preview seafarer contracts')
                    )
                  }
                }
              }
            ]
          },
          {
            path: 'Crew/seafarer_contracts',
            redirect: 'Crew/seafarer_contracts/contracts',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t(
                'labels.backend.seafarer_contracts.titles.main.contracts'
              )
            },
            children: [
              {
                path: 'contracts',
                name: 'seafarer_contracts',
                component: () => import('../views/SeafarerContractList'),
                meta: {
                  label: i18n.t(
                    'labels.backend.seafarer_contracts.titles.index.contracts'
                  ),
                  permission: 'view own seafarer contracts'
                }
              },
              {
                path: 'contracts/:id/edit',
                name: 'seafarer_contracts_edit',
                component: () => import('../views/SeafarerContractForm'),
                props: true,
                meta: {
                  label: i18n.t(
                    'labels.backend.seafarer_contracts.titles.edit.contracts'
                  ),
                  permission: 'edit own seafarer contracts'
                }
              },
              {
                path: 'crew_requests',
                name: 'crew_requests',
                component: () => import('../views/CrewRequestList'),
                meta: {
                  label: i18n.t(
                    'labels.backend.seafarer_contracts.titles.index.crew_requests'
                  ),
                  permission: 'view crew requests'
                }
              },
              {
                path: 'crew_requests/create',
                name: 'crew_requests_create',
                component: () => import('../views/CrewRequestForm'),
                meta: {
                  label: i18n.t(
                    'labels.backend.seafarer_contracts.titles.create.crew_requests'
                  ),
                  permission: 'create crew requests'
                }
              },
              {
                path: 'crew_requests/:id/edit',
                name: 'crew_requests_edit',
                component: () => import('../views/CrewRequestForm'),
                props: true,
                meta: {
                  label: i18n.t(
                    'labels.backend.seafarer_contracts.titles.edit.crew_requests'
                  ),
                  permission: 'edit crew requests'
                }
              }
            ]
          },
          {
            path: 'Hierarchy/departments',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.departments.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'departments',
                component: () => import('../views/DepartmentList'),
                meta: {
                  label: i18n.t('labels.backend.departments.titles.index'),
                  permission: 'view vessels'
                }
              },
              {
                path: 'create',
                name: 'departments_create',
                component: () => import('../views/DepartmentForm'),
                meta: {
                  label: i18n.t('labels.backend.departments.titles.create'),
                  permission: 'create departments'
                }
              },
              {
                path: ':id/edit',
                name: 'departments_edit',
                component: () => import('../views/DepartmentForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.departments.titles.edit'),
                  permission: 'edit departments'
                }
              }
            ]
          },
          {
            path: 'Hierarchy/branches',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.branches.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'branches',
                component: () => import('../views/BranchList'),
                meta: {
                  label: i18n.t('labels.backend.branches.titles.index'),
                  permission: 'view branches'
                }
              },
              {
                path: 'create',
                name: 'branches_create',
                component: () => import('../views/BranchForm'),
                meta: {
                  label: i18n.t('labels.backend.branches.titles.create'),
                  permission: 'create branches'
                }
              },
              {
                path: ':id/edit',
                name: 'branches_edit',
                component: () => import('../views/BranchForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.branches.titles.edit'),
                  permission: 'edit branches'
                }
              }
            ]
          },
          {
            path: 'Hierarchy/designations',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.designations.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'designations',
                component: () => import('../views/DesignationList'),
                meta: {
                  label: i18n.t('labels.backend.designations.titles.index'),
                  permission: 'view designations'
                }
              },
              {
                path: 'create',
                name: 'designations_create',
                component: () => import('../views/DesignationForm'),
                meta: {
                  label: i18n.t('labels.backend.designations.titles.create'),
                  permission: 'create designations'
                }
              },
              {
                path: ':id/edit',
                name: 'designations_edit',
                component: () => import('../views/DesignationForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.designations.titles.edit'),
                  permission: 'edit designations'
                }
              }
            ]
          },
          {
            path: 'Access/users',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.users.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'users',
                component: () => import('../views/UserList'),
                meta: {
                  label: i18n.t('labels.backend.users.titles.index'),
                  permission: 'view users'
                }
              },
              {
                path: 'create',
                name: 'users_create',
                component: () => import('../views/UserForm'),
                meta: {
                  label: i18n.t('labels.backend.users.titles.create'),
                  permission: 'create users'
                }
              },
              {
                path: ':id/edit',
                name: 'users_edit',
                component: () => import('../views/UserForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.users.titles.edit'),
                  permission: 'edit users'
                }
              }
            ]
          },
          {
            path: 'Access/roles',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.roles.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'roles',
                component: () => import('../views/RoleList'),
                meta: {
                  label: i18n.t('labels.backend.roles.titles.index'),
                  permission: 'view roles'
                }
              },
              {
                path: 'create',
                name: 'roles_create',
                component: () => import('../views/RoleForm'),
                meta: {
                  label: i18n.t('labels.backend.roles.titles.create'),
                  permission: 'create roles'
                }
              },
              {
                path: ':id/edit',
                name: 'roles_edit',
                component: () => import('../views/RoleForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.roles.titles.edit'),
                  permission: 'edit roles'
                }
              }
            ]
          },
          {
            path: 'Access/groups',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.groups.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'groups',
                component: () => import('../views/GroupList'),
                meta: {
                  label: i18n.t('labels.backend.groups.titles.index'),
                  permission: 'view groups'
                }
              },
              {
                path: 'create',
                name: 'groups_create',
                component: () => import('../views/GroupForm'),
                meta: {
                  label: i18n.t('labels.backend.groups.titles.create'),
                  permission: 'create groups'
                }
              },
              {
                path: ':id/edit',
                name: 'groups_edit',
                component: () => import('../views/GroupForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.groups.titles.edit'),
                  permission: 'edit groups'
                }
              }
            ]
          },
          {
            path: 'Work/tasks',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.tasks.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'tasks',
                component: () => import('../views/TaskList'),
                meta: {
                  label: i18n.t('labels.backend.tasks.titles.index'),
                  permission: 'view vessels'
                }
              }
            ]
          },
          {
            path: 'Work/activities',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.activities.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'activities',
                component: () => import('../views/ActivityList'),
                meta: {
                  label: i18n.t('labels.backend.activities.titles.index'),
                  permission: 'view vessels'
                }
              }
            ]
          },
          // {
          //   path: 'Purchases/purchase_requisitions',
          //   component: {
          //     render(c) {
          //       return c('router-view')
          //     }
          //   },
          //   meta: {
          //     label: i18n.t('labels.backend.purchase_requisitions.titles.main')
          //   },
          //   children: [
          //     {
          //       path: '/',
          //       name: 'purchase_requisitions',
          //       component: () => import('../views/PurchaseRequisitionList'),
          //       meta: {
          //         label: i18n.t(
          //           'labels.backend.purchase_requisitions.titles.index'
          //         ),
          //         permission: 'view purchase requisitions'
          //       }
          //     },
          //     {
          //       path: 'create',
          //       name: 'purchase_requisitions_create',
          //       component: () => import('../views/PurchaseRequisitionForm'),
          //       meta: {
          //         label: i18n.t(
          //           'labels.backend.purchase_requisitions.titles.create'
          //         ),
          //         permission: 'create purchase requisitions'
          //       }
          //     },
          //     {
          //       path: ':id/edit',
          //       name: 'purchase_requisitions_edit',
          //       component: () => import('../views/PurchaseRequisitionForm'),
          //       props: true,
          //       meta: {
          //         label: i18n.t(
          //           'labels.backend.purchase_requisitions.titles.edit'
          //         ),
          //         permission: 'edit purchase requisitions'
          //       }
          //     }
          //   ]
          // },
          {
            path: 'Finance/cash_requisitions',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.cash_requisitions.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'cash_requisitions',
                // component: CashRequisitionList,
                component: () => import('../views/CashRequisitionList'),
                meta: {
                  label: i18n.t(
                    'labels.backend.cash_requisitions.titles.index'
                  ),
                  permission: 'view own cash requisitions'
                }
              },
              {
                path: 'create',
                name: 'cash_requisitions_create',
                // component: CashRequisitionForm,
                component: () => import('../views/CashRequisitionForm'),
                meta: {
                  label: i18n.t(
                    'labels.backend.cash_requisitions.titles.create'
                  ),
                  permission: 'create cash requisitions'
                }
              },
              {
                path: ':id/edit',
                name: 'cash_requisitions_edit',
                // component: CashRequisitionForm,
                component: () => import('../views/CashRequisitionForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.cash_requisitions.titles.edit'),
                  permission: 'edit own cash requisitions'
                }
              }
            ]
          },
          {
            path: 'Finance/payment_requisitions',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.payment_requisitions.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'payment_requisitions',
                // component: PaymentRequisitionList,
                component: () => import('../views/PaymentRequisitionList'),
                meta: {
                  label: i18n.t(
                    'labels.backend.payment_requisitions.titles.index'
                  ),
                  permission: 'view own payment requisitions'
                }
              },
              {
                path: 'create',
                name: 'payment_requisitions_create',
                // component: PaymentRequisitionForm,
                component: () => import('../views/PaymentRequisitionForm'),
                meta: {
                  label: i18n.t(
                    'labels.backend.payment_requisitions.titles.create'
                  ),
                  permission: 'create payment requisitions'
                }
              },
              {
                path: ':id/edit',
                name: 'payment_requisitions_edit',
                // component: PaymentRequisitionForm,
                component: () => import('../views/PaymentRequisitionForm'),
                props: true,
                meta: {
                  label: i18n.t(
                    'labels.backend.payment_requisitions.titles.edit'
                  ),
                  permission: 'edit own payment requisitions'
                }
              }
            ]
          },
          {
            path: 'AdminForms/flight_reservations',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.flight_reservations.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'flight_reservations',
                component: () => import('../views/FlightReservationList'),
                meta: {
                  label: i18n.t(
                    'labels.backend.flight_reservations.titles.index'
                  ),
                  permission: 'view own flight reservations'
                }
              },
              {
                path: 'create',
                name: 'flight_reservations_create',
                component: () => import('../views/FlightReservationForm'),
                meta: {
                  label: i18n.t(
                    'labels.backend.flight_reservations.titles.create'
                  ),
                  permission: 'create flight reservations'
                }
              },
              {
                path: ':id/edit',
                name: 'flight_reservations_edit',
                component: () => import('../views/FlightReservationForm'),
                props: true,
                meta: {
                  label: i18n.t(
                    'labels.backend.flight_reservations.titles.edit'
                  ),
                  permission: 'edit own flight reservations'
                }
              }
            ]
          },
          {
            path: 'HR/Attendance',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            redirect: 'HR/Attendance/attendances',
            meta: {
              label: i18n.t('labels.backend.attendances.titles.main')
            },
            children: [
              {
                path: 'attendances',
                name: 'attendances',
                component: () => import('../views/AttendanceList'),
                meta: {
                  label: i18n.t('labels.backend.attendances.titles.index'),
                  permission: 'view attendances'
                }
              },
              {
                path: 'dept_movement',
                name: 'dept_movement',
                component: () => import('../views/DepartmentMovement'),
                meta: {
                  label: i18n.t(
                    'labels.backend.attendances.titles.dept_movement'
                  ),
                  permission: 'create attendances'
                }
              },
              {
                path: 'hr_representative',
                name: 'hr_representative',
                component: () => import('../views/RepresentativeList'),
                meta: {
                  label: i18n.t(
                    'labels.backend.attendances.titles.representative'
                  ),
                  permission: 'create attendances'
                }
              },
              {
                path: 'fingerprints',
                name: 'fingerprints',
                component: () => import('../views/FingerprintList'),
                meta: {
                  label: i18n.t(
                    'labels.backend.attendances.titles.fingerprints.index'
                  ),
                  permission: 'view fingerprints'
                }
              }
            ]
          },
          {
            path: 'HR/employees',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.employees.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'employees',
                component: () => import('../views/EmployeeList'),
                meta: {
                  label: i18n.t('labels.backend.employees.titles.index'),
                  permission: 'view own employees'
                }
              },
              {
                path: 'create',
                name: 'employees_create',
                component: () => import('../views/EmployeeForm'),
                meta: {
                  label: i18n.t('labels.backend.employees.titles.create'),
                  permission: 'create employees'
                }
              },
              {
                path: ':id/edit',
                name: 'employees_edit',
                component: () => import('../views/EmployeeForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.employees.titles.edit'),
                  permission: 'edit own employees'
                }
              }
            ]
          },
          {
            path: 'HR/leaves',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.leaves.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'leaves',
                component: () => import('../views/LeaveList'),
                meta: {
                  label: i18n.t('labels.backend.leaves.titles.index'),
                  permission: 'view own leaves'
                }
              },
              {
                path: 'create',
                name: 'leaves_create',
                component: () => import('../views/LeaveForm'),
                meta: {
                  label: i18n.t('labels.backend.leaves.titles.create'),
                  permission: 'create leaves'
                }
              },
              {
                path: ':id/edit',
                name: 'leaves_edit',
                component: () => import('../views/LeaveForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.leaves.titles.edit'),
                  permission: 'edit own leaves'
                }
              }
            ]
          },
          {
            path: 'HR/holidays',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.holidays.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'holidays',
                component: () => import('../views/HolidayList'),
                meta: {
                  label: i18n.t('labels.backend.holidays.titles.index'),
                  permission: 'view holidays'
                }
              }
            ]
          },
          {
            path: 'HR/announcements',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.announcements.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'announcements',
                component: () => import('../views/AnnouncementList'),
                meta: {
                  label: i18n.t('labels.backend.announcements.titles.index'),
                  permission: 'view announcements'
                }
              },
              {
                path: 'create',
                name: 'announcements_create',
                component: () => import('../views/AnnouncementForm'),
                meta: {
                  label: i18n.t('labels.backend.announcements.titles.create'),
                  permission: 'create announcements'
                }
              },
              {
                path: ':id/edit',
                name: 'announcements_edit',
                component: () => import('../views/AnnouncementForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.announcements.titles.edit'),
                  permission: 'edit announcements'
                }
              }
            ]
          },
          {
            path: 'doc_templates',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.doc_templates.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'doc_templates',
                component: () => import('../views/DocTemplateList'),
                meta: {
                  label: i18n.t('labels.backend.doc_templates.titles.index'),
                  permission: 'view doc templates'
                }
              },
              {
                path: 'create',
                name: 'doc_templates_create',
                component: () => import('../views/DocTemplateForm'),
                meta: {
                  label: i18n.t('labels.backend.doc_templates.titles.create'),
                  permission: 'create doc templates'
                }
              },
              {
                path: ':id/edit',
                name: 'doc_templates_edit',
                component: () => import('../views/DocTemplateForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.doc_templates.titles.edit'),
                  permission: 'edit doc templates'
                }
              }
            ]
          },
          {
            path: 'vessel_forms',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.vessel_forms.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'vessel_forms',
                component: () => import('../views/VesselFormList'),
                meta: {
                  label: i18n.t('labels.backend.vessel_forms.titles.index'),
                  permission: 'view vessel forms'
                }
              },
              {
                path: 'create',
                name: 'vessel_forms_create',
                component: () => import('../views/VesselFormForm'),
                meta: {
                  label: i18n.t('labels.backend.vessel_forms.titles.create'),
                  permission: 'create vessel forms'
                }
              },
              {
                path: ':id/edit',
                name: 'vessel_forms_edit',
                component: () => import('../views/VesselFormForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.vessel_forms.titles.edit'),
                  permission: 'edit vessel forms'
                }
              }
            ]
          },
          {
            path: 'Audits',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.Audits.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'Audits',
                // component: AuditList,
                component: () => import('../views/AuditList'),
                meta: {
                  label: i18n.t('labels.backend.Audits.titles.index'),
                  permission: 'view audits'
                }
              }
            ]
          },
          {
            path: 'VM/vessel_insurances',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.vessel_insurances.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'vessel_insurances',
                component: () => import('../views/VesselInsuranceList'),
                meta: {
                  label: i18n.t(
                    'labels.backend.vessel_insurances.titles.index'
                  ),
                  permission: 'view vessel insurances'
                }
              },
              {
                path: 'create',
                name: 'vessel_insurances_create',
                component: () => import('../views/VesselInsuranceForm'),
                meta: {
                  label: i18n.t(
                    'labels.backend.vessel_insurances.titles.create'
                  ),
                  permission: 'create vessel insurances'
                }
              },
              {
                path: ':id/edit',
                name: 'vessel_insurances_edit',
                component: () => import('../views/VesselInsuranceForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.vessel_insurances.titles.edit'),
                  permission: 'edit vessel insurances'
                }
              }
            ]
          },
          {
            path: 'VM/inventory/inventory_item_categories',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t(
                'labels.backend.inventory_item_categories.titles.main'
              )
            },
            children: [
              {
                path: '/',
                name: 'inventory_item_categories',
                component: () => import('../views/InventoryItemCategoryList'),
                meta: {
                  label: i18n.t(
                    'labels.backend.inventory_item_categories.titles.index'
                  ),
                  permission: 'view inventory item categories'
                }
              },
              {
                path: 'create',
                name: 'inventory_item_categories_create',
                component: () => import('../views/InventoryItemCategoryForm'),
                meta: {
                  label: i18n.t(
                    'labels.backend.inventory_item_categories.titles.create'
                  ),
                  permission: 'create inventory item categories'
                }
              },
              {
                path: ':id/edit',
                name: 'inventory_item_categories_edit',
                component: () => import('../views/InventoryItemCategoryForm'),
                props: true,
                meta: {
                  label: i18n.t(
                    'labels.backend.inventory_item_categories.titles.edit'
                  ),
                  permission: 'edit inventory item categories'
                }
              }
            ]
          },
          {
            path: 'VM/inventory/inventory_items',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.inventory_items.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'inventory_items',
                component: () => import('../views/InventoryItemList'),
                meta: {
                  label: i18n.t('labels.backend.inventory_items.titles.index'),
                  permission: 'view inventory items'
                }
              },
              {
                path: 'create',
                name: 'inventory_items_create',
                component: () => import('../views/InventoryItemForm'),
                meta: {
                  label: i18n.t('labels.backend.inventory_items.titles.create'),
                  permission: 'create inventory items'
                }
              },
              {
                path: ':id/edit',
                name: 'inventory_items_edit',
                component: () => import('../views/InventoryItemForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.inventory_items.titles.edit'),
                  permission: 'edit inventory items'
                }
              }
            ]
          },
          {
            path: 'VM/inventory/inventories',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.inventories.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'inventories',
                component: () => import('../views/InventoryList'),
                meta: {
                  label: i18n.t('labels.backend.inventories.titles.index'),
                  permission: 'view own inventories'
                }
              },
              {
                path: 'create',
                name: 'inventories_create',
                component: () => import('../views/InventoryForm'),
                meta: {
                  label: i18n.t('labels.backend.inventories.titles.create'),
                  permission: 'create inventories'
                }
              },
              {
                path: ':id/edit',
                name: 'inventories_edit',
                component: () => import('../views/InventoryForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.inventories.titles.edit'),
                  permission: 'edit inventories'
                }
              }
            ]
          },
          {
            path: 'VM/inventory/inventory_transactions',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.inventory_transactions.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'inventory_transactions',
                component: () => import('../views/InventoryTransactionList'),
                meta: {
                  label: i18n.t(
                    'labels.backend.inventory_transactions.titles.index'
                  ),
                  permission: 'view own inventory transactions'
                }
              },
              {
                path: 'create',
                name: 'inventory_transactions_create',
                component: () => import('../views/InventoryTransactionForm'),
                meta: {
                  label: i18n.t(
                    'labels.backend.inventory_transactions.titles.create'
                  ),
                  permission: 'create inventory transactions'
                }
              },
              {
                path: ':id/edit',
                name: 'inventory_transactions_edit',
                component: () => import('../views/InventoryTransactionForm'),
                props: true,
                meta: {
                  label: i18n.t(
                    'labels.backend.inventory_transactions.titles.edit'
                  ),
                  permission: 'edit inventory transactions'
                }
              }
            ]
          },
          {
            path: 'VM/inventory/report',
            name: 'rob_inventory_transactions',
            component: () => import('../views/RobInventoryTransaction'),
            props: true,
            meta: {
              label: i18n.t('labels.backend.inventory_transactions.ROB.main'),
              permission: 'view own rob report'
            }
          },
          {
            path: 'VM/inventory/equipment',
            component: {
              render(c) {
                return c('router-view')
              }
            },
            meta: {
              label: i18n.t('labels.backend.equipment.titles.main')
            },
            children: [
              {
                path: '/',
                name: 'equipment',
                component: () => import('../views/EquipmentList'),
                meta: {
                  label: i18n.t('labels.backend.equipment.titles.index'),
                  permission: 'view own equipment'
                }
              },
              {
                path: 'create',
                name: 'equipment_create',
                component: () => import('../views/EquipmentForm'),
                meta: {
                  label: i18n.t('labels.backend.equipment.titles.create'),
                  permission: 'create equipment'
                }
              },
              {
                path: ':id/edit',
                name: 'equipment_edit',
                component: () => import('../views/EquipmentForm'),
                props: true,
                meta: {
                  label: i18n.t('labels.backend.equipment.titles.edit'),
                  permission: 'edit equipment'
                }
              }
            ]
          }
        ]
      }
    ]
  })

  router.beforeEach(async (to, from, next) => {
    if (
      to.meta.hasOwnProperty('permission') &&
      ((typeof to.meta.permission === 'string' &&
        !router.app.$app.user.can(to.meta.permission)) ||
        (typeof to.meta.permission === 'function' &&
          !to.meta.permission(router.app.$app)))
    ) {
      next('/')
    }

    next()
  })
  return router
}
