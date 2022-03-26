<template>
  <b-row class="record pr-5" style="position: relative">
    <b-button
      class="close"
      size="sm"
      variant="danger"
      v-b-tooltip.hover
      :title="$t('buttons.delete')"
      @click="dropRecord()"
      v-if="!disabled && !deletable"
    >
      <i class="fe fe-x"></i>
    </b-button>

    <b-col xl="9">
      <b-form-group
        :label="$t('validation.attributes.quarter_date')"
        :label-cols="4"
        :invalid-feedback="feedback(quarter_date_field)"
        :state="state(quarter_date_field)"
      >
        <b-input-group v-if="!disabled && !disabledCert">
          <p-datetimepicker
            :config="config"
            :value="value.quarter_date"
            ref="quarter_date"
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
        <h3 v-else>
          <b-badge variant="primary" class="mr-1">
            {{ value.quarter_date ? value.quarter_date : ' -- ' }}
          </b-badge>
        </h3>
      </b-form-group>
    </b-col>
    <b-col xl="12">
      <b-form-group
        :label="$t('validation.attributes.certificate_summary_file')"
        :label-cols="3"
        :invalid-feedback="feedback(record_file_field)"
        class="file-upload-group required"
      >
        <div class="media">
          <div v-if="value.url" class="m-1 record_link">
            <a :href="value.url" target="_blank">
              {{ $t('validation.attributes.certificate_summary_file') }}
            </a>
          </div>

          <div class="media-body" v-if="!disabled && !disabledCert">
            <b-form-file
              :placeholder="$t('labels.no_file_chosen')"
              v-model="value.file"
              :ref="compName + '-' + index"
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
import validation from '../../mixins/validation'

export default {
  name: 'VesselReport',
  mixins: [validation],
  props: {
    value: {
      default: () => ({
        id: 0,
        quarter_date: null,
        fill: null,
        url: null
      }),
      type: Object
    },
    index: {
      default: 0,
      type: Number
    },
    compName: {
      default: 'file',
      type: String,
      required: false
    },
    disabled: {
      type: Boolean,
      default: false,
      required: false
    },
    disabledCert: {
      type: Boolean,
      default: false,
      required: false
    },
    deletable: {
      type: Boolean,
      default: false,
      required: false
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
    quarter_date_field: function() {
      return 'certificates_summary.' + this.index + '.quarter_date'
    },
    record_file_field: function() {
      return 'certificates_summary.' + this.index + '.file'
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        id: this.value.id,
        quarter_date: this.$refs.quarter_date
          ? this.$refs.quarter_date.$el.value
          : null,
        file: this.$refs[`${this.compName}-${this.index}`].$el.firstChild.files
          .length
          ? this.$refs[`${this.compName}-${this.index}`].$el.firstChild.files[0]
          : null,
        url: this.value.url
      })
    },
    onFileChange(e) {
      const file = e.target.files[0]
      this.$emit('input', {
        id: this.value.id,
        quarter_date: this.$refs.quarter_date
          ? this.$refs.quarter_date.$el.value
          : null,
        file: file,
        url: this.value.url
      })
    },
    dropRecord() {
      this.$emit('deleted', { index: this.index })
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
