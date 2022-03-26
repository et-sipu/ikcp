<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="12">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t(
                      'labels.backend.flight_reservation_quotations.titles.create'
                    )
                  : $t(
                      'labels.backend.flight_reservation_quotations.titles.edit'
                    )
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.flight_time')"
                  label-for="flight_time"
                  :label-cols="3"
                  :invalid-feedback="feedback('flight_time')"
                  :state="state('flight_time')"
                >
                  <b-input-group>
                    <p-datetimepicker
                      :config="config"
                      id="flight_time"
                      name="flight_time"
                      v-model="model.flight_time"
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

                <b-form-group
                  :label="$t('validation.attributes.heading')"
                  label-for="heading"
                  :label-cols="3"
                  :invalid-feedback="feedback('heading')"
                  :state="state('heading')"
                >
                  <multiselect
                    v-model="model.heading"
                    :options="headings"
                    id="heading"
                    name="heading"
                    track-by="id"
                    label="name"
                    :close-on-select="true"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.heading') + ' --'
                    "
                    :state="state('heading')"
                  ></multiselect>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.airlines')"
                  label-for="airlines"
                  :label-cols="3"
                  :invalid-feedback="feedback('airlines')"
                >
                  <b-form-input
                    id="airlines"
                    name="airlines"
                    :placeholder="$t('validation.attributes.airlines')"
                    v-model="model.airlines"
                    :state="state('airlines')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.website')"
                  label-for="website"
                  :label-cols="3"
                  :invalid-feedback="feedback('website')"
                >
                  <b-form-input
                    id="website"
                    name="website"
                    :placeholder="$t('validation.attributes.website')"
                    v-model="model.website"
                    :state="state('website')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.price')"
                  label-for="price"
                  :label-cols="3"
                  :invalid-feedback="feedback('price')"
                >
                  <b-input-group append="MYR">
                    <b-form-input
                      id="price"
                      name="price"
                      :placeholder="$t('validation.attributes.price')"
                      v-model="model.price"
                      :state="state('price')"
                    ></b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  variant="danger"
                  size="sm"
                  @click="$emit('form-submission-canceled')"
                >
                  {{ $t('buttons.cancel') }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  class="float-right"
                  :disabled="pending"
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                </b-button>
              </b-col>
            </b-row>
          </b-card>
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
import axios from 'axios'
import form from '../mixins/form'
import Multiselect from 'vue-multiselect'

export default {
  name: 'FlightReservationQuotationForm',
  components: { Multiselect },
  mixins: [form],
  props: {
    flightReservationId: {
      type: [Number, String],
      required: true
    }
  },
  data() {
    return {
      modelName: 'flight_reservation_quotation',
      resourceRoute: 'flight_reservation_quotations',
      model: {
        flight_time: '12:00',
        airlines: null,
        website: null,
        price: null,
        heading: null,
        flight_reservation_id: null
      },
      headings: [],
      config: {
        wrap: true,
        allowInput: false,
        clickOpens: false,
        enableTime: true,
        noCalendar: true
      }
    }
  },
  computed: {
    listPath() {
      return (
        '/AdminForms/flight_reservations/' + this.flightReservationId + '/edit'
      )
    }
  },
  async created() {
    this.model.flight_reservation_id = this.flightReservationId
    let { data } = await axios.get(
      this.$app.route(`flight_reservations.get_flight_items`, {
        flight_reservation: this.flightReservationId
      })
    )
    this.headings = data
  }
}
</script>
