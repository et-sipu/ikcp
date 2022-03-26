<template>
  <div class="animated fadeIn">
    <b-modal v-model="modalShow" id="modal-xl" size="xl" scrollable>
      <template slot="modal-header">
        <!-- Emulate built in modal header close button action -->
        <div class="float-left">
          <font-awesome-icon icon="bullhorn"></font-awesome-icon>
          <span class="ml-1 font-xl">{{ model.subject }}</span>
        </div>
      </template>
      <div v-html="model.content" class="ck-content"></div
    ></b-modal>
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="12">
          <b-card>
            <template slot="header">
              <h4>
                {{
                  isNew
                    ? $t('labels.backend.announcements.titles.create')
                    : $t('labels.backend.announcements.titles.edit')
                }}
              </h4>
              <div v-if="model.status !== null" class="card-options">
                <h3 class="mb-0">
                  <b-badge variant="primary">
                    {{ model.status }}
                    <span v-if="model.status === 'PUBLISHED'"
                      >@{{ model.published_at }}</span
                    >
                  </b-badge>
                </h3>
              </div>
            </template>
            <b-row class="justify-content-center">
              <b-col xl="7">
                <b-form-group
                  :label="$t('validation.attributes.destination')"
                  label-for="destination"
                  :label-cols="3"
                  :invalid-feedback="feedback('destination.destination_type')"
                  :state="state('destination.destination_type')"
                >
                  <h4 v-if="model.status === 'PUBLISHED'">
                    <b-badge variant="info">{{
                      model.destination.destination_type
                    }}</b-badge>
                  </h4>
                  <b-form-radio-group
                    v-else
                    v-model="model.destination.destination_type"
                    id="destination_type"
                    buttons
                    button-variant="outline-primary"
                    :options="[
                      'ALL',
                      'LOCATION',
                      'DEPARTMENT',
                      'USERS',
                      'GROUPS'
                    ]"
                    name="radioInline"
                    @change="model.destination.to = null"
                    :disabled="disabled"
                  >
                  </b-form-radio-group>
                </b-form-group>
                <b-form-group
                  :label-cols="3"
                  :label="$t('validation.attributes.departments')"
                  label-for="departments"
                  :invalid-feedback="feedback('destination.to')"
                  :state="state('destination.to')"
                  v-if="model.destination.destination_type === 'DEPARTMENT'"
                >
                  <multiselect
                    v-model="model.destination.to"
                    :options="departments"
                    id="departments"
                    name="departments"
                    label="name"
                    track-by="id"
                    :multiple="true"
                    :close-on-select="false"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.departments') + ' --'
                    "
                    :disabled="disabled"
                  ></multiselect>
                </b-form-group>
                <b-form-group
                  :label-cols="3"
                  label-for="branches"
                  :label="$t('validation.attributes.branches')"
                  :invalid-feedback="feedback('destination.to')"
                  :state="state('destination.to')"
                  v-if="model.destination.destination_type === 'LOCATION'"
                >
                  <multiselect
                    v-model="model.destination.to"
                    :options="branches"
                    id="branches"
                    name="branches"
                    label="name"
                    track-by="id"
                    :multiple="true"
                    :close-on-select="false"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.branches') + ' --'
                    "
                    :disabled="disabled"
                  ></multiselect>
                </b-form-group>
                <b-form-group
                  :label-cols="3"
                  :label="$t('validation.attributes.groups')"
                  label-for="groups"
                  :invalid-feedback="feedback('destination.to')"
                  :state="state('destination.to')"
                  v-if="model.destination.destination_type === 'GROUPS'"
                >
                  <multiselect
                    v-model="model.destination.to"
                    :options="groups"
                    id="groups"
                    name="groups"
                    label="name"
                    track-by="id"
                    :multiple="true"
                    :close-on-select="false"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.groups') + ' --'
                    "
                    :disabled="disabled"
                  ></multiselect>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.users')"
                  label-for="users"
                  :label-cols="3"
                  :plaintext="disabled"
                  :invalid-feedback="feedback('destination.cc')"
                  :state="state('destination.cc')"
                  v-if="model.destination.destination_type === 'USERS'"
                >
                  <tags-input
                    id="users"
                    v-model="model.destination.to"
                    :existing-tags="emails"
                    :typeahead="true"
                    placeholder="Add email address"
                    typeahead-style="dropdown"
                  ></tags-input>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.CC')"
                  label-for="CC"
                  :label-cols="3"
                  :plaintext="disabled"
                  :invalid-feedback="feedback('destination.cc')"
                  :state="state('destination.cc')"
                >
                  <h4 v-if="model.status === 'PUBLISHED'">
                    <span
                      v-for="(cc, index) in model.destination.cc"
                      :key="index"
                      ><b-badge variant="info">
                        {{ cc }}
                      </b-badge></span
                    >
                  </h4>
                  <tags-input
                    v-else
                    v-model="model.destination.cc"
                    :existing-tags="emails"
                    :typeahead="true"
                    placeholder="Add email address"
                    typeahead-style="dropdown"
                  ></tags-input>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.subject')"
                  label-for="subject"
                  :label-cols="3"
                >
                  <b-form-input
                    id="subject"
                    name="subject"
                    :placeholder="$t('validation.attributes.subject')"
                    v-model="model.subject"
                    :state="state('subject')"
                    :plaintext="disabled"
                  ></b-form-input>
                </b-form-group>
                <br />
                <hr style="width: 100%; border-top: 2px solid #c9c7c7;" />
                <b-form-group
                  :label="$t('validation.attributes.content')"
                  label-for="content"
                  :label-cols="3"
                >
                  <b-row class="ml-0">
                    <ckeditor
                      class="announcement_body"
                      :editor="editor"
                      :config="editorConfig"
                      v-model="model.content"
                      :disabled="disabled"
                      @ready="onReady"
                    ></ckeditor>
                  </b-row>
                </b-form-group>

                <Attachments
                  v-model="model.attachments"
                  :disabled="disabled"
                ></Attachments>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  :to="{ name: 'announcements' }"
                  variant="danger"
                  size="sm"
                >
                  {{ $t('buttons.back') }}
                </b-button>
              </b-col>
              <b-col>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  class="float-right"
                  :disabled="pending"
                  v-if="
                    (isNew && this.$app.user.can('create announcements')) ||
                      (this.$app.user.can('edit announcements') &&
                        model.status === 'DRAFT')
                  "
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                </b-button>
                <b-button
                  size="sm"
                  @click="modalShow = !modalShow"
                  class="float-right mr-2"
                  >{{ $t('buttons.view_content') }}</b-button
                >
                <b-button
                  v-if="model.status === 'DRAFT'"
                  size="sm"
                  variant="primary"
                  class="float-right mr-2"
                  :title="$t('buttons.publish')"
                  @click="publish()"
                >
                  {{ $t('buttons.publish') }}
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
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import CKEditor from '@ckeditor/ckeditor5-vue'
import DocumentEditor from '@ckeditor/ckeditor5-build-decoupled-document'
import Attachments from './components/Attachments'
import VoerroTagsInput from '@voerro/vue-tagsinput'
import '@voerro/vue-tagsinput/dist/style.css'

