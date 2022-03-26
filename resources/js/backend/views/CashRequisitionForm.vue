<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col
          :xl="!fromRouter ? 12 : model.status === 'hod_approving' ? 6 : 12"
        >
          <b-card style="overflow: hidden">
            <template slot="header">
              <b-row class="w-100 mr-0 ml-0">
                <b-col>
                  <h4>
                    {{
                      isNew
                        ? $t('labels.backend.cash_requisitions.titles.create')
                        : $t('labels.backend.cash_requisitions.titles.edit')
                    }}
                  </h4>
                </b-col>
                <b-col>
                  <b-row v-if="!isNew" class="justify-content-center pos w-100">
                    <div class="wrapper-mains">
                      <div class="left-align w-100">
                        <StepProgress
                          :length="9"
                          :available-transitions="
                            model.available_transitions.length ? true : false
                          "
                          :status="model.status"
                          :step-history="model.transitions"
                          :active-color="
                            model.status === 'rejected' ? '#cd201f' : '#09856e'
                          "
                          workflow="cash_requisitions"
                        ></StepProgress>
                      </div>
                    </div>
                  </b-row>
                </b-col>
                <b-col>
                  <div class="card-options float-right" v-if="!isNew">
                    <h4>
                      <b-badge
                        :variant="
                          model.status === 'rejected' ? 'danger' : 'info'
                        "
                      >
                        {{
                          $t(
                            `labels.backend.cash_requisitions.states.${model.status}`
                          )
                        }}
                      </b-badge>
                    </h4>
                  </div>
                </b-col>
              </b-row>
            </template>
            <b-row class="justify-content-center">
              <b-col :xl="model.status === 'hod_approving' ? 12 : 6">
                <b-form-group
                  :label="$t('validation.attributes.request_type')"
                  :label-cols="4"
                  :invalid-feedback="feedback('request_type')"
                  :state="state('request_type')"
                >
                  <b-form-radio-group
                    id="request_type"
                    v-model="model.request_type"
                    buttons
                    button-variant="outline-primary"
                    v-if="model.can_edit && model.status === 'hod_approving'"
                  >
                    <b-form-radio
                      :value="type"
                      v-for="(type, index) in types"
                      :key="index"
                    >
                      {{ $t(`validation.attributes.${type}`) }}
                    </b-form-radio>
                  </b-form-radio-group>
                  <h4 v-else>
                    <b-badge variant="info">
                      {{ $t(`validation.attributes.${model.request_type}`) }}
                    </b-badge>
                  </h4>
                </b-form-group>

                <template v-if="!isNew">
                  <b-form-group
                    :label="$t('validation.attributes.requester')"
                    :label-cols="4"
                  >
                    <h4>
                      <b-badge variant="info">
                        {{ model.requester_name }}
                      </b-badge>
                      <b-btn
                        v-b-tooltip.hover
                        :title="
                          $t('buttons.cash_requisitions.requester_crf_history')
                        "
                        size="sm"
                        :to="{
                          name: 'cash_requisitions',
                          query: {
                            requester: JSON.stringify({
                              id: model.requester_id,
                              name: model.requester_name
                            })
                          }
                        }"
                        target="_blank"
                      >
                        <font-awesome-icon icon="history"></font-awesome-icon>
                      </b-btn>
                    </h4>
                  </b-form-group>
                  <b-form-group
                    :label="$t('validation.attributes.department')"
                    :label-cols="4"
                  >
                    <h4>
                      <b-badge variant="info">
                        {{ model.department_name }}
                      </b-badge>
                    </h4>
                  </b-form-group>
                </template>

                <b-form-group
                  :label="$t('validation.attributes.purpose')"
                  label-for="purpose"
                  :label-cols="4"
                  :invalid-feedback="feedback('purpose')"
                >
                  <b-form-textarea
                    id="purpose"
                    name="purpose"
                    :rows="3"
                    :placeholder="$t('validation.attributes.purpose')"
                    v-model="model.purpose"
                    :state="state('purpose')"
                    :disabled="
                      !(model.can_edit && model.status === 'hod_approving')
                    "
                  ></b-form-textarea>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.amount')"
                  label-for="amount"
                  :label-cols="4"
                  :invalid-feedback="feedback('amount')"
                  :state="state('amount')"
                >
                  <b-input-group>
                    <b-input-group-append v-if="!isNew">
                      <b-btn id="test"
                        ><font-awesome-icon icon="history"></font-awesome-icon
                      ></b-btn>
                      <b-popover
                        target="test"
                        placement="test"
                        title="Old Values"
                      >
                        <p v-for="(value, key) in old_Values" :key="key">
                          {{ value.old }} => {{ value.new }} by
                          {{ value.username }}@{{ value.created_at }}
                        </p>
                      </b-popover>
                    </b-input-group-append>
                    <b-form-input
                      id="amount"
                      name="amount"
                      type="number"
                      step="any"
                      :placeholder="$t('validation.attributes.amount')"
                      v-model="model.amount"
                      :state="state('amount')"
                      :disabled="
                        !(model.can_edit && model.status === 'hod_approving')
                      "
                    ></b-form-input>
                  </b-input-group>
                </b-form-group>
                <fieldset>
                  <h5 class="mb-5">
                    {{ $t('validation.attributes.attachments') }}
                  </h5>

                  <b-row>
                    <b-col
                      xl="12"
                      v-for="(attachment, index) in attachments"
                      :key="index"
                    >
                      <b-button
                        class="close"
                        size="sm"
                        variant="danger"
                        v-b-tooltip.hover
                        :title="$t('buttons.reset')"
                        @click="
                          dropFile(
                            'file-' + index,
                            attachment,
                            'attachments',
                            index
                          )
                        "
                        v-if="
                          model.can_edit && model.status === 'hod_approving'
                        "
                      >
                        <i class="fe fe-x"></i>
                      </b-button>
                      <b-form-group
                        :label="
                          $t('validation.attributes.attachment') +
                            ' ' +
                            (index + 1)
                        "
                        :label-cols="4"
                        :invalid-feedback="
                          feedback('attachments.' + index + '.file')
                        "
                        class="file-upload-group required"
                      >
                        <div class="media">
                          <div
                            v-if="attachment.url"
                            class="m-1 certificate_link"
                          >
                            <a :href="attachment.url" target="_blank">
                              {{ $t('validation.attributes.attachment') }}
                            </a>
                          </div>

                          <div
                            class="media-body"
                            v-if="
                              model.can_edit && model.status === 'hod_approving'
                            "
                          >
                            <b-form-file
                              accept=".jpg, .png, .gif, .pdf"
                              :placeholder="$t('labels.no_file_chosen')"
                              v-model="attachment.file"
                              :key="'filekey-' + index"
                              :ref="'file-' + index"
                              :state="state('attachments.' + index + '.file')"
                            ></b-form-file>
                          </div>
                        </div>
                      </b-form-group>
                    </b-col>
                  </b-row>
                  <div
                    class="float-right"
                    v-if="model.can_edit && model.status === 'hod_approving'"
                  >
                    <b-button
                      size="sm"
                      variant="primary"
                      v-b-tooltip.hover
                      :title="$t('buttons.add_attachment')"
                      @click="addFile('attachments')"
                    >
                      <i class="fe fe-plus-circle"></i>
                    </b-button>
                  </div>
                </fieldset>
              </b-col>
              <b-col xl="6" v-if="model.status !== 'hod_approving'">
                <b-form-group
                  :label="$t('validation.attributes.approved_amount')"
                  label-for="approved_amount"
                  :label-cols="4"
                  :invalid-feedback="feedback('approved_amount')"
                >
                  <b-form-input
                    id="approved_amount"
                    name="approved_amount"
                    :placeholder="$t('validation.attributes.approved_amount')"
                    v-model="model.approved_amount"
                    :state="state('approved_amount')"
                    :disabled="
                      !(model.can_edit && model.status === 'finance_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.cash_advance_taken')"
                  label-for="cash_advance_taken"
                  :label-cols="4"
                  :invalid-feedback="feedback('cash_advance_taken')"
                >
                  <b-form-input
                    id="cash_advance_taken"
                    name="cash_advance_taken"
                    :placeholder="
                      $t('validation.attributes.cash_advance_taken')
                    "
                    v-model="model.cash_advance_taken"
                    :state="state('cash_advance_taken')"
                    :disabled="
                      !(model.can_edit && model.status === 'finance_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.cash_advance_taken_date')"
                  label-for="cash_advance_taken_date"
                  :label-cols="4"
                  :invalid-feedback="feedback('cash_advance_taken_date')"
                  :state="state('cash_advance_taken_date')"
                >
                  <b-input-group
                    v-if="
                      model.can_edit && model.status === 'finance_approving'
                    "
                  >
                    <p-datetimepicker
                      :config="config"
                      id="cash_advance_taken_date"
                      name="cash_advance_taken_date"
                      v-model="model.cash_advance_taken_date"
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
                      {{
                        model.cash_advance_taken_date
                          ? model.cash_advance_taken_date
                          : ' -- '
                      }}
                    </b-badge>
                  </h3>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.total_receipt_returned')"
                  label-for="total_receipt_returned"
                  :label-cols="4"
                  :invalid-feedback="feedback('total_receipt_returned')"
                >
                  <b-form-input
                    id="total_receipt_returned"
                    name="total_receipt_returned"
                    :placeholder="
                      $t('validation.attributes.total_receipt_returned')
                    "
                    v-model="model.total_receipt_returned"
                    :state="state('total_receipt_returned')"
                    :disabled="
                      !(model.can_edit && model.status === 'finance_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="
                    $t('validation.attributes.total_receipt_returned_date')
                  "
                  label-for="total_receipt_returned_date"
                  :label-cols="4"
                  :invalid-feedback="feedback('total_receipt_returned_date')"
                  :state="state('total_receipt_returned_date')"
                >
                  <b-input-group
                    v-if="
                      model.can_edit && model.status === 'finance_approving'
                    "
                  >
                    <p-datetimepicker
                      :config="config"
                      id="total_receipt_returned_date"
                      name="total_receipt_returned_date"
                      v-model="model.total_receipt_returned_date"
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
                      {{
                        model.total_receipt_returned_date
                          ? model.total_receipt_returned_date
                          : ' -- '
                      }}
                    </b-badge>
                  </h3>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.outstanding_ca')"
                  label-for="outstanding_ca"
                  :label-cols="4"
                  :invalid-feedback="feedback('outstanding_ca')"
                >
                  <b-form-input
                    id="outstanding_ca"
                    name="outstanding_ca"
                    :placeholder="$t('validation.attributes.outstanding_ca')"
                    v-model="model.outstanding_ca"
                    :state="state('outstanding_ca')"
                    :disabled="
                      !(model.can_edit && model.status === 'finance_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.remarks')"
                  label-for="remarks"
                  :label-cols="4"
                  :invalid-feedback="feedback('remarks')"
                >
                  <b-form-textarea
                    id="remarks"
                    name="remarks"
                    :placeholder="$t('validation.attributes.remarks')"
                    v-model="model.remarks"
                    :state="state('remarks')"
                    rows="3"
                    :disabled="
                      !(model.can_edit && model.status === 'releasing')
                    "
                  ></b-form-textarea>
                </b-form-group>

                <fieldset>
                  <!--<h5 class="mb-5">-->
                  <!--{{ $t('validation.attributes.receipts') }}-->
                  <!--</h5>-->

                  <b-form-group
                    :label="$t('validation.attributes.receipts')"
                    label-size="lg"
                    label-class="font-weight-bold pt-0"
                    class="mb-0"
                    :invalid-feedback="feedback('receipts')"
                    :state="state('receipts')"
                  >
                    <b-row>
                      <b-col
                        xl="12"
                        v-for="(receipt, index) in receipts"
                        :key="index"
                      >
                        <b-button
                          class="close"
                          size="sm"
                          variant="danger"
                          v-b-tooltip.hover
                          :title="$t('buttons.reset')"
                          @click="
                            dropFile(
                              'receipt-' + index,
                              receipt,
                              'receipts',
                              index
                            )
                          "
                          v-if="model.can_edit && model.status === 'proofing'"
                        >
                          <i class="fe fe-x"></i>
                        </b-button>
                        <b-form-group
                          :label="
                            $t('validation.attributes.receipt') +
                              ' ' +
                              (index + 1)
                          "
                          :label-cols="4"
                          :invalid-feedback="
                            feedback('receipts.' + index + '.file')
                          "
                          class="file-upload-group required"
                        >
                          <div class="media">
                            <div
                              v-if="receipt.url"
                              class="m-1 certificate_link"
                            >
                              <a :href="receipt.url" target="_blank">
                                {{ $t('validation.attributes.receipt') }}
                              </a>
                            </div>

                            <div
                              class="media-body"
                              v-if="
                                model.can_edit && model.status === 'proofing'
                              "
                            >
                              <b-form-file
                                accept=".jpg, .png, .gif, .pdf"
                                :placeholder="$t('labels.no_file_chosen')"
                                v-model="receipt.file"
                                :key="'filekey-' + index"
                                :ref="'receipt-' + index"
                                :state="state('receipts.' + index + '.file')"
                              ></b-form-file>
                            </div>
                          </div>
                        </b-form-group>
                      </b-col>
                    </b-row>
                    <div
                      class="float-right"
                      v-if="model.can_edit && model.status === 'proofing'"
                    >
                      <b-button
                        size="sm"
                        variant="primary"
                        v-b-tooltip.hover
                        :title="$t('buttons.vessels.add_certificate')"
                        @click="addFile('receipts')"
                      >
                        <i class="fe fe-plus-circle"></i>
                      </b-button>
                    </div>
                  </b-form-group>
                </fieldset>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  to="/Finance/cash_requisitions"
                  variant="danger"
                  size="sm"
                  v-if="fromRouter"
                >
                  {{ $t('buttons.back') }}
                </b-button>
                <b-button
                  variant="danger"
                  size="sm"
                  v-else
                  @click="$emit('form-submission-canceled')"
                >
                  {{ $t('buttons.cancel') }}
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
                      :disabled="pending"
                      v-if="
                        (isNew &&
                          this.$app.user.can('create cash requisitions')) ||
                          model.can_edit
                      "
                    >
                      {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                    </b-button>
                  </b-button-group>
                </b-button-toolbar>
              </b-col>
            </b-row>
            <atom-spinner
              :animation-duration="1000"
              :size="400"
              :color="'#DBB11E'"
              :loading="loading"
              :full="true"
            ></atom-spinner>
          </b-card>
        </b-col>
      </b-row>
      <comments
        ref="comments"
        model-name="CashRequisition"
        :model-id="id"
        v-if="!isNew"
      ></comments>
    </form>
  </div>
</template>

<script>
import form from '../mixins/form'
import Comments from './components/Comments'
import workflow from '../mixins/workflow'
import WorkflowTransitions from './components/WorkflowTransitions'
import axios from 'axios'
import stepper from './components/stepper'
import StepProgress from './components/StepProgress'

export default {
  name: 'CashRequisitionForm',
  components: { StepProgress, Comments, WorkflowTransitions },
  mixins: [form, workflow],
  props: {
    fromRouter: {
      default: true,
      type: Boolean,
      required: false
    }
  },
  data() {
    return {
      stepList: [],
      config: {
        wrap: true,
        allowInput: true
      },
      modelName: 'cash_requisition',
      resourceRoute: 'cash_requisitions',
      item_type: 'cash_requisition',
      types: ['cash_advance', 'reimbursement', 'deposit'],
      old_Values: [],
      model: {
        requester_id: null,
        requester_name: null,
        department_name: null,
        purpose: null,
        request_type: null,
        amount: null,
        approved_amount: null,
        cash_advance_taken: null,
        cash_advance_taken_date: null,
        total_receipt_returned: null,
        total_receipt_returned_date: null,
        outstanding_ca: null,
        status: 'hod_approving',
        attachments: [],
        receipts: [],
        available_transitions: [],
        transitions: {},
        remarks: null,
        can_pass: true,
        can_pass_urgently: false,
        can_edit: true
      }
    }
  },
  computed: {
    listPath: function() {
      if (this.model.status === 'finance_approving') {
        return this.$router.currentRoute
      } else {
        return '/Finance/cash_requisitions'
      }
    },
    attachments: function() {
      return this.model.attachments.filter(
        attachment => attachment.id === null || attachment.url !== null
      )
    },
    receipts: function() {
      return this.model.receipts.filter(
        receipt => receipt.id === null || receipt.url !== null
      )
    }
  },
  async created() {
    if (!this.isNew) {
      ;({ data } = await axios.get(
        this.$app.route('audits.get_cash_requisition_history', {
          cash_requisition: this.id
        })
      ))
      this.old_Values = data
    }
  },
  methods: {
    addFile(collection) {
      this.model[collection].push({
        id: null,
        url: null,
        file: null
      })
    },
    dropFile(ref, file, collection = 'attachments', index = 0) {
      this.$refs[ref][0].reset()
      if (file.id === null && file.url == null && file.file === null) {
        this.model[collection].splice(index, 1)
      }
      file.url = null
    }
  }
}
</script>

<style scoped>
.wrapper-mains {
  width: 100%;
  height: auto;
  background: white;
  text-align: left;
}
.pos {
  margin-left: -5%;
  margin-right: -5%;
}
</style>
