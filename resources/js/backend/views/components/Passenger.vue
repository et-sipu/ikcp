<template>
  <b-row class="passenger pr-5" style="position: relative">
    <b-button
      class="close"
      size="sm"
      variant="danger"
      v-b-tooltip.hover
      :title="$t('buttons.flight_reservations.drop_passenger')"
      @click="dropPassenger()"
      v-if="!disabled"
    >
      <i class="fe fe-x"></i>
    </b-button>

    <b-button
      class="close mt-5 mr-1"
      size="md"
      variant="default"
      @click="value.collapse_status = !value.collapse_status"
      v-b-tooltip.hover
      :title="$t('buttons.flight_reservations.expand_passenger')"
    >
      <font-awesome-icon icon="sort-down"></font-awesome-icon>
    </b-button>

    <b-col xl="6">
      <b-form-group
        :label="$t('validation.attributes.passengers_info.name')"
        :invalid-feedback="feedback(passenger_name_field)"
        :state="state(passenger_name_field)"
      >
        <b-form-input
          id="passenger_name"
          name="passenger_name"
          :placeholder="$t('validation.attributes.passengers_info.name')"
          v-model="value.name"
          :state="state(passenger_name_field)"
          :plaintext="disabled"
        ></b-form-input>
      </b-form-group>
    </b-col>
    <b-col xl="6">
      <b-form-group
        :label="$t('validation.attributes.passengers_info.designation')"
        :invalid-feedback="feedback(passenger_designation_field)"
        :state="state(passenger_designation_field)"
      >
        <b-form-input
          id="passenger_designation"
          name="passenger_designation"
          :placeholder="$t('validation.attributes.passengers_info.designation')"
          v-model="value.designation"
          :state="state(passenger_designation_field)"
          :plaintext="disabled"
        ></b-form-input>
      </b-form-group>
    </b-col>

    <b-collapse
      :id="`passenger_details_${index}`"
      v-model="value.collapse_status"
      class="mt-2"
      accordion="my-passengers"
    >
      <b-row class="mx-0">
        <b-col xl="4">
          <b-form-group
            :label="$t('validation.attributes.passengers_info.nationality')"
            :invalid-feedback="feedback(passenger_nationality_field)"
            :state="state(passenger_nationality_field)"
          >
            <b-form-input
              id="passenger_nationality"
              name="passenger_nationality"
              :placeholder="
                $t('validation.attributes.passengers_info.nationality')
              "
              v-model="value.nationality"
              :state="state(passenger_nationality_field)"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col xl="4">
          <b-form-group
            :label="$t('validation.attributes.passengers_info.hp')"
            :invalid-feedback="feedback(passenger_hp_field)"
            :state="state(passenger_hp_field)"
          >
            <b-form-input
              id="passenger_hp"
              name="passenger_hp"
              :placeholder="$t('validation.attributes.passengers_info.hp')"
              v-model="value.hp"
              :state="state(passenger_hp_field)"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col xl="4">
          <b-form-group
            :label="$t('validation.attributes.passengers_info.passport')"
            :invalid-feedback="feedback(passenger_passport_field)"
            :state="state(passenger_passport_field)"
          >
            <b-form-input
              id="passenger_passport"
              name="passenger_passport"
              :placeholder="
                $t('validation.attributes.passengers_info.passport')
              "
              v-model="value.passport"
              :state="state(passenger_passport_field)"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col xl="6">
          <b-form-group
            :label="$t('validation.attributes.passengers_info.dob')"
            :invalid-feedback="feedback(passenger_dob_field)"
            :state="state(passenger_dob_field)"
          >
            <b-input-group v-if="!disabled">
              <p-datetimepicker
                :config="config"
                :value="value.dob"
                ref="dob"
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
                {{ value.dob ? value.dob : ' -- ' }}
              </b-badge>
            </h3>
          </b-form-group>
        </b-col>
        <b-col xl="6">
          <b-form-group
            :label="$t('validation.attributes.passengers_info.expiry')"
            :invalid-feedback="feedback(passenger_expiry_field)"
            :state="state(passenger_expiry_field)"
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

        <b-col xl="12">
          <b-form-group
            :label="$t('validation.attributes.passengers_info.file')"
            :label-cols="3"
            :invalid-feedback="feedback(passenger_file_field)"
            class="file-upload-group required"
          >
            <div class="media">
              <div v-if="value.url" class="m-1 passenger_link">
                <a :href="value.url" target="_blank">
                  {{ $t('validation.attributes.passengers_info.file') }}
                </a>
              </div>

              <div class="media-body" v-if="!disabled">
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
    </b-collapse>
  </b-row>
</template>
<script>
export default {
  name: 'VesselPassenger',
  props: {
    value: {
      default: () => ({
        id: 0,
        name: null,
        designation: null,
        passport: null,
        nationality: null,
        dob: null,
        hp: null,
        expiry: null,
        fill: null,
        url: null,
        collapse_status: true
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
    passenger_label_field: function() {
      return 'passengers_info.label'
    },
    passenger_name_field: function() {
      return 'passengers.' + this.index + '.name'
    },
    passenger_designation_field: function() {
      return 'passengers.' + this.index + '.designation'
    },
    passenger_passport_field: function() {
      return 'passengers.' + this.index + '.passport'
    },
    passenger_nationality_field: function() {
      return 'passengers.' + this.index + '.nationality'
    },
    passenger_dob_field: function() {
      return 'passengers.' + this.index + '.dob'
    },
    passenger_hp_field: function() {
      return 'passengers.' + this.index + '.hp'
    },
    passenger_expiry_field: function() {
      return 'passengers.' + this.index + '.expiry'
    },
    passenger_file_field: function() {
      return 'passengers.' + this.index + '.file'
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        id: this.value.id,
        name: this.value.name,
        designation: this.value.designation,
        passport: this.value.passport,
        nationality: this.value.nationality,
        dob: this.$refs.dob ? this.$refs.dob.$el.value : null,
        hp: this.value.hp,
        expiry: this.$refs.expiry ? this.$refs.expiry.$el.value : null,
        file: this.$refs[`${this.compName}-${this.index}`].$el.firstChild.files
          .length
          ? this.$refs[`${this.compName}-${this.index}`].$el.firstChild.files[0]
          : null,
        url: this.value.url,
        collapse_status: this.value.collapse_status
      })
    },
    onFileChange(e) {
      const file = e.target.files[0]
      this.$emit('input', {
        id: this.value.id,
        name: this.value.name,
        designation: this.value.designation,
        passport: this.value.passport,
        nationality: this.value.nationality,
        dob: this.$refs.dob ? this.$refs.dob.$el.value : null,
        hp: this.value.hp,
        expiry: this.$refs.expiry ? this.$refs.expiry.$el.value : null,
        file: file,
        url: this.value.url,
        collapse_status: this.value.collapse_status
      })
    },
    dropPassenger() {
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
.btn-group >>> .btn.active {
  z-index: 0 !important;
}
.is-invalid >>> .multiselect__tags {
  border-color: #f86c6b !important;
}
.is-invalid >>> .form-control {
  border-color: #f86c6b !important;
}
.passenger_link {
  width: 30%;
}
.passenger {
  background-color: #f0fffd;
  padding-top: 8px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 3px;
  border: 1px solid #e9ecef;
}
.passenger:hover {
  background-color: #e4fffd;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
}
.close {
  position: absolute;
  right: 0px;
  top: 0px;
}
</style>
