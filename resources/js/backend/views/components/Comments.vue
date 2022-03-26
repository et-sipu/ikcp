<template>
  <form @submit.prevent="CommentSubmitted">
    <b-card>
      <template slot="header">
        <h4>{{ $t('labels.comments') }}</h4>
      </template>
      <b-row class="justify-content-center">
        <div class="list-group" style="width: 95%">
          <a
            v-for="(cmt, index) in comments"
            :key="index"
            class="list-group-item list-group-item-action flex-column align-items-start"
          >
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1 text-primary font-weight-bold">{{ cmt.name }}</h5>
              <small style="direction: ltr"> {{ cmt.created_at }} </small>
            </div>
            <p class="mb-1">{{ cmt.comment }}</p>
          </a>
        </div>
      </b-row>
      <b-row slot="footer">
        <b-col md>
          <b-form-textarea
            id="comment"
            name="comment"
            :rows="1"
            :placeholder="$t('validation.attributes.comment')"
            v-model="comment"
          ></b-form-textarea>
        </b-col>
        <b-col md>
          <b-button
            type="submit"
            :disabled="pending || !comment"
            variant="primary"
            size="md"
          >
            {{ $t('buttons.send') }}
          </b-button>
        </b-col>
      </b-row>
    </b-card>
  </form>
</template>
<script>
import axios from 'axios'

export default {
  name: 'Comments',
  props: {
    modelName: {
      default: () => '',
      type: String,
      required: true
    },
    modelId: {
      default: '',
      type: [String, Number],
      required: true
    }
  },
  data() {
    return {
      comment: null,
      comments: [],
      pending: false
    }
  },
  async created() {
    this.getComments()
  },
  methods: {
    async getComments() {
      let { data } = await axios.get(
        this.$app.route('get_comments', {
          controller_name: this.modelName,
          item: this.modelId
        })
      )
      this.comments = data
    },
    async CommentSubmitted() {
      this.pending = true
      let action = this.$app.route('add_comment', {
        controller_name: this.modelName,
        item: this.modelId
      })

      try {
        let { data } = await axios.post(action, {
          comment: this.comment
        })
        this.pending = false

        this.$app.noty[data.status](data.message)

        this.comment = null
        this.getComments()
      } catch (e) {
        this.pending = false

        if (e.response.status === 422) {
          this.validation = e.response.data
          return
        }

        this.$app.error(e)
      }
    }
  }
}
</script>
