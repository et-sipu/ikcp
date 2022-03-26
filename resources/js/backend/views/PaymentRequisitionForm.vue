<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col :xl="12">
          <b-card style="overflow: hidden">
            <template slot="header">
              <b-row class="w-100 mr-0 ml-0">
                <b-col>
                  <h4>
                    {{
                      isNew
                        ? $t(
                            'labels.backend.payment_requisitions.titles.create'
                          )
                        : $t('labels.backend.payment_requisitions.titles.edit')
                    }}
                  </h4>
                </b-col>
                <b-col>
                  <b-row v-if="!isNew" class="justify-content-center pos w-100">
                    <div class="wrapper-mains">
                      <div class="left-align w-100">
                        <StepProgress
                          :available-transitions="
                            model.available_transitions.length ? true : false
                          "
                          :status="model.status"
                          :step-history="model.transitions"
                          :active-color="
                            model.status === 'rejected' ? '#cd201f' : '#09856e'
                          "
                          workflow="payment_requisitions"
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
                            `labels.backend.payment_requisitions.states.${model.status}`
                          )
                        }}
                      </b-badge>
                    </h4>
                  </div>
                </b-col>
              </b-row>
            </template>

            <b-row class="justify-content-center">
              <b-col
                :xl="!model.status || model.status === 'hod_approving' ? 12 : 6"
              >
                <b-form-group
                  :label="$t('validation.attributes.requester')"
                  :label-cols="4"
                  v-if="!isNew"
                >
                  <h4>
                    <b-badge variant="info">
                      {{ model.requester_name }}
                    </b-badge>
                    <b-btn
                      v-b-tooltip.hover
                      :title="
                        $t('buttons.payment_requisitions.requester_prf_history')
                      "
                      size="sm"
                      :to="{
                        name: 'payment_requisitions',
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
                  :label="$t('validation.attributes.released_to')"
                  label-for="released_to"
                  :label-cols="4"
                  :invalid-feedback="feedback('released_to')"
                >
                  <b-form-input
                    id="released_to"
                    name="released_to"
                    :placeholder="$t('validation.attributes.released_to')"
                    v-model="model.released_to"
                    :state="state('released_to')"
                    :disabled="
                      !(model.can_edit && model.status === 'hod_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.title')"
                  label-for="title"
                  :label-cols="4"
                  :invalid-feedback="feedback('title')"
                >
                  <b-form-input
                    id="title"
                    name="title"
                    :placeholder="$t('validation.attributes.title')"
                    v-model="model.title"
                    :state="state('title')"
                    :disabled="
                      !(model.can_edit && model.status === 'hod_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.details')"
                  label-for="details"
                  :label-cols="4"
                  :invalid-feedback="feedback('details')"
                >
                  <b-form-textarea
                    id="details"
                    name="details"
                    :rows="3"
                    :placeholder="$t('validation.attributes.details')"
                    v-model="model.details"
                    :state="state('details')"
                    :disabled="
                      !(model.can_edit && model.status === 'hod_approving')
                    "
                  ></b-form-textarea>
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
                    :rows="3"
                    :placeholder="$t('validation.attributes.remarks')"
                    v-model="model.remarks"
                    :state="state('remarks')"
                    :disabled="
                      !(model.can_edit && model.status === 'hod_approving')
                    "
                  ></b-form-textarea>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.project')"
                  label-for="project"
                  :label-cols="4"
                  :invalid-feedback="feedback('project')"
                >
                  <b-form-input
                    id="project"
                    name="project"
                    :placeholder="$t('validation.attributes.project')"
                    v-model="model.project"
                    :state="state('project')"
                    :disabled="
                      !(model.can_edit && model.status === 'hod_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.supplier')"
                  label-for="supplier"
                  :label-cols="4"
                  :invalid-feedback="feedback('supplier')"
                >
                  <b-form-input
                    id="supplier"
                    name="supplier"
                    :placeholder="$t('validation.attributes.supplier')"
                    v-model="model.supplier"
                    :state="state('supplier')"
                    :disabled="
                      !(model.can_edit && model.status === 'hod_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.payment')"
                  label-for="new_outstanding_invoices"
                  :label-cols="4"
                  :invalid-feedback="feedback('new_outstanding_invoices')"
                >
                  <b-input-group>
                    <b-input-group-append v-if="!isNew">
                      <b-btn class="mb-3" id="test"
                        ><font-awesome-icon icon="history"></font-awesome-icon
                      ></b-btn>
                      <b-popover target="test" placement="test" title="History">
                        <p v-for="(value, key) in old_Values" :key="key">
                          {{ value.old }} => {{ value.new }} by
                          {{ value.username }}@{{ value.created_at }}
                        </p>
                      </b-popover>
                    </b-input-group-append>
                    <b-form-input
                      id="payment"
                      name="payment"
                      :placeholder="$t('validation.attributes.payment')"
                      v-model="model.new_outstanding_invoices"
                      :state="state('new_outstanding_invoices')"
                      :disabled="
                        !(model.can_edit && model.status === 'hod_approving')
                      "
                    ></b-form-input>
                    <b-input-group-append>
                      <b-form-select
                        v-model="model.currency"
                        :options="['MYR', 'USD', 'GSD', 'Euro']"
                        class="mb-3"
                        :disabled="
                          !(model.can_edit && model.status === 'hod_approving')
                        "
                      ></b-form-select>
                    </b-input-group-append>
                  </b-input-group>
                </b-form-group>
                <fieldset>
                  <h5 class="mb-5">
                    {{ $t('validation.attributes.attachments') }}
                  </h5>

                  <b-row>
                    <b-col
                      xl="12"
                      v-for="(attachment, index) in model.attachments"
                      :key="index"
                    >
                      <b-button
                        class="close"
                        size="sm"
                        variant="danger"
                        v-b-tooltip.hover
                        :title="$t('buttons.reset')"
                        @click="dropAttachment(index)"
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
                            <a
                              :href="attachment.url"
                              v-b-tooltip.hover
                              :title="attachment.url.match(/\/([^\/]*)$/)[1]"
                              target="_blank"
                            >
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
                      :title="$t('buttons.vessels.add_certificate')"
                      @click="addAttachment()"
                    >
                      <i class="fe fe-plus-circle"></i>
                    </b-button>
                  </div>
                </fieldset>
              </b-col>
              <b-col xl="6" v-if="model.status !== 'hod_approving'">
                <b-form-group
                  :label="$t('validation.attributes.last_pv_no')"
                  label-for="last_pv_no"
                  :label-cols="4"
                  :invalid-feedback="feedback('last_pv_no')"
                >
                  <b-form-input
                    id="last_pv_no"
                    name="last_pv_no"
                    :placeholder="$t('validation.attributes.last_pv_no')"
                    v-model="model.last_pv_no"
                    :state="state('last_pv_no')"
                    :disabled="
                      !(model.can_edit && model.status === 'finance_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.last_payment_amount')"
                  label-for="last_payment_amount"
                  :label-cols="4"
                  :invalid-feedback="feedback('last_payment_amount')"
                >
                  <b-form-input
                    id="last_payment_amount"
                    name="last_payment_amount"
                    :placeholder="
                      $t('validation.attributes.last_payment_amount')
                    "
                    v-model="model.last_payment_amount"
                    :state="state('last_payment_amount')"
                    :disabled="
                      !(model.can_edit && model.status === 'finance_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.last_payment_date')"
                  label-for="last_payment_date"
                  :label-cols="4"
                  :invalid-feedback="feedback('last_payment_date')"
                  :state="state('last_payment_date')"
                >
                  <b-input-group
                    v-if="
                      model.can_edit && model.status === 'finance_approving'
                    "
                  >
                    <p-datetimepicker
                      :config="config"
                      id="last_payment_date"
                      name="last_payment_date"
                      v-model="model.last_payment_date"
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
                        model.last_payment_date
                          ? model.last_payment_date
                          : ' -- '
                      }}
                    </b-badge>
                  </h3>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.old_outstanding_invoices')"
                  label-for="old_outstanding_invoices"
                  :label-cols="4"
                  :invalid-feedback="feedback('old_outstanding_invoices')"
                >
                  <b-form-input
                    id="old_outstanding_invoices"
                    name="old_outstanding_invoices"
                    :placeholder="
                      $t('validation.attributes.old_outstanding_invoices')
                    "
                    v-model="model.old_outstanding_invoices"
                    :state="state('old_outstanding_invoices')"
                    :disabled="
                      !(model.can_edit && model.status === 'finance_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.proposed_payment')"
                  label-for="payment"
                  :label-cols="4"
                  :invalid-feedback="feedback('payment')"
                >
                  <b-form-input
                    id="proposed_payment"
                    name="proposed_payment"
                    :placeholder="$t('validation.attributes.proposed_payment')"
                    v-model="model.payment"
                    :state="state('payment')"
                    :disabled="
                      !(model.can_edit && model.status === 'finance_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.approved_payment')"
                  label-for="approved_payment"
                  :label-cols="4"
                  :invalid-feedback="feedback('approved_payment')"
                >
                  <b-form-input
                    id="approved_payment"
                    name="approved_payment"
                    :placeholder="$t('validation.attributes.approved_payment')"
                    v-model="model.approved_payment"
                    :state="state('approved_payment')"
                    :disabled="
                      !(model.can_edit && model.status === 'dec_approving')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.cheque_no')"
                  label-for="cheque_no"
                  :label-cols="4"
                  :invalid-feedback="feedback('cheque_no')"
                >
                  <b-form-input
                    id="cheque_no"
                    name="cheque_no"
                    :placeholder="$t('validation.attributes.cheque_no')"
                    v-model="model.cheque_no"
                    :state="state('cheque_no')"
                    :disabled="
                      !(model.can_edit && model.status === 'releasing')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.cheque_bank')"
                  label-for="cheque_bank"
                  :label-cols="4"
                  :invalid-feedback="feedback('cheque_bank')"
                >
                  <b-form-input
                    id="name"
                    name="name"
                    :placeholder="$t('validation.attributes.cheque_bank')"
                    v-model="model.cheque_bank"
                    :state="state('cheque_bank')"
                    :disabled="
                      !(model.can_edit && model.status === 'releasing')
                    "
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.cheque_date')"
                  label-for="cheque_date"
                  :label-cols="4"
                  :invalid-feedback="feedback('cheque_date')"
                  :state="state('cheque_date')"
                >
                  <b-input-group
                    v-if="model.can_edit && model.status === 'releasing'"
                  >
                    <p-datetimepicker
                      :config="config"
                      id="cheque_date"
                      name="cheque_date"
                      v-model="model.cheque_date"
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
                      {{ model.cheque_date ? model.cheque_date : ' -- ' }}
                    </b-badge>
                  </h3>
                </b-form-group>
                <b-btn
                  @click="generatePaymentVoucher()"
                  v-if="
                    $app.user.can('generate payment voucher') &&
                      model.status === 'releasing'
                  "
                >
                  Generate Payment Voucher
                </b-btn>
                <b-form-group
                  :label="$t('validation.attributes.payment_voucher')"
                  :label-cols="4"
                  :invalid-feedback="feedback('payment_voucher')"
                  class="file-upload-group required"
                >
                  <div class="media">
                    <div
                      v-if="model.payment_voucher_url"
                      class="m-1 attachment_link"
                    >
                      <a :href="model.payment_voucher_url" target="_blank">
                        {{ $t('validation.attributes.payment_voucher') }}
                      </a>
                    </div>

                    <div
                      class="media-body"
                      v-if="model.can_edit && model.status === 'releasing'"
                    >
                      <b-form-file
                        accept=".jpg, .png, .gif, .pdf"
                        :placeholder="$t('labels.no_file_chosen')"
                        v-model="model.payment_voucher"
                        ref="payment_voucher"
                        :state="state('payment_voucher')"
                      ></b-form-file>
                    </div>
                  </div>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  :to="{ name: 'payment_requisitions' }"
                  variant="danger"
                  size="sm"
                >
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
                      :disabled="pending"
                      v-if="
                        (isNew &&
                          $app.user.can('create payment requisitions')) ||
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
        </b-col>
      </b-row>
      <comments
        ref="comments"
        model-name="PaymentRequisition"
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
import fileReader from '../lib/fileReader'
import axios from 'axios'
import BCol from 'bootstrap-vue/esm/components/layout/col'
import StepProgress from './components/StepProgress'

