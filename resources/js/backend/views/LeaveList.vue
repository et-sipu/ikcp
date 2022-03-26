<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.leaves.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create leaves')">
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
          <b-button
            :to="{ name: 'leaves_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.leaves.create')"
            size="sm"
            v-if="$app.user.can('create leaves')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="leaves.search"
        delete-route="leaves.destroy"
        :length-change="true"
        :paging="true"
        :infos="true"
        :export-data="false"
        :extra-search-criteria="extraSearchCriteria"
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="filter" class="mt-2">
              <b-row>
                <b-col md="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.departments')"
                    label-for="departments"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.departments"
                      :options="departments"
                      id="departments"
                      name="departments"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.departments') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col md="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.branches')"
                    label-for="branches"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.branches"
                      :options="branches"
                      id="branches"
                      name="branches"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.branches') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col md="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.employees')"
                    label-for="employee"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.employees"
                      :options="employees"
                      id="employees"
                      name="employees"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.employees') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col xl="6" lg="6">
                  <b-form-group
                    :label="$t('validation.attributes.date')"
                    label-for="start_date"
                    :label-cols="2"
                  >
                    <b-input-group>
                      <p-datetimepicker
                        :config="config"
                        id="date"
                        name="date"
                        v-model="extraSearchCriteria.date"
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
              v-if="row.item.can_view"
              :to="{ name: 'leaves_edit', params: { id: row.item.id } }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="leave_type" slot-scope="row">
            <h4>
              <b-badge variant="primary">
                {{ $t(`labels.backend.leaves.leave_types.${row.value.id}`) }}
              </b-badge>
            </h4>
          </template>
          <template slot="status" slot-scope="row">
            <h4>
              <b-badge :variant="row.value === 'rejected' ? 'danger' : 'info'">
                {{ $t(`labels.backend.leaves.states.${row.value}`) }}
              </b-badge>
            </h4>
          </template>
          <template slot="period" slot-scope="row">
            <h4 v-if="row.value">
              <b-badge>
                {{ $t(`labels.backend.leaves.periods.${row.value}`) }}
              </b-badge>
            </h4>
          </template>
          <template slot="days_count" slot-scope="row">
            <h4>
              <b-badge variant="info">
                {{ row.value + (row.value > 1 ? ' Days' : ' Day') }}
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
              >
                <b-button
                  v-if="row.item.can_view"
                  size="sm"
                  variant="primary"
                  :to="{ name: 'leaves_edit', params: { id: row.item.id } }"
                  v-b-tooltip.hover
                  :title="
                    row.item.status === 'rejected' ||
                    row.item.status === 'approved'
                      ? $t('buttons.view')
                      : $t('buttons.edit')
                  "
                >
                  <i
                    :class="{
                      'fe-eye':
                        row.item.status === 'rejected' ||
                        row.item.status === 'approved',
                      'fe-edit': !(
                        row.item.status === 'rejected' ||
                        row.item.status === 'approved'
                      ),
                      fe: true
                    }"
                  ></i>
                </b-button>
                <b-button
                  v-if="
                    row.item.can_delete &&
                      row.item.status !== 'rejected' &&
                      row.item.status !== 'approved'
                  "
                  size="sm"
                  variant="danger"
                  v-b-tooltip.hover
                  :title="$t('buttons.delete')"
                  @click.stop="onDelete(row.item.id)"
                >
                  <i class="fe fe-trash"></i>
                </b-button>
              </WorkflowTransitions>
            </b-button-toolbar>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import list from '../mixins/list'
import WorkflowTransitions from './components/WorkflowTransitions'
import workflow from '../mixins/workflow'
import Multiselect from 'vue-multiselect'
import { get_branches, get_departments, get_employees } from '../lib/options'

export default {
  name: 'LeaveList',
  components: {
    WorkflowTransitions,
    Multiselect
  },
  mixins: [list, workflow],
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true,
        mode: 'range'
      },
      isBusy: false,
      item_type: 'leave',
      extraSearchCriteria: {
        departments: [],
        branches: [],
        employees: [],
        date: null
      },
      departments: [],
      branches: [],
      employees: []
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
          key: 'requester_name',
          label: this.$t('validation.attributes.requester'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'department_name',
          label: this.$t('validation.attributes.department'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'leave_type',
          label: this.$t('validation.attributes.leave_type'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'start_date',
          label: this.$t('validation.attributes.start_date'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'end_date',
          label: this.$t('validation.attributes.end_date'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'period',
          label: this.$t('validation.attributes.period'),
          class: 'text-center'
        },
        {
          key: 'days_count',
          label: this.$t('validation.attributes.days_count'),
          class: 'text-center'
        },
        {
          key: 'status',
          label: this.$t('validation.attributes.status'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap text-center'
        }
      ]

      return fields
    }
  },
  async created() {
    this.branches = await get_branches(this.$app)
    this.departments = await get_departments(this.$app)
    this.employees = await get_employees(this.$app)
  },
  methods: {}
}
</script>
