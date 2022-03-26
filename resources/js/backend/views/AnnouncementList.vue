<template>
  <div class="animated fadeIn">
    <b-modal v-model="modalShow" size="xl">
      <template slot="modal-header">
        <!-- Emulate built in modal header close button action -->
        <div class="float-left">
          <font-awesome-icon icon="bullhorn"></font-awesome-icon>
          <span class="ml-1 font-xl" v-if="announcement">{{
            announcement.subject
          }}</span>
        </div>
      </template>
      <div
        v-if="announcement"
        v-html="announcement.content"
        class="ck-content"
      ></div>
      <div slot="modal-footer" class="w-100">
        <div class="float-left" v-if="announcement && announcement.attachments">
          <ul>
            <li
              v-for="(attachment, index) in announcement.attachments"
              :key="index"
            >
              <a :href="attachment.url" target="_blank">{{ attachment.url }}</a>
            </li>
          </ul>
        </div>
        <b-button
          variant="danger"
          size="sm"
          class="float-right"
          @click="modalShow = false"
        >
          {{ $t('buttons.cancel') }}
        </b-button>
      </div></b-modal
    >
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.announcements.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create announcements')">
          <b-button
            :to="{ name: 'announcements_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.announcements.create')"
            size="sm"
            v-if="$app.user.can('create announcements')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="announcements.search"
        delete-route="announcements.destroy"
        :length-change="true"
        :paging="true"
        :infos="true"
        :export-data="false"
      >
        <b-table
          ref="datatable"
          striped
          bordered
          show-empty
          stacked="md"
          no-local-sorting
          :empty-text="$t('labels.datatables.no_results')"
          :empty-filtered-text="$t('labels.datatables.no_matched_results')"
          :fields="fields"
          :items="dataLoadProvider"
          sort-by="id"
          :sort-desc="true"
          :busy.sync="isBusy"
        >
          <template slot="id" slot-scope="row">
            <router-link
              v-if="row.item.can_edit"
              :to="{ name: 'announcements_edit', params: { id: row.item.id } }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="status" slot-scope="row">
            <h4>
              <b-badge
                :variant="row.value === 'PUBLISHED' ? 'success' : 'warning'"
              >
                {{ row.value }}
              </b-badge>
            </h4>
          </template>
          <template slot="subject" slot-scope="row">
            <p v-for="(attachment, index) in row.item.attachments" :key="index">
              <a :href="attachment.url" target="_blank">{{
                `${row.value} (${index + 1})`
              }}</a>
            </p>
            <span v-if="row.item.attachments.length === 0">
              <a @click="show(row.item)" tabindex="-1">{{ row.value }}</a>
            </span>
          </template>
          <template slot="destination" slot-scope="row">
            <h4>
              <b-badge variant="warning" :id="`popover_${row.item.id}`">{{
                row.value.destination_type
              }}</b-badge>
            </h4>
            <b-popover
              :target="`popover_${row.item.id}`"
              triggers="hover focus"
            >
              <ul v-for="(to, index) in row.value.to" :key="index">
                <li>{{ to.name ? to.name : to }}</li>
              </ul>
            </b-popover>
          </template>
          <template slot="actions" slot-scope="row">
            <b-button-toolbar class="action-toolbar">
              <b-button
                v-if="row.item.can_edit"
                size="sm"
                variant="primary"
                :to="{
                  name: 'announcements_edit',
                  params: { id: row.item.id }
                }"
                v-b-tooltip.hover
                :title="$t('buttons.edit')"
                class="mr-1"
              >
                <i class="fe fe-edit"></i>
              </b-button>
              <b-button
                size="sm"
                variant="danger"
                v-b-tooltip.hover
                :title="$t('buttons.delete')"
                @click.stop="onDelete(row.item.id)"
                class="mr-1"
              >
                <i class="fe fe-trash"></i>
              </b-button>
              <b-button size="sm" @click="show(row.item)">
                <i class="fe fe-eye"></i>
              </b-button>
            </b-button-toolbar>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import list from '../mixins/list'

export default {
  name: 'AnnouncementList',
  mixins: [list],
  data() {
    return {
      isBusy: false,
      item_type: 'announcement',
      modalShow: false,
      announcement: null
    }
  },
  computed: {
    fields() {
      let fields = [
        {
          key: 'id',
          label: this.$t('validation.attributes.id'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'subject',
          label: this.$t('validation.attributes.subjectList'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'status',
          label: this.$t('validation.attributes.status'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'published_at',
          label: this.$t('validation.attributes.published_at'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'destination',
          label: this.$t('validation.attributes.destination_list'),
          class: 'text-center',
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit announcements') ||
        this.$app.user.can('delete announcements')
      ) {
        fields.push({
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap',
          class: 'text-center'
        })
      }
      return fields
    }
  },
  methods: {
    show(announcement) {
      this.announcement = announcement
      this.modalShow = !this.modalShow
    }
  }
}
</script>

<style lang="scss">
.ck-content {
  & .image-style-side {
    height: 200px;
  }
  & .image-style-align-left,
  & .image-style-align-center,
  & .image-style-align-right {
  }

  & .image-style-side {
    float: left;
    margin-left: var(--ck-image-style-spacing);
  }

  & .image-style-align-left {
    float: left;
    margin-right: var(--ck-image-style-spacing);
  }

  & .image-style-align-center {
    margin-left: auto;
    margin-right: auto;
  }

  & .image-style-align-right {
    float: right;
    margin-left: var(--ck-image-style-spacing);
  }

  & .image {
    clear: both;
    text-align: center;
    margin: 1em 0;
    position: relative;
    overflow: hidden;
  }
  & .image > img {
    display: block;
    margin: 0 auto;
    max-width: 100%;
  }

  .table td {
    width: auto;
    max-width: 150px;
    min-width: 90px !important;
    height: 40px;
  }
}
</style>

<style scoped>
.popover {
  word-break: unset !important;
}
.popover ul {
  padding-left: 5px;
}
</style>
