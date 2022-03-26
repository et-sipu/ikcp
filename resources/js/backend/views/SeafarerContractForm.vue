<template>
  <div class="animated fadeIn">
    <b-row class="justify-content-center">
      <b-col xl="12">
        <form @submit.prevent="onSubmit" ref="myform">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t(
                      'labels.backend.seafarer_contracts.titles.create.contracts'
                    )
                  : $t(
                      'labels.backend.seafarer_contracts.titles.edit.contracts'
                    )
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="6">
                <fieldset class="form-fieldset">
                  <h5 class="mb-5">
                    {{
                      $t(
                        'labels.backend.seafarer_contracts.titles.contract_info'
                      )
                    }}
                  </h5>

                  <b-form-group
                    :label="$t('validation.attributes.seafarer')"
                    label-for="seafarer"
                    :label-cols="3"
                    v-if="model.seafarer"
                  >
                    <b-form-input
                      v-model="model.seafarer.name"
                      readonly
                    ></b-form-input>
                  </b-form-group>

                  <b-form-group
                    :label="$t('validation.attributes.capabilities_info.rank')"
                    label-for="rank"
                    :label-cols="3"
                    :invalid-feedback="feedback('rank')"
                    :state="state('rank')"
                  >
                    <multiselect
                      :disabled="!is_editable"
                      v-model="model.rank"
                      :options="ranks"
                      id="rank"
                      name="rank"
                      group-values="ranks"
                      group-label="department"
                      :group-select="true"
                      track-by="id"
                      label="name"
                      :searchable="true"
                      :close-on-select="true"
                      :show-labels="false"
                      :placeholder="
                        '-- ' +
                          $t('validation.attributes.capabilities_info.rank') +
                          ' --'
                      "
                    ></multiselect>
                  </b-form-group>

                  <b-form-group
                    :label="$t('validation.attributes.vessel')"
                    label-for="vessel"
                    :label-cols="3"
                    :invalid-feedback="feedback('vessel')"
                    :state="state('vessel')"
                  >
                    <multiselect
                      v-model="model.vessel"
                      :options="vessels"
                      id="vessel"
                      name="vessel"
                      track-by="id"
                      label="name"
                      :disabled="!isNew"
                      :searchable="false"
                      :close-on-select="true"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.vessel') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>

                  <b-form-group
                    :label="$t('validation.attributes.duration_of_service')"
                    label-for="duration_of_service"
                    :label-cols="3"
                    :invalid-feedback="feedback('duration_of_service')"
                    :state="state('duration_of_service')"
                  >
                    <b-input-group>
                      <b-form-input
                        id="duration_of_service"
                        name="duration_of_service"
                        type="number"
                        min="1"
                        max="36"
                        :placeholder="
                          $t('validation.attributes.duration_of_service')
                        "
                        v-model="model.duration_of_service"
                        :disabled="!is_editable"
                        :state="state('duration_of_service')"
                      ></b-form-input>
                      <b-input-group-append>
                        <b-form-radio-group
                          v-if="is_editable"
                          buttons
                          button-variant="outline-secondary"
                          v-model="model.duration_of_service_unit"
                          :options="['Month', 'Week']"
                          name="duration_of_service_unit"
                        ></b-form-radio-group>
                        <b-input-group-prepend v-else>
                          <b-btn variant="secondary">
                            {{ model.duration_of_service_unit }}
                          </b-btn>
                        </b-input-group-prepend>
                      </b-input-group-append>
                    </b-input-group>
                  </b-form-group>

                  <b-form-group
                    :label="$t('validation.attributes.basic_salary')"
                    label-for="basic_salary"
                    :label-cols="3"
                    :invalid-feedback="feedback('basic_salary')"
                    :state="state('basic_salary')"
                  >
                    <b-input-group>
                      <b-input-group-prepend>
                        <b-form-radio-group
                          v-if="is_editable"
                          buttons
                          button-variant="outline-primary"
                          v-model="model.currency"
                          :options="currencies"
                          name="currency"
                        ></b-form-radio-group>
                        <b-input-group-prepend v-else>
                          <b-btn variant="primary">
                            {{ model.currency }}
                          </b-btn>
                        </b-input-group-prepend>
                      </b-input-group-prepend>
                      <b-form-input
                        id="basic_salary"
                        name="basic_salary"
                        type="number"
                        :placeholder="$t('validation.attributes.basic_salary')"
                        v-model="model.basic_salary"
                        :disabled="!is_editable"
                        :state="state('basic_salary')"
                      ></b-form-input>
                      <b-input-group-append>
                        <b-form-radio-group
                          v-if="is_editable"
                          buttons
                          button-variant="outline-secondary"
                          v-model="model.pay_frequency"
                          :options="pay_frequencies"
                          name="pay_frequency"
                        ></b-form-radio-group>
                        <b-input-group-prepend v-else>
                          <b-btn variant="secondary">
                            {{ model.pay_frequency }}
                          </b-btn>
                        </b-input-group-prepend>
                      </b-input-group-append>
                    </b-input-group>
                  </b-form-group>

                  <b-form-group
                    :label="$t('validation.attributes.pay_leave')"
                    label-for="pay_leave"
                    :label-cols="3"
                    :invalid-feedback="feedback('pay_leave')"
                    :state="state('pay_leave')"
                  >
                    <b-input-group>
                      <b-form-input
                        id="pay_leave"
                        name="pay_leave"
                        type="number"
                        :placeholder="$t('validation.attributes.pay_leave')"
                        :disabled="!is_editable"
                        v-model="model.pay_leave"
                        :state="state('pay_leave')"
                      ></b-form-input>
                    </b-input-group>
                    <p
                      class="form-text text-muted pt-2"
                      v-html="
                        $t('labels.descriptions.allowed_pay_leave_values')
                      "
                    ></p>
                  </b-form-group>

                  <b-form-group
                    :label="$t('validation.attributes.signed_contract')"
                    label-for="basic_documents"
                    :label-cols="3"
                    :invalid-feedback="feedback('signed_contract')"
                    class="file-upload-group required"
                    v-if="!isNew"
                  >
                    <div class="media">
                      <div
                        v-if="model.signed_contract_path"
                        class="m-1 document_link"
                      >
                        <a :href="model.signed_contract_path" target="_blank">
                          {{ $t('validation.attributes.signed_contract') }}
                        </a>
                      </div>

                      <div class="media-body" v-else>
                        <b-form-file
                          id="basic_documents"
                          name="basic_documents"
                          :placeholder="$t('labels.no_file_chosen')"
                          v-model="model.signed_contract"
                          :state="state('signed_contract')"
                        ></b-form-file>
                        <p class="form-text text-muted pt-2">
                          {{
                            $t('labels.descriptions.allowed_documents_types')
                          }}
                        </p>
                      </div>
                    </div>
                  </b-form-group>
                </fieldset>
              </b-col>
              <b-col xl="6">
                <fieldset class="form-fieldset">
                  <h5 class="mb-5">
                    {{
                      $t(
                        'labels.backend.seafarer_contracts.titles.joining_info'
                      )
                    }}
                  </h5>

                  <b-form-group
                    :label="$t('validation.attributes.scheduled_sign_on_date')"
                    label-for="scheduled_sign_on_date"
                    :label-cols="3"
                    :invalid-feedback="feedback('scheduled_sign_on_date')"
                    :state="state('scheduled_sign_on_date')"
                  >
                    <b-input-group v-if="is_editable">
                      <p-datetimepicker
                        :config="config"
                        id="scheduled_sign_on_date"
                        name="scheduled_sign_on_date"
                        v-model="model.scheduled_sign_on_date"
                        :disabled="!is_editable"
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
                        {{ model.scheduled_sign_on_date }}
                      </b-badge>
                    </h3>
                  </b-form-group>

                  <b-form-group
                    :label="$t('validation.attributes.sign_on_port')"
                    label-for="sign_on_port"
                    :label-cols="3"
                    :invalid-feedback="feedback('sign_on_port')"
                    :state="state('sign_on_port')"
                  >
                    <multiselect
                      v-model="model.sign_on_port"
                      :options="portsOfRegistry"
                      id="port"
                      name="port"
                      track-by="id"
                      label="name"
                      :searchable="true"
                      :close-on-select="true"
                      :show-labels="false"
                      :disabled="!is_editable"
                      :placeholder="
                        '-- ' + $t('validation.attributes.sign_on_port') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </fieldset>
                <fieldset class="form-fieldset">
                  <h5 class="mb-5">
                    {{
                      $t(
                        'labels.backend.seafarer_contracts.titles.insurance_info'
                      )
                    }}
                  </h5>

                  <b-form-group
                    :label="$t('validation.attributes.insurance_type')"
                    label-for="insurance_type"
                    :label-cols="3"
                    :invalid-feedback="feedback('insurance_type')"
                    :state="state('insurance_type')"
                  >
                    <multiselect
                      v-model="model.insurance_type"
                      :options="insurance_type"
                      id="insurance_type"
                      @input="reset_medical_info"
                      name="insurance_type"
                      :searchable="false"
                      :close-on-select="true"
                      :show-labels="false"
                      :disabled="!is_editable"
                      :placeholder="
                        '-- ' +
                          $t('validation.attributes.insurance_type') +
                          ' --'
                      "
                    ></multiselect>
                  </b-form-group>

                  <template v-if="model.insurance_type == 'SPIPKA'">
                    <b-form-group
                      :label="$t('validation.attributes.insurance_company')"
                      label-for="insurance_company"
                      :label-cols="3"
                      :invalid-feedback="feedback('insurance_company')"
                    >
                      <b-form-input
                        id="insurance_company"
                        name="insurance_company"
                        :placeholder="
                          $t('validation.attributes.insurance_company')
                        "
                        v-model="model.insurance_company"
                        :disabled="!is_editable"
                        :state="state('insurance_company')"
                      ></b-form-input>
                    </b-form-group>

                    <b-form-group
                      :label="$t('validation.attributes.insurance_expiry_date')"
                      label-for="insurance_expiry_date"
                      :label-cols="3"
                      :invalid-feedback="feedback('insurance_expiry_date')"
                      :state="state('insurance_expiry_date')"
                    >
                      <b-input-group v-if="is_editable">
                        <p-datetimepicker
                          :config="config"
                          id="insurance_expiry_date"
                          name="insurance_expiry_date"
                          v-model="model.insurance_expiry_date"
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
                          {{ model.insurance_expiry_date }}
                        </b-badge>
                      </h3>
                    </b-form-group>
                  </template>
                  <b-form-group
                    :label="$t('validation.attributes.tested_by')"
                    label-for="tested_by"
                    :label-cols="3"
                    :invalid-feedback="feedback('tested_by')"
                    :state="state('tested_by')"
                  >
                    <b-form-radio-group
                      buttons
                      button-variant="outline-primary"
                      v-model="model.tested_by"
                      :options="medically_test_options"
                      name="tested_by"
                      :state="state('tested_by')"
                      v-if="is_editable"
                    ></b-form-radio-group>
                    <b-btn variant="primary" v-else-if="model.tested_by">
                      {{ model.tested_by }}
                    </b-btn>
                  </b-form-group>
                </fieldset>
                <fieldset>
                  <b-form-group
                    :label="$t('validation.attributes.remarks')"
                    label-for="remarks"
                    :label-cols="3"
                    :invalid-feedback="feedback('reason')"
                    :state="state('remarks')"
                  >
                    <b-form-textarea
                      id="remarks"
                      name="remarks"
                      :rows="3"
                      :placeholder="$t('validation.attributes.remarks')"
                      v-model="model.remarks"
                      :state="state('remarks')"
                      :disabled="!is_editable"
                    ></b-form-textarea>
                  </b-form-group>
                </fieldset>

                <fieldset class="form-fieldset" v-if="model.crew_request">
                  <h5 class="mb-5">
                    {{
                      $t(
                        'labels.backend.seafarer_contracts.titles.crew_request'
                      )
                    }}
                  </h5>

                  <p>
                    <router-link
                      :to="
                        `/Crew/seafarer_contracts/crew_requests/${model.crew_request.id}/edit`
                      "
                    >
                      #{{ model.crew_request.id }} ({{
                        model.crew_request.vessel_name
                      }}) @[{{ model.crew_request.created_at }}]
                    </router-link>
                  </p>
                </fieldset>
              </b-col>
            </b-row>
            <b-row slot="footer">
              <b-col md>
                <b-button
                  to="/Crew/seafarer_contracts/contracts"
                  variant="danger"
                  size="sm"
                >
                  {{ $t('buttons.back') }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  class="float-right ml-2"
                  :disabled="pending"
                  v-if="
                    (isNew && $app.user.can('create seafarer contracts')) ||
                      $app.user.can('edit seafarer contracts')
                  "
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                </b-button>
                <b-button
                  @click="onPreview"
                  variant="info"
                  size="sm"
                  class="float-right"
                  v-if="this.$app.user.can('preview seafarer contracts')"
                >
                  <font-awesome-icon icon="search"></font-awesome-icon>
                  {{ $t('buttons.preview') }}
                </b-button>
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
        </form>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import axios from 'axios'
import form from '../mixins/form'
import Multiselect from 'vue-multiselect'
import fileReader from '../lib/fileReader'

export default {
  name: 'SeafarerForm',
  components: { Multiselect },
  mixins: [form],
  props: {
    seafarerId: {
      type: [Number, String],
      required: false,
      default: 0
    }
  },
  data() {
    return {
      ranks: [],
      config: {
        wrap: true,
        allowInput: true
      },
      modelName: 'seafarer_contract',
      resourceRoute: 'seafarer_contracts',
      listPath: '/Crew/seafarer_contracts/contracts',
      vessels: [],
      portsOfRegistry: [],
      model: {
        vessel: null,
        seafarer: null,
        duration_of_service: null,
        duration_of_service_unit: 'Month',
        basic_salary: null,
        scheduled_sign_on_date: null,
        sign_on_port: null,
        pay_leave: null,
        signed_contract: null,
        signed_contract_path: null,
        tested_by: null,
        insurance_company: null,
        insurance_type: null,
        insurance_expiry_date: null,
        currency: 'MYR',
        pay_frequency: 'Monthly',
        status: 'pending',
        crew_request_id: null,
        crew_request: null,
        rank: null,
        remarks: null
      },
      currencies: ['MYR', 'USD'],
      pay_frequencies: ['Monthly', 'Daily'],
      medically_test_options: ['Vessel', 'Officer'],
      insurance_type: ['Marine P&I', 'Medical Group', 'SPIPKA']
    }
  },
  computed: {
    is_editable() {
      // return this.model.sign_on_date == null && this.$app.user.can('edit seafarer contracts')
      return this.model.status === 'pending'
    }
  },
  async created() {
    var { data } = await axios.get(this.$app.route(`vessels.limited_search`))
    this.vessels = data.data
    ;({ data } = await axios.get(this.$app.route(`ports.search`), {
      params: {
        page: 1,
        perPage: 1000,
        column: 'name',
        direction: 'asc'
      }
    }))
    this.portsOfRegistry = data
    this.portsOfRegistry = data.data.map(item => {
      return { id: item.id, name: item.name }
    })

    if (this.seafarerId !== 0) {
      ;({ data } = await axios.get(
        this.$app.route(`seafarers.show`, { id: this.seafarerId })
      ))
      if (data.personal_info.blacklisted) {
        this.$app.noty['error'](
          this.$t('exceptions.backend.seafarer_contracts.seafarer_blacklisted')
        )
        this.$router.push({ path: '/' })
      }
      this.model.seafarer = {
        id: data.personal_info.id,
        name: data.personal_info.full_name
      }
      this.model.rank = data.capabilities_info.rank
      if (this.isNew && data.last_contract) {
        this.model.basic_salary = data.last_contract.salary
        this.model.currency = data.last_contract.currency
        this.model.pay_frequency = data.last_contract.pay_frequency
      }
    }

    if (this.$route.query.vessel) {
      this.model.vessel = this.vessels.find(item => {
        return item.id === Number(this.$route.query.vessel)
      })
    }

    if (this.$route.query.sign_on_date) {
      this.model.scheduled_sign_on_date = this.$route.query.sign_on_date
    }

    if (this.$route.query.crew_request_id) {
      this.model.crew_request_id = this.$route.query.crew_request_id
    }

    ;({ data } = await axios.get(this.$app.route(`get_list_of_ranks`)))

    var ranks = []

    for (let department in data) {
      var dep = { department: department, ranks: [] }

      for (let rank in data[department]) {
        dep.ranks.push(data[department][rank])
      }
      ranks.push(dep)
    }
    this.ranks = ranks

    document.querySelectorAll('.flatpickr-calendar').forEach(calendar => {
      calendar.style.width = ''
    })
  },
  methods: {
    reset_medical_info() {
      this.model.insurance_expiry_date = null
      this.model.insurance_company = null
    },
    async onPreview() {
      if (!this.$refs.myform.checkValidity()) {
        this.$refs.myform.reportValidity()
        return
      }
      this.pending = true
      this.loading = true

      let formData = this.$app.objectToFormData(this.model)
      let url = null
      try {
        let { data } = await axios({
          url: this.$app.route('seafarer_contracts.preview'),
          responseType: 'blob',
          method: 'POST',
          data: formData
        })

        url = window.URL.createObjectURL(
          new Blob([data], { type: 'application/pdf' })
        )
        const link = document.createElement('a')
        link.href = url
        link.target = '_blank'
        // link.setAttribute('download', 'file1.pdf')
        document.body.appendChild(link)

        link.click()
        this.pending = false
        this.loading = false
      } catch (e) {
        this.pending = false
        this.loading = false
        // console.log(e.response.data)

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
.btn-group >>> .btn.active {
  z-index: 0 !important;
}

.is-invalid >>> .multiselect__tags {
  border-color: #f86c6b !important;
}

.is-invalid >>> .form-control {
  border-color: #f86c6b !important;
}
</style>
