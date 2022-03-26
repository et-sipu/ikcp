<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-card>
        <template slot="header">
          <h4 slot="header">
            {{
              isNew
                ? $t('labels.backend.employees.titles.create')
                : $t('labels.backend.employees.titles.edit')
            }}
          </h4>
          <div class="card-options">
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
            <div v-if="model.job_info.employment_status !== null">
              <h3 class="mb-0">
                <b-badge :variant="model.job_info.employment_status">
                  {{
                    $t(
                      `labels.backend.employees.employment_status.${model.job_info.employment_status}`
                    )
                  }}
                </b-badge>
              </h3>
            </div>
          </div>
        </template>
        <b-row class="justify-content-center">
          <b-col xl="6">
            <fieldset class="form-fieldset">
              <h5 class="mb-5">
                {{ $t('labels.backend.employees.titles.personal_info') }}
              </h5>

              <photo
                v-model="model.photo"
                :label="$t('validation.attributes.personal_info.photo')"
                field-name="photo"
                :image-path="model.personal_info.photo_path"
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
                :label="
                  $t('validation.attributes.personal_info.marital_status')
                "
                label-for="marital_status"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.marital_status')"
                :state="state('personal_info.marital_status')"
              >
                <b-form-radio-group
                  v-model="model.personal_info.marital_status"
                  :state="state('personal_info.marital_status')"
                  :options="marital_statuses"
                  name="marital_status"
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
                :label="$t('validation.attributes.personal_info.blood_type')"
                label-for="blood_type"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.blood_type')"
                :state="state('personal_info.blood_type')"
              >
                <multiselect
                  v-model="model.personal_info.blood_type"
                  :options="blood_types"
                  id="blood_type"
                  name="blood_type"
                  :searchable="false"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' +
                      $t('validation.attributes.personal_info.blood_type') +
                      ' --'
                  "
                ></multiselect>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.personal_info.race')"
                label-for="race"
                :label-cols="3"
                :invalid-feedback="feedback('personal_info.race')"
                :state="state('personal_info.race')"
              >
                <b-form-input
                  id="race"
                  name="race"
                  :placeholder="$t('validation.attributes.personal_info.race')"
                  v-model="model.personal_info.race"
                  :state="state('personal_info.race')"
                ></b-form-input>
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
              <h5>{{ $t('labels.backend.employees.titles.contact_info') }}</h5>

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
                :label="$t('validation.attributes.contact_info.personal_email')"
                label-for="personal_email"
                :label-cols="3"
                :invalid-feedback="feedback('contact_info.personal_email')"
              >
                <b-form-input
                  id="personal_email"
                  name="personal_email"
                  email
                  :placeholder="
                    $t('validation.attributes.contact_info.personal_email')
                  "
                  v-model="model.contact_info.personal_email"
                  :state="state('contact_info.personal_email')"
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

              <b-form-group
                :label="
                  $t(
                    'validation.attributes.contact_info.next_of_kin_relationship'
                  )
                "
                label-for="next_of_kin_relationship"
                :label-cols="3"
                :invalid-feedback="
                  feedback('contact_info.next_of_kin_relationship')
                "
              >
                <b-form-input
                  id="next_of_kin_relationship"
                  name="next_of_kin_relationship"
                  :placeholder="
                    $t(
                      'validation.attributes.contact_info.next_of_kin_relationship'
                    )
                  "
                  v-model="model.contact_info.next_of_kin_relationship"
                  :state="state('contact_info.next_of_kin_relationship')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="
                  $t('validation.attributes.contact_info.next_of_kin_address')
                "
                label-for="next_of_kin_address"
                :label-cols="3"
                :invalid-feedback="feedback('contact_info.next_of_kin_address')"
              >
                <b-form-textarea
                  id="next_of_kin_address"
                  name="next_of_kin_address"
                  :placeholder="
                    $t('validation.attributes.contact_info.next_of_kin_address')
                  "
                  v-model="model.contact_info.next_of_kin_address"
                  :state="state('contact_info.next_of_kin_address')"
                ></b-form-textarea>
              </b-form-group>
            </fieldset>
          </b-col>
          <b-col xl="6">
            <fieldset class="form-fieldset">
              <h5>
                {{
                  $t('labels.backend.employees.titles.academic_qualifications')
                }}
              </h5>

              <b-form-group
                :invalid-feedback="feedback('academic_qualifications')"
                :state="state('academic_qualifications')"
              >
              </b-form-group>

              <template
                v-for="(qualification, index) in model.qualifications_info
                  .academic_qualifications"
              >
                <AcademicQualification
                  v-model="
                    model.qualifications_info.academic_qualifications[index]
                  "
                  qualification-type="academic_qualifications"
                  :index="index"
                  :ref="'academic_qualifications-' + index"
                  :key="index"
                  @deleted="dropQualification('academic_qualifications', index)"
                ></AcademicQualification>
              </template>

              <div class="float-right">
                <b-button
                  size="sm"
                  variant="primary"
                  v-b-tooltip.hover
                  :title="$t('buttons.employees.add_qualifications')"
                  @click="addQualification('academic_qualifications')"
                >
                  <i class="fe fe-plus-circle"></i>
                </b-button>
              </div>
            </fieldset>

            <fieldset class="form-fieldset">
              <h5>
                {{
                  $t(
                    'labels.backend.employees.titles.professional_qualifications'
                  )
                }}
              </h5>

              <b-form-group
                :invalid-feedback="feedback('professional_qualifications')"
                :state="state('professional_qualifications')"
              >
              </b-form-group>

              <template
                v-for="(qualification, index) in model.qualifications_info
                  .professional_qualifications"
              >
                <ProfessionalQualification
                  v-model="
                    model.qualifications_info.professional_qualifications[index]
                  "
                  qualification-type="professional_qualifications"
                  :index="index"
                  :ref="'professional_qualifications-' + index"
                  :key="index"
                  @deleted="
                    dropQualification('professional_qualifications', index)
                  "
                ></ProfessionalQualification>
              </template>

              <div class="float-right">
                <b-button
                  size="sm"
                  variant="primary"
                  v-b-tooltip.hover
                  :title="$t('buttons.employees.add_qualifications')"
                  @click="addQualification('professional_qualifications')"
                >
                  <i class="fe fe-plus-circle"></i>
                </b-button>
              </div>
            </fieldset>

            <fieldset class="form-fieldset">
              <h5>
                {{ $t('labels.backend.employees.titles.job_info') }}
              </h5>

              <b-form-group
                :label="$t('validation.attributes.department')"
                label-for="department"
                :label-cols="3"
                :invalid-feedback="feedback('job_info.department')"
                :state="state('job_info.department')"
              >
                <multiselect
                  v-model="model.job_info.department"
                  :options="departments"
                  id="department"
                  name="department"
                  track-by="id"
                  label="name"
                  :searchable="true"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' + $t('validation.attributes.department') + ' --'
                  "
                  @input="getDesignatinos()"
                  :disabled="normal_employee"
                ></multiselect>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.designation')"
                label-for="designation"
                :label-cols="3"
                :invalid-feedback="feedback('job_info.designation')"
                :state="state('job_info.designation')"
              >
                <multiselect
                  v-model="model.job_info.designation"
                  :options="designations"
                  id="designation"
                  name="designation"
                  track-by="id"
                  label="name"
                  :searchable="true"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' + $t('validation.attributes.designation') + ' --'
                  "
                  :disabled="normal_employee"
                ></multiselect>
              </b-form-group>
              <b-form-group
                :label="$t('validation.attributes.branch')"
                label-for="branch"
                :label-cols="3"
                :invalid-feedback="feedback('job_info.branch')"
                :state="state('job_info.branch')"
              >
                <multiselect
                  v-model="model.job_info.branch"
                  :options="branches"
                  id="branch"
                  name="branch"
                  track-by="id"
                  label="name"
                  :searchable="true"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' + $t('validation.attributes.branch') + ' --'
                  "
                  :disabled="normal_employee"
                ></multiselect>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.employment_status')"
                label-for="father_status"
                :label-cols="3"
                :invalid-feedback="feedback('job_info.employment_status')"
                :state="state('job_info.employment_status')"
              >
                <b-form-radio-group
                  buttons
                  button-variant="outline-primary"
                  v-model="model.job_info.employment_status"
                  :state="state('job_info.employment_status')"
                  name="father_status"
                  v-if="!normal_employee"
                >
                  <b-form-radio
                    :value="status"
                    v-for="(status, index) in [
                      'on_probation',
                      'permanent',
                      'terminated',
                      'resigned'
                    ]"
                    :key="index"
                  >
                    {{
                      $t(`labels.backend.employees.employment_status.${status}`)
                    }}
                  </b-form-radio>
                </b-form-radio-group>
                <h3 v-else>
                  <b-badge variant="primary">
                    {{
                      $t(
                        `labels.backend.employees.employment_status.${model.job_info.employment_status}`
                      )
                    }}
                  </b-badge>
                </h3>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.probationary_period')"
                label-for="probationary_period"
                :label-cols="3"
                :invalid-feedback="feedback('job_info.probationary_period')"
                :state="state('job_info.probationary_period')"
              >
                <b-input-group>
                  <b-form-input
                    id="probationary_period"
                    name="probationary_period"
                    type="number"
                    min="1"
                    max="12"
                    :placeholder="
                      $t('validation.attributes.probationary_period')
                    "
                    v-model="model.job_info.probationary_period"
                    :state="state('job_info.probationary_period')"
                    :plaintext="normal_employee"
                  ></b-form-input>
                  <b-input-group-append>
                    <b-input-group-prepend>
                      <b-btn variant="secondary">Month</b-btn>
                    </b-input-group-prepend>
                  </b-input-group-append>
                </b-input-group>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.level')"
                label-for="level"
                :label-cols="3"
                :invalid-feedback="feedback('job_info.level')"
                :state="state('job_info.level')"
              >
                <multiselect
                  v-model="model.job_info.level"
                  :options="levels"
                  id="level"
                  name="level"
                  track-by="id"
                  label="level"
                  :searchable="true"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' + $t('validation.attributes.level') + ' --'
                  "
                  :disabled="normal_employee"
                ></multiselect>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.report_to')"
                label-for="report_to"
                :label-cols="3"
                :invalid-feedback="feedback('job_info.report_to')"
                :state="state('job_info.report_to')"
              >
                <multiselect
                  v-model="model.job_info.report_to"
                  :options="employees"
                  id="report_to"
                  name="report_to"
                  track-by="id"
                  label="name"
                  :searchable="true"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' + $t('validation.attributes.report_to') + ' --'
                  "
                  :disabled="normal_employee"
                ></multiselect>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.email')"
                label-for="email"
                :label-cols="3"
                :invalid-feedback="feedback('job_info.email')"
                v-if="!model.personal_info.email"
              >
                <b-form-input
                  id="email"
                  name="email"
                  email
                  :placeholder="$t('validation.attributes.email')"
                  v-model="model.job_info.email"
                  :state="state('job_info.email')"
                  :plaintext="normal_employee"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.staff_id')"
                label-for="staff_id"
                :label-cols="3"
                :invalid-feedback="feedback('job_info.staff_id')"
              >
                <b-form-input
                  id="staff_id"
                  name="staff_id"
                  :placeholder="$t('validation.attributes.staff_id')"
                  v-model="model.job_info.staff_id"
                  :state="state('job_info.staff_id')"
                  :plaintext="normal_employee"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.thumbs')"
                label-for="thumbs"
                :label-cols="3"
                :invalid-feedback="feedback('job_info.thumbs')"
              >
                <b-form-input
                  id="thumbs"
                  name="thumbs"
                  :placeholder="$t('validation.attributes.thumbs')"
                  v-model="model.job_info.thumbs"
                  :state="state('job_info.thumbs')"
                  :plaintext="normal_employee"
                ></b-form-input>
              </b-form-group>
            </fieldset>

            <fieldset
              class="form-fieldset"
              v-if="
                model.personal_info.marital_status &&
                  model.personal_info.marital_status === 'Married'
              "
            >
              <h5>
                {{ $t('labels.backend.employees.titles.spouse_info') }}
              </h5>

              <b-form-group
                :label="$t('validation.attributes.spouse_info.name')"
                label-for="spouse_name"
                :label-cols="3"
                :invalid-feedback="feedback('spouse_info.name')"
              >
                <b-form-input
                  id="spouse_name"
                  name="spouse_name"
                  :placeholder="$t('validation.attributes.spouse_info.name')"
                  v-model="model.spouse_info.name"
                  :state="state('spouse_info.name')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.spouse_info.nric_no')"
                label-for="spouse_nric_no"
                :label-cols="3"
                :invalid-feedback="feedback('spouse_info.nric_no')"
              >
                <b-form-input
                  id="spouse_nric_no"
                  name="spouse_nric_no"
                  :placeholder="$t('validation.attributes.spouse_info.nric_no')"
                  v-model="model.spouse_info.nric_no"
                  :state="state('spouse_info.nric_no')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.spouse_info.occupation')"
                label-for="occupation"
                :label-cols="3"
                :invalid-feedback="feedback('spouse_info.occupation')"
              >
                <b-form-input
                  id="occupation"
                  name="occupation"
                  :placeholder="
                    $t('validation.attributes.spouse_info.occupation')
                  "
                  v-model="model.spouse_info.occupation"
                  :state="state('spouse_info.occupation')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.spouse_info.employer_name')"
                label-for="employer_name"
                :label-cols="3"
                :invalid-feedback="feedback('spouse_info.employer_name')"
              >
                <b-form-input
                  id="employer_name"
                  name="employer_name"
                  :placeholder="
                    $t('validation.attributes.spouse_info.employer_name')
                  "
                  v-model="model.spouse_info.employer_name"
                  :state="state('spouse_info.employer_name')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="
                  $t('validation.attributes.spouse_info.employer_address')
                "
                label-for="employer_address"
                :label-cols="3"
                :invalid-feedback="feedback('spouse_info.employer_address')"
              >
                <b-form-textarea
                  id="employer_address"
                  name="employer_address"
                  :placeholder="
                    $t('validation.attributes.spouse_info.employer_address')
                  "
                  v-model="model.spouse_info.employer_address"
                  :state="state('spouse_info.employer_address')"
                ></b-form-textarea>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.spouse_info.hp')"
                label-for="spouse_hp"
                :label-cols="3"
                :invalid-feedback="feedback('spouse_info.hp')"
              >
                <b-form-input
                  id="spouse_hp"
                  name="spouse_hp"
                  :placeholder="$t('validation.attributes.spouse_info.hp')"
                  v-model="model.spouse_info.hp"
                  :state="state('spouse_info.hp')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.spouse_info.dom')"
                label-for="dom"
                :label-cols="3"
                :invalid-feedback="feedback('spouse_info.dom')"
              >
                <b-input-group>
                  <p-datetimepicker
                    :config="config"
                    id="dom"
                    name="dom"
                    v-model="model.spouse_info.dom"
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
            </fieldset>

            <fieldset class="form-fieldset">
              <h5>
                {{ $t('labels.backend.employees.titles.parents_info') }}
              </h5>

              <b-form-group
                :label="$t('validation.attributes.parents_info.father_name')"
                label-for="father_name"
                :label-cols="3"
                :invalid-feedback="feedback('parents_info.father_name')"
              >
                <b-form-input
                  id="father_name"
                  name="father_name"
                  :placeholder="
                    $t('validation.attributes.parents_info.father_name')
                  "
                  v-model="model.parents_info.father_name"
                  :state="state('parents_info.father_name')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.parents_info.father_status')"
                label-for="father_status"
                :label-cols="3"
                :invalid-feedback="feedback('parents_info.father_status')"
                :state="state('parents_info.father_status')"
              >
                <b-form-radio-group
                  v-model="model.parents_info.father_status"
                  :state="state('parents_info.father_status')"
                  :options="['Live', 'Deceased']"
                  name="father_status"
                >
                </b-form-radio-group>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.parents_info.mother_name')"
                label-for="mother_name"
                :label-cols="3"
                :invalid-feedback="feedback('parents_info.mother_name')"
              >
                <b-form-input
                  id="mother_name"
                  name="mother_name"
                  :placeholder="
                    $t('validation.attributes.parents_info.mother_name')
                  "
                  v-model="model.parents_info.mother_name"
                  :state="state('parents_info.mother_name')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.parents_info.mother_status')"
                label-for="father_status"
                :label-cols="3"
                :invalid-feedback="feedback('parents_info.mother_status')"
                :state="state('parents_info.mother_status')"
              >
                <b-form-radio-group
                  v-model="model.parents_info.mother_status"
                  :state="state('parents_info.mother_status')"
                  :options="['Live', 'Deceased']"
                  name="mother_status"
                >
                </b-form-radio-group>
              </b-form-group>
            </fieldset>

            <fieldset
              class="form-fieldset"
              v-if="
                model.personal_info.marital_status &&
                  model.personal_info.marital_status !== 'Single'
              "
            >
              <h5>
                {{ $t('labels.backend.employees.titles.children_info') }}
              </h5>

              <template v-for="(children, index) in model.children_info">
                <EmployeeChild
                  v-model="model.children_info[index]"
                  :index="index"
                  :ref="'children-' + index"
                  :key="index"
                  @deleted="dropChild(index)"
                ></EmployeeChild>
              </template>

              <div class="float-right">
                <b-button
                  size="sm"
                  variant="primary"
                  v-b-tooltip.hover
                  :title="$t('buttons.employees.add_child')"
                  @click="addChild()"
                >
                  <i class="fe fe-plus-circle"></i>
                </b-button>
              </div>
            </fieldset>

            <fieldset class="form-fieldset">
              <h5>
                {{ $t('labels.backend.employees.titles.financial_info') }}
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
              </template>

              <b-form-group
                :label="
                  $t('validation.attributes.financial_info.income_tax_no')
                "
                label-for="account_no"
                :label-cols="3"
                :invalid-feedback="feedback('financial_info.income_tax_no')"
              >
                <b-form-input
                  id="income_tax_no"
                  name="income_tax_no"
                  :placeholder="
                    $t('validation.attributes.financial_info.income_tax_no')
                  "
                  v-model="model.financial_info.income_tax_no"
                  :state="state('financial_info.income_tax_no')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.financial_info.epf')"
                label-for="epf"
                :label-cols="3"
                :invalid-feedback="feedback('financial_info.epf')"
              >
                <b-form-input
                  id="epf"
                  name="epf"
                  :placeholder="$t('validation.attributes.financial_info.epf')"
                  v-model="model.financial_info.epf"
                  :state="state('financial_info.epf')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.financial_info.socso_no')"
                label-for="socso_no"
                :label-cols="3"
                :invalid-feedback="feedback('financial_info.socso_no')"
              >
                <b-form-input
                  id="socso_no"
                  name="socso_no"
                  :placeholder="
                    $t('validation.attributes.financial_info.socso_no')
                  "
                  v-model="model.financial_info.socso_no"
                  :state="state('financial_info.socso_no')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.financial_info.zakat')"
                label-for="zakat"
                :label-cols="3"
                :invalid-feedback="feedback('financial_info.zakat')"
              >
                <b-form-input
                  id="zakat"
                  name="zakat"
                  :placeholder="
                    $t('validation.attributes.financial_info.zakat')
                  "
                  v-model="model.financial_info.zakat"
                  :state="state('financial_info.zakat')"
                ></b-form-input>
              </b-form-group>
            </fieldset>
          </b-col>
        </b-row>
        <b-row slot="footer">
          <b-col md>
            <b-button :to="{ name: 'employees' }" variant="danger" size="sm">
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
              {{ isNew ? $t('buttons.create') : $t('buttons.save') }}
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
      model-name="Employee"
      :model-id="id"
      v-if="!isNew"
    ></comments>
    <b-collapse id="history" @shown="goDown">
      <history
        ref="history"
        model-name="Employee"
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
import AcademicQualification from './components/AcademicQualification'
import ProfessionalQualification from './components/ProfessionalQualification'
import EmployeeChild from './components/EmployeeChild'
import { get_employees } from '../lib/options'

