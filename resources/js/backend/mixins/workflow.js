import axios from 'axios'

export default {
  methods: {
    getTransitionInfo(transition) {
      let urgent_regex = RegExp('^urgent')
      let acceptance_regex = RegExp('approved$')
      let rejection_regex = RegExp('declined$')
      if (urgent_regex.test(transition))
        return {
          icon: 'fast-forward',
          variant: 'info',
          label: 'urgent_pass',
          urgent: true
        }
      else if (acceptance_regex.test(transition))
        return {
          icon: 'check',
          variant: 'success',
          label: 'approve',
          urgent: false
        }
      else if (rejection_regex.test(transition))
        return {
          icon: 'ban',
          variant: 'danger',
          label: 'reject',
          urgent: false
        }
      return {
        icon: 'forward',
        variant: 'primary',
        label: 'forward',
        urgent: false
      }
    },
    async changeStatus(id, status, is_form = false) {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        text: this.$t('labels.descriptions.action_not_reversible'),
        type: 'info',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: RegExp('declined$').test(status)
          ? 'red'
          : '#4bb21f',
        confirmButtonText: this.$t('buttons.yes')
      })

      if (result.value) {
        this.loading = true
        if (is_form) {
          this.pending = true
        }
        try {
          let { data } = await axios.post(
            this.$app.route(`${this.item_type}s.change_status`, {
              [this.item_type]: id,
              status: status
            })
          )
          this.loading = false
          if (is_form) {
            this.fetchData()
            this.pending = false
            this.validation = []
          } else {
            this.onContextChanged()
          }
          this.$app.noty[data.status](data.message)
        } catch (e) {
          this.$app.error(e)
          this.loading = false
          if (is_form) this.pending = false
        }
      }
    }
  }
}
