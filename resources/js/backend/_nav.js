export default (app, i18n) => {
  return [
    {
      name: i18n.t('labels.backend.titles.dashboard'),
      url: '/dashboard',
      icon: 'fe fe-trending-up',
      access: true
    },
    {
      divider: true,
      access: true
    },
    {
      name: i18n.t('labels.backend.vessels.titles.main'),
      url: '/VM',
      icon: 'fa.ship',
      access:
        app.user.can('view own vessels') ||
        app.user.can('view vessel certificates') ||
        app.user.can('view vessel forms') ||
        app.user.can('view vessel insurances'),
      children: [
        {
          name: i18n.t('labels.backend.vessels.titles.main'),
          url: '/VM/vessels',
          icon: 'fa.ship',
          access:
            app.user.can('view own vessels') ||
            app.user.can('view vessel certificates') ||
            app.user.can('view vessel forms')
        },
        {
          name: i18n.t('labels.backend.vessel_insurances.titles.main'),
          url: '/VM/vessel_insurances',
          icon: 'fa.shield-alt',
          access: app.user.can('view vessel insurances')
        },
        {
          name: i18n.t('labels.backend.sidebar.vessel_inventory'),
          url: '/VM/inventory',
          icon: 'fa.warehouse',
          access:
            app.user.can('view inventory item categories') ||
            app.user.can('view inventory items') ||
            app.user.can('view own inventories') ||
            app.user.can('view own inventory transactions') ||
            app.user.can('view own equipment'),
          children: [
            {
              name: i18n.t(
                'labels.backend.inventory_item_categories.titles.main'
              ),
              url: '/VM/inventory/inventory_item_categories',
              icon: 'fa.list',
              access: app.user.can('view inventory item categories')
            },
            {
              name: i18n.t('labels.backend.inventory_items.titles.main'),
              url: '/VM/inventory/inventory_items',
              icon: 'fa.box',
              access: app.user.can('view inventory items')
            },
            {
              name: i18n.t('labels.backend.inventories.titles.main'),
              url: '/VM/inventory/inventories',
              icon: 'fa.warehouse',
              access: app.user.can('view own inventories')
            },
            {
              name: i18n.t('labels.backend.inventory_transactions.titles.main'),
              url: '/VM/inventory/inventory_transactions',
              icon: 'fa.exchange-alt',
              access: app.user.can('view own inventory transactions')
            },
            {
              name: i18n.t('labels.backend.equipment.titles.main'),
              url: '/VM/inventory/equipment',
              icon: 'fa.tools',
              access: app.user.can('view own equipment')
            },
            {
              name: i18n.t('labels.backend.inventory_transactions.ROB.main'),
              url: '/VM/inventory/report',
              icon: 'fa.chart-area',
              access: app.user.can('view own rob report')
            }
          ]
        }
      ]
    },
    {
      divider: true,
      access: true
    },
    {
      name: i18n.t('labels.backend.sidebar.crew'),
      url: '/Crew',
      icon: 'fi flaticon-profession',
      access: app.user.can('view seafarers'),
      children: [
        {
          name: i18n.t('labels.backend.seafarers.titles.main'),
          url: '/Crew/seafarers',
          icon: 'fi flaticon-profession',
          access: app.user.can('view seafarers')
        },
        {
          name: i18n.t('labels.backend.seafarer_contracts.titles.menu'),
          url: '/Crew/seafarer_contracts',
          icon: 'fa.file-signature',
          access:
            app.user.can('view own seafarer contracts') ||
            app.user.can('view crew requests'),
          children: [
            {
              name: i18n.t(
                'labels.backend.seafarer_contracts.titles.main.contracts'
              ),
              url: '/Crew/seafarer_contracts/contracts',
              icon: 'fa.file-signature',
              access: app.user.can('view own seafarer contracts')
            },
            {
              name: i18n.t(
                'labels.backend.seafarer_contracts.titles.main.crew_requests'
              ),
              url: '/Crew/seafarer_contracts/crew_requests',
              icon: 'fa.user-plus',
              access: app.user.can('view crew requests')
            }
          ]
        },
        {
          name: i18n.t('labels.backend.sidebar.settings'),
          icon: 'fa.cog',
          url: '/Crew/Settings',
          access: app.user.can('view ports'),
          children: [
            {
              name: i18n.t('labels.backend.ports.titles.main'),
              url: '/Crew/Settings/ports',
              icon: 'fa.anchor',
              access: app.user.can('view ports')
            }
          ]
        }
      ]
    },
    {
      divider: true,
      access: true
    },
    {
      name: i18n.t('labels.backend.sidebar.purchases'),
      url: '/Purchases',
      icon: 'fa.dolly',
      access: app.user.can('view purchase requisitions'),
      children: [
        {
          name: i18n.t('labels.backend.purchase_requisitions.titles.main'),
          url: '/Purchases/purchase_requisitions',
          icon: 'fa.dolly',
          access: app.user.can('view purchase requisitions')
        }
      ]
    },
    {
      divider: true,
      access: true
    },
    {
      name: i18n.t('labels.backend.sidebar.admin_forms'),
      url: '/AdminForms',
      icon: 'fa.newspaper',
      access: app.user.can('view own flight reservations'),
      children: [
        {
          name: i18n.t('labels.backend.flight_reservations.titles.main'),
          url: '/AdminForms/flight_reservations',
          icon: 'fa.plane',
          access: app.user.can('view own flight reservations')
        }
      ]
    },
    {
      name: i18n.t('labels.backend.sidebar.hr'),
      url: '/HR',
      icon: 'fa.address-book',
      access:
        app.user.can('view attendances') ||
        app.user.can('create attendances') ||
        app.user.can('view own employees'),
      children: [
        {
          name: i18n.t('labels.backend.sidebar.attendance'),
          url: '/HR/Attendance',
          icon: 'fa.calendar-alt',
          access:
            app.user.can('view attendances') ||
            app.user.can('create attendances'),
          children: [
            {
              name: i18n.t(
                'labels.backend.attendances.titles.daily_attendance'
              ),
              url: '/HR/Attendance/attendances',
              icon: 'fa.calendar-alt',
              access: app.user.can('view attendances')
            },
            {
              name: i18n.t('labels.backend.attendances.titles.dept_movement'),
              url: '/HR/Attendance/dept_movement',
              icon: 'fa.hand-paper',
              access: app.user.can('create attendances')
            },
            {
              name: i18n.t('labels.backend.attendances.titles.representative'),
              url: '/HR/Attendance/hr_representative',
              icon: 'fa.user-check',
              access:
                app.user.can('create attendances') &&
                app.user.can('attendances representative')
            },
            {
              name: i18n.t(
                'labels.backend.attendances.titles.fingerprints.main'
              ),
              url: '/HR/Attendance/fingerprints',
              icon: 'fa.fingerprint',
              access: app.user.can('view fingerprints')
            }
          ]
        },
        {
          name: i18n.t('labels.backend.employees.titles.main'),
          url: '/HR/employees',
          icon: 'fa.user-tie',
          access: app.user.can('view own employees')
        },
        {
          name: i18n.t('labels.backend.leaves.titles.main'),
          url: '/HR/leaves',
          icon: 'fa.cocktail',
          access: app.user.can('view own leaves')
        },
        {
          name: i18n.t('labels.backend.holidays.titles.main'),
          url: '/HR/holidays',
          icon: 'fa.umbrella-beach',
          access: app.user.can('view holidays')
        },
        {
          name: i18n.t('labels.backend.announcements.titles.main'),
          url: '/HR/announcements',
          icon: 'fa.bullhorn',
          access: app.user.can('view own announcements')
        }
      ]
    },
    {
      divider: true,
      access: true
    },
    {
      name: i18n.t('labels.backend.sidebar.finance'),
      url: '/Finance',
      icon: 'fa.dollar-sign',
      access:
        app.user.can('view own cash requisitions') ||
        app.user.can('view own payment requisitions'),
      children: [
        {
          name: i18n.t('labels.backend.cash_requisitions.titles.main'),
          url: '/Finance/cash_requisitions',
          icon: 'fa.money-bill-wave',
          access: app.user.can('view own cash requisitions')
        },
        {
          name: i18n.t('labels.backend.payment_requisitions.titles.main'),
          url: '/Finance/payment_requisitions',
          icon: 'fa.file-invoice-dollar',
          access: app.user.can('view own payment requisitions')
        }
      ]
    },
    {
      divider: true,
      access: true
    },
    {
      name: i18n.t('labels.backend.sidebar.work'),
      url: '/Work',
      icon: 'fa.tasks',
      access:
        app.user.can('view own tasks') || app.user.can('view own activities'),
      children: [
        {
          name: i18n.t('labels.backend.tasks.titles.main'),
          url: '/Work/tasks',
          icon: 'fa.tasks',
          access: app.user.can('view own tasks')
        },
        {
          name: i18n.t('labels.backend.activities.titles.main'),
          url: '/Work/activities',
          icon: 'fa.thumbtack',
          access: app.user.can('view own activities')
        }
      ]
    },
    {
      divider: true,
      access: true
    },
    {
      name: i18n.t('labels.backend.sidebar.hierarchy'),
      url: '/Hierarchy',
      icon: 'fa.building',
      access: app.user.can('view departments'),
      children: [
        {
          name: i18n.t('labels.backend.departments.titles.main'),
          url: '/Hierarchy/departments',
          icon: 'fa.sitemap',
          access: app.user.can('view departments')
        },
        {
          name: i18n.t('labels.backend.branches.titles.main'),
          url: '/Hierarchy/branches',
          icon: 'fa.industry',
          access: app.user.can('view branches')
        },
        {
          name: i18n.t('labels.backend.designations.titles.main'),
          url: '/Hierarchy/designations',
          icon: 'fab.black-tie',
          access: app.user.can('view designations')
        }
      ]
    },
    {
      divider: true,
      access: true
    },
    {
      name: i18n.t('labels.backend.sidebar.access'),
      url: '/Access',
      icon: 'fa.user-shield',
      access: app.user.can('view users') || app.user.can('view roles'),
      children: [
        {
          name: i18n.t('labels.backend.users.titles.main'),
          url: '/Access/users',
          icon: 'fa.users-cog',
          access: app.user.can('view users')
        },
        {
          name: i18n.t('labels.backend.roles.titles.main'),
          url: '/Access/roles',
          icon: 'fa.user-shield',
          access: app.user.can('view roles')
        },
        {
          name: i18n.t('labels.backend.groups.titles.main'),
          url: '/Access/groups',
          icon: 'fa.users',
          access: app.user.can('view groups')
        }
      ]
    },
    {
      name: i18n.t('labels.backend.Audits.titles.main'),
      url: '/Audits',
      icon: 'fa.history',
      access: app.user.can('view audits')
    },
    {
      name: i18n.t('labels.backend.doc_templates.titles.main'),
      url: '/doc_templates',
      icon: 'fa.folder-open',
      access: app.user.can('view doc templates')
    }
  ]
}
