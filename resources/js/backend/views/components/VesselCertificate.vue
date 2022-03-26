<template>
  <b-row
    class="certificate pr-5"
    :class="{ 'bg-yellow': warning, 'bg-danger': critical, 'bg-gray': expired }"
    tyle="position: relative"
  >
    <b-button
      v-if="!deletable"
      class="close"
      size="sm"
      variant="danger"
      v-b-tooltip.hover
      :title="$t('buttons.vessels.drop_certificate')"
      @click="dropCertificate()"
    >
      <i class="fe fe-x"></i>
    </b-button>
    <b-col xl="6">
      <b-form-group
        :label="$t('validation.attributes.certificates_info.type')"
        :label-cols="4"
        :invalid-feedback="feedback(certificate_type_field)"
        :state="state(certificate_type_field)"
      >
        <multiselect
          :plaintext="!disabled"
          :options="certificateTypes"
          id="certificate_type"
          name="certificate_type"
          v-model="value.type"
          :searchable="true"
          :close-on-select="true"
          :show-labels="false"
          @input.native="updateInput"
          :placeholder="
            '-- ' + $t('validation.attributes.certificates_info.type') + ' --'
          "
          :disabled="disabled"
        ></multiselect>
      </b-form-group>
    </b-col>

    <b-col xl="6">
      <b-form-group
        :label="$t('validation.attributes.certificates_info.expiry')"
        :label-cols="3"
        :invalid-feedback="feedback(certificate_expiry_field)"
        :state="state(certificate_expiry_field)"
      >
        <b-input-group v-if="!disabled">
          <p-datetimepicker
            :config="config"
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
        <h3 v-else>
          <b-badge variant="primary" class="mr-1">
            {{ value.expiry ? value.expiry : ' -- ' }}
          </b-badge>
        </h3>
      </b-form-group>
    </b-col>

    <b-col xl="6">
      <b-form-group
        :label="$t('validation.attributes.certificates_info.number')"
        :label-cols="4"
        :invalid-feedback="feedback(certificate_number_field)"
        :state="state(certificate_number_field)"
      >
        <b-form-input
          id="certificate_number"
          name="certificate_number"
          :placeholder="$t('validation.attributes.certificates_info.number')"
          v-model="value.number"
          :state="state(certificate_number_field)"
          :plaintext="disabled"
        ></b-form-input>
      </b-form-group>
    </b-col>

    <b-col xl="6">
      <b-form-group
        :label="$t('validation.attributes.certificates_info.remarks')"
        :label-cols="4"
        :invalid-feedback="feedback(certificate_remarks_field)"
        :state="state(certificate_remarks_field)"
      >
        <b-form-input
          id="certificate_remarks"
          name="certificate_remarks"
          :placeholder="$t('validation.attributes.certificates_info.remarks')"
          v-model="value.remarks"
          :state="state(certificate_remarks_field)"
          :plaintext="disabled"
        ></b-form-input>
      </b-form-group>
    </b-col>

    <b-col xl="12">
      <b-form-group
        :label="$t('validation.attributes.certificates_info.file')"
        :label-cols="3"
        :invalid-feedback="feedback(certificate_file_field)"
        class="file-upload-group required"
      >
        <div class="media">
          <div v-if="value.url" class="m-1 certificate_link">
            <a :href="value.url" target="_blank">
              {{ $t('validation.attributes.certificates_info.file') }}
            </a>
          </div>

          <div class="media-body" v-if="!disabled">
            <b-form-file
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
import Multiselect from 'vue-multiselect'
import dayjs from 'dayjs'
import validation from '../../mixins/validation'

export default {
  name: 'VesselCertificate',
  components: { Multiselect },
  mixins: [validation],
  props: {
    disabled: {
      type: Boolean,
      default: false,
      required: false
    },
    deletable: {
      type: Boolean,
      default: false,
      required: false
    },
    value: {
      default: () => ({
        id: null,
        type: null,
        number: null,
        remarks: null,
        expiry: null,
        fill: null,
        url: null
      }),
      type: Object
    },
    certificateTypes: {
      default: () => [],
      type: Array,
      required: true
    },
    index: {
      default: 0,
      type: Number
    }
    // ,errors: {
    //   default: () => null,
    //   type: Object,
    //   required: false
    // }
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
    certificate_label_field: function() {
      return 'certificates_info.label'
    },
    certificate_type_field: function() {
      return 'certificates.' + this.index + '.type'
    },
    certificate_number_field: function() {
      return 'certificates.' + this.index + '.number'
    },
    certificate_remarks_field: function() {
      return 'certificates.' + this.index + '.remarks'
    },
    certificate_expiry_field: function() {
      return 'certificates.' + this.index + '.expiry'
    },
    certificate_file_field: function() {
      return 'certificates.' + this.index + '.file'
    },
    expired: function() {
      return dayjs(this.value.expiry).diff(dayjs(), 'day') <= 0
    },
    critical: function() {
      return (
        dayjs(this.value.expiry).diff(dayjs(), 'day') <= 30 &&
        dayjs(this.value.expiry).diff(dayjs(), 'day') > 0
      )
    },
    warning: function() {
      return (
        dayjs(this.value.expiry).diff(dayjs(), 'day') <= 60 &&
        dayjs(this.value.expiry).diff(dayjs(), 'day') > 30
      )
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        id: this.value.id,
        type: this.value.type,
        number: this.value.number,
        remarks: this.value.remarks,
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
        id: this.value.id,
        type: this.value.type,
        number: this.value.number,
        remarks: this.value.remarks,
        expiry: this.$refs.expiry ? this.$refs.expiry.$el.value : null,
        file: file,
        url: this.value.url
      })
    },
    dropCertificate() {
      this.$emit('deleted', { index: this.index })
    }
    // feedback(name) {
    //   if (this.state(name)) {
    //     if (this.errors !== null) return this.errors[name][0]
    //     else this.$parent.validation.errors[name][0]
    //   }
    // },
    // state(name) {
    //   let errors =
    //     this.errors !== null ? this.errors : this.$parent.validation.errors
    //
    //   return errors !== undefined && errors.hasOwnProperty(name)
    //     ? 'invalid'
    //     : null
    // }
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
.certificate_link {
  width: 30%;
}
.certificate {
  background-color: #f0fffd;
  padding-top: 8px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 3px;
  border: 1px solid #e9ecef;
  position: relative;
}
.certificate:hover {
  background-color: #e4fffd;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
}
.close {
  position: absolute;
  right: 0px;
  top: 0px;
}
</style>
