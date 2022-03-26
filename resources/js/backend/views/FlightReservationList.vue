<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.flight_reservations.titles.index') }}
        </h3>
        <div
          class="card-options"
          v-if="$app.user.can('create flight reservations')"
        >
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
          <b-btn
            :to="{ name: 'flight_reservations_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.flight_reservations.create')"
            size="sm"
            v-if="$app.user.can('create flight reservations')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-btn>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="flight_reservations.search"
        delete-route="flight_reservations.destroy"
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
                <b-col xl="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.date')"
                    label-for="date"
                    :label-cols="4"
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
                <b-col xl="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.form_target')"
                    label-for="form_target"
                    :label-cols="4"
                  >
                    <b-form-input
                      id="form_target"
                      name="form_target"
                      @input="debounceInput"
                      :placeholder="$t('validation.attributes.form_target')"
                      v-model="extraSearchCriteria.form_target"
                    >
                    </b-form-input>
                  </b-form-group>
                </b-col>
                <b-col xl="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.passenger_name')"
                    label-for="passport_no"
                    :label-cols="4"
                  >
                    <b-form-input
                      id="passenger_name"
                      name="passenger_name"
                      @input="debounceInput"
                      :placeholder="$t('validation.attributes.passenger_name')"
                      v-model="extraSearchCriteria.passenger_name"
                    >
                    </b-form-input>
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
          :selectable="in_prf_issuing"
          select-mode="range"
          selected-variant="success"
          @row-selected="rowSelected"
        >
          <template slot="HEAD_checkbox" slot-scope="data">
            <b-form-checkbox
              v-model="allSelected"
              @change="toggleSelection"
            ></b-form-checkbox>
          </template>
          <template slot="destination" slot-scope="row">
            <h4 v-if="row.item.departure_from">
              <b-badge>{{ row.item.departure_from }}</b-badge>
              &#8646;
              <b-badge>{{ row.item.departure_to }}</b-badge>
            </h4>
            <!--            <h4 v-else-if="row.item.trip_type == 'HOTEL'">-->
            <!--              <b-badge>{{ row.item.trip_attributes[0].location }}</b-badge>-->
            <!--            </h4>-->
          </template>
          <template slot="checkbox" slot-scope="row">
            <b-form-checkbox
              :value="row.item.id"
              v-model="selected"
            ></b-form-checkbox>
          </template>
          <template slot="id" slot-scope="row">
            <router-link
              v-if="row.item.can_edit"
              :to="{
                name: 'flight_reservations_edit',
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
                  $t(`labels.backend.flight_reservations.states.${row.value}`)
                }}
              </b-badge>
            </h4>
          </template>
          <template slot="form_target" slot-scope="row">
            <span v-if="row.item.form_type === 'VESSEL'">{{
              row.value.name
            }}</span>
            <span v-else>{{ row.value }}</span>
          </template>
          <template slot="passengers_names" slot-scope="row">
            <h4 v-for="(passenger, index) in row.value" :key="index">
              <b-badge v-text="passenger"></b-badge>
            </h4>
          </template>
          <template slot="deduction" slot-scope="row">
            <span
              v-if="row.item.deduction_type === 'MYR'"
              v-text="row.value"
            ></span>
            <span
              v-else-if="row.item.deduction_value"
              v-text="`${row.item.deduction_value} (${row.value}%)`"
            ></span>
            <span v-else v-text="`${row.value}%`"></span>
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
                <b-btn
                  size="sm"
                  v-b-tooltip.hover
                  :title="$t('buttons.flight_reservations.generate_prf')"
                  @click="generatePrf(row.item.id)"
                  v-if="
                    row.item.status === 'prf_issuing' &&
                      !row.item.prf &&
                      $app.user.can('pass prf_issuing flight reservations')
                  "
                >
                  <font-awesome-icon icon="newspaper"></font-awesome-icon>
                </b-btn>
                <b-btn
                  size="sm"
                  v-b-tooltip.hover
                  :title="$t('buttons.flight_reservations.view_prf')"
                  :to="{
                    name: 'payment_requisitions_edit',
                    params: { id: row.item.prf }
                  }"
                  v-else-if="row.item.prf"
                >
                  <font-awesome-icon
                    icon="file-invoice-dollar"
                  ></font-awesome-icon>
                </b-btn>
                <b-btn
                  v-if="row.item.can_view"
                  size="sm"
                  variant="primary"
                  :to="{
                    name: 'flight_reservations_edit',
                    params: { id: row.item.id }
                  }"
                  v-b-tooltip.hover
                  :title="
                    row.item.status === 'rejected' ||
                    row.item.status === 'closed'
                      ? $t('buttons.view')
                      : $t('buttons.edit')
                  "
                >
                  <i
                    :class="{
                      'fe-eye':
                        row.item.status === 'rejected' ||
                        row.item.status === 'closed',
                      'fe-edit': !(
                        row.item.status === 'rejected' ||
                        row.item.status === 'closed'
                      ),
                      fe: true
                    }"
                  ></i>
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
              </WorkflowTransitions>
            </b-button-toolbar>
          </template>
        </b-table>
      </b-datatable>
      <b-row>
        <b-col>
          <b-button
            class="mx-3 sm"
            @click="bulkGeneratePrf"
            variant="primary"
            :disabled="working"
            v-if="selected_for_generate_prf.length > 0 && in_prf_issuing"
            ><font-awesome-icon icon="newspaper"></font-awesome-icon>
            {{ $t('buttons.flight_reservations.generate_prf') }}</b-button
          >
          <b-button
            class="mx-3 sm"
            @click="bulkGenerateReport"
            variant="secondary"
            :disabled="working"
            v-if="selected.length > 0"
            ><font-awesome-icon icon="newspaper"></font-awesome-icon>
            {{ $t('buttons.flight_reservations.generate_report') }}</b-button
          >
        </b-col>
      </b-row>
    </b-card>
  </div>
