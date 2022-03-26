<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.cash_requisitions.titles.index') }}
        </h3>
        <div
          class="card-options"
          v-if="$app.user.can('create cash requisitions')"
        >
          <b-btn
            v-b-toggle.filter
            variant="primary"
            class="mr-1"
            size="sm"
            v-b-tooltip.hover
            :title="$t('buttons.show_hide_filters')"
            v-if="$app.user.can('view cash requisitions')"
          >
            <font-awesome-icon icon="filter"></font-awesome-icon>
          </b-btn>
          <b-button
            to="/Finance/cash_requisitions/create"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.cash_requisitions.create')"
            size="sm"
            v-if="$app.user.can('create cash requisitions')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
          <!--<b-btn @click="id = null;showModal = !showModal">-->
          <!--Launch demo modal-->
          <!--</b-btn>-->
          <!--<model-modal componenet-name="cash-requisition-form" :model-id="id ? id : 0" :show-modal="showModal" @update:showModal="showModal = !showModal"></model-modal>-->
          <!--<model-modal componenet-name="CashRequisitionForm" :model-id="id ? id : 0" :show-modal.sync="showModal"></model-modal>-->
          <!--<b-modal id="modal1" title="Bootstrap-Vue" size="lg" lazy :hide-header="true" :hide-footer="true" class="create-modal" v-model="showModal">-->
          <!--<cash-requisition-form :from-router="false" @form-submitted-successfully="onFormSubmitted()" @form-submission-canceled="onFormSubmissionCanceled()" v-if="id === null"></cash-requisition-form>-->
          <!--<cash-requisition-form :from-router="false" @form-submitted-successfully="onFormSubmitted()" @form-submission-canceled="onFormSubmissionCanceled()" :id="id" v-else></cash-requisition-form>-->
          <!--</b-modal>-->
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="cash_requisitions.search"
        delete-route="cash_requisitions.destroy"
        export-route="cash_requisitions.export"
        :extra-search-criteria="extraSearchCriteria"
        :length-change="true"
        :paging="true"
        :infos="true"
        :export-data="$app.user.can('export cash requisitions') ? true : false"
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="filter" class="mt-2">
              <b-row>
                <b-col md="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.departments')"
                    label-for="action_by_dept"
                    :label-cols="4"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.departments"
                      :options="departments"
                      id="ass_depat"
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
                    label="Status"
                    label-for="status"
                    :label-cols="4"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.status_selected"
                      :options="status"
                      id="status"
                      name="status"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.statuses') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col md="4" lg="4">
                  <b-form-group
                    label="Requesters"
                    label-for="requester"
                    :label-cols="4"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.requesters"
                      :options="requesters"
                      id="requester"
                      name="requesters"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.requester') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col md="4" lg="4">
                  <b-form-group label="Type" label-for="Type" :label-cols="4">
                    <multiselect
                      v-model="extraSearchCriteria.selected_type"
                      :options="types"
                      id="type"
                      name="types"
                      @input="onContextChangedWithPage"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.requester') + ' --'
                      "
                    >
                      <template slot="singleLabel" slot-scope="props">
                        <span class="option__title">
                          {{ $t(`validation.attributes.${props.option}`) }}
                        </span>
                      </template>
                      <template slot="option" slot-scope="props">
                        <span class="option__title">
                          {{ $t(`validation.attributes.${props.option}`) }}
                        </span>
                      </template>
                    </multiselect>
                  </b-form-group>
                </b-col>
                <b-col xl="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.start_date')"
                    label-for="start_date"
                    :label-cols="4"
                  >
                    <b-input-group>
                      <p-datetimepicker
                        :config="config"
                        id="start_date"
                        name="start_date"
                        v-model="extraSearchCriteria.start_date"
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
                <b-col xl="4" lg="4">
                  <b-form-group
                    label="End Date"
                    label-for="end_date"
                    :label-cols="4"
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
          :busy.sync="isBusy"
        >
          <template slot="id" slot-scope="row">
            <router-link
              v-if="row.item.can_view && row.item.status !== 'rejected'"
              :to="`/Finance/cash_requisitions/${row.item.id}/edit`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="request_type" slot-scope="row">
            <b-badge variant="info">
              {{ $t(`validation.attributes.${row.value}`) }}
            </b-badge>
          </template>
          <template slot="purpose" slot-scope="row">
            <p>{{ row.value | part }}</p>
          </template>
          <template slot="status" slot-scope="row">
            <h4>
              <b-badge :variant="row.value === 'rejected' ? 'danger' : 'info'">
                {{ $t(`labels.backend.cash_requisitions.states.${row.value}`) }}
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
                  :to="`/Finance/cash_requisitions/${row.item.id}/edit`"
                  v-b-tooltip.hover
                  :title="$t('buttons.edit')"
                >
                  <i class="fe fe-edit"></i>
                </b-btn>
                <!--<b-btn v-if="row.item.can_view && row.item.status !== 'rejected' && row.item.status !== 'closed'" size="sm" variant="primary" @click="id = row.item.id;showModal = !showModal" v-b-tooltip.hover :title="$t('buttons.edit')" class="mr-1">-->
                <!--<i class="fe fe-edit"></i>-->
                <!--</b-btn>-->
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
                  :to="`/cash_requisitions/${row.item.id}/print`"
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
  name: 'CashRequisitionList',
  components: {
    Multiselect,
    WorkflowTransitions
  },
  filters: {
    part(value) {
      return value.length <= 50 ? value : value.substr(0, 50) + '...'
    }
  },
  mixins: [list, workflow],
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true
      },
      extraSearchCriteria: {
        status_selected: [],
        departments: [],
        requesters: [],
        user_name: [],
        selected_type: [],
        selected_users: [],
        start_date: null,
        end_date: null
      },
      isBusy: false,
      item_type: 'cash_requisition',
      departments: [],
      requesters: [],
      status: [],
      types: ['cash_advance', 'reimbursement', 'deposit'],
      // showModal: false,
      id: null
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
          key: 'purpose',
          label: this.$t('validation.attributes.purpose'),
          class: 'text-center',
          sortable: true,
          thStyle: { width: '30% !important' }
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
          key: 'request_type',
          label: this.$t('validation.attributes.request_type'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'amount',
          label: this.$t('validation.attributes.amount'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'approved_amount',
          label: this.$t('validation.attributes.approved_amount'),
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
    },
    printUrl() {
      return (
        '/tasks/print?extraSearch=' + JSON.stringify(this.extraSearchCriteria)
      )
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
    // ;({ data } = await axios.get(this.$app.route(`users.search`), {
    //   params: {
    //     page: 1,
    //     perPage: 100,
    //     column: 'name'
    //   }
    // }))
    // this.requesters = data.data
    //
    // this.requesters = data.data.map(item => {
    //   return { id: item.id, name: item.name }
    // })
    ;({ data } = await axios.get(this.$app.route(`get_list_of_users`)))
    this.requesters = data
    ;({ data } = await axios.get(
      this.$app.route(`cash_requisitions.get_statuses`),
      {
        params: {
          page: 1,
          perPage: 100,
          column: 'name'
        }
      }
    ))

    this.status = data.map(item => {
      return {
        id: item,
        name: this.$t(`labels.backend.cash_requisitions.states.${item}`)
      }
    })
  },
  mounted() {
    if (this.$route.query.requester)
      this.extraSearchCriteria.requesters.push(
        JSON.parse(this.$route.query.requester)
      )
  }
}
</script>
