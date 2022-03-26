<template>
  <b-row class="qualification pr-5" style="position: relative">
    <b-button
      class="close"
      size="sm"
      variant="danger"
      v-b-tooltip.hover
      :title="$t('buttons.employees.drop_qualification')"
      @click="dropQualification()"
    >
      <i class="fe fe-x"></i>
    </b-button>

    <b-col xl="6">
      <b-form-group
        :label="$t('validation.attributes.qualifications_info.institute')"
        :invalid-feedback="feedback(qualification_authority_field)"
        :state="state(qualification_authority_field)"
      >
        <b-form-input
          :placeholder="
            $t('validation.attributes.qualifications_info.institute')
          "
          v-model="value.authority"
          :state="state(qualification_authority_field)"
        ></b-form-input>
      </b-form-group>
    </b-col>

    <b-col xl="6">
      <b-form-group
        :label="$t('validation.attributes.qualifications_info.specialization')"
        :invalid-feedback="feedback(qualification_degree_field)"
        :state="state(qualification_degree_field)"
      >
        <b-form-input
          :placeholder="
            $t('validation.attributes.qualifications_info.specialization')
          "
          v-model="value.degree"
          :state="state(qualification_degree_field)"
        ></b-form-input>
      </b-form-group>
    </b-col>

    <b-col xl="4">
      <b-form-group
        :label="$t('validation.attributes.qualifications_info.year')"
        :invalid-feedback="feedback(qualification_year_field)"
        :state="state(qualification_year_field)"
      >
        <b-form-input
          :placeholder="$t('validation.attributes.qualifications_info.year')"
          v-model="value.year"
          :state="state(qualification_year_field)"
        ></b-form-input>
      </b-form-group>
    </b-col>

    <b-col xl="8">
      <b-form-group
        :label="$t('validation.attributes.qualifications_info.file')"
        :invalid-feedback="feedback(qualification_file_field)"
        class="file-upload-group required"
      >
        <div class="media">
          <div v-if="value.url" class="m-1 qualification_link">
            <a :href="value.url" target="_blank">
              {{ $t('validation.attributes.qualifications_info.file') }}
            </a>
          </div>

          <div class="media-body">
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
export default {
  name: 'EmployeeProfessionalQualification',
  props: {
    value: {
      default: () => ({
        id: null,
        authority: null,
        degree: null,
        year: null,
        type: 'Professional',
        url: null,
        file: null
      }),
      type: Object
    },
    index: {
      default: 0,
      type: Number
    },
    compName: {
      default: 'professional_qualification',
      type: String,
      required: false
    }
  },
  computed: {
    qualification_authority_field: function() {
      return (
        'qualifications_info.professional_qualifications.' +
        this.index +
        '.authority'
      )
    },
    qualification_degree_field: function() {
      return (
        'qualifications_info.professional_qualifications.' +
        this.index +
        '.degree'
      )
    },
    qualification_year_field: function() {
      return (
        'qualifications_info.professional_qualifications.' +
        this.index +
        '.year'
      )
    },
    qualification_file_field: function() {
      return (
        'qualifications_info.professional_qualifications.' +
        this.index +
        '.file'
      )
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        id: this.value.id,
        authority: this.value.authority,
        degree: this.value.degree,
        year: this.value.year,
        type: this.value.type,
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
        authority: this.value.authority,
        degree: this.value.degree,
        year: this.value.year,
        type: this.value.type,
        file: file,
        url: this.value.url
      })
    },
    dropQualification() {
      this.$emit('deleted', { index: this.index })
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
.qualification {
  background-color: #f0fffd;
  padding-top: 8px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 3px;
  border: 1px solid #e9ecef;
}
.qualification:hover {
  background-color: #e4fffd;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
}
.close {
  position: absolute;
  right: 0px;
  top: 0px;
}
.qualification_link {
  width: 30%;
}
</style>
