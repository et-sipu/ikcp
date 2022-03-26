import './load-client-scripts'

// Vue & axios
import Vue from 'vue'
import '../axios-config'
import axios from 'axios'

import 'babel-polyfill'
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm'

// Vendor plugins components
import '../vendor/coreui/components'
import DataTable from './components/Plugins/DataTable'
import DateTimePicker from './components/Plugins/DateTimePicker'
import Switch from './components/Plugins/Switch'
import vSelect from './components/Plugins/Select'

import { createRouter } from './router'
import { createStore } from './store'
import { createLocales } from '../vue-i18n-config'

import App from './App.vue'
// import Noty from 'noty'
import Toasted from 'vue-toasted'
import VueProgressBar from 'vue-progressbar'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Echo from 'laravel-echo'
// window.io = require('socket.io-client')

window.Pusher = require('pusher-js')

// if (typeof io !== 'undefined') {
let pusher_config = window.settings.pusher_config
if (pusher_config.wsHost === '') pusher_config.wsHost = window.location.hostname

window.Echo = new Echo(pusher_config)

const options = {
  color: '#20a8d8',
  failedColor: 'red',
  thickness: '2px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}

Vue.use(VueProgressBar, options)

Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.config.productionTip = false

// Bootstrap Vue
Vue.use(BootstrapVue)

// vue-select
Vue.component('v-select', vSelect)

// Custom components
Vue.component('c-switch', Switch)
Vue.component('p-datetimepicker', DateTimePicker)
Vue.component('b-datatable', DataTable)

export function createApp() {
  // Init router and store
  const i18n = createLocales(window.settings.locale)
  const router = createRouter(window.settings.adminHomePath, i18n, window.route)
  const store = createStore(window.route)

  Vue.use(Toasted, {
    iconPack: 'fontawesome',
    router: router
  })

  /**
   * Server-side settings
   */
  Vue.prototype.$app = window.settings

  /**
   * Server-side named routes function router
   */
  Vue.prototype.$app.route = window.route

  /**
   * Client-side permissions
   */
  if (Vue.prototype.$app.user) {
    Vue.prototype.$app.user.can = permission => {
      if (
        Vue.prototype.$app.user.id === 1 ||
        Vue.prototype.$app.permissions.includes('access all backend')
      ) {
        return true
      }
      return Vue.prototype.$app.permissions.includes(permission)
    }

    Vue.prototype.$app.user.isSuperAdmin = () => {
      if (
        Vue.prototype.$app.user.id === 1 ||
        Vue.prototype.$app.permissions.includes('access all backend')
      ) {
        return true
      }
      return false
    }
  }

  let objectToFormData = (model, form, namespace) => {
    let formData = form || new FormData()

    if (model == null) {
      formData.append(namespace, '')
    } else if (typeof model === 'string' || typeof model === 'number') {
      formData.append(namespace, model.toString())
      return formData
    }

    for (let propertyName in model) {
      if (!model.hasOwnProperty(propertyName)) {
        continue
      }
      let formKey = namespace ? `${namespace}[${propertyName}]` : propertyName
      if (model[propertyName] instanceof Date) {
        formData.append(formKey, model[propertyName].toISOString())
      } else if (model[propertyName] instanceof Array) {
        if (!model[propertyName].length) {
          formData.append(formKey, '')
        }
        model[propertyName].forEach((element, index) => {
          const tempFormKey = `${formKey}[${index}]`
          objectToFormData(element, formData, tempFormKey)
        })
      } else if (
        typeof model[propertyName] === 'object' &&
        !(model[propertyName] instanceof File)
      ) {
        objectToFormData(model[propertyName], formData, formKey)
      } else if (model[propertyName] instanceof File) {
        formData.append(formKey, model[propertyName])
      } else {
        formData.append(formKey, model[propertyName].toString())
      }
    }
    return formData
  }

  Vue.prototype.$app.objectToFormData = objectToFormData

  /**
   * Notifications
   */
  // let noty = (type, text) => {
  //   new Noty({
  //     layout: 'topRight',
  //     theme: 'bootstrap-v4',
  //     timeout: 4000,
  //     text,
  //     type
  //   }).show()
  // }

  Vue.prototype.$app.noty = {
    alert: text => {
      // noty('alert', text)
      Vue.toasted.global.alert(text)
    },
    success: text => {
      // noty('success', text)
      Vue.toasted.global.success(text)
    },
    error: text => {
      // noty('error', text)
      Vue.toasted.global.error(text)
    },
    info: text => {
      // noty('info', text)
      Vue.toasted.global.info(text)
    },
    notify: (text, notification_id, to = null) => {
      // console.log(to)
      // noty('info', text)
      // Vue.toasted.global.notification({ text: text, to: to })
      let options = {
        type: 'info',
        theme: 'outline',
        icon: { name: 'bell' },
        duration: 5000,
        className: 'toasted-noty',
        keepOnHover: true
      }

      if (to) {
        let to_route = Object.assign({}, router.resolve(to).resolved)
        to_route.dontClose = true
        options.action = [
          {
            text: 'Follow Up',
            onClick: (e, toastObject) => {
              store.dispatch('MARK_NOTIFICATION_AS', {
                notification_id: notification_id,
                status: 'read'
              })
            },
            push: to_route
          }
        ]
      }

      Vue.toasted.show(text, options)
    }
  }

  Vue.toasted.register(
    'error',
    message => {
      if (!message) {
        return 'Oops.. Something Went Wrong..'
      }
      return '<span>Oops.. ' + message + '</span>'
    },
    {
      type: 'error',
      theme: 'outline',
      icon: { name: 'times-circle' },
      duration: 5000,
      className: 'toasted-noty',
      keepOnHover: true
    }
  )
  Vue.toasted.register(
    'alert',
    message => {
      if (!message) {
        return 'Alert'
      }
      return '<span>' + message + '</span>'
    },
    {
      type: 'info',
      theme: 'outline',
      icon: { name: 'exclamation-circle' },
      duration: 5000,
      className: 'toasted-noty',
      keepOnHover: true
    }
  )
  Vue.toasted.register(
    'success',
    message => {
      if (!message) {
        return 'Success'
      }
      return '<span>' + message + '</span>'
    },
    {
      type: 'success',
      theme: 'outline',
      icon: { name: 'check-circle' },
      duration: 5000,
      className: 'toasted-noty',
      keepOnHover: true
    }
  )
  Vue.toasted.register(
    'info',
    message => {
      if (!message) {
        return 'Info'
      }
      return '<span>' + message + '</span>'
    },
    {
      type: 'info',
      theme: 'outline',
      icon: { name: 'info-circle' },
      duration: 5000,
      className: 'toasted-noty',
      keepOnHover: true
    }
  )

  Vue.prototype.$app.error = error => {
    // if (error instanceof String) {
    if (typeof error === 'string' || error instanceof String) {
      // noty('error', error)
      Vue.toasted.global.error('error', error)
      return
    }

    if (error.hasOwnProperty('response') && error.response) {
      // Not allowed error
      if (error.response.status === 403) {
        // window.location = window.route('home')
        router.push({ name: 'home' }, () =>
          // noty('error', i18n.t('exceptions.unauthorized'))
          Vue.toasted.global.error(i18n.t('exceptions.unauthorized'))
        )
        // noty('error', i18n.t('exceptions.unauthorized'))
        return
      }

      if (error.response.status === 404) {
        // noty('error', i18n.t('exceptions.not_found'))
        Vue.toasted.global.error(i18n.t('exceptions.not_found'))
        return
      }

      if (error.response.status === 401) {
        // noty('error', i18n.t('exceptions.login_again'))
        window.location = window.route('login')
        return
      }

      if (error.response.status === 419) {
        // console.log('Session Expired')

        return
      }

      // Domain error
      if (
        error.response.data.error !== undefined ||
        (error.response.data.hasOwnProperty('message') &&
          error.response.data.message !== undefined)
      ) {
        // noty('error', error.response.data.message)
        Vue.toasted.global.error(error.response.data.message)
        return
      }
    } else if (error.response === undefined) {
      return
    }

    // Generic error
    // noty('error', i18n.t('exceptions.general'))
    Vue.toasted.global.error(i18n.t('exceptions.general'))
  }

  router.beforeEach((to, from, next) => {
    document.title = `${to.meta.label} | ${window.settings.appName}`
    next()
  })

  const app = new Vue({
    router,
    store,
    i18n,
    render: h => h(App)
  })

  return { app, router, store }
}

// Init App
if (document.getElementById('app') !== null) {
  const { app } = createApp()
  app.$mount('#app')
}
