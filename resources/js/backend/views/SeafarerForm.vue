<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-card>
        <template slot="header">
          <h4 slot="header">
            {{
              isNew
                ? $t('labels.backend.seafarers.titles.create')
                : $t('labels.backend.seafarers.titles.edit')
            }}
          </h4>
          <div
            class="card-options"
            v-if="
              !isNew &&
                model.personal_info.name &&
                $app.user.can('create seafarer contracts')
            "
          >
            <!--<b-button variant="info" class="mr-2" :to="`/seafarer_contracts/contracts/${model.last_contract.id}/edit`" target="_blank" v-if="model.last_contract">-->
            <!--{{ $t('labels.backend.seafarers.titles.last_drawn_salary') }}-->
            <!--<b-badge style="font-size: 14px" pill variant="light">-->
            <!--{{ model.last_contract.last_salary }}-->
            <!--</b-badge>-->
            <!--</b-button>-->
            <b-button
              v-b-toggle.history
              size="sm"
              variant="outline-primary"
              v-b-tooltip.hover
              :title="$t('buttons.history')"
              class="mr-3"
              v-if="!isNew && $app.user.can('view audits')"
            >
              <font-awesome-icon icon="history"></font-awesome-icon>
            </b-button>
            <b-button
              v-if="
                $app.user.can('blacklist seafarers') &&
                  (!model.personal_info.blacklisted ||
                    model.personal_info.blacklisted.length == 0)
              "
              size="sm"
              variant="danger"
              v-b-tooltip.hover
              :title="$t('buttons.seafarers.blacklisted')"
              class="mr-3"
              @click.stop="onBlacklisted"
            >
              <font-awesome-icon icon="ban"></font-awesome-icon>
            </b-button>
            <b-button
              v-else-if="
                $app.user.can('whitelist seafarers') &&
                  (model.personal_info.blacklisted &&
                    model.personal_info.blacklisted.length !== 0)
              "
              size="sm"
              variant="danger"
              v-b-tooltip.hover
              :title="$t('buttons.seafarers.whitelist')"
              class="mr-3"
              @click.stop="onWhitelisted"
            >
              <font-awesome-icon icon="lock-open"></font-awesome-icon>
            </b-button>
            <b-button
              size="sm"
              variant="secondary"
              :to="`/Crew/seafarers/${id}/create_contract`"
              v-b-tooltip.hover
              :title="$t('buttons.seafarer_contracts.create')"
              v-if="
                !isNew &&
                  $app.user.can('create seafarer contracts') &&
                  (!model.personal_info.blacklisted ||
                    model.personal_info.blacklisted.length == 0)
              "
            >
              <font-awesome-icon icon="file-signature"></font-awesome-icon>
            </b-button>
          </div>
        </template>
        <b-row class="justify-content-center">
          <b-col xl="6">
            <fieldset class="form-fieldset">
              <h5 class="mb-5">
                {{ $t('labels.backend.seafarers.titles.personal_info') }}
              </h5>

              <photo
                v-model="model.photo"
                :label="$t('validation.attributes.personal_info.photo')"
                field-name="photo"
                :image-path="model.personal_info.photo_path"
              ></photo>

              <photo
                v-model="model.signature"
                :label="$t('validation.attributes.personal_info.signature')"
                field-name="signature"
                :image-path="model.personal_info.signature_path"
              ></photo>

              <b-form-group
                :label="$t('validation.attributes.personal_info.name')"
                label-for="name"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.name')"
              >
                <b-form-input
                  id="name"
                  name="name"
                  :placeholder="$t('validation.attributes.personal_info.name')"
                  v-model="model.personal_info.name"
                  :state="state('personal_info.name')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.personal_info.surname')"
                label-for="surname"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.surname')"
              >
                <b-form-input
                  id="surname"
                  name="surname"
                  :placeholder="
                    $t('validation.attributes.personal_info.surname')
                  "
                  v-model="model.personal_info.surname"
                  :state="state('personal_info.surname')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.personal_info.sex')"
                label-for="sex"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.sex')"
                :state="state('personal_info.sex')"
              >
                <b-form-radio-group
                  v-model="model.personal_info.sex"
                  :state="state('personal_info.sex')"
                  :options="genders"
                  name="sex"
                >
                </b-form-radio-group>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.personal_info.religion')"
                label-for="religion"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.religion')"
                :state="state('personal_info.religion')"
              >
                <multiselect
                  v-model="model.personal_info.religion"
                  :options="religions"
                  id="religion"
                  name="religion"
                  :searchable="false"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' +
                      $t('validation.attributes.personal_info.religion') +
                      ' --'
                  "
                ></multiselect>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.personal_info.nationality')"
                label-for="nationality"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.nationality')"
                :state="state('personal_info.nationality')"
              >
                <multiselect
                  v-model="model.personal_info.nationality"
                  :options="nationalities"
                  id="nationality"
                  name="nationality"
                  @input="reset_nric"
                  label="nationality"
                  track-by="id"
                  :searchable="true"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' +
                      $t('validation.attributes.personal_info.nationality') +
                      ' --'
                  "
                ></multiselect>
              </b-form-group>

              <b-form-group
                v-if="
                  model.personal_info.nationality !== null &&
                    model.personal_info.nationality.id == 'MAS'
                "
                :label="$t('validation.attributes.personal_info.nric_no')"
                label-for="nric_no"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.nric_no')"
                v-mask="'######-##-####'"
              >
                <b-form-input
                  id="nric_no"
                  name="nric_no"
                  :placeholder="
                    $t('validation.attributes.personal_info.nric_no')
                  "
                  v-model="model.personal_info.nric_no"
                  :state="state('personal_info.nric_no')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.personal_info.address')"
                label-for="address"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.address')"
              >
                <b-form-textarea
                  id="address"
                  name="address"
                  :rows="3"
                  :placeholder="
                    $t('validation.attributes.personal_info.address')
                  "
                  v-model="model.personal_info.address"
                  :state="state('personal_info.address')"
                ></b-form-textarea>
              </b-form-group>

              <b-form-group
                :label="
                  $t('validation.attributes.personal_info.place_of_birth')
                "
                label-for="place_of_birth"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.place_of_birth')"
              >
                <b-form-input
                  id="place_of_birth"
                  name="place_of_birth"
                  :placeholder="
                    $t('validation.attributes.personal_info.place_of_birth')
                  "
                  v-model="model.personal_info.place_of_birth"
                  :state="state('personal_info.place_of_birth')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.personal_info.date_of_birth')"
                label-for="date_of_birth"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.date_of_birth')"
                :state="state('personal_info.date_of_birth')"
              >
                <b-input-group>
                  <p-datetimepicker
                    :config="config"
                    id="date_of_birth"
                    name="date_of_birth"
                    v-model="model.personal_info.date_of_birth"
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

              <b-form-group
                :label="$t('validation.attributes.personal_info.date_of_join')"
                label-for="date_of_join"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.date_of_join')"
                :state="state('personal_info.date_of_join')"
              >
                <b-input-group>
                  <p-datetimepicker
                    :config="config"
                    id="date_of_join"
                    name="date_of_join"
                    v-model="model.personal_info.date_of_join"
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

              <employee-document
                document-type="PASSPORT"
                v-model="model.documents_info.PASSPORT"
              ></employee-document>

              <employee-document
                document-type="WORK_PERMIT"
                v-model="model.documents_info.WORK_PERMIT"
                :with_no="false"
              ></employee-document>
            </fieldset>

            <fieldset class="form-fieldset">
              <h5>{{ $t('labels.backend.seafarers.titles.contact_info') }}</h5>

              <b-form-group
                :label="$t('validation.attributes.contact_info.personal_hp')"
                label-for="personal_hp"
                :label-cols="3"
                :invalid-feedback="feedback('contact_info.personal_hp')"
              >
                <b-form-input
                  id="personal_hp"
                  name="personal_hp"
                  :placeholder="
                    $t('validation.attributes.contact_info.personal_hp')
                  "
                  v-model="model.contact_info.personal_hp"
                  :state="state('contact_info.personal_hp')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="
                  $t('validation.attributes.contact_info.next_of_kin_name')
                "
                label-for="next_of_kin_name"
                :label-cols="3"
                :invalid-feedback="feedback('contact_info.next_of_kin_name')"
              >
                <b-form-input
                  id="next_of_kin_name"
                  name="next_of_kin_name"
                  :placeholder="
                    $t('validation.attributes.contact_info.next_of_kin_name')
                  "
                  v-model="model.contact_info.next_of_kin_name"
                  :state="state('contact_info.next_of_kin_name')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.contact_info.next_of_kin_hp')"
                label-for="next_of_kin_hp"
                :label-cols="3"
                :invalid-feedback="feedback('contact_info.next_of_kin_hp')"
              >
                <b-form-input
                  id="next_of_kin_hp"
                  name="next_of_kin_hp"
                  :placeholder="
                    $t('validation.attributes.contact_info.next_of_kin_hp')
                  "
                  v-model="model.contact_info.next_of_kin_hp"
                  :state="state('contact_info.next_of_kin_hp')"
                ></b-form-input>
              </b-form-group>
            </fieldset>
          </b-col>
          <b-col xl="6">
            <fieldset class="form-fieldset">
              <h5>
                {{ $t('labels.backend.seafarers.titles.capabilities_info') }}
              </h5>

              <b-form-group
                :label="$t('validation.attributes.capabilities_info.rank')"
                label-for="rank"
                :label-cols="3"
                :invalid-feedback="feedback('capabilities_info.rank')"
                :state="state('capabilities_info.rank')"
              >
                <multiselect
                  v-model="model.capabilities_info.rank"
                  :options="ranks"
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
                :label="
                  $t(
                    'validation.attributes.capabilities_info.coc_certificate_type'
                  )
                "
                label-for="coc_certificate_type"
                :label-cols="3"
                :invalid-feedback="
                  feedback('capabilities_info.coc_certificate_type')
                "
                :state="state('capabilities_info.coc_certificate_type')"
              >
                <multiselect
                  v-model="model.capabilities_info.coc_certificate_type"
                  :options="coc_certificate_types"
                  @input="check_coc_availability"
                  :searchable="true"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' +
                      $t(
                        'validation.attributes.capabilities_info.coc_certificate_type'
                      ) +
                      ' --'
                  "
                ></multiselect>
              </b-form-group>

              <template
                v-if="
                  model.capabilities_info.coc_certificate_type != null &&
                    model.capabilities_info.coc_certificate_type !== ''
                "
              >
                <b-form-group
                  :label="
                    $t(
                      'validation.attributes.capabilities_info.coc_certificate_class'
                    )
                  "
                  label-for="coc_certificate_class"
                  :label-cols="3"
                  :invalid-feedback="
                    feedback('capabilities_info.coc_certificate_class')
                  "
                  :state="state('capabilities_info.coc_certificate_class')"
                >
                  <b-form-radio-group
                    buttons
                    button-variant="outline-primary"
                    v-model="model.capabilities_info.coc_certificate_class"
                    :options="coc_classes"
                    name="coc_certificate_class"
                    :state="state('capabilities_info.coc_certificate_class')"
                  ></b-form-radio-group>
                </b-form-group>

                <employee-document
                  document-type="COC_CERT"
                  v-model="model.documents_info.COC_CERT"
                ></employee-document>
              </template>

              <b-form-group
                :label="
                  $t('validation.attributes.capabilities_info.security_course')
                "
                label-for="security_course"
                :label-cols="3"
                :invalid-feedback="
                  feedback('capabilities_info.security_course')
                "
                :state="state('capabilities_info.security_course')"
              >
                <multiselect
                  v-model="model.capabilities_info.security_course"
                  :options="security_course_options"
                  :multiple="true"
                  :searchable="false"
                  :close-on-select="false"
                  :show-labels="false"
                  :placeholder="
                    '-- ' +
                      $t(
                        'validation.attributes.capabilities_info.security_course'
                      ) +
                      ' --'
                  "
                ></multiselect>
              </b-form-group>

              <b-form-group
                :label="
                  $t('validation.attributes.capabilities_info.other_courses')
                "
                label-for="other_courses"
                :label-cols="3"
                :invalid-feedback="feedback('capabilities_info.other_courses')"
                :state="state('capabilities_info.other_courses')"
              >
                <multiselect
                  v-model="model.capabilities_info.other_courses"
                  :options="other_courses_options"
                  :multiple="true"
                  :searchable="false"
                  :close-on-select="false"
                  :show-labels="false"
                  :placeholder="
                    '-- ' +
                      $t(
                        'validation.attributes.capabilities_info.other_courses'
                      ) +
                      ' --'
                  "
                ></multiselect>
              </b-form-group>

              <employee-document
                document-type="SMC"
                v-model="model.documents_info.SMC"
              ></employee-document>

              <employee-document
                document-type="DISCHARGE_BOOK"
                v-model="model.documents_info.DISCHARGE_BOOK"
                :with_expiry="false"
              ></employee-document>

              <employee-document
                document-type="GOC_GMDSS"
                v-model="model.documents_info.GOC_GMDSS"
              ></employee-document>

              <employee-document
                document-type="MG_COR"
                v-model="model.documents_info.MG_COR"
              ></employee-document>
              <employee-document
                document-type="COE"
                v-model="model.documents_info.COE"
              ></employee-document>
              <employee-document
                document-type="MY_COR"
                v-model="model.documents_info.MY_COR"
              ></employee-document>
            </fieldset>

            <fieldset class="form-fieldset">
              <h5>
                {{ $t('labels.backend.seafarers.titles.financial_info') }}
              </h5>

              <b-form-group
                :label="
                  $t('validation.attributes.financial_info.payment_method')
                "
                label-for="payment_method"
                :label-cols="3"
                :invalid-feedback="feedback('financial_info.payment_method')"
                :state="state('financial_info.payment_method')"
              >
                <b-form-radio-group
                  buttons
                  button-variant="outline-primary"
                  v-model="model.financial_info.payment_method"
                  :options="payment_methods"
                  name="payment_method"
                  :state="state('financial_info.payment_metho')"
                  @input="check_financial_data"
                ></b-form-radio-group>
              </b-form-group>

              <template v-if="model.financial_info.payment_method != 'Cash'">
                <b-form-group
                  :label="$t('validation.attributes.financial_info.bank')"
                  label-for="bank"
                  :label-cols="3"
                  :invalid-feedback="feedback('financial_info.bank')"
                >
                  <b-form-input
                    id="bank"
                    name="bank"
                    :placeholder="
                      $t('validation.attributes.financial_info.bank')
                    "
                    v-model="model.financial_info.bank"
                    :state="state('financial_info.bank')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.financial_info.account_no')"
                  label-for="account_no"
                  :label-cols="3"
                  :invalid-feedback="feedback('financial_info.account_no')"
                >
                  <b-form-input
                    id="account_no"
                    name="account_no"
                    :placeholder="
                      $t('validation.attributes.financial_info.account_no')
                    "
                    v-model="model.financial_info.account_no"
                    :state="state('financial_info.account_no')"
                  ></b-form-input>
                </b-form-group>

                <template
                  v-if="model.financial_info.payment_method == 'Home Allotment'"
                >
                  <b-form-group
                    :label="
                      $t('validation.attributes.financial_info.swift_code')
                    "
                    label-for="swift_code"
                    :label-cols="3"
                    :invalid-feedback="feedback('financial_info.swift_code')"
                  >
                    <b-form-input
                      id="swift_code"
                      name="swift_code"
                      :placeholder="
                        $t('validation.attributes.financial_info.swift_code')
                      "
                      v-model="model.financial_info.swift_code"
                      :state="state('financial_info.swift_code')"
                    ></b-form-input>
                  </b-form-group>

                  <b-form-group
                    :label="
                      $t(
                        'validation.attributes.financial_info.country_of_origin'
                      )
                    "
                    label-for="country_of_origin"
                    :label-cols="3"
                    :invalid-feedback="
                      feedback('financial_info.country_of_origin')
                    "
                  >
                    <b-form-input
                      id="country_of_origin"
                      name="country_of_origin"
                      :placeholder="
                        $t(
                          'validation.attributes.financial_info.country_of_origin'
                        )
                      "
                      v-model="model.financial_info.country_of_origin"
                      :state="state('financial_info.country_of_origin')"
                    ></b-form-input>
                  </b-form-group>
                </template>
              </template>
            </fieldset>

            <fieldset class="form-fieldset">
              <h5>{{ $t('labels.backend.seafarers.titles.medical_info') }}</h5>

              <employee-document
                document-type="MEDICAL_CERT"
                v-model="model.documents_info.MEDICAL_CERT"
              ></employee-document>
            </fieldset>
          </b-col>
        </b-row>
        <b-row slot="footer">
          <b-col md>
            <b-button to="/Crew/seafarers" variant="danger" size="sm">
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
    <comments
      ref="comments"
      model-name="Seafarer"
      :model-id="id"
      v-if="!isNew"
    ></comments>
    <b-collapse id="history" @shown="goDown">
      <history
        ref="history"
        model-name="Seafarer"
        :model-id="id"
        v-if="getHis"
      ></history>
    </b-collapse>
  </div>
