import AtomSpinner from '../components/Plugins/AtomSpinner.vue'

export default {
  components: { AtomSpinner },
  data() {
    return {
      validation: {},
      pending: false,
      loading: false,
      full_loader: false
    }
  },
  methods: {
    feedback(name) {
      if (this.state(name)) {
        return this.validation.errors[name][0]
      }
    },
    state(name) {
      return this.validation.errors !== undefined &&
        this.validation.errors.hasOwnProperty(name)
        ? 'invalid'
        : null
    }
  }
}
