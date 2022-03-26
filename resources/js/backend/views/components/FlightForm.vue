<template>
  <b-row @click="value.collapse_status = true">
    <h6 class="mb-5 ml-1">
      {{ $t('labels.backend.flight_reservations.titles.flight') }}
    </h6>
    <b-collapse
      :id="`flight_${index}`"
      v-model="value.collapse_status"
      class="col-xl-12"
      accordion="my-accordion"
    >
      <b-col xl="12">
        <b-form-group
          :label="$t('validation.attributes.flight_type')"
          :label-cols="4"
          :invalid-feedback="feedback(flight_type_field)"
          :state="state(flight_type_field)"
        >
          <b-form-radio-group
            id="flight_type"
            v-model="value.flight_type"
            buttons
            button-variant="outline-primary"
            v-if="!disabled"
          >
            <b-form-radio
              :value="type"
              v-for="(type, index1) in flight_types"
              :key="index1"
            >
              {{
                $t(`labels.backend.flight_reservations.flight_types.${type}`)
              }}
            </b-form-radio>
          </b-form-radio-group>
          <h3 v-else>
            <b-badge variant="primary">{{ value.flight_type }} </b-badge>
          </h3>
        </b-form-group>
      </b-col>
      <b-row>
        <b-col>
          <b-form-group
            :label="$t('validation.attributes.departure_date')"
            label-for="departure_date"
            :label-cols="value.flight_type !== 'RETURN' ? 4 : null"
            :invalid-feedback="feedback(departure_date_field)"
            :state="state(departure_date_field)"
          >
            <b-input-group v-if="!disabled">
              <p-datetimepicker
                :config="config"
                id="departure_date"
                name="departure_date"
                v-model="value.departure_date"
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
                {{ value.departure_date ? value.departure_date : ' -- ' }}
              </b-badge>
            </h3>
          </b-form-group>
          <b-form-group
            :label="$t('validation.attributes.departure_period')"
            label-for="departure_period"
            :label-cols="value.flight_type !== 'RETURN' ? 4 : null"
            :invalid-feedback="feedback(departure_period_field)"
            :state="state(departure_period_field)"
          >
            <b-form-radio-group
              v-model="value.departure_period"
              buttons
              button-variant="outline-primary"
              :options="['AM', 'PM']"
              name="radioInline"
              v-if="!disabled"
            >
            </b-form-radio-group>
            <h3 v-else>
              <b-badge variant="primary">{{ value.departure_period }} </b-badge>
            </h3>
          </b-form-group>
        </b-col>
        <b-col xl="6" v-if="value.flight_type === 'RETURN'">
          <b-form-group
            :label="$t('validation.attributes.return_date')"
            label-for="return_date"
            :invalid-feedback="feedback(return_date_field)"
            :state="state(return_date_field)"
          >
            <b-input-group v-if="!disabled">
              <p-datetimepicker
                :config="config"
                id="return_date"
                name="return_date"
                v-model="value.return_date"
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
                {{ value.return_date ? value.return_date : ' -- ' }}
              </b-badge>
            </h3>
          </b-form-group>
          <b-form-group
            :label="$t('validation.attributes.return_period')"
            label-for="return_period"
            :invalid-feedback="feedback(return_period_field)"
            :state="state(return_period_field)"
          >
            <b-form-radio-group
              v-model="value.return_period"
              buttons
              button-variant="outline-primary"
              :options="['AM', 'PM']"
              name="radioInline"
              v-if="!disabled"
            >
            </b-form-radio-group>
            <h3 v-else>
              <b-badge variant="primary">{{ value.return_period }} </b-badge>
            </h3>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group
            :label="$t('validation.attributes.departure_from')"
            label-for="departure_from"
            :label-cols="4"
            :invalid-feedback="feedback(departure_from_field)"
          >
            <b-form-input
              id="departure_from"
              name="departure_from"
              :placeholder="$t('validation.attributes.departure_from')"
              v-model="value.departure_from"
              :state="state(departure_from_field)"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>

          <b-form-group
            :label="$t('validation.attributes.departure_to')"
            label-for="departure_to"
            :label-cols="4"
            :invalid-feedback="feedback(departure_to_field)"
          >
            <b-form-input
              id="departure_to"
              name="departure_to"
              :placeholder="$t('validation.attributes.departure_to')"
              v-model="value.departure_to"
              :state="state(departure_to_field)"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>

          <b-form-group
            :label="$t('validation.attributes.departure_luggage')"
            label-for="departure_luggage"
            :label-cols="4"
            :invalid-feedback="feedback(departure_luggage_field)"
          >
            <b-input-group append="KG">
              <b-form-input
                id="departure_luggage"
                name="departure_luggage"
                :type="disabled ? 'text' : 'number'"
                :placeholder="$t('validation.attributes.departure_luggage')"
                v-model="value.departure_luggage"
                :state="state(departure_luggage_field)"
                :plaintext="disabled"
              ></b-form-input>
            </b-input-group>
          </b-form-group>

          <b-form-group
            :label="$t('validation.attributes.price')"
            label-for="price"
            :label-cols="4"
            :invalid-feedback="feedback(price_field)"
            :state="state(price_field)"
          >
            <b-form-input
              id="price"
              name="price"
              :placeholder="$t('validation.attributes.price')"
              v-model="value.price"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
    </b-collapse>
  </b-row>
