import Vue from 'vue'
import Vuex from 'vuex'

import { createActions } from './actions'
import mutations from './mutations'

Vue.use(Vuex)

export function createStore(route) {
  const actions = createActions(route)

  return new Vuex.Store({
    state: {
      counters: {
        unreadNotificationsCount: 0
      },
      latestUnreadNotifications: [],
      user: null
    },
    actions,
    mutations,
    getters: {
      getUser: state => state => state.user
    }
  })
}
