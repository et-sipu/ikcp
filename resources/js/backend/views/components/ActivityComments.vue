<template>
  <form @submit.prevent>
    <b-card>
      <div class="input-group" slot="header" v-if="withInput">
        <div class="input-group-prepend">
          <b-button
            @click.stop="CommentSubmitted(true)"
            :disabled="pending || !comment"
            v-if="withDone"
            variant="success"
            size="md"
          >
            <font-awesome-icon icon="check"></font-awesome-icon>
          </b-button>
        </div>
        <b-form-input
          id="comment"
          name="comment"
          :placeholder="$t('validation.attributes.comment')"
          v-model="comment"
        ></b-form-input>
        <div class="input-group-append">
          <b-button
            @click.stop="CommentSubmitted(false)"
            :disabled="pending || !comment"
            variant="primary"
            size="md"
          >
            <font-awesome-icon icon="comment-dots"></font-awesome-icon>
          </b-button>
        </div>
      </div>
      <ul
        class="list-group card-list-group"
        v-for="(cmt, index) in comments"
        :key="index"
      >
        <li class="list-group-item pt-3">
          <div class="media">
            <div
              class="media-object avatar avatar-md mr-2"
              :style="
                'background-image: url(' +
                  cmt.avatar +
                  '); background-size: contain'
              "
            ></div>
            <div class="media-body">
              <div class="media-heading">
                <small class="float-right text-muted">
                  {{ cmt.created_at }}
                </small>
                <h5>{{ cmt.name }}</h5>
              </div>
              <div>{{ cmt.comment }}</div>
            </div>
          </div>
        </li>
      </ul>
      <div
        slot="footer"
        class="text-center"
        v-if="load_more"
        @click="getComments"
      >
        <font-awesome-icon icon="sync" :spin="loading_more"></font-awesome-icon>
      </div>
    </b-card>
  </form>
</template>
<script>
import axios from 'axios'

export default {
  name: 'ActivityComments',
  props: {
    modelName: {
      default: () => '',
      type: String,
      required: true
    },
    modelId: {
      default: 0,
      type: Number,
      required: true
    },
    withDone: {
      default: true,
      type: Boolean
    },
    withInput: {
      default: true,
      type: Boolean
    }
  },
  data() {
    return {
      comment: null,
      comments: [],
      current_page: 0,
      per_page: 5,
      load_more: true,
      pending: false,
      loading_more: false
    }
  },
  async created() {
    this.getComments()
  },
  methods: {
    async getComments() {
      this.loading_more = true
      let { data } = await axios.get(
        this.$app.route('get_comments', {
          controller_name: this.modelName,
          item: this.modelId
        }) +
          '?current_page=' +
          this.current_page++ +
          '&per_page=' +
          this.per_page
      )
      this.comments.push(...data)

      if (data.length < this.per_page) {
        this.load_more = false
      }
      this.loading_more = false
    },
    async CommentSubmitted(done = false) {
      this.pending = true

      let action = this.$app.route('add_comment', {
        controller_name: this.modelName,
        item: this.modelId
      })

      try {
        let { data } = await axios.post(action, {
          comment: this.comment,
          done: done
        })
        this.pending = false

        this.$app.noty[data.status](data.message)

        this.comment = null
        if (done) {
          this.$emit('task-done')
        } else {
          this.current_page = 0
          this.comments = []
          this.getComments()
        }
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