export default {
  name: 'EmployeeForm',
  components: {
    EmployeeChild,
    History,
    EmployeeDocument,
    Multiselect,
    Comments,
    Photo,
    AcademicQualification,
    ProfessionalQualification
  },
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
      genders: [],
      marital_statuses: [],
      departments: [],
      designations: [],
      branches: [],
      levels: [],
      employees: [],
      blood_types: ['A-', 'A+', 'B-', 'B+', 'AB-', 'AB+', 'O-', 'O+'],
      payment_methods: ['Cash', 'Bank'],
      modelName: 'employee',
      resourceRoute: 'employees',
      listPath: '/HR/employees',
      model: {
        personal_info: {
          name: null,
          surname: null,
          sex: null,
          marital_status: null,
          religion: null,
          race: null,
          nationality: null,
          address: null,
          nric_no: null,
          date_of_join: null,
          date_of_birth: null,
          place_of_birth: null,
          photo_path: null,
          email: null,
          blood_type: null
        },
        contact_info: {
          personal_hp: null,
          personal_email: null,
          next_of_kin_name: null,
          next_of_kin_hp: null,
          next_of_kin_address: null,
          next_of_kin_relationship: null
        },
        financial_info: {
          payment_method: 'Cash',
          bank: null,
          account_no: null,
          income_tax_no: null,
          epf: null,
          socso_no: null,
          zakat: null
        },
        job_info: {
          department: null,
          branch: null,
          designation: null,
          email: null,
          staff_id: null,
          thumbs: null,
          report_to: null,
          employment_status: null,
          probationary_period: null
        },
        spouse_info: {
          name: null,
          nric_no: null,
          occupation: null,
          employer_name: null,
          employer_address: null,
          hp: null,
          dom: null
        },
        parents_info: {
          father_name: null,
          father_status: null,
          mother_name: null,
          mother_status: null
        },
        children_info: [],
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
          }
        },
        qualifications_info: {
          academic_qualifications: [],
          professional_qualifications: []
        },
        photo: null,
        can_edit: false
      }
    }
  },
  computed: {
    normal_employee() {
      return !this.$app.user.can('view employees')
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
    ;({ data } = await axios.get(
      this.$app.route(`get_list_of_employment_levels`)
    ))
    this.levels = data
    ;({ data } = await axios.get(
      this.$app.route(`get_list_of_constants`, { constant: 'marital_statuses' })
    ))
    this.marital_statuses = data
    ;({ data } = await axios.get(this.$app.route(`departments.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    }))
    this.departments = data.data

    this.departments = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
    ;({ data } = await axios.get(this.$app.route(`branches.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    }))
    this.branches = data.data

    this.branches = data.data.map(item => {
      return { id: item.id, name: item.name }
    })

    this.employees = await get_employees(this.$app)

    // document.querySelectorAll('.flatpickr-calendar').forEach(calendar => {
    //   calendar.style.width = ''
    // })
  },
  methods: {
    addQualification(type) {
      this.model.qualifications_info[type].push({
        id: null,
        authority: null,
        degree: null,
        type: type === 'academic_qualifications' ? 'Academic' : 'Professional',
        year: null,
        url: null,
        file: null
      })
    },

    dropQualification(type, index = 0) {
      this.model.qualifications_info[type].splice(index, 1)
    },
    addChild() {
      this.model.children_info.push({
        name: null,
        icno: null,
        bcno: null,
        dob: null,
        gender: null,
        marital_status: null
      })
    },
    dropChild(index = 0) {
      this.model.children_info.splice(index, 1)
    },
    async getDesignatinos() {
      if (this.model.job_info.department) {
        let { data } = await axios.get(this.$app.route(`designations.search`), {
          params: {
            page: 1,
            perPage: 100,
            column: 'title',
            extraSearch: {
              department_id: this.model.job_info.department.id
            }
          }
        })
        this.designations = data.data

        this.designations = data.data.map(item => {
          return { id: item.id, name: item.title }
        })
      }
    },
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
          this.$t('labels.backend.employees.actions.blacklist') +
          '\xa0' +
          this.model.personal_info.name +
          '\xa0' +
          this.model.personal_info.surname,
        text: this.$t('labels.backend.employees.text.are_you_sure_blacklist'),
        type: 'warning',
        input: 'textarea',
        inputValidator: value => {
          return !value && 'You need to write something!'
        },
        inputPlaceholder: this.$t(
          'labels.backend.employees.text.please_add_your_comments'
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
            this.$app.route('employees.blacklist', { employee: this.id }),
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
          this.$t('labels.backend.employees.actions.whitelist') +
          '\xa0' +
          this.model.personal_info.name +
          '\xa0' +
          this.model.personal_info.surname,
        text: this.$t('labels.backend.employees.text.are_you_sure_whitelist'),
        type: 'warning',
        input: 'textarea',
        inputValidator: value => {
          return !value && 'You need to write something!'
        },
        inputPlaceholder: this.$t(
          'labels.backend.employees.text.please_add_your_comments'
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
            this.$app.route('employees.whitelist', { employee: this.id }),
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
