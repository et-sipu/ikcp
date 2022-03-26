<template>
  <div class="animated fadeIn">
    <b-modal
      id="notification-modal"
      :title="note.subject"
      v-model="showNotificationModal"
      :hide-footer="note.link === null || note.link === undefined"
      :size="note.trace ? 'xl' : 'lg'"
    >
      <p class="my-4" v-html="note.body"></p>
      <b-button v-b-toggle.trace size="sm" v-if="note.trace"
        >Stack Trace</b-button
      >
      <b-collapse id="trace" class="mt-2" v-if="note.trace">
        <b-card>
          <div>
            <code v-html="note.trace"></code>
          </div>
        </b-card>
      </b-collapse>
      <div slot="modal-footer" class="w-100">
        <b-button
          variant="primary"
          size="sm"
          class="float-right"
          :to="note.link"
        >
          Follow Up
        </b-button>
      </div>
    </b-modal>
    <div class="email-app mb-4" style="position: relative">
      <nav>
        <ul class="nav">
          <li class="nav-item">
            <a
              href="#"
              class="nav-link"
              @click="type = null"
              :class="{ active: type === null }"
              ><font-awesome-icon icon="inbox"></font-awesome-icon> Inbox
              <span class="badge badge-danger">{{
                $store.state.counters.unreadNotificationsCount
              }}</span></a
            >
          </li>
          <li
            class="nav-item"
            v-for="notification_type in notification_types"
            :key="notification_type.type"
          >
            <a
              href="#"
              class="nav-link"
              @click="type = notification_type.actual_type"
              :class="{ active: type === notification_type.actual_type }"
              ><font-awesome-icon
                :icon="notification_type.icon"
              ></font-awesome-icon>
              {{ notification_type.type }}
              <span
                class="badge badge-danger"
                v-if="notification_type.count > 0"
                >{{ notification_type.count }}</span
              >
            </a>
          </li>
        </ul>
      </nav>
      <main class="inbox">
        <div class="toolbar">
          <div class="btn-group mr-1">
            <button
              type="button"
              class="btn btn-light"
              @click="toggleSelectAll()"
              v-b-tooltip.hover
              :title="
                selectAll
                  ? $t('buttons.unselect_all')
                  : $t('buttons.select_all')
              "
            >
              <font-awesome-icon
                :icon="['far', 'check-square']"
                :style="{ color: selectAll ? 'var(--primary)' : '' }"
              ></font-awesome-icon>
            </button>
            <button
              type="button"
              class="btn btn-light"
              @click="markAs('unread')"
              v-if="readSelected"
              v-b-tooltip.hover
              :title="$t('buttons.mark_as_unread')"
            >
              <font-awesome-icon icon="envelope"></font-awesome-icon>
            </button>
            <button
              type="button"
              class="btn btn-light"
              @click="markAs('read')"
              v-if="unreadSelected"
              :title="$t('buttons.mark_as_read')"
            >
              <font-awesome-icon icon="envelope-open-text"></font-awesome-icon>
            </button>
            <button type="button" class="btn btn-light" @click="fetchData()">
              <font-awesome-icon
                icon="sync"
                :pulse="loading"
              ></font-awesome-icon>
            </button>
          </div>
          <div class="btn-group float-right">
            <h4 class="m-auto pr-2">
              <b-badge variant="info">
                {{ paginationString }}
              </b-badge>
            </h4>
            <span v-b-tooltip.hover :title="$t('buttons.first')">
              <button
                type="button"
                class="btn btn-light"
                @click="currentPage = 1"
                :disabled="currentPage === 1"
              >
                <font-awesome-icon icon="angle-double-left"></font-awesome-icon>
              </button>
            </span>
            <span v-b-tooltip.hover :title="$t('buttons.previous')">
              <button
                type="button"
                class="btn btn-light"
                @click="previousPage()"
                :disabled="currentPage === 1"
              >
                <font-awesome-icon icon="angle-left"></font-awesome-icon>
              </button>
            </span>
            <span v-b-tooltip.hover :title="$t('buttons.next')">
              <button
                type="button"
                class="btn btn-light"
                @click="nextPage()"
                :disabled="currentPage === pageCount"
              >
                <font-awesome-icon icon="angle-right"></font-awesome-icon>
              </button>
            </span>
            <span v-b-tooltip.hover :title="$t('buttons.last')">
              <button
                type="button"
                class="btn btn-light"
                @click="currentPage = pageCount"
                :disabled="currentPage === pageCount"
              >
                <font-awesome-icon
                  icon="angle-double-right"
                ></font-awesome-icon>
              </button>
            </span>
          </div>
        </div>
        <ul class="messages">
          <li
            class="message"
            v-for="(notification, index) in notifications"
            :class="{
              unread: !notification.read,
              selected: selected.includes(notification.id)
            }"
            :key="index"
          >
            <Loader
              :active="isLoading[index] !== undefined && isLoading[index]"
              :can-cancel="false"
              :is-full-page="fullPage"
              loader="bars"
              background-color="#09856E"
              color="#09856E"
              :opacity="0.3"
            ></Loader>
            <div class="actions">
              <span class="action">
                <b-form-checkbox
                  v-model="selected"
                  :value="notification.id"
                ></b-form-checkbox>
              </span>
              <span
                class="action"
                @click="toggleRead(notification.id, index)"
                v-b-tooltip.hover
                :title="
                  notification.read
                    ? $t('buttons.mark_as_unread')
                    : $t('buttons.mark_as_read')
                "
                ><font-awesome-icon
                  icon="eye"
                  :style="{ color: notification.read ? '' : 'var(--primary)' }"
                >
                </font-awesome-icon>
              </span>
            </div>
            <a @click="showNotification(notification, index)">
              <div class="header">
                <span class="title">{{ notification.data.subject }}</span
                ><span class="date"> {{ notification.created_at }}</span>
              </div>
              <div class="description">
                <div v-html="notification.data.body"></div>
                <div class="category">
                  <h4>
                    <b-badge variant="secondary">
                      <font-awesome-icon
                        :icon="
                          $t(
                            `labels.backend.notifications.icons.${notification.type}`
                          )
                        "
                      ></font-awesome-icon>
                      {{
                        $t(
                          `labels.backend.notifications.types.${notification.type}`
                        )
                      }}</b-badge
                    >
                  </h4>
                </div>
              </div>
            </a>
          </li>
        </ul>
      </main>
      <atom-spinner
        :animation-duration="1000"
        :size="400"
        :color="'#DBB11E'"
        :loading="loading"
        :full="false"
      ></atom-spinner>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import AtomSpinner from '../components/Plugins/AtomSpinner.vue'
