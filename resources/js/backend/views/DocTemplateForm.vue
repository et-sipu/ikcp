<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="12">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.doc_templates.titles.create')
                  : $t('labels.backend.doc_templates.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.template_type')"
                  label-for="template_type"
                  :label-cols="3"
                  :invalid-feedback="feedback('template_type')"
                  :state="state('template_type')"
                >
                  <b-form-radio-group
                    id="form_type"
                    v-model="model.template_type"
                    buttons
                    button-variant="outline-primary"
                  >
                    <b-form-radio
                      :value="type"
                      v-for="(type, index) in [
                        'SMS',
                        'ISM',
                        'Training',
                        'Reports'
                      ]"
                      :key="index"
                    >
                      {{
                        $t(
                          `labels.backend.doc_templates.template_types.${type}`
                        )
                      }}
                    </b-form-radio>
                  </b-form-radio-group>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.code')"
                  label-for="code"
                  :label-cols="3"
                  :invalid-feedback="feedback('code')"
                >
                  <b-form-input
                    id="code"
                    name="code"
                    :placeholder="$t('validation.attributes.code')"
                    v-model="model.code"
                    :state="state('code')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.title')"
                  label-for="title"
                  :label-cols="3"
                  :invalid-feedback="feedback('title')"
                >
                  <b-form-input
                    id="title"
                    name="title"
                    :placeholder="$t('validation.attributes.title')"
                    v-model="model.title"
                    :state="state('title')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.template_file')"
                  :label-cols="3"
                  class="file-upload-group required"
                  :invalid-feedback="feedback('template')"
                >
                  <div class="media">
                    <div class="m-1 attachment_link" v-if="model.template_url">
                      <a :href="model.template_url" target="_blank">
                        {{ $t('validation.attributes.template_file') }}
                      </a>
                    </div>
                    <div class="media-body">
                      <b-form-file
                        accept=".doc, .docx, .xls, .xlsx, .pdf"
                        :placeholder="$t('labels.no_file_chosen')"
                        v-model="model.template"
                        ref="template"
                        :state="state('template')"
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
                  :block="uploadPercentage !== null"
                  class="float-right"
                  :disabled="pending"
                  v-if="
                    (isNew && this.$app.user.can('create doc templates')) ||
                      this.$app.user.can('edit doc templates')
                  "
                >
                  <template
                    v-if="uploadPercentage === null || uploadPercentage === 100"
                    >{{
                      isNew ? $t('buttons.create') : $t('buttons.edit')
                    }}</template
                  >
                  <b-progress
                    :max="100"
                    height="100%"
                    variant="info"
                    striped
                    :animated="true"
                    v-else
                  >
                    <b-progress-bar :value="uploadPercentage" show-progress
                      >{{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                      <strong
                        >{{ uploadPercentage }} / {{ 100 }}</strong
                      ></b-progress-bar
                    >
                  </b-progress>
                </b-button>
              </b-col>
            </b-row>
            <atom-spinner
              :animation-duration="1000"
              :size="200"
              :color="'#DBB11E'"
              :loading="loading"
              :full="true"
            ></atom-spinner>
          </b-card>
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
import form from '../mixins/form'

export default {
  name: 'DocTemplateForm',
  components: {},
  mixins: [form],
  data() {
    return {
      modelName: 'doc_template',
      resourceRoute: 'doc_templates',
      listPath: '/doc_templates',
      model: {
        template_type: null,
        code: null,
        title: null,
        template: null,
        template_url: null
      }
    }
  },
  methods: {
    close() {
      this.$emit('form-submission-canceled')
      this.$refs.template.reset()
      this.validation.errors = {}
    },
    afterSave() {
      this.$refs.template.reset()
      return false
    }
  }
}
</script>
