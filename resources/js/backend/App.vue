<template>
  <div>
    <router-view></router-view>
    <vue-progress-bar></vue-progress-bar>
  </div>
</template>

<script>
export default {
  name: 'App',
  mounted() {
    //  [App.vue specific] When App.vue is finish loading finish the progress bar
    this.$Progress.finish()
    this.$store.watch(this.$store.getters.getUser, user => {
      // console.log(user)
      if (!user) {
        window.location = this.$app.route('logout')
      }
    })

    window.Echo.private(`App.Models.User.${this.$app.user.id}`).notification(
      async notification => {
        // console.log(notification)
        this.$app.noty['notify'](
          notification.subject,
          notification.id,
          notification.link ? notification.link : null
        )

        await this.$store.dispatch('LOAD_COUNTERS')
        await this.$store.dispatch('GET_NOTIFICATIONS')
        if (!('Notification' in window)) {
          alert('Web Notification is not supported')
          return
        }

        Notification.requestPermission(permission => {
          let ntf = new Notification(notification.subject, {
            body: notification.body, // content for the alert
            icon: 'https://ikcp.imwsb.com.my/images/logo.png' // optional image url
          })

          // link to page on clicking the notification
          ntf.onclick = () => {
            window.open(window.location.href)
          }
        })
      }
    )
    // window.Echo.private('user.1').listen('PrivateEvent', e => {
    //   console.log(e)
    // })
  },
  created() {
    //  [App.vue specific] When App.vue is first loaded start the progress bar
    this.$Progress.start()
    //  hook the progress bar to start before we move router-view
    this.$router.beforeEach((to, from, next) => {
      //  does the page we want to go to have a meta.progress object
      if (to.meta.progress !== undefined) {
        let meta = to.meta.progress
        // parse meta tags
        this.$Progress.parseMeta(meta)
      }
      //  start the progress bar
      this.$Progress.start()
      //  continue to next page
      next()
    })
    //  hook the progress bar to finish after we've finished moving router-view
    this.$router.afterEach((to, from) => {
      //  finish the progress bar
      this.$Progress.finish()
    })
  }
}
</script>
