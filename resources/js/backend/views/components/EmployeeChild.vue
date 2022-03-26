<template>
  <b-row class="child pr-5" style="position: relative">
    <b-button
      class="close"
      size="sm"
      variant="danger"
      v-b-tooltip.hover
      :title="$t('buttons.employees.drop_child')"
      @click="dropChild()"
    >
      <i class="fe fe-x"></i>
    </b-button>

    <b-col xl="4">
      <b-form-group
        :label="$t('validation.attributes.children_info.name')"
        :invalid-feedback="feedback(child_name_field)"
        :state="state(child_name_field)"
      >
        <b-form-input
          :placeholder="$t('validation.attributes.children_info.name')"
          v-model="value.name"
          :state="state(child_name_field)"
        ></b-form-input>
      </b-form-group>
    </b-col>

    <b-col xl="4">
      <b-form-group
        :label="$t('validation.attributes.children_info.icno')"
        :invalid-feedback="feedback(child_icno_field)"
        :state="state(child_icno_field)"
      >
        <b-form-input
          :placeholder="$t('validation.attributes.children_info.icno')"
          v-model="value.icno"
          :state="state(child_icno_field)"
        ></b-form-input>
      </b-form-group>
    </b-col>

    <b-col xl="4">
      <b-form-group
        :label="$t('validation.attributes.children_info.bcno')"
        :invalid-feedback="feedback(child_bcno_field)"
        :state="state(child_bcno_field)"
      >
        <b-form-input
          :placeholder="$t('validation.attributes.children_info.bcno')"
          v-model="value.bcno"
          :state="state(child_bcno_field)"
        ></b-form-input>
      </b-form-group>
    </b-col>

    <b-col xl="4">
      <b-form-group
        :label="$t('validation.attributes.children_info.dob')"
        label-for="child_dob"
        :invalid-feedback="feedback(child_dob_field)"
        :state="state(child_dob_field)"
      >
        <b-input-group>
          <p-datetimepicker
            :config="config"
            id="child_dob"
            name="child_dob"
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
      </b-form-group>
    </b-col>

    <b-col xl="4">
      <b-form-group
        :label="$t('validation.attributes.children_info.gender')"
        label-for="gender"
        :invalid-feedback="feedback(child_gender_field)"
        :state="state(child_gender_field)"
        class="gender"
      >
        <b-form-radio-group
          v-model="value.gender"
          :state="state(child_gender_field)"
          :options="['MALE', 'FEMALE']"
          name="gender"
        >
        </b-form-radio-group>
      </b-form-group>
    </b-col>

    <b-col xl="4">
      <b-form-group
        :label="$t('validation.attributes.children_info.marital_status')"
        label-for="marital_status"
        :invalid-feedback="feedback(child_marital_status_field)"
        :state="state(child_marital_status_field)"
      >
        <b-form-radio-group
          v-model="value.marital_status"
          :state="state(child_marital_status_field)"
          :options="['Single', 'Married', 'Student']"
        >
        </b-form-radio-group>
      </b-form-group>
    </b-col>
  </b-row>
</template>
<script>
export default {
  name: 'EmployeeChild',
  props: {
    value: {
      default: () => ({
        name: null,
        icno: null,
        bcno: null,
        dob: null,
        gender: null,
        marital_status: null
      }),
      type: Object
    },
    index: {
      default: 0,
      type: Number
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
    child_name_field: function() {
      return 'children_info.' + this.index + '.name'
    },
    child_icno_field: function() {
      return 'children_info.' + this.index + '.icno'
    },
    child_bcno_field: function() {
      return 'children_info.' + this.index + '.bcno'
    },
    child_dob_field: function() {
      return 'children_info.' + this.index + '.dob'
    },
    child_gender_field: function() {
      return 'children_info.' + this.index + '.gender'
    },
    child_marital_status_field: function() {
      return 'children_info.' + this.index + '.marital_status'
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        name: this.value.name,
        icno: this.value.icno,
        bcno: this.value.bcno,
        dob: this.$refs.dob ? this.$refs.dob.$el.value : null,
        gender: this.value.gender,
        marital_status: this.value.marital_status
      })
    },
    dropChild() {
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
.child {
  background-color: #f0fffd;
  padding-top: 8px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 3px;
  border: 1px solid #e9ecef;
}
.child:hover {
  background-color: #e4fffd;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
}
.close {
  position: absolute;
  right: 0px;
  top: 0px;
}
.gender >>> .col {
  margin: auto;
}
</style>