export default {
  name: 'AnnouncementForm',
  components: {
    Attachments,
    Multiselect,
    ckeditor: CKEditor.component,
    'tags-input': VoerroTagsInput
  },
  mixins: [form],
  data() {
    return {
      // editor: ClassicEditor,
      selectedTags: [],
      editor: DocumentEditor,
      editorConfig: {
        ckfinder: {
          // Upload the images to the server using the CKFinder QuickUpload command.
          uploadUrl:
            this.$app.route(`ckfinder_connector`) +
            '?command=QuickUpload&type=Images&responseType=json',
          options: {
            resourceType: 'Images'
          }
        }
      },
      modelName: 'announcement',
      resourceRoute: 'announcements',
      listPath: '/HR/announcements',
      collapse_status: true,
      departments: [],
      groups: [],
      emails: {},
      branches: [],
      modalShow: false,
      model: {
        subject: null,
        content: '',
        status: null,
        published_at: null,
        destination: {
          destination_type: null,
          to: null,
          cc: []
        },
        attachments: []
      }
    }
  },
  computed: {
    disabled() {
      return this.model.status === 'PUBLISHED'
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`departments.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    })
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
    ;({ data } = await axios.get(
      this.$app.route(`employees.get_employees_emails`)
    ))
    this.emails = data
    ;({ data } = await axios.get(this.$app.route(`groups.search`)))
    this.groups = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
  },
  methods: {
    async publish() {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        type: 'info',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#446edd',
        confirmButtonText: this.$t('buttons.publish')
      })

      if (result.value) {
        try {
          let { data } = await axios.post(
            this.$app.route('announcements.publish', { announcement: this.id })
          )
          this.$app.noty[data.status](data.message)
          this.fetchData()
        } catch (e) {
          this.$app.error(e)
        }
      }
    },
    onReady(editor) {
      // Insert the toolbar before the editable area.
      editor.ui
        .getEditableElement()
        .parentElement.insertBefore(
          editor.ui.view.toolbar.element,
          editor.ui.getEditableElement()
        )
    }
  }
}
</script>
<style>
.announcement_body {
  min-height: 500px !important;
  width: 825px !important;
  border: 1px solid var(--ck-color-toolbar-border) !important;
  /*border: 1px solid black !important;*/
}
.typeahead-dropdown {
  z-index: 1000 !important;
}
:root {
  --ck-image-style-spacing: 1.5em;
}
</style>
<style lang="scss">
.ck-content {
  & .image-style-side {
    height: 200px;
  }
  & .image-style-align-left,
  & .image-style-align-center,
  & .image-style-align-right {
  }

  & .image-style-side {
    float: left;
    margin-left: var(--ck-image-style-spacing);
  }

  & .image-style-align-left {
    float: left;
    margin-right: var(--ck-image-style-spacing);
  }

  & .image-style-align-center {
    margin-left: auto;
    margin-right: auto;
  }

  & .image-style-align-right {
    float: right;
    margin-left: var(--ck-image-style-spacing);
  }
  .table td {
    width: auto;
    max-width: 150px;
    min-width: 90px !important;
    height: 40px;
  }
}
</style>
