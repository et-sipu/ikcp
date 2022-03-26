<template>
  <div class="animated fadeIn">
    <b-row class="justify-content-center">
      <b-col xl="8">
        <form @submit.prevent="onSubmit">
          <b-card>
            <template slot="header">
              <h4>
                {{
                  isNew
                    ? $t('labels.backend.leaves.titles.create')
                    : $t('labels.backend.leaves.titles.edit')
                }}
              </h4>
              <div class="card-options" v-if="!isNew">
                <div v-if="model.status !== null">
                  <h3 class="mb-0">
                    <b-badge
                      :variant="
                        model.status === 'rejected' ? 'danger' : 'primary'
                      "
                    >
                      {{ $t(`labels.backend.leaves.states.${model.status}`) }}
                    </b-badge>
                  </h3>
                </div>
              </div>
            </template>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <template v-if="!isNew">
                  <b-form-group
                    :label="$t('validation.attributes.requester')"
                    :label-cols="3"
                  >
                    <h3 class="my-auto">
                      <b-badge variant="primary">
                        {{ model.requester_name }}
                      </b-badge>
                    </h3>
                  </b-form-group>
                  <b-form-group
                    :label="$t('validation.attributes.department')"
                    :label-cols="3"
                  >
                    <h3>
                      <b-badge variant="primary">
                        {{ model.department_name }}
                      </b-badge>
                    </h3>
                  </b-form-group>
                </template>
                <b-form-group
                  :label="$t('validation.attributes.leave_type')"
                  label-for="leave_type"
                  :label-cols="3"
                  :invalid-feedback="feedback('leave_type')"
                  :state="state('leave_type')"
                >
                  <multiselect
                    v-model="model.leave_type"
                    :options="leave_types"
                    id="leave_type"
                    label="name"
                    track-by="id"
                    :placeholder="$t('validation.attributes.leave_type')"
                    :multiple="false"
                    :close-on-select="true"
                    :show-labels="false"
                    :disabled="disabled"
                  >
                  </multiselect>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.start_date')"
                  label-for="start_date"
                  :label-cols="3"
                  :invalid-feedback="feedback('start_date')"
                  :state="state('start_date')"
                >
                  <b-input-group v-if="!disabled">
                    <p-datetimepicker
                      :config="start_date_config_full"
                      id="start_date"
                      name="start_date"
                      v-model="model.start_date"
                      @input="onStartChange"
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
                  <h3 v-else>
                    <b-badge variant="primary" class="mr-1">
                      {{ model.start_date ? model.start_date : ' -- ' }}
                    </b-badge>
                  </h3>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.end_date')"
                  label-for="end_date"
                  :label-cols="3"
                  :invalid-feedback="feedback('end_date')"
                  :state="state('end_date')"
                >
                  <b-input-group v-if="!disabled">
                    <p-datetimepicker
                      :config="end_date_config_full"
                      id="end_date"
                      name="end_date"
                      v-model="model.end_date"
                      @input="onEndChange"
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
                  <h3 v-else>
                    <b-badge variant="primary" class="mr-1">
                      {{ model.end_date ? model.end_date : ' -- ' }}
                    </b-badge>
                  </h3>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.period')"
                  label-for="period"
                  :label-cols="3"
                  :invalid-feedback="feedback('period')"
                  :state="state('period')"
                  v-if="one_day"
                >
                  <b-form-radio-group
                    id="period"
                    v-model="model.period"
                    buttons
                    button-variant="outline-primary"
                    v-if="!disabled"
                  >
                    <b-form-radio
                      :value="period"
                      v-for="(period, index) in ['M', 'AN', 'F']"
                      :key="index"
                    >
                      {{ $t(`labels.backend.leaves.periods.${period}`) }}
                    </b-form-radio>
                  </b-form-radio-group>
                  <h3 class="my-auto" v-else>
                    <b-badge variant="primary">
                      {{ $t(`labels.backend.leaves.periods.${model.period}`) }}
                    </b-badge>
                  </h3>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.days_count')"
                  :label-cols="3"
                  v-if="model.days_count && model.days_count > 0"
                >
                  <h3 class="my-auto">
                    <b-badge variant="primary">
                      {{ model.days_count }}
                    </b-badge>
                  </h3>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.reason')"
                  label-for="reason"
                  :label-cols="3"
                  :invalid-feedback="feedback('reason')"
                >
                  <b-form-textarea
                    id="reason"
                    name="reason"
                    :rows="3"
                    :placeholder="$t('validation.attributes.reason')"
                    v-model="model.reason"
                    :state="state('reason')"
                    :disabled="disabled"
                  ></b-form-textarea>
                </b-form-group>

                <Attachments
                  v-model="model.attachments"
                  :disabled="disabled"
                ></Attachments>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button :to="{ name: 'leaves' }" variant="danger" size="sm">
                  {{ $t('buttons.back') }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button-toolbar class="float-right">
                  <WorkflowTransitions
                    :available-transitions="model.available_transitions"
                    :can-pass="model.can_pass"
                    :can-pass-urgently="model.can_pass_urgently"
                    :change-status="changeStatus"
                    :get-transition-info="getTransitionInfo"
                    :is-form="true"
                    :id="id"
                    v-if="!isNew"
                  ></WorkflowTransitions>
                  <b-button-group>
                    <b-button
                      type="submit"
                      variant="success"
                      size="sm"
                      class="float-right"
                      :disabled="pending"
                      v-if="
                        (isNew && this.$app.user.can('create leaves')) ||
                          model.can_edit
                      "
                    >
                      {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                    </b-button>
                  </b-button-group>
                </b-button-toolbar>
              </b-col>
            </b-row>
          </b-card>
        </form>
      </b-col>
      <b-col xl="4">
        <b-card>
          <h4 slot="header">
            {{ $t('labels.backend.leaves.titles.leave_entitlements') }}
          </h4>

          <b-row class="justify-content-center">
            <table class="table card-table">
              <tbody>
                <tr
                  v-for="(leave_entitlement, index) in leave_entitlements"
                  :key="index"
                >
                  <td>
                    {{
                      $t(
                        `labels.backend.leaves.leave_types.${leave_entitlement.leave_type}`
                      )
                    }}
                  </td>
                  <td class="text-right">
                    <b-badge variant="info"
                      >{{ leave_entitlement.taken }}
                      <template
                        v-if="
                          leave_entitlement.entitle &&
                            leave_entitlement.entitle !== 0
                        "
                      >
                        /
                        {{ leave_entitlement.entitle }}
                      </template></b-badge
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </b-row>
        </b-card>
      </b-col>
    </b-row>
    <comments
      ref="comments"
      model-name="Leave"
      :model-id="id"
      v-if="!isNew"
    ></comments>
  </div>
</template>

<script>
import form from '../mixins/form'
import Multiselect from 'vue-multiselect'
import Comments from './components/Comments'
import workflow from '../mixins/workflow'
import WorkflowTransitions from './components/WorkflowTransitions'
import Attachments from './components/Attachments'
import axios from 'axios'

export default {
  name: 'LeaveForm',
  components: {
    Comments,
    WorkflowTransitions,
    Attachments,
    Multiselect
  },
  mixins: [form, workflow],
  data() {
    return {
      start_date_config: {
        wrap: true,
        allowInput: true,
        minDate: null,
        maxDate: null
      },
      end_date_config: {
        wrap: true,
        allowInput: true,
        minDate: null,
        maxDate: null
      },
      modelName: 'leave',
      resourceRoute: 'leaves',
      listPath: '/HR/leaves',
      item_type: 'leave',
      holidays: [],
      model: {
        requester_id: null,
        employee_id: null,
        requester_name: null,
        department_name: null,
        leave_type: null,
        start_date: null,
        end_date: null,
        days_count: null,
        period: null,
        reason: null,
        status: 'hod_approving',
        attachments: [],
        available_transitions: [],
        can_pass: true,
        can_pass_urgently: false,
        can_edit: true
      },
      leave_types: [
        { name: this.$t('labels.backend.leaves.leave_types.MC'), id: 'MC' },
        { name: this.$t('labels.backend.leaves.leave_types.AL'), id: 'AL' },
        { name: this.$t('labels.backend.leaves.leave_types.EL'), id: 'EL' },
        { name: this.$t('labels.backend.leaves.leave_types.SPL'), id: 'SPL' },
        { name: this.$t('labels.backend.leaves.leave_types.UL'), id: 'UL' },
        { name: this.$t('labels.backend.leaves.leave_types.CL'), id: 'CL' },
        { name: this.$t('labels.backend.leaves.leave_types.ML'), id: 'ML' },
        { name: this.$t('labels.backend.leaves.leave_types.MA'), id: 'MA' },
        { name: this.$t('labels.backend.leaves.leave_types.PL'), id: 'PL' },
        { name: this.$t('labels.backend.leaves.leave_types.RL'), id: 'RL' },
        { name: this.$t('labels.backend.leaves.leave_types.SL'), id: 'SL' },
        { name: this.$t('labels.backend.leaves.leave_types.HP'), id: 'HP' }
      ],
      leave_entitlements: [],
      start_of_contract_date: '1970-01-01',
      end_of_contract_date: '1970-01-01'
    }
  },
  computed: {
    one_day() {
      if (
        this.model.start_date &&
        this.model.start_date === this.model.end_date
      )
        return true
      return false
    },
    disabled() {
      return !(
        this.model.can_edit &&
        (RegExp('hod_approving').test(this.model.status) || !this.model.status)
      )
    },
    start_date_config_full() {
      let config = {
        disable: [
          function(date) {
            return date.getDay() === 0 || date.getDay() === 6
          }
        ]
          .concat(this.holidays)
          .concat([
            {
              from: '2010-01-01',
              to: this.start_of_contract_date
            },
            {
              from: this.end_of_contract_date,
              to: '2030-01-01'
            }
          ])
      }
      return { ...this.start_date_config, ...config }
    },
    end_date_config_full() {
      let config = {
        disable: [
          function(date) {
            return date.getDay() === 0 || date.getDay() === 6
          }
        ]
          .concat(this.holidays)
          .concat([
            {
              from: '2010-01-01',
              to: this.start_of_contract_date
            },
            {
              from: this.end_of_contract_date,
              to: '2030-01-01'
            }
          ])
      }

      return { ...this.end_date_config, ...config }
    }
  },
  watch: {
    'model.start_date': function(newVal, oldVal) {
      if (!this.one_day) this.model.period = null
    },
    'model.end_date': function(newVal, oldVal) {
      if (!this.one_day) this.model.period = null
    },
    'model.period': function(newVal, oldVal) {
      if (newVal === 'F') this.model.days_count = 1
      else if (newVal === 'M' || newVal === 'AN') this.model.days_count = 0.5
    },
    'model.employee_id': function(newVal, oldVal) {
      if (newVal) this.getEmployeeEntitlements()
    }
  },
  created() {
    if (this.isNew) {
      this.getEmployeeEntitlements()
      this.getContractingInfo()
    }
    this.getHolidays()
  },
  methods: {
    onStartChange(dateStr) {
      this.$set(this.end_date_config, 'minDate', dateStr)
      this.getDaysCount()
    },
    onEndChange(dateStr) {
      this.$set(this.start_date_config, 'maxDate', dateStr)
      this.getDaysCount()
    },
    onModelChanged() {
      this.getContractingInfo()
    },
    async getContractingInfo() {
      let request_data = this.isNew ? {} : { employee: this.model.employee_id }
      let { data } = await axios.get(
        this.$app.route('employees.get_contracting_info', request_data)
      )
      this.start_of_contract_date = new Date(data.start_date)
      this.start_of_contract_date.setDate(
        this.start_of_contract_date.getDate() - 1
      )

      this.start_of_contract_date = this.start_of_contract_date
        .toISOString()
        .split('T')[0]

      this.end_of_contract_date = new Date(data.end_date)
      this.end_of_contract_date.setDate(this.end_of_contract_date.getDate() + 1)

      this.end_of_contract_date = this.end_of_contract_date
        .toISOString()
        .split('T')[0]
    },
    async getEmployeeEntitlements() {
      let request_data = this.isNew ? {} : { employee: this.model.employee_id }

      let { data } = await axios.get(
        this.$app.route('leaves.employee_leave_entitlements', request_data)
      )

      this.leave_entitlements = data
    },
    async getHolidays() {
      let { data } = await axios.get(this.$app.route('holidays.get_as_ranges'))

      this.holidays = data
    },
    async getDaysCount() {
      if (!this.model.start_date || !this.model.end_date) {
        this.model.days_count = null
        return
      }
      let { data } = await axios.get(
        this.$app.route('get_business_days_count', {
          start_date: this.model.start_date,
          end_date: this.model.end_date
        })
      )

      this.model.days_count = data
    }
  }
}
</script>
