<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="12">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.doc_audits.titles.create')
                  : $t('labels.backend.doc_audits.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.audit_date')"
                  label-for="audit_date"
                  :label-cols="3"
                  :invalid-feedback="feedback('audit_date')"
                  :state="state('audit_date')"
                >
                  <b-input-group>
                    <p-datetimepicker
                      :config="config"
                      id="audit_date"
                      name="audit_date"
                      v-model="model.audit_date"
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
                  :label="$t('validation.attributes.nc')"
                  label-for="nc"
                  :label-cols="3"
                  :invalid-feedback="feedback('nc')"
                >
                  <b-form-input
                    id="nc"
                    name="nc"
                    :placeholder="$t('validation.attributes.nc')"
                    v-model="model.nc"
                    :state="state('nc')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.obs')"
                  label-for="obs"
                  :label-cols="3"
                  :invalid-feedback="feedback('obs')"
                >
                  <b-form-input
                    id="obs"
                    name="obs"
                    :placeholder="$t('validation.attributes.obs')"
                    v-model="model.obs"
                    :state="state('obs')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.audit_file')"
                  :label-cols="3"
                  class="file-upload-group required"
                  :invalid-feedback="feedback('audit_file')"
                >
                  <div class="media">
                    <div
                      class="m-1 attachment_link"
                      v-if="model.audit_file_url"
                    >
                      <a :href="model.audit_file_url" target="_blank">
                        {{ model.audit_file_name }}
                      </a>
                    </div>
                    <div class="media-body">
                      <b-form-file
                        accept=".doc, .docx, .xls, .xlsx, .pdf"
                        :placeholder="$t('labels.no_file_chosen')"
                        v-model="model.audit_file"
                        ref="audit_file"
                        :state="state('audit_file')"
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
                    (isNew && this.$app.user.can('create doc audits')) ||
                      this.$app.user.can('edit doc audits')
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
  name: 'DocAuditForm',
  components: {},
  mixins: [form],
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true
      },
      modelName: 'doc_audit',
      resourceRoute: 'doc_audits',
      listPath: '/doc_audits',
      model: {
        audit_date: null,
        nc: null,
        obs: null,
        audit_file: null,
        audit_file_name: null,
        audit_file_url: null
      }
    }
  },
  methods: {
    close() {
      this.$emit('form-submission-canceled')
      this.$refs.audit_file.reset()
      this.validation.errors = {}
    },
    afterSave() {
      this.$refs.audit_file.reset()
      return true
    }
  }
}
</script>
