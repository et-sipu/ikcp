export default {
  props: {
    errors: {
      default: () => null,
      type: Object,
      required: false
    }
  },
  methods: {
    feedback(name) {
      if (this.state(name)) {
        if (this.errors !== null) return this.errors[name][0]
        else return this.$parent.validation.errors[name][0]
      }
    },
    state(name) {
      let errors =
        this.errors !== null ? this.errors : this.$parent.validation.errors

      return errors !== undefined && errors.hasOwnProperty(name)
        ? 'invalid'
        : null
    }
  }
}
