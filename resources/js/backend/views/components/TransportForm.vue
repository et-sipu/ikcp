<template>
  <b-row @click="value.collapse_status = true">
    <h6 class="mb-5 ml-1">
      {{ $t('labels.backend.flight_reservations.titles.transport') }}
    </h6>
    <b-collapse
      v-model="value.collapse_status"
      class="col-xl-12"
      accordion="my-accordion"
      :id="`${value.trip_type}_${index}`"
    >
      <b-row>
        <b-col xl="6">
          <b-form-group
            :label="$t('validation.attributes.transport_from')"
            label-for="transport_from"
            :invalid-feedback="feedback(transport_from_field)"
          >
            <b-form-input
              id="transport_from"
              name="transport_from"
              :placeholder="$t('validation.attributes.transport_from')"
              v-model="value.transport_from"
              :state="state(transport_from_field)"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col xl="6">
          <b-form-group
            :label="$t('validation.attributes.transport_to')"
            label-for="transport_to"
            :invalid-feedback="feedback(transport_to_field)"
          >
            <b-form-input
              id="transport_to"
              name="transport_to"
              :placeholder="$t('validation.attributes.transport_to')"
              v-model="value.transport_to"
              :state="state(transport_to_field)"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col xl="6">
          <b-form-group
            :label="$t('validation.attributes.departure_date')"
            label-for="departure_date"
            :label-cols="10"
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
        </b-col>
        <b-col xl="6">
          <b-form-group
            :label="$t('validation.attributes.price')"
            label-for="price"
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
  name: 'TansportForm',
  props: {
    value: {
      default: () => ({
        id: 0,
        transport_from: null,
        transport_to: null,
        departure_date: null,
        price: null,
        collapse_status: true
      }),
      type: Object
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
      config: {
        wrap: true,
        allowInput: true
      }
    }
  },
  computed: {
    transport_from_field: function() {
      return 'trips.' + this.index + '.trip_attributes.transport_from'
    },
    transport_to_field: function() {
      return 'trips.' + this.index + '.trip_attributes.transport_to'
    },
    departure_date_field: function() {
      return 'trips.' + this.index + '.trip_attributes.departure_date'
    },
    price_field: function() {
      return 'trips.' + this.index + '.trip_attributes.price'
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        transport_from: this.transport_from,
        transport_to: this.value.transport_to,
        departure_date: this.value.departure_date,
        price_field: this.value.price_field,
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
