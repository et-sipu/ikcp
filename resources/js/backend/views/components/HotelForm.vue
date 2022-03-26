<template>
  <b-row @click="value.collapse_status = true">
    <h6 class="mb-5 ml-1">
      {{ $t('labels.backend.flight_reservations.titles.hotel') }}
    </h6>
    <b-collapse
      v-model="value.collapse_status"
      class="col-xl-12"
      accordion="my-accordion"
      :id="`hotel_${index}`"
    >
      <b-row>
        <b-col xl="6">
          <b-form-group
            :label="$t('validation.attributes.location')"
            label-for="location"
            :invalid-feedback="feedback(location_field)"
          >
            <b-form-input
              id="location"
              name="location"
              :placeholder="$t('validation.attributes.location')"
              v-model="value.location"
              :state="state(location_field)"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col xl="6">
          <b-form-group
            :label="$t('validation.attributes.hotel')"
            label-for="hotel"
            :invalid-feedback="feedback(hotel_field)"
          >
            <b-form-input
              id="hotel"
              name="hotel"
              :placeholder="$t('validation.attributes.hotel')"
              v-model="value.hotel"
              :state="state(hotel_field)"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col xl="4">
          <b-form-group
            :label="$t('validation.attributes.nights')"
            label-for="nights"
            :invalid-feedback="feedback(nights_field)"
          >
            <b-form-input
              id="nights"
              name="nights"
              :placeholder="$t('validation.attributes.nights')"
              v-model="value.nights"
              :state="state(nights_field)"
              :type="disabled ? 'text' : 'number'"
              :plaintext="disabled"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col xl="4">
          <b-form-group
            :label="$t('validation.attributes.check_in')"
            label-for="check_in"
            :invalid-feedback="feedback(check_in_field)"
            :state="state(check_in_field)"
          >
            <b-input-group v-if="!disabled">
              <p-datetimepicker
                :config="config"
                id="check_in"
                name="check_in"
                v-model="value.check_in"
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
                {{ value.check_in ? value.check_in : ' -- ' }}
              </b-badge>
            </h3>
          </b-form-group>
        </b-col>
        <b-col xl="4">
          <b-form-group
            :label="$t('validation.attributes.price')"
            label-for="price"
            :invalid-feedback="feedback(price_field)"
          >
            <b-form-input
              id="price"
              name="price"
              :placeholder="$t('validation.attributes.price')"
              v-model="value.price"
              :state="state(price_field)"
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
  name: 'HotelForm',
  props: {
    value: {
      default: () => ({
        id: 0,
        location: null,
        hotel: null,
        nights: null,
        check_in: null,
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
    location_field: function() {
      return 'trips.' + this.index + '.trip_attributes.location'
    },
    hotel_field: function() {
      return 'trips.' + this.index + '.trip_attributes.hotel'
    },
    nights_field: function() {
      return 'trips.' + this.index + '.trip_attributes.nights'
    },
    check_in_field: function() {
      return 'trips.' + this.index + '.trip_attributes.check_in'
    },
    price_field: function() {
      return 'trips.' + this.index + '.trip_attributes.price'
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        location: this.value.location,
        hotel: this.value.hotel,
        nights: this.value.nights,
        check_in: this.value.check_in,
        price: this.value.price,
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
