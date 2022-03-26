import axios from 'axios'

export function createActions(route) {
  return {
    LOAD_COUNTERS: ({ commit }) => {
      // return new Promise((resolve) => {
      //   axios.all([
      //     axios.get(route('posts.draft.counter')),
      //     axios.get(route('posts.pending.counter')),
      //     axios.get(route('posts.published.counter')),
      //     axios.get(route('users.active.counter')),
      //     axios.get(route('form_submissions.counter'))
      //   ])
      //     .then(axios.spread(
      //       (
      //         newPostsCount,
      //         pendingPostsCount,
      //         publishedPostsCount,
      //         activeUsersCount,
      //         formSubmissionsCount
      //       ) => {
      //         commit('SET_COUNTER', {type: 'newPostsCount', counter: newPostsCount.data})
      //         commit('SET_COUNTER', {type: 'pendingPostsCount', counter: pendingPostsCount.data})
      //         commit('SET_COUNTER', {type: 'publishedPostsCount', counter: publishedPostsCount.data})
      //         commit('SET_COUNTER', {type: 'activeUsersCount', counter: activeUsersCount.data})
      //         commit('SET_COUNTER', {type: 'formSubmissionsCount', counter: formSubmissionsCount.data})
      //
      //         resolve()
      //       }))
      // })

      return new Promise(resolve => {
        axios
          .get(route('notifications.get_unread_notifications_count'))
          .then(response => {
            commit('SET_COUNTER', {
              type: 'unreadNotificationsCount',
              counter: response.data.count
            })

            resolve()
          })
      })
    },
    GET_NOTIFICATIONS: ({ commit }) => {
      return new Promise(resolve => {
        axios
          .get(route('notifications.get_notifications', { latest: 'latest' }))
          .then(response => {
            commit('SET_NOTIFICATIONS', { notifications: response.data.data })

            resolve()
          })
      })
    },
    MARK_NOTIFICATION_AS: ({ dispatch }, payload) => {
      // console.log(payload)
      return new Promise(resolve => {
        // console.log(payload)
        axios
          .post(
            route('notifications.toggle_notification_status', {
              notification: payload.notification_id,
              mark_as: payload.status
              // mark_as: mark_as
            })
          )
          .then(async response => {
            await dispatch('LOAD_COUNTERS')
            await dispatch('GET_NOTIFICATIONS')
          })

        resolve()
      })
    },
    GET_USER: ({ commit }) => {
      return new Promise(resolve => {
        axios.get(route('session_status')).then(response => {
          commit('SET_USER', { user: response.data.user })

          resolve()
        })
      })
    }
  }
}