export default {
  name: 'PaymentRequisitionForm',
  components: { StepProgress, BCol, Comments, WorkflowTransitions },
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
      statuses: [],
      config: {
        wrap: true,
        allowInput: true
      },
      modelName: 'payment_requisition',
      resourceRoute: 'payment_requisitions',
      item_type: 'payment_requisition',
      old_Values: [],
      model: {
        requester_id: null,
        requester_name: null,
        released_to: null,
        title: null,
        details: null,
        remarks: null,
        project: null,
        supplier: null,
        last_pv_no: null,
        cheque_no: null,
        cheque_bank: null,
        cheque_date: null,
        last_payment_amount: null,
        last_payment_date: null,
        old_outstanding_invoices: null,
        new_outstanding_invoices: null,
        currency: 'MYR',
        payment: null,
        approved_payment: null,
        status: 'hod_approving',
        attachments: [],
        available_transitions: [],
        transitions: {},
        payment_voucher_url: null,
        payment_voucher: null,
        can_pass: true,
        can_pass_urgently: false,
        can_edit: true
      }
    }
  },
  computed: {
    listPath: function() {
      if (
        this.model.status === 'finance_approving' ||
        this.model.status === 'dec_approving' ||
        this.model.status === 'releasing'
      ) {
        return this.$router.currentRoute
      } else {
        return '/Finance/payment_requisitions'
      }
    }
  },
  async created() {
    let { data } = await axios.get(
      this.$app.route(`payment_requisitions.get_statuses`)
    )

    this.statuses = data.map(item => {
      return {
        id: item,
        name: this.$t(`labels.backend.payment_requisitions.states.${item}`)
      }
    })
    if (!this.isNew) {
      let { data } = await axios.get(
        this.$app.route('audits.get_payment_requisition_history', {
          payment_requisition: this.id
        })
      )
      this.old_Values = data
    }
  },
  methods: {
    addAttachment() {
      this.model.attachments.push({
        id: null,
        url: null,
        file: null
      })
    },
    dropAttachment(index) {
      this.$refs['file-' + index][0].reset()
    },
    async generatePaymentVoucher() {
      let formData = this.$app.objectToFormData({
        cheque_no: this.model.cheque_no,
        cheque_bank: this.model.cheque_bank,
        cheque_date: this.model.cheque_date
      })
      let url = null
      try {
        let { data } = await axios({
          url: this.$app.route(
            'payment_requisitions.generate_payment_voucher',
            {
              payment_requisition: this.id
            }
          ),
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
        link.setAttribute('download', `PaymentVoucher-${this.id}.pdf`)
        document.body.appendChild(link)

        link.click()
        this.validation = []
        this.pending = false
        this.loading = false
      } catch (e) {
        this.pending = false
        this.loading = false

        // Validation errors
        if (e.hasOwnProperty('response') && e.response.status === 422) {
          const file = await fileReader(e.response.data)
          // Parse content and retrieve 'message'
          const errors = JSON.parse(file)

          this.validation = errors
          return
        } else {
          this.validation = []
        }
        this.$app.error(e)
      }
      if (url) {
        setTimeout(() => {
          window.URL.revokeObjectURL(url)
        }, 10000)
      }
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