</template>
<script>
export default {
  name: 'FlightForm',
  props: {
    value: {
      default: () => ({
        id: 0,
        flight_type: null,
        departure_date: null,
        departure_period: null,
        departure_from: null,
        departure_to: null,
        departure_luggage: null,
        return_date: null,
        return_period: null,
        price: null,
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
      },
      flight_types: ['ONEWAY', 'RETURN']
    }
  },
  computed: {
    flight_type_field: function() {
      return 'trips.' + this.index + '.trip_attributes.flight_type'
    },
    departure_date_field: function() {
      return 'trips.' + this.index + '.trip_attributes.departure_date'
    },
    departure_period_field: function() {
      return 'trips.' + this.index + '.trip_attributes.departure_period'
    },
    departure_from_field: function() {
      return 'trips.' + this.index + '.trip_attributes.departure_from'
    },
    departure_to_field: function() {
      return 'trips.' + this.index + '.trip_attributes.departure_to'
    },
    departure_luggage_field: function() {
      return 'trips.' + this.index + '.trip_attributes.departure_luggage'
    },
    return_date_field: function() {
      return 'trips.' + this.index + '.trip_attributes.return_date'
    },
    return_period_field: function() {
      return 'trips.' + this.index + '.trip_attributes.return_period'
    },
    price_field: function() {
      return 'trips.' + this.index + '.trip_attributes.price'
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        flight_type: this.flight_type.name,
        departure_date: this.value.departure_date,
        departure_period: this.value.departure_period,
        departure_from: this.value.departure_from,
        departure_luggage: this.value.departure_luggage,
        departure_to: this.value.departure_to,
        price_field: this.value.price_field,
        return_date: this.$refs.return_date
          ? this.$refs.return_date.$el.value
          : null,
        return_period: this.$refs.return_period
          ? this.$refs.return_period.$el.value
          : null,
        return_from: this.$refs.return_from
          ? this.$refs.return_from.$el.value
          : null,
        return_to: this.$refs.return_to ? this.$refs.return_to.$el.value : null,
        return_luggage: this.$refs.return_luggage
          ? this.$refs.return_luggage.$el.value
          : null,
        collapse_status: this.value.collapse_status
      })
    },
    feedback(name) {
      if (this.state(name)) {
        return this.$parent.$parent.validation.errors[name][0]
      }
    },
    state(name) {
      return this.$parent.$parent.validation.errors !== undefined &&
        this.$parent.$parent.validation.errors.hasOwnProperty(name)
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