</template>

<script>
import list from '../mixins/list'
import WorkflowTransitions from './components/WorkflowTransitions'
import workflow from '../mixins/workflow'
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import _ from 'lodash'

export default {
  name: 'FlightReservationList',
  components: {
    Multiselect,
    WorkflowTransitions
  },
  mixins: [list, workflow],
  data() {
    return {
      isBusy: false,
      item_type: 'flight_reservation',
      selected: [],
      allSelected: false,
      working: false,
      selected_for_generate_prf: [],
      config: {
        wrap: true,
        allowInput: true,
        mode: 'range'
      },
      extraSearchCriteria: {
        status_selected: [],
        departments: [],
        requesters: [],
        user_name: [],
        selected_type: [],
        selected_users: [],
        passenger_name: null,
        date: null,
        form_target: null
      },
      departments: [],
      requesters: [],
      status: []
    }
  },
  computed: {
    fields() {
      let fields = [
        {
          key: 'checkbox',
          hStyle: { width: '2% !important' },
          class: 'pr-0 pl-2 mx-0'
        },
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
          key: 'destination',
          label: this.$t('validation.attributes.destination'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'form_target',
          label: this.$t('validation.attributes.form_target'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'passengers_names',
          label: this.$t('validation.attributes.passengers'),
          class: 'text-center',
          sortable: false
        },
        {
          key: 'date',
          label: this.$t('validation.attributes.date'),
          class: 'text-center',
          sortable: false
        },
        {
          key: 'status',
          label: this.$t('validation.attributes.status'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'budget',
          label: this.$t('validation.attributes.budget'),
          class: 'text-center',
          sortable: false
        },
        {
          key: 'price',
          label: this.$t('validation.attributes.price'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'deduction',
          label: this.$t('validation.attributes.deduction'),
          class: 'text-center'
        },
        {
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap text-center'
        }
      ]

      return fields
    },
    in_prf_issuing() {
      return (
        this.extraSearchCriteria.status_selected.length === 1 &&
        this.extraSearchCriteria.status_selected[0].id === 'prf_issuing'
        // extraSearchCriteria.status_selected[0].id === 'closed'
      )
    }
  },
  watch: {
    selected: function(newVal, oldVal) {
      if (newVal.length === this.$refs.datatable.localItems.length) {
        this.allSelected = true
      } else {
        this.allSelected = false
      }
      // if (
      //   newVal.length ===
      //   this.$refs.datatable.localItems.filter(
      //     item => item.status === 'prf_issuing' && !item.prf
      //   ).length
      // ) {
      //   this.allSelected = true
      // } else {
      //   this.allSelected = false
      // }
    }
    // allSelected: function(newVal, oldVal) {
    //   if (newVal)
    //     this.selected = this.$refs.datatable.localItems
    //       // .filter(item => item.status === 'prf_issuing' && !item.prf)
    //       .map(item => item.id)
    // }
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
    ;({ data } = await axios.get(this.$app.route(`get_list_of_users`)))
    this.requesters = data
    //
    // let { data } = await axios.get(this.$app.route(`get_list_of_users`))
    // this.users = data

    // this.requesters = data.data.map(item => {
    //   return { id: item.id, name: item.name }
    // })
    ;({ data } = await axios.get(
      this.$app.route(`flight_reservations.get_statuses`),
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
        name: this.$t(`labels.backend.flight_reservations.states.${item}`)
      }
    })
  },
  methods: {
    debounceInput: _.debounce(function() {
      // this.onContextChanged()
      this.onContextChangedWithPage()
    }, 1000),
    toggleSelection() {
      this.allSelected = !this.allSelected
      this.selected = this.allSelected
        ? this.$refs.datatable.localItems.map(item => item.id)
        : []
    },
    async generatePrf(id) {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#dd4b39',
        confirmButtonText: this.$t('buttons.confirm')
      })

      if (result.value) {
        try {
          this.working = true
          let { data } = await axios.post(
            this.$app.route('flight_reservations.generate_prf', {
              flight_reservation: id
            })
          )

          this.selected = []
          this.onContextChangedWithPage()
          this.$app.noty[data.status](data.message)
          this.working = false
        } catch (e) {
          this.$app.error(e)
          this.working = false
        }
      }
    },
    async bulkGeneratePrf() {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#dd4b39',
        confirmButtonText: this.$t('buttons.confirm')
      })
      if (result.value) {
        try {
          this.working = true

          let formData = this.$app.objectToFormData({
            ids: this.selected_for_generate_prf
          })

          let { data } = await axios.post(
            this.$app.route('flight_reservations.bulk_generate_prf'),
            formData
          )

          this.selected = []
          this.selected_for_generate_prf = []
          this.onContextChangedWithPage()
          this.$app.noty[data.status](data.message)
          this.working = false
        } catch (e) {
          this.$app.error(e)
          this.working = false
        }
      }
    },
    async bulkGenerateReport() {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#dd4b39',
        confirmButtonText: this.$t('buttons.confirm')
      })

      if (result.value) {
        try {
          this.working = true

          let formData = this.$app.objectToFormData({
            ids: this.selected
          })

          let url = null
          let { data } = await axios({
            url: this.$app.route('flight_reservations.bulk_generate_report'),
            responseType: 'blob',
            method: 'POST',
            data: formData
          })

          url = window.URL.createObjectURL(
            new Blob([data], { type: 'application/pdf' })
          )
          const link = document.createElement('a')
          link.href = url
          // link.target = '_blank'
          link.setAttribute('download', 'report.pdf')
          document.body.appendChild(link)

          link.click()

          this.selected = []
          this.selected_for_generate_prf = []
          this.onContextChangedWithPage()
          this.$app.noty[data.status](data.message)
          this.working = false
        } catch (e) {
          this.$app.error(e)
          this.working = false
        }
      }
    },
    // toggleAll() {
    //   this.allSelected = !this.allSelected
    //   this.selected = this.allSelected
    //     ? this.$refs.datatable.localItems
    //         .filter(item => item.status === 'prf_issuing' && !item.prf)
    //         .map(item => item.id)
    //     : []
    // },
    rowSelected(items) {
      this.selected_for_generate_prf = items.map(item => {
        return item.id
      })
    }
  }
}
</script>
