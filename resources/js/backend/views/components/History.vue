<template>
  <form @submit.prevent="CommentSubmitted">
    <b-card>
      <template slot="header">
        <h4>History</h4>
      </template>
      <b-row class="justify-content-center">
        <div class="list-group" style="width: 95%">
          <a
            v-for="(rec, index) in history"
            :key="index"
            class="list-group-item list-group-item-action flex-column align-items-start"
          >
            <b-row class="b-row d-flex w-100 justify-content-between mb-4">
              <b-col cols="4">
                <h5 class="mb-1 text-primary font-weight-bold">
                  {{ rec.user_name }}
                </h5>
              </b-col>
              <b-col cols="4" class="text-center">
                <h6 class="mb-1 text-primary font-weight-bold">
                  {{ rec.auditable_type }}
                </h6>
              </b-col>
              <b-col cols="4" class="text-right">
                <small style="direction: ltr"> {{ rec.created_at }} </small>
              </b-col>
            </b-row>
            <div v-for="(value, key) in Object.keys(rec.new_values)" :key="key">
              <div
                v-if="
                  rec.old_values[value] !== null ||
                    rec.new_values[value] !== null
                "
              >
                {{ value }}:
                <b-badge variant="danger">
                  {{ rec.old_values[value] }}
                </b-badge>
                =>
                <b-badge variant="success">
                  {{ rec.new_values[value] }}
                </b-badge>
              </div>
            </div>
          </a>
        </div>
      </b-row>
      <b-row> </b-row>
    </b-card>
  </form>
</template>
<script>
import axios from 'axios'

export default {
  name: 'History',
  props: {
    modelName: {
      default: () => '',
      type: String,
      required: true
    },
    modelId: {
      default: '',
      type: String,
      required: true
    }
  },
  data() {
    return {
      notnull: false,
      history: [],
      pending: false
    }
  },
  async created() {
    this.getHistory()
  },
  methods: {
    async getHistory() {
      let { data } = await axios.get(
        this.$app.route('get_history', {
          controller_name: this.modelName,
          item: this.modelId
        })
      )
      this.history = data
    }
  }
}
</script>
