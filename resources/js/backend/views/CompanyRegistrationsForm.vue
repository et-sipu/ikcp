<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="12">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.company_registrations.titles.create')
                  : $t('labels.backend.company_registrations.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="
                    $t(
                      'validation.attributes.company_registrations.registration'
                    )
                  "
                  label-for="registration"
                  :label-cols="3"
                  :invalid-feedback="feedback('registration')"
                >
                  <b-form-input
                    id="registration"
                    name="registration"
                    :placeholder="
                      $t(
                        'validation.attributes.company_registrations.registration'
                      )
                    "
                    v-model="model.registration"
                    :state="state('registration')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="
                    $t(
                      'validation.attributes.company_registrations.registration_no'
                    )
                  "
                  label-for="registration_no"
                  :label-cols="3"
                  :invalid-feedback="feedback('registration_no')"
                >
                  <b-form-input
                    id="registration_no"
                    name="registration_no"
                    :placeholder="
                      $t(
                        'validation.attributes.company_registrations.registration_no'
                      )
                    "
                    v-model="model.registration_no"
                    :state="state('registration_no')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.company')"
                  label-for="company"
                  :label-cols="3"
                  :invalid-feedback="feedback('company')"
                >
                  <b-form-input
                    id="company"
                    name="company"
                    :placeholder="$t('validation.attributes.company')"
                    v-model="model.company"
                    :state="state('company')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.type')"
                  label-for="type"
                  :label-cols="3"
                  :invalid-feedback="feedback('type')"
                  :state="state('type')"
                >
                  <b-form-radio-group
                    :placeholder="$t('validation.attributes.type')"
                    v-model="model.type"
                    :state="state('type')"
                    :options="types"
                    name="type"
                    id="type"
                  >
                  </b-form-radio-group>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.grade')"
                  label-for="grade"
                  :label-cols="3"
                  :invalid-feedback="feedback('grade')"
                >
                  <b-form-input
                    id="grade"
                    name="grade"
                    :placeholder="$t('validation.attributes.grade')"
                    v-model="model.grade"
                    :state="state('grade')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.validity_from')"
                  label-for="validity_from"
                  :label-cols="3"
                  :invalid-feedback="feedback('validity_from')"
                  :state="state('validity_from')"
                >
                  <b-input-group>
                    <p-datetimepicker
                      :config="config"
                      id="validity_from"
                      name="validity_from"
                      v-model="model.validity_from"
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
                  :label="$t('validation.attributes.validity_to')"
                  label-for="validity_to"
                  :label-cols="3"
                  :invalid-feedback="feedback('validity_to')"
                  :state="state('validity_to')"
                >
                  <b-input-group>
                    <p-datetimepicker
                      :config="config"
                      id="validity_to"
                      name="validity_to"
                      v-model="model.validity_to"
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
                  :label="$t('validation.attributes.registration_file')"
                  :label-cols="3"
                  class="file-upload-group required"
                  :invalid-feedback="feedback('registrations_file')"
                >
                  <div class="media">
                    <div
                      class="m-1 attachment_link"
                      v-if="model.registrations_file_url"
                    >
                      <a :href="model.registrations_file_url" target="_blank">
                        {{ model.registrations_file_name | truncate(100) }}
                      </a>
                    </div>
                    <div class="media-body">
                      <b-form-file
                        accept=".doc, .docx, .xls, .xlsx, .pdf"
                        :placeholder="$t('labels.no_file_chosen')"
                        v-model="model.registration_file"
                        ref="registration_file"
                        :state="state('registration_file')"
                      ></b-form-file>
                    </div>
                  </div>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  variant="danger"
                  size="sm"
                  :disabled="pending"
                  @click="close"
                >
                  {{ $t('buttons.cancel') }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  class="float-right"
                  :disabled="pending"
                  v-if="
                    (isNew &&
                      this.$app.user.can('create company registrations')) ||
                      this.$app.user.can('edit company registrations')
                  "
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                </b-button>
              </b-col>
            </b-row>
          </b-card>
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
import form from '../mixins/form'

export default {
  name: 'CompanyRegistrationsForm',
  components: {},
  mixins: [form],
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true
      },
      modelName: 'company_registration',
      resourceRoute: 'company_registrations',
      listPath: '/company_registrations',
      types: ['New', 'Renewal'],
      model: {
        registration: null,
        registration_no: null,
        company: null,
        type: null,
        grade: null,
        validity_from: null,
        validity_to: null,
        registration_file: null,
        registrations_file_name: null,
        registrations_file_url: null
      }
    }
  },
  methods: {
    close() {
      this.$emit('form-submission-canceled')
      this.$refs.registration_file.reset()
      this.validation.errors = {}
    },
    afterSave() {
      this.$refs.registration_file.reset()
      return true
    }
  }
}
</script>