</template>

<script>
import axios from 'axios'
import form from '../mixins/form'
import { mask } from 'vue-the-mask'
import Multiselect from 'vue-multiselect'
import EmployeeDocument from './components/EmployeeDocument'
import Comments from './components/Comments'
import Photo from './components/Photo'
import History from './components/History'

export default {
  name: 'SeafarerForm',
  components: { History, EmployeeDocument, Multiselect, Comments, Photo },
  directives: { mask },
  mixins: [form],
  data() {
    return {
      getHis: false,
      config: {
        wrap: true,
        allowInput: true
      },
      religions: [],
      nationalities: [],
      ranks: [],
      genders: {},
      payment_methods: ['Cash', 'Bank', 'Home Allotment'],
      coc_certificate_types: [
        'C/E Officer of 3000kW or more UL',
        'Engineering watch of 750kW or NC',
        'C/E Officer of 3000kW or more NC',
        'C/E OFFICER CLASS 1',
        'WKO OF 500GT OR MORE NC',
        'WKO OF 500GT OR MORE FG',
        'WKE 750KW UL',
        'CHIEF MATE of 3000 GT or MORE',
        'MASTER OF 3000GT OR MORE UL',
        'ASE',
        'ASD'
      ],
      coc_classes: ['FG', 'NC', 'WKR'],
      security_course_options: ['DSD', 'SSA', 'SSO'],
      other_courses_options: [
        'BST',
        'TANK FAM',
        'AFF',
        'MFA',
        'SUR CRAFT',
        'Food Handling'
      ],
      modelName: 'seafarer',
      resourceRoute: 'seafarers',
      listPath: '/Crew/seafarers',
      model: {
        personal_info: {
          name: null,
          surname: null,
          sex: null,
          religion: null,
          nationality: null,
          address: null,
          nric_no: null,
          date_of_join: null,
          date_of_birth: null,
          place_of_birth: null,
          photo_path: null,
          signature_path: null
        },
        contact_info: {
          personal_hp: null,
          next_of_kin_name: null,
          next_of_kin_hp: null
        },
        financial_info: {
          payment_method: 'Cash',
          bank: null,
          account_no: null,
          swift_code: null,
          country_of_origin: null
        },
        capabilities_info: {
          rank: null,
          coc_certificate_type: null,
          coc_certificate_class: null,
          security_course: null,
          other_courses: null
        },
        documents_info: {
          PASSPORT: {
            id: null,
            no: null,
            expiry: null,
            file: null,
            url: null
          },
          WORK_PERMIT: {
            id: null,
            no: null,
            expiry: null,
            file: null,
            url: null
          },
          MEDICAL_CERT: {
            id: null,
            no: null,
            expiry: null,
            file: null,
            url: null
          },
          COC_CERT: {
            id: null,
            no: null,
            expiry: null,
            file: null,
            url: null
          },
          SMC: {
            id: null,
            no: null,
            expiry: null,
            file: null,
            url: null
          },
          DISCHARGE_BOOK: {
            id: null,
            no: null,
            expiry: null,
            file: null,
            url: null
          },
          GOC_GMDSS: {
            id: null,
            no: null,
            expiry: null,
            file: null,
            url: null
          },
          MG_COR: {
            id: null,
            no: null,
            expiry: null,
            file: null,
            url: null
          },
          COE: {
            id: null,
            no: null,
            expiry: null,
            file: null,
            url: null
          },
          MY_COR: {
            id: null,
            no: null,
            expiry: null,
            file: null,
            url: null
          }
        },
        photo: null,
        signature: null,
        last_contract: null,
        can_edit: false
      }
    }
  },
  async created() {
    // this.fetchData()
    var { data } = await axios.get(
      this.$app.route(`get_list_of_constants`, { constant: 'nationalities' })
    )
    this.nationalities = Object.values(data)
    ;({ data } = await axios.get(
      this.$app.route(`get_list_of_constants`, { constant: 'religions' })
    ))
    this.religions = data
    ;({ data } = await axios.get(
      this.$app.route(`get_list_of_constants`, { constant: 'genders' })
    ))
    this.genders = data
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
    goDown() {
      this.getHis = true
      setTimeout(function() {
        let elmnt = document.getElementById('history')
        elmnt.scrollIntoView()
      }, 500)
    },
    reset_nric: function(newNationality) {
      if (newNationality.id !== 'MAS') {
        this.model.personal_info.nric_no = null
      }
    },
    check_coc_availability: function(newValue) {
      if (newValue === null) {
        this.model.capabilities_info.coc_certificate_class = null
        this.model.documents_info.COC_CERT.no = null
        this.model.documents_info.COC_CERT.expiry = null
      }
    },
    check_financial_data: function(paymentMethod) {
      if (paymentMethod === 'Cash') {
        this.model.financial_info.bank = null
        this.model.financial_info.account_no = null
        this.model.financial_info.swift_code = null
        this.model.financial_info.country_of_origin = null
      } else if (paymentMethod === 'Bank') {
        this.model.financial_info.swift_code = null
        this.model.financial_info.country_of_origin = null
      }
    },
    async onBlacklisted() {
      let result = await window.swal({
        title:
          this.$t('labels.backend.seafarers.actions.blacklist') +
          '\xa0' +
          this.model.personal_info.name +
          '\xa0' +
          this.model.personal_info.surname,
        text: this.$t('labels.backend.seafarers.text.are_you_sure_blacklist'),
        type: 'warning',
        input: 'textarea',
        inputValidator: value => {
          return !value && 'You need to write something!'
        },
        inputPlaceholder: this.$t(
          'labels.backend.seafarers.text.please_add_your_comments'
        ),
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#dd4b39',
        confirmButtonText: this.$t('buttons.confirm')
      })

      if (result.value) {
        try {
          this.loading = true
          let formData = this.$app.objectToFormData({ comment: result.value })
          let { data } = await axios.post(
            this.$app.route('seafarers.blacklist', { seafarer: this.id }),
            formData
          )
          this.loading = false
          this.$app.noty[data.status](data.message)
          this.$refs.comments.getComments()
          this.fetchData()
        } catch (e) {
          this.loading = false
          this.$app.error(e)
        }
      }
    },
    async onWhitelisted() {
      let result = await window.swal({
        title:
          this.$t('labels.backend.seafarers.actions.whitelist') +
          '\xa0' +
          this.model.personal_info.name +
          '\xa0' +
          this.model.personal_info.surname,
        text: this.$t('labels.backend.seafarers.text.are_you_sure_whitelist'),
        type: 'warning',
        input: 'textarea',
        inputValidator: value => {
          return !value && 'You need to write something!'
        },
        inputPlaceholder: this.$t(
          'labels.backend.seafarers.text.please_add_your_comments'
        ),
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#dd4b39',
        confirmButtonText: this.$t('buttons.confirm')
      })

      if (result.value) {
        try {
          this.loading = true
          let formData = this.$app.objectToFormData({ comment: result.value })
          let { data } = await axios.post(
            this.$app.route('seafarers.whitelist', { seafarer: this.id }),
            formData
          )
          this.loading = false
          this.$app.noty[data.status](data.message)
          this.$refs.comments.getComments()
          this.fetchData()
        } catch (e) {
          this.loading = false
          this.$app.error(e)
        }
      }
    }
  }
}
</script>

<style scoped>
.file-upload-group .invalid-feedback {
  display: block;
}
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
