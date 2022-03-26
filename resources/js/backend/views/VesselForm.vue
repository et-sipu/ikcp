<template>
  <div class="animated fadeIn mb-3">
    <model-modal
      componenet-name="VesselFormForm"
      :model-id="0"
      :componenet-props="{
        vesselId: id
      }"
      :show-modal.sync="showCreateModal"
      :reload-table="false"
      @form-submitted-successfully="onVesselFormCreated"
    ></model-modal>
    <b-tabs fill>
      <b-tab v-if="$app.user.can('view vessels')" lazy>
        <div slot="title">
          <h4 class="d-inline">
            {{
              isNew
                ? $t('labels.backend.vessels.titles.create')
                : $t('labels.backend.vessels.titles.edit')
            }}
          </h4>
          <b-badge
            variant="primary"
            class="mr-1"
            v-if="$app.user.can('edit vessels')"
          >
            {{ model.name }}
          </b-badge>
          <b-btn-group class="float-right">
            <b-btn
              v-b-toggle.history
              size="sm"
              variant="primary"
              v-b-tooltip.hover
              :title="$t('buttons.history')"
              v-if="!isNew && $app.user.can('view audits')"
            >
              <font-awesome-icon icon="history"></font-awesome-icon>
            </b-btn>
          </b-btn-group>
        </div>
        <form @submit.prevent="onSubmit">
          <b-card no-body>
            <b-row class="justify-content-center">
              <b-col xl="6">
                <fieldset class="form-fieldset">
                  <h5 class="mb-5">
                    {{ $t('labels.backend.vessels.titles.info') }}
                  </h5>
                  <b-form-group
                    :label="$t('validation.attributes.name')"
                    label-for="name"
                    :label-cols="3"
                    :invalid-feedback="feedback('name')"
                  >
                    <b-form-input
                      id="name"
                      name="name"
                      required
                      :placeholder="$t('validation.attributes.name')"
                      v-model="model.name"
                      :state="state('name')"
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group
                    :label="$t('validation.attributes.code')"
                    label-for="name"
                    :label-cols="3"
                    :invalid-feedback="feedback('code')"
                  >
                    <b-form-input
                      id="code"
                      name="code"
                      required
                      :placeholder="$t('validation.attributes.code')"
                      v-model="model.code"
                      :state="state('code')"
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group
                    :label="$t('validation.attributes.imo_number')"
                    label-for="name"
                    :label-cols="3"
                    :invalid-feedback="feedback('imo_number')"
                  >
                    <b-form-input
                      id="imo_number"
                      name="imo_number"
                      required
                      :placeholder="$t('validation.attributes.imo_number')"
                      v-model="model.imo_number"
                      :state="state('imo_number')"
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group
                    :label="$t('validation.attributes.official_number')"
                    label-for="official_number"
                    :label-cols="3"
                    :invalid-feedback="feedback('call_sign')"
                  >
                    <b-form-input
                      id="official_number"
                      name="official_number"
                      :placeholder="$t('validation.attributes.official_number')"
                      v-model="model.official_number"
                      :state="state('official_number')"
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group
                    :label="$t('validation.attributes.call_sign')"
                    label-for="call_sign"
                    :label-cols="3"
                    :invalid-feedback="feedback('call_sign')"
                  >
                    <b-form-input
                      id="call_sign"
                      name="call_sign"
                      :placeholder="$t('validation.attributes.call_sign')"
                      v-model="model.call_sign"
                      :state="state('call_sign')"
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group
                    :label="$t('validation.attributes.port_of_registry')"
                    label-for="port_of_registry"
                    :label-cols="3"
                    :invalid-feedback="feedback('port_of_registry')"
                    :state="state('port_of_registry')"
                  >
                    <multiselect
                      v-model="model.port_of_registry"
                      :options="portsOfRegistry"
                      id="port_of_registry"
                      name="port_of_registry"
                      track-by="id"
                      label="name"
                      :searchable="true"
                      :close-on-select="true"
                      :show-labels="false"
                      :placeholder="
                        '-- ' +
                          $t('validation.attributes.port_of_registry') +
                          ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                  <b-form-group
                    :label="$t('validation.attributes.piloted_by')"
                    label-for="piloted_by"
                    :label-cols="3"
                    :invalid-feedback="feedback('piloted_by')"
                    :state="state('piloted_by')"
                  >
                    <multiselect
                      v-model="model.pilot"
                      :options="masters"
                      id="piloted_by"
                      name="piloted_by"
                      track-by="id"
                      label="name"
                      :searchable="true"
                      :close-on-select="true"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.piloted_by') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>

                  <b-form-group
                    :label="$t('validation.attributes.flag')"
                    label-for="name"
                    :label-cols="3"
                    :invalid-feedback="feedback('flag')"
                  >
                    <b-form-input
                      id="flag"
                      name="flag"
                      required
                      :placeholder="$t('validation.attributes.flag')"
                      v-model="model.flag"
                      :state="state('flag')"
                    ></b-form-input>
                  </b-form-group>
                  <photo
                    v-model="model.stamp"
                    :label="$t('validation.attributes.stamp')"
                    field-name="stamp"
                    :image-path="model.stamp_path"
                    :editable="false"
                    :errors="
                      validation && validation.errors ? validation.errors : {}
                    "
                  ></photo>
                  <b-form-group
                    :label="$t('validation.attributes.vessel_specs')"
                    label-for="basic_documents"
                    :label-cols="3"
                    :invalid-feedback="feedback('signed_contract')"
                    class="file-upload-group required"
                    v-if="!isNew"
                  >
                    <div class="media">
                      <div
                        v-if="model.vessel_specs_path"
                        class="m-1 document_link"
                      >
                        <a :href="model.vessel_specs_path" target="_blank">
                          {{ $t('validation.attributes.vessel_specs') }}
                        </a>
                      </div>

                      <div class="media-body" v-else>
                        <b-form-file
                          id="basic_documents"
                          name="basic_documents"
                          :placeholder="$t('labels.no_file_chosen')"
                          v-model="model.vessel_specs"
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

                  <p>
                    <a
                      href="https://marine45.marine.gov.my/public/ENPage2a.aspx"
                      target="_blank"
                    >
                      Seafarer Documentation System
                    </a>
                  </p>
                </fieldset>
              </b-col>
              <b-col xl="6">
                <fieldset class="form-fieldset">
                  <h5 class="mb-5">
                    {{ $t('labels.backend.vessels.titles.port_clearance') }}
                  </h5>

                  <template
                    v-for="(port_clearance, index) in model.port_clearances"
                  >
                    <port-clearance
                      :disabled="false"
                      v-model="model.port_clearances[index]"
                      :index="index"
                      :ref="'port_clearance-' + index"
                      :key="index"
                      v-if="
                        port_clearance.id === 0 || port_clearance.url !== null
                      "
                      @deleted="
                        dropFile(
                          'port_clearance-' + index,
                          port_clearance,
                          'port_clearances',
                          index
                        )
                      "
                      :errors="
                        validation && validation.errors ? validation.errors : {}
                      "
                      comp-name="port_clearance"
                    ></port-clearance>
                  </template>

                  <div class="float-right">
                    <b-button
                      size="sm"
                      variant="primary"
                      v-b-tooltip.hover
                      :title="$t('buttons.vessels.add_port_clearance')"
                      @click="addPortClearance()"
                    >
                      <i class="fe fe-plus-circle"></i>
                    </b-button>
                  </div>
                </fieldset>
                <fieldset class="form-fieldset">
                  <h5 class="mb-5">
                    {{ $t('labels.backend.vessels.titles.GA_Plans') }}
                  </h5>
                  <template>
                    <attachments v-model="model.GA_Plans"></attachments>
                  </template>
                </fieldset>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button to="/VM/vessels" variant="danger" size="sm">
                  {{ $t('buttons.back') }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  class="float-right"
                  :disabled="pending"
                  v-if="isNew || model.can_edit"
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
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
      </b-tab>
      <b-tab v-if="$app.user.can('view vessel certificates')" lazy>
        <div slot="title">
          <h4 class="d-inline">
            {{ $t('labels.backend.vessels.titles.certificates') }}
          </h4>
          <b-badge
            variant="primary"
            class="mr-1"
            v-if="!$app.user.can('edit vessels')"
          >
            {{ model.name }}
          </b-badge>
          <b-btn-group class="float-right">
            <b-btn
              size="sm"
              @click="gothere(id)"
              v-b-tooltip.hover
              :title="$t('buttons.vessels.export_certificates')"
              v-if="
                !isNew &&
                  ($app.user.can('view vessels') ||
                    $app.user.can('view vessel certificates'))
              "
            >
              <font-awesome-icon icon="file-download"></font-awesome-icon>
            </b-btn>
          </b-btn-group>
        </div>
        <form @submit.prevent="onSubmit">
          <b-card no-body>
            <div class="row">
              <div class="col-6">
                <fieldset class="form-fieldset">
                  <h5 class="mb-5">
                    {{
                      $t('labels.backend.vessels.titles.in_vessel_insurance')
                    }}
                  </h5>
                  <VesselInsuranceList
                    :extra-search-criteria="{ vessel_id: id }"
                    :for-include="true"
                  ></VesselInsuranceList>
                </fieldset>
                <fieldset class="form-fieldset">
                  <h5 class="mb-5">
                    {{
                      $t('labels.backend.vessels.titles.certificates_summary')
                    }}
                  </h5>
                  <template
                    v-for="(certificates_summary,
                    index) in model.certificates_summaries"
                  >
                    <certificates-summary
                      :disabled="false"
                      v-model="model.certificates_summaries[index]"
                      :index="index"
                      :ref="'certificates_summary-' + index"
                      :key="index"
                      v-if="
                        certificates_summary.id === 0 ||
                          certificates_summary.url !== null
                      "
                      @deleted="
                        dropCertificates(
                          'certificates_summary-' + index,
                          certificates_summary,
                          'certificates_summaries',
                          index
                        )
                      "
                      comp-name="certificates_summary"
                      :errors="
                        validation && validation.errors ? validation.errors : {}
                      "
                      :disabled-cert="
                        !$app.user.can('edit vessel certificates')
                      "
                      :deletable="!$app.user.can('delete vessel certificates')"
                    ></certificates-summary>
                  </template>

                  <div class="float-right">
                    <b-button
                      v-if="$app.user.can('create vessel certificates')"
                      size="sm"
                      variant="primary"
                      v-b-tooltip.hover
                      :title="$t('buttons.vessels.add_certificate_summary')"
                      @click="addCertificatesSummary()"
                    >
                      <i class="fe fe-plus-circle"></i>
                    </b-button>
                  </div>
                </fieldset>
              </div>
              <div class="col-6">
                <fieldset class="form-fieldset">
                  <h5 class="mb-5">
                    {{ $t('labels.backend.vessels.titles.certificates') }}
                  </h5>

                  <certificate
                    v-for="(certificate, index) in model.certificates"
                    :key="index"
                    v-model="model.certificates[index]"
                    :index="index"
                    :certificate-types="vesselCertificateTypes"
                    @deleted="dropCertificate($event)"
                    :errors="
                      validation && validation.errors ? validation.errors : {}
                    "
                    :disabled="!$app.user.can('edit vessel certificates')"
                    :deletable="!$app.user.can('delete vessel certificates')"
                  ></certificate>

                  <div class="float-right">
                    <b-button
                      v-if="$app.user.can('create vessel certificates')"
                      size="sm"
                      variant="primary"
                      v-b-tooltip.hover
                      :title="$t('buttons.vessels.add_certificate')"
                      @click="addCertificate()"
                    >
                      <i class="fe fe-plus-circle"></i>
                    </b-button>
                  </div>
                </fieldset>
              </div>
            </div>
            <b-row slot="footer">
              <b-col md>
                <b-button to="/VM/vessels" variant="danger" size="sm">
                  {{ $t('buttons.back') }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  class="float-right"
                  :disabled="pending"
                  v-if="$app.user.can('edit vessel certificates')"
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
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
      </b-tab>
      <b-tab v-if="$app.user.can('view vessel forms')" lazy>
        <div slot="title">
          <h4 class="d-inline">
            {{ $t('labels.backend.vessels.titles.reports') }}
          </h4>
          <b-badge
            variant="primary"
            class="mr-1"
            v-if="
              !$app.user.can('edit vessels') &&
                !$app.user.can('view vessel certificates')
            "
          >
            {{ model.name }}
          </b-badge>
          <b-btn-group class="float-right">
            <b-btn
              variant="primary"
              v-b-tooltip.hover
              :title="$t('buttons.doc_templates.create')"
              size="sm"
              @click="showCreateModal = !showCreateModal"
              v-if="$app.user.can('create vessel forms')"
            >
              <i class="fe fe-plus-circle"></i>
            </b-btn>
            <b-btn
              v-b-toggle.filter
              variant="secondary"
              size="sm"
              v-b-tooltip.hover
              :title="$t('buttons.show_hide_filters')"
            >
              <font-awesome-icon icon="filter"></font-awesome-icon>
            </b-btn>
          </b-btn-group>
        </div>
        <VesselFormList ref="vessel_form_list" :vessel-id="id"></VesselFormList>
      </b-tab>
    </b-tabs>
    <b-collapse id="history" @shown="goDown" class="mt-2">
      <history
        ref="history"
        model-name="Vessel"
        :model-id="id"
        v-if="getHis"
      ></history>
    </b-collapse>
  </div>
</template>

<script>
import axios from 'axios'
import form from '../mixins/form'
import Multiselect from 'vue-multiselect'
import Photo from './components/Photo'
import Attachments from './components/Attachments'
import Certificate from './components/VesselCertificate'
import History from './components/History'
import PortClearance from './components/PortClearance'
import CertificatesSummary from './components/CertificatesSummary'
import VesselFormList from './VesselFormList'
import ModelModal from './components/ModelModal'
import VesselInsuranceList from './VesselInsuranceList'

export default {
  name: 'VesselForm',
  components: {
    VesselInsuranceList,
    ModelModal,
    VesselFormList,
    Attachments,
    CertificatesSummary,
    PortClearance,
    History,
    Multiselect,
    Photo,
    Certificate
  },
  mixins: [form],
  data() {
    return {
      getHis: false,
      portsOfRegistry: [],
      vesselCertificateTypes: [],
      masters: [],
      modelName: 'vessel',
      resourceRoute: 'vessels',
      listPath: '/VM/vessels',
      model: {
        name: null,
        code: null,
        imo_number: null,
        official_number: null,
        call_sign: null,
        port_of_registry: null,
        pilot: null,
        flag: null,
        stamp_path: null,
        stamp: null,
        vessel_specs: null,
        vessel_specs_path: null,
        certificates: [],
        GA_Plans: [],
        port_clearances: [],
        certificates_summaries: [],
        can_edit: false
      },
      showCreateModal: false
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`ports.search`), {
      params: {
        page: 1,
        perPage: 1000,
        column: 'name',
        direction: 'asc'
      }
    })
    // this.portsOfRegistry = data
    this.portsOfRegistry = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
    ;({ data } = await axios.get(
      this.$app.route(`get_list_of_constants`, {
        constant: 'vessel_certificate_types'
      })
    ))
    this.vesselCertificateTypes = data
    ;({ data } = await axios.get(this.$app.route(`get_list_of_masters`)))
    this.masters = data
  },
  methods: {
    addPortClearance() {
      this.model.port_clearances.push({
        id: 0,
        departure_port: null,
        departure_date: null,
        url: null,
        file: null
      })
    },
    addCertificatesSummary() {
      this.model.certificates_summaries.push({
        id: 0,
        quarter_date: null,
        url: null,
        file: null
      })
    },
    dropFile(ref, file, collection = 'attachments', index = 0) {
      this.$refs['port_clearance-' + index][0].$refs[ref].reset()
      if (file.id === 0 && file.url == null && file.file === null) {
        this.model[collection].splice(index, 1)
      }
      file.url = null
    },
    dropCertificates(ref, file, collection = 'attachments', index = 0) {
      this.$refs['certificates_summary-' + index][0].$refs[ref].reset()
      if (file.id === 0 && file.url == null && file.file === null) {
        this.model[collection].splice(index, 1)
      }
      file.url = null
    },
    goDown() {
      this.getHis = true
      setTimeout(function() {
        let elmnt = document.getElementById('history')
        elmnt.scrollIntoView()
      }, 500)
    },
    gothere(id) {
      window.location = this.$app.route('vessels.export_certificates', {
        vessel: id
      })
      // return this.$app.route('vessels.export_certificates', { vessel: id })
    },

    addCertificate() {
      this.model.certificates.push({
        id: null,
        type: null,
        number: null,
        remarks: null,
        expiry: null,
        url: null,
        file: null
      })
    },
    dropCertificate(event) {
      this.model.certificates.splice(event.index, 1)
    },
    afterSave() {
      if (this.isNew) return false
      this.fetchData()
      return true
    },
    onVesselFormCreated() {
      this.$refs.vessel_form_list.onContextChangedWithPage()
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
.record_link {
  width: 30%;
}
.record {
  background-color: #f0fffd;
  padding-top: 8px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 3px;
  border: 1px solid #e9ecef;
}
.record:hover {
  background-color: #e4fffd;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
}
.close {
  position: absolute;
  right: 0px;
  top: 0px;
}
</style>