import Loader from 'vue-loading-overlay'
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css'

export default {
  name: 'NotificationList',
  components: { AtomSpinner, Loader },
  data() {
    return {
      notification_types: [],
      notifications: [],
      currentPage: 1,
      pageCount: 1,
      selected: [],
      loading: false,
      paginationString: '',
      type: null,
      note: {},
      showNotificationModal: false,
      selectAll: false,
      isLoading: [],
      fullPage: false
    }
  },
  computed: {
    unreadSelected() {
      return (
        this.notifications.filter(n => this.selected.includes(n.id) && !n.read)
          .length > 0
      )
    },
    readSelected() {
      return (
        this.notifications.filter(n => this.selected.includes(n.id) && n.read)
          .length > 0
      )
    }
  },
  watch: {
    currentPage(newVal, oldVal) {
      this.fetchNotifications()
    },
    type(newVal, oldVal) {
      if (this.currentPage !== 1) {
        this.currentPage = 1
      } else {
        this.fetchNotifications()
      }
    },
    selected(newVal, oldVal) {
      if (newVal.length === this.notifications.length) {
        this.selectAll = true
      } else {
        this.selectAll = false
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    showNotification(notification, index) {
      this.note.subject = notification.data.subject
      this.note.body = notification.data.body
      this.note.link = notification.data.link
      this.note.trace = notification.data.trace ? notification.data.trace : null
      this.showNotificationModal = true
      if (!notification.read) this.toggleRead(notification.id, index, 'read')
    },
    fetchData() {
      this.fetchNotificationTypes()
      this.fetchNotifications()
    },
    async fetchNotificationTypes() {
      let { data } = await axios.get(
        this.$app.route('notifications.get_unread_notifications_count_by_type')
      )
      this.notification_types = data
    },
    async fetchNotifications() {
      this.loading = true
      let { data } = await axios.get(
        this.$app.route('notifications.get_notifications', { latest: 'non' }),
        {
          params: {
            page: this.currentPage,
            type: this.type
          }
        }
      )
      this.notifications = data.data
      this.currentPage = data.meta.pagination.current_page
      this.pageCount = data.meta.pagination.total_pages
      this.paginationString =
        (this.currentPage - 1) * 15 +
        1 +
        ' to ' +
        ((this.currentPage - 1) * 15 + 15 < data.meta.pagination.total
          ? (this.currentPage - 1) * 15 + 15
          : data.meta.pagination.total) +
        ' of ' +
        data.meta.pagination.total
      this.loading = false
    },
    nextPage() {
      if (this.currentPage < this.pageCount) this.currentPage++
    },
    previousPage() {
      if (this.currentPage > 1) this.currentPage--
    },
    toggleRead(id, index, mark_as = 'non') {
      this.$set(this.isLoading, index, true)
      let that = this
      this.$store
        .dispatch('MARK_NOTIFICATION_AS', {
          notification_id: id,
          status: mark_as
        })
        .then(() => {
          that.$set(
            this.notifications[index],
            'read',
            !this.notifications[index].read
          )

          that.fetchNotificationTypes()
        })
      setTimeout(() => {
        this.$set(this.isLoading, index, false)
      }, 2000)
    },
    toggleSelectAll() {
      this.selectAll = !this.selectAll
      if (this.selectAll) {
        this.selected = this.notifications.map(n => n.id)
      } else {
        this.selected = []
      }
    },
    async markAs(status) {
      this.loading = true

      let formData = this.$app.objectToFormData({
        notifications: this.selected
      })

      await axios.post(
        this.$app.route('notifications.bulk_toggle_notification_status', {
          mark_as: status
        }),
        formData
      )

      this.selected = []

      await this.$store.dispatch('LOAD_COUNTERS')
      await this.$store.dispatch('GET_NOTIFICATIONS')

      this.fetchData()
    }
  }
}
</script>

<style>
.email-app {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
  -ms-flex-direction: row;
  flex-direction: row;
  background: #fff;
  border: 1px solid #c8ced3;
}
.email-app nav {
  -webkit-box-flex: 0;
  -ms-flex: 0 0 300px;
  flex: 0 0 300px;
  padding: 1rem;
  border-right: 1px solid #c8ced3;
}
.email-app nav .btn-block {
  margin-bottom: 15px;
}
.email-app nav .nav {
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
}
.email-app nav .nav-item {
  position: relative;
}
.email-app nav .nav-link {
  color: #23282c;
  border-bottom: 1px solid #c8ced3;
}
.email-app nav .nav-link.active {
  background-color: var(--primary);
}
.email-app nav .nav-link i {
  width: 20px;
  margin: 0 10px 0 0;
  font-size: 14px;
  text-align: center;
}
.email-app nav .nav-link .badge {
  float: right;
  margin-top: 4px;
  margin-left: 10px;
}
.email-app main {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  min-width: 0;
  padding: 1rem;
}
.email-app .inbox .toolbar {
  padding-bottom: 1rem;
  border-bottom: 1px solid #c8ced3;
}
.email-app .inbox .messages {
  padding: 0;
  list-style: none;
}
.email-app .inbox .message {
  position: relative;
  padding: 1rem 1rem 1rem 2rem;
  cursor: pointer;
  border-bottom: 1px solid #c8ced3;
}
.email-app .inbox .message:hover {
  background: #f0f3f5;
}
.email-app .inbox .message.selected {
  background: rgba(219, 177, 30, 0.3);
}
.email-app .inbox .message .actions {
  position: absolute;
  left: 0;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
}
.email-app .inbox .message .actions .action {
  width: 2rem;
  margin-bottom: 0.5rem;
  color: #c8ced3;
  text-align: center;
}
.email-app .inbox .message a {
  color: #000;
  outline: none;
}
.email-app .inbox .message.unread a {
  color: var(--primary);
}
.email-app .inbox .message a:hover {
  text-decoration: none;
}
.email-app .inbox .message.unread .header,
.email-app .inbox .message.unread .title {
  font-weight: 700;
}
.email-app .inbox .message .header,
.email-app .inbox .message .description {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
  -ms-flex-direction: row;
  flex-direction: row;
  margin-bottom: 0.5rem;
}
.email-app .inbox .message .header .date,
.email-app .inbox .message .description .category {
  margin-left: auto;
}
.email-app .inbox .message .title {
  margin-bottom: 0.5rem;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.email-app .inbox .message .description {
  font-size: 12px;
}
@media (max-width: 767.98px) {
  .email-app {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
  }
  .email-app nav {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
  }
}
@media (max-width: 575.98px) {
  .email-app .message .header,
  .email-app .message .description {
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -ms-flex-flow: row wrap;
    flex-flow: row wrap;
  }
  .email-app .message .header .date,
  .email-app .message .description .category {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
  }
}
</style>
