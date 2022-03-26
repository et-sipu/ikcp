<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="12">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.vessel_forms.titles.create')
                  : $t('labels.backend.vessel_forms.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.doc_template')"
                  label-for="doc_template"
                  :label-cols="3"
                  :invalid-feedback="feedback('doc_template')"
                  :state="state('doc_template')"
                >
                  <multiselect
                    v-model="model.doc_template"
                    :options="templates_names"
                    id="doc_template"
                    name="doc_template"
                    track-by="id"
                    label="name"
                    :searchable="true"
                    :close-on-select="true"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.doc_template') + ' --'
                    "
                  ></multiselect>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.form_file')"
                  :label-cols="3"
                  class="file-upload-group required"
                  :invalid-feedback="feedback('form')"
                >
                  <div class="media">
                    <div class="m-1 attachment_link" v-if="model.form_url">
                      <a :href="model.form_url" target="_blank">
                        {{ $t('validation.attributes.form_file') }}
                      </a>
                    </div>
                    <div class="media-body">
                      <b-form-file
                        accept=".doc, .docx, .xls, .xlsx, .pdf"
                        :placeholder="$t('labels.no_file_chosen')"
                        v-model="model.form"
                        ref="template"
                        :state="state('form')"
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
                  @click="close"
                  class="float-left"
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
                    (isNew && this.$app.user.can('create vessel forms')) ||
                      this.$app.user.can('edit vessel forms')
                  "
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
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
import form from '../mixins/form'
import Multiselect from 'vue-multiselect'
import { get_doc_templates } from '../lib/options'

export default {
  name: 'VesselFormForm',
  components: {
    Multiselect
  },
  mixins: [form],
  props: {
    vesselId: {
      type: [Number, String],
      required: false,
      default: 0
    }
  },
  data() {
    return {
      modelName: 'vessel_form',
      resourceRoute: 'vessel_forms',
      listPath: '/vessel_forms',
      model: {
        doc_template: null,
        vessel_id: null,
        form: null,
        form_url: null
      },
      templates_names: []
    }
  },
  async created() {
    if (this.vesselId !== 0) this.model.vessel_id = this.vesselId

    this.templates_names = await get_doc_templates(this.$app)
  },
  methods: {
    close() {
      this.$emit('form-submission-canceled')
      this.validation.errors = {}
    },
    afterSave() {
      return true
    }
  }
}
</script>
