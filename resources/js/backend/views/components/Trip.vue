<template>
  <b-row class="trip pr-6 w-200" style="position: relative">
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

    <b-button
      class="close mt-5 mr-1"
      size="md"
      variant="default"
      @click="
        value.trip_attributes.collapse_status = !value.trip_attributes
          .collapse_status
      "
      v-b-tooltip.hover
      :title="$t('buttons.flight_reservations.expand_trip')"
      v-if="value.trip_type"
    >
      <font-awesome-icon icon="sort-down"></font-awesome-icon>
    </b-button>

    <b-col xl="12">
      <b-form-group
        :label="$t('validation.attributes.trip_type')"
        :label-cols="4"
        label-for="trip_type"
        :invalid-feedback="feedback('trips.' + index + '.trip_type')"
        :state="state('trips.' + index + '.trip_type')"
      >
        <b-form-radio-group
          v-model="value.trip_type"
          id="trip_type"
          buttons
          button-variant="outline-primary"
          :options="['BUS', 'CAB', 'TRAIN', 'FERRY', 'FLIGHT', 'HOTEL']"
          name="radioInline"
          @input="tripTypeChanged()"
          :state="state('trips.' + index + '.trip_type')"
          v-if="!disabled"
        >
        </b-form-radio-group>
        <h3 v-else>
          <b-badge variant="primary">{{ value.trip_type }} </b-badge>
        </h3>
      </b-form-group>
    </b-col>
    <b-col xl="12" v-if="value.trip_type === 'FLIGHT'">
      <flight-form
        v-model="value.trip_attributes"
        :index="index"
        :ref="'trip-' + index"
        :key="index"
        :disabled="disabled"
      ></flight-form>
    </b-col>
    <b-col xl="12" v-else-if="value.trip_type === 'HOTEL'">
      <hotel-form
        v-model="value.trip_attributes"
        :index="index"
        :ref="'trip-' + index"
        :key="index"
        :disabled="disabled"
      ></hotel-form>
    </b-col>
    <b-col xl="12" v-else-if="value.trip_type">
      <transport-form
        v-model="value.trip_attributes"
        :index="index"
        :ref="'trip-' + index"
        :key="index"
        :disabled="disabled"
      ></transport-form>
    </b-col>
  </b-row>
</template>
<script>
import FlightForm from './FlightForm'
import TransportForm from './TransportForm'
import HotelForm from './HotelForm'

export default {
  name: 'Trip',
  components: {
    FlightForm,
    TransportForm,
    HotelForm
  },
  props: {
    value: {
      default: () => ({
        id: null,
        trip_type: null,
        trip_attributes: null
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
    return {}
  },
  computed: {},
  methods: {
    updateInput: function() {
      // this.$emit('input', {
      //   flight_type: this.flight_type.name,
      //   departure_date: this.value.departure_date,
      //   departure_period: this.value.departure_period,
      //   departure_from: this.value.departure_from,
      //   departure_luggage: this.value.departure_luggage,
      //   departure_to: this.value.departure_to,
      //   return_date: this.$refs.return_date
      //     ? this.$refs.return_date.$el.value
      //     : null,
      //   return_period: this.$refs.return_period
      //     ? this.$refs.return_period.$el.value
      //     : null,
      //   return_from: this.$refs.return_from
      //     ? this.$refs.return_from.$el.value
      //     : null,
      //   return_to: this.$refs.return_to ? this.$refs.return_to.$el.value : null,
      //   return_luggage: this.$refs.return_luggage
      //     ? this.$refs.return_luggage.$el.value
      //     : null,
      //   collapse_status: this.value.collapse_status
      // })
    },
    tripTypeChanged() {
      if (this.value.trip_type === 'FLIGHT') {
        this.value.trip_attributes = {
          flight_type: null,
          departure_date: null,
          departure_period: null,
          departure_from: null,
          departure_to: null,
          departure_luggage: null,
          return_date: null,
          return_period: null,
          collapse_status: true
        }
      } else if (this.value.trip_type === 'HOTEL') {
        this.value.trip_attributes = {
          location: null,
          hotel: null,
          nights: null,
          departure_date: null,
          price: null,
          collapse_status: true
        }
      } else {
        this.value.trip_attributes = {
          transport_from: null,
          transport_to: null,
          departure_date: null,
          price: null,
          collapse_status: true
        }
      }
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
.trip {
  background-color: #f0fffd;
  padding-top: 8px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 3px;
  border: 1px solid #e9ecef;
  width: 100%;
  margin-left: 1px;
}
.trip:hover {
  background-color: #e4fffd;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
}
.close {
  position: absolute;
  right: 0px;
  top: 0px;
}
</style>
