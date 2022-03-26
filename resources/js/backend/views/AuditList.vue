<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.Audits.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create departments')">
          <b-btn
            v-b-toggle.filter
            variant="primary"
            class="mr-1"
            size="sm"
            v-b-tooltip.hover
            :title="$t('buttons.show_hide_filters')"
          >
            <font-awesome-icon icon="filter"></font-awesome-icon>
          </b-btn>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="Audits.search"
        delete-route="Audits.destroy"
        :extra-search-criteria="extraSearchCriteria"
        :length-change="true"
        :paging="true"
        :infos="false"
        :export-data="false"
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="filter" class="mt-2">
              <b-row>
                <b-col xl="3" lg="6">
                  <b-form-group
                    label="By Event"
                    label-for="event"
                    :label-cols="3"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.events"
                      :options="events"
                      id="event"
                      name="event"
                      @input="onContextChangedWithPage"
                      :searchable="true"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      placeholder="Event"
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col xl="3" lg="6">
                  <b-form-group
                    label="by User"
                    label-for="user_name"
                    :label-cols="3"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.selected_users"
                      id="ajax"
                      label="name"
                      track-by="id"
                      placeholder="Type to search"
                      open-direction="bottom"
                      :options="users"
                      :multiple="true"
                      :searchable="true"
                      :loading="isLoading"
                      :internal-search="false"
                      :clear-on-select="false"
                      :close-on-select="false"
                      :options-limit="300"
                      :limit="3"
                      :max-height="600"
                      :show-no-results="true"
                      :hide-selected="true"
                      @search-change="asyncFind"
                      @input="onContextChangedWithPage"
                    >
                    </multiselect>
                  </b-form-group>
                </b-col>
                <b-col xl="3" lg="6">
                  <b-form-group
                    :label="$t('validation.attributes.start_date')"
                    label-for="start_date"
                    :label-cols="3"
                  >
                    <b-input-group>
                      <p-datetimepicker
                        :config="config"
                        id="start_date"
                        name="start_date"
                        v-model="extraSearchCriteria.start_date"
                      ></p-datetimepicker>
                      <b-input-group-append>
                        <b-input-group-text data-toggle>
                          <i class="fe fe-calendar"></i>
                        </b-input-group-text>
                        <b-input-group-text data-clear>
                          <i class="fe fe-x-circle"></i>
                        </b-input-group-text>
                      </b-input-group-append>
                    </b-input-group>
                  </b-form-group>
                </b-col>
                <b-col xl="3" lg="6">
                  <b-form-group
                    label="End Date"
                    label-for="end_date"
                    :label-cols="3"
                  >
                    <b-input-group>
                      <p-datetimepicker
                        :config="config"
                        id="end_date"
                        name="end_date"
                        v-model="extraSearchCriteria.end_date"
                        @input="onContextChangedWithPage"
                      ></p-datetimepicker>
                      <b-input-group-append>
                        <b-input-group-text data-toggle>
                          <i class="fe fe-calendar"></i>
                        </b-input-group-text>
                        <b-input-group-text data-clear>
                          <i class="fe fe-x-circle"></i>
                        </b-input-group-text>
                      </b-input-group-append>
                    </b-input-group>
                  </b-form-group>
                </b-col>
              </b-row>
            </b-collapse>
          </b-col>
        </template>
        <b-table
          ref="datatable"
          responsive
          striped
          bordered
          show-empty
          no-local-sorting
          :empty-text="$t('labels.datatables.no_results')"
          :empty-filtered-text="$t('labels.datatables.no_matched_results')"
          :fields="fields"
          :items="dataLoadProvider"
          sort-by="id"
          :sort-desc="true"
          width="2px"
          :busy.sync="isBusy"
          v-if="fields.length > 0"
        >
          <template slot="type" slot-scope="row">
            <span v-if="row.item.auditable_type === 'SeafarerContract'">
              <router-link
                :to="
                  `/seafarer_contracts/contracts/${row.item.auditable_id}/edit`
                "
                v-text="row.item.auditable_type"
              ></router-link>
            </span>
            <span v-else>
              <router-link
                :to="
                  `/${row.item.auditable_type}s/${row.item.auditable_id}/edit`
                "
                v-text="row.item.auditable_type"
              ></router-link>
            </span>
          </template>

          <template slot="modifications" slot-scope="row">
            <div
              v-for="(value, key) in getIntersect(
                row.item.old_values,
                row.item.new_values
              )"
              :key="key"
            >
              <div
                v-if="
                  row.item.old_values[value] ||
                    row.item.new_values[value] !== null
                "
              >
                {{ value }}:
                <b-badge variant="danger">
                  {{ row.item.old_values[value] }}
                </b-badge>
                =>
                <b-badge v-if="row.item.event === 'created'" variant="success">
                  {{ row.item.new_values[value] }}
                </b-badge>

                <b-badge
                  v-else-if="row.item.event === 'updated'"
                  variant="primary"
                >
                  {{ row.item.new_values[value] }}
                </b-badge>
              </div>
            </div>
          </template>

          <template slot="event" slot-scope="row">
            <div v-if="row.item.event === 'created'">
              <h4>
                <b-badge variant="success">
                  {{ row.item.event.toUpperCase() }}
                </b-badge>
              </h4>
            </div>
            <div v-else-if="row.item.event === 'deleted'">
              <h4>
                <b-badge variant="danger">
                  {{ row.item.event.toUpperCase() }}
                </b-badge>
              </h4>
            </div>
            <h4 v-else>
              <b-badge variant="primary">
                {{ row.value.toUpperCase() }}
              </b-badge>
            </h4>
          </template>
          <template slot="created_at" slot-scope="row">
            <h4>{{ row.value.toUpperCase() }}</h4>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import _ from 'lodash'
import list from '../mixins/list'
// import chart from './chart'

export default {
  name: 'AuditList',
  components: {
    Multiselect
  },
  mixins: [list],
  data() {
    return {
      extraSearchCriteria: {
        events: [],
        user_name: [],
        selected_users: [],
        start_date: null,
        end_date: null
      },
      config: {
        wrap: true,
        allowInput: true
      },
      isBusy: false,
      events: ['created', 'updated', 'deleted'],
      user_name: [],
      users: [],
      isLoading: false,
      chartdata: []
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
          key: 'user_name',
          label: 'User Name',
          class: 'text-center',
          sortable: false
        },
        {
          key: 'event',
          label: 'Event',
          class: 'text-center',
          sortable: true
        },
        {
          key: 'type',
          label: this.$t('validation.attributes.auditable_type'),
          class: 'text-center'
        },
        {
          key: 'modifications',
          label: 'Modifications',
          class: 'text-center',
          thStyle: { width: '40% !important' }
        },
        {
          key: 'created_at',
          label: this.$t('validation.attributes.date'),
          class: 'text-center',
          sortable: true
        }
      ]

      return fields
    }
  },
  methods: {
    async asyncFind(query) {
      this.isLoading = true
      let { data } = await axios.get(this.$app.route(`users.search`), {
        params: {
          perPage: 100,
          search: query
        }
      })
      this.users = data.data

      this.users = data.data.map(item => {
        return { id: item.id, name: item.name }
      })
      this.isLoading = false
    },
    getIntersect(first, second) {
      return _.union(_.keys(first), _.keys(second))
    }
  }
}
</script>
