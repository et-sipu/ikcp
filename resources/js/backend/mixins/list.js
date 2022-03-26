export default {
  mounted() {
    let page_number = this.$route.hash ? this.$route.hash.substr(1) : 1

    this.$refs.datasource.currentPage = page_number
  },
  methods: {
    dataLoadProvider(ctx) {
      return this.$refs.datasource.loadData(ctx.sortBy, ctx.sortDesc)
    },
    onContextChanged() {
      return this.$refs.datatable.refresh()
    },
    onContextChangedWithPage() {
      this.$refs.datasource.currentPage = 1
      this.onContextChanged()
    },
    onDelete(id) {
      this.$refs.datasource.deleteRow({ [this.item_type]: id })
    }
  }
}
