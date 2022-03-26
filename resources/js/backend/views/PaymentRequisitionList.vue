<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.payment_requisitions.titles.index') }}
        </h3>
        <div
          class="card-options"
          v-if="$app.user.can('create payment requisitions')"
        >
          <b-btn
            v-b-toggle.filter
            variant="primary"
            class="mr-1"
            size="sm"
            v-b-tooltip.hover
            :title="$t('buttons.show_hide_filters')"
            v-if="$app.user.can('view payment requisitions')"
          >
            <font-awesome-icon icon="filter"></font-awesome-icon>
          </b-btn>
          <b-button
            :to="{ name: 'payment_requisitions_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.payment_requisitions.create')"
            size="sm"
            v-if="$app.user.can('create payment requisitions')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="payment_requisitions.search"
        export-route="payment_requisitions.export"
        delete-route="payment_requisitions.destroy"
        :extra-search-criteria="extraSearchCriteria"
        :length-change="true"
        :paging="true"
        :infos="true"
        :export-data="
          $app.user.can('export payment requisitions') ? true : false
        "
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="filter" class="mt-2">
              <b-row>
                <b-col xl="3" lg="6">
                  <b-form-group
                    :label="$t('validation.attributes.requester')"
                    label-for="requester"
                    :label-cols="3"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.users"
                      id="users"
                      name="users"
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
                    :label="$t('validation.attributes.department')"
                    label-for="department"
                    :label-cols="3"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.departments"
                      :options="departments"
                      id="departments"
                      name="departments"
                      track-by="id"
                      label="name"
                      :multiple="true"
                      :searchable="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.departments') + ' --'
                      "
                      @input="onContextChangedWithPage"
                    ></multiselect>
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
                        @input="onContextChangedWithPage"
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
                        @input="onContextChangedWithPage"
                        v-model="extraSearchCriteria.end_date"
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
                    :label="$t('validation.attributes.statuses')"
                    label-for="statuses"
                    :label-cols="3"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.statuses"
                      :options="statuses"
                      id="statuses"
                      name="statuses"
                      track-by="id"
                      label="name"
                      :multiple="true"
                      :searchable="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.statuses') + ' --'
                      "
                      @input="onContextChangedWithPage"
                    ></multiselect>
                  </b-form-group>
                </b-col>
              </b-row>
            </b-collapse>
          </b-col>
        </template>
        <b-table
          ref="datatable"
          striped
          bordered
          show-empty
          responsive
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
              v-if="row.item.can_view && row.item.status !== 'rejected'"
              :to="{
                name: 'payment_requisitions_edit',
                params: { id: row.item.id }
              }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="status" slot-scope="row">
            <h4>
              <b-badge :variant="row.value === 'rejected' ? 'danger' : 'info'">
                {{
                  $t(`labels.backend.payment_requisitions.states.${row.value}`)
                }}
              </b-badge>
            </h4>
          </template>
          <template slot="actions" slot-scope="row">
            <b-button-toolbar class="action-toolbar">
              <WorkflowTransitions
                :available-transitions="row.item.available_transitions"
                :can-pass="row.item.can_pass"
                :can-pass-urgently="row.item.can_pass_urgently"
                :change-status="changeStatus"
                :get-transition-info="getTransitionInfo"
                :id="row.item.id"
              ></WorkflowTransitions>
              <b-button-group>
                <b-btn
                  v-if="
                    row.item.can_view &&
                      row.item.status !== 'rejected' &&
                      row.item.status !== 'closed'
                  "
                  size="sm"
                  variant="primary"
                  :to="`/Finance/payment_requisitions/${row.item.id}/edit`"
                  v-b-tooltip.hover
                  :title="$t('buttons.edit')"
                >
                  <i class="fe fe-edit"></i>
                </b-btn>
                <b-btn
                  v-if="
                    row.item.can_delete &&
                      row.item.status !== 'rejected' &&
                      row.item.status !== 'closed'
                  "
                  size="sm"
                  variant="danger"
                  v-b-tooltip.hover
                  :title="$t('buttons.delete')"
                  @click.stop="onDelete(row.item.id)"
                >
                  <i class="fe fe-trash"></i>
                </b-btn>
                <b-btn
                  variant="secondary"
                  v-b-tooltip.hover
                  :title="$t('buttons.print')"
                  size="sm"
                  v-if="row.item.can_print"
                  :to="`/payment_requisitions/${row.item.id}/print`"
                  target="_blank"
                >
                  <font-awesome-icon icon="print"></font-awesome-icon>
                </b-btn>
              </b-button-group>
            </b-button-toolbar>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import list from '../mixins/list'
import workflow from '../mixins/workflow'
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import WorkflowTransitions from './components/WorkflowTransitions'

export default {
  name: 'PaymentRequisitionList',
  components: {
    Multiselect,
    WorkflowTransitions
  },
  mixins: [list, workflow],
  data() {
    return {
      isBusy: false,
      item_type: 'payment_requisition',
      departments: [],
      users: [],
      statuses: [],
      extraSearchCriteria: {
        departments: [],
        users: [],
        statuses: [],
        start_date: null,
        end_date: null
      },
      config: {
        wrap: true,
        allowInput: true
      },
      isLoading: false
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
          key: 'status',
          label: this.$t('validation.attributes.status'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'requester_name',
          label: this.$t('validation.attributes.requester'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'title',
          label: this.$t('validation.attributes.title'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'project',
          label: this.$t('validation.attributes.project'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'supplier',
          label: this.$t('validation.attributes.supplier'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'payment',
          label: this.$t('validation.attributes.proposed_payment'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'approved_payment',
          label: this.$t('validation.attributes.approved_payment'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'created_at',
          label: this.$t('validation.attributes.date'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap'
        }
      ]

      return fields
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`departments.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    })
    this.departments = data.data

    this.departments = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
    ;({ data } = await axios.get(
      this.$app.route(`payment_requisitions.get_statuses`)
    ))

    this.statuses = data.map(item => {
      return {
        id: item,
        name: this.$t(`labels.backend.payment_requisitions.states.${item}`)
      }
    })
  },
  mounted() {
    if (this.$route.query.requester)
      this.extraSearchCriteria.users.push(
        JSON.parse(this.$route.query.requester)
      )
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
    }
  }
}
</script>
