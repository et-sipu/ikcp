export default {
  SET_COUNTER: (state, { type, counter }) => {
    state.counters[type] = counter
  },
  SET_USER: (state, { user }) => {
    state.user = user
  },
  SET_NOTIFICATIONS: (state, { notifications }) => {
    state.latestUnreadNotifications = notifications
  }
}
