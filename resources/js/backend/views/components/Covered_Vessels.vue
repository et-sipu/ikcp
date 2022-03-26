<template>
  <b-row
    @click="value.collapse_status = true"
    class="trip pr-6 w-200"
    style="position: relative"
  >
    <b-button
      class="close"
      size="sm"
      variant="danger"
      v-b-tooltip.hover
      :title="$t('buttons.flight_reservations.drop_trip')"
      @click="drop()"
      v-if="!disabled"
    >
      <i class="fe fe-x"></i>
    </b-button>
    <b-collapse
      v-model="value.collapse_status"
      class="col-xl-12"
      :id="`hotel_${index}`"
    >
      <b-row>
        <b-col xl="6">
          <b-form-group
            :label="$t('validation.attributes.vessel_covered')"
            label-for="Permissions"
            :invalid-feedback="feedback(vessel_id_field)"
          >
            <multiselect
              v-model="value.vessel_covered"
              :options="vessels"
              id="hod"
              name="hod"
              track-by="id"
              label="name"
              :searchable="true"
              :close-on-select="true"
              :show-labels="false"
              :state="state(value_covered_field)"
            ></multiselect>
          </b-form-group>
        </b-col>
        <b-col xl="6">
          <b-form-group
            :label="$t('validation.attributes.value_covered')"
            label-for="value_covered"
            :invalid-feedback="feedback(value_covered_field)"
            :lable-cols="4"
          >
            <b-form-input
              id="value_covered"
              name="value_covered"
              v-model="value.value_covered"
              :state="state(value_covered_field)"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
    </b-collapse>
  </b-row>
</template>
<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
export default {
  name: 'HotelForm',
  components: {
    Multiselect
  },
  props: {
    value: {
      default: () => ({
        vessel_covered: null,
        value_covered: null,
        collapse_status: true
      }),
      type: Object
    },
    vessels: {
      type: Array,
      default() {
        return ''
      }
    },
    index: {
      default: 0,
      type: Number
    },
    disabled: {
      type: Boolean,
      default: false,
      required: false
    }
  },
  data() {
    return {
      vessels_name: [],
      config: {
        wrap: true,
        allowInput: true
      }
    }
  },
  computed: {
    vessel_id_field: function() {
      return 'trips.' + this.index + '.trip_attributes.vessel_id'
    },
    value_covered_field: function() {
      return 'trips.' + this.index + '.trip_attributes.value_covered'
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        vessel_id: this.value.vessel_id,
        value_covered: this.value.value_covered,
        collapse_status: this.value.collapse_status
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
    },
    drop() {
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
.passenger_link {
  width: 28%;
}
.passenger {
  background-color: #f0fffd;
  padding-top: 8px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 3px;
  border: 1px solid #e9ecef;
  width: 100%;
  margin-left: 1px;
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
