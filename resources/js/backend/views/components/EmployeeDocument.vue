<template>
  <b-row>
    <b-col xl="3">
      <label class="col-form-label">
        {{ $t('validation.attributes.' + document_label_field) }}
      </label>
      <input name="id" ref="id" type="hidden" :value="value.id" />
    </b-col>
    <b-col xl="4" v-if="withNo">
      <b-form-group
        :label="$t('validation.attributes.' + document_id_field)"
        :label-for="document_id"
        :label-cols="4"
        :invalid-feedback="feedback(document_id_field)"
      >
        <b-form-input
          :id="document_id"
          :name="document_id"
          :placeholder="$t('validation.attributes.' + document_id_field)"
          :value="value.no"
          :state="state(document_id_field)"
          ref="no"
          @input.native="updateInput"
        ></b-form-input>
      </b-form-group>
    </b-col>

    <b-col xl="5" v-if="withExpiry">
      <b-form-group
        :label="$t('validation.attributes.' + document_expiry_field)"
        :label-for="document_expiry"
        :label-cols="3"
        :invalid-feedback="feedback(document_expiry_field)"
        :state="state(document_expiry_field)"
      >
        <b-input-group>
          <p-datetimepicker
            :config="config"
            :id="document_expiry"
            :name="document_expiry"
            :value="value.expiry"
            ref="expiry"
            @input="updateInput"
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
    </b-col>

    <b-col xl="12">
      <b-form-group
        :label="$t('validation.attributes.' + document_file_field)"
        label-for="basic_documents"
        :label-cols="3"
        :invalid-feedback="feedback(document_file_field)"
        class="file-upload-group required"
      >
        <div class="media">
          <div
            v-if="value.url && value.url !== 'DROP'"
            class="m-1 document_link"
            style="position: relative"
          >
            <a :href="value.url" target="_blank">
              {{ $t('validation.attributes.' + document_file_field) }}
            </a>
            <b-button
              class="close"
              size="sm"
              variant="danger"
              v-b-tooltip.hover
              :title="$t('buttons.drop_file')"
              @click="value.url = 'DROP'"
            >
              <i class="fe fe-x"></i>
            </b-button>
          </div>

          <div class="media-body">
            <b-form-file
              id="basic_documents"
              name="basic_documents"
              :placeholder="$t('labels.no_file_chosen')"
              v-model="value.file"
              ref="file"
              :state="state('value.file')"
              @change="onFileChange"
            ></b-form-file>
          </div>
        </div>
      </b-form-group>
    </b-col>
  </b-row>
</template>
<script>
export default {
  name: 'EmployeeDocument',
  props: {
    value: {
      default: () => ({
        id: null,
        no: null,
        expiry: null,
        fill: null,
        url: null
      }),
      type: Object
    },
    documentType: {
      default: () => 'PASSPORT',
      type: String,
      required: true
    },
    withExpiry: {
      type: Boolean,
      required: false,
      default: true
    },
    withNo: {
      type: Boolean,
      required: false,
      default: true
    }
  },
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true
      }
    }
  },
  computed: {
    document_id: function() {
      return this.documentType + '_id'
    },
    document_expiry: function() {
      return this.documentType + '_expiry'
    },
    document_label_field: function() {
      return 'documents_info.' + this.documentType + '.label'
    },
    document_id_field: function() {
      return 'documents_info.' + this.documentType + '.no'
    },
    document_expiry_field: function() {
      return 'documents_info.' + this.documentType + '.expiry'
    },
    document_file_field: function() {
      return 'documents_info.' + this.documentType + '.file'
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        id: this.$refs.id ? this.$refs.id.value : null,
        no: this.$refs.no ? this.$refs.no.$el.value : null,
        expiry: this.$refs.expiry ? this.$refs.expiry.$el.value : null,
        file: this.$refs.file.$el.firstChild.files.length
          ? this.$refs.file.$el.firstChild.files[0]
          : null,
        url: this.value.url
      })
    },
    onFileChange(e) {
      const file = e.target.files[0]
      this.$emit('input', {
        id: this.$refs.id ? this.$refs.id.value : null,
        no: this.$refs.no ? this.$refs.no.$el.value : null,
        expiry: this.$refs.expiry ? this.$refs.expiry.$el.value : null,
        file: file,
        url: this.value.url
      })
    },
    feedback(name) {
      if (this.state(name)) {
        return this.$parent.validation.errors[name][0]
      }
    },
    state(name) {
      return this.$parent.validation.errors !== undefined &&
        this.$parent.validation.errors.hasOwnProperty(name)
        ? 'invalid'
        : null
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
.document_link {
  width: 40%;
}
.close {
  position: absolute;
  right: 0px;
  top: 0px;
}
</style>
