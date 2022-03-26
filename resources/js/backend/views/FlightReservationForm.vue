<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-card style="overflow: hidden">
        <template slot="header">
          <b-row class="w-100 mr-0 ml-0">
            <b-col>
              <h4>
                {{
                  isNew
                    ? $t('labels.backend.flight_reservations.titles.create')
                    : $t('labels.backend.flight_reservations.titles.edit')
                }}
              </h4>
            </b-col>
            <b-col>
              <b-row v-if="!isNew" class="justify-content-center pos w-100">
                <div class="wrapper-mains">
                  <div class="left-align w-100">
                    <StepProgress
                      v-if="model.status"
                      :length="9"
                      :available-transitions="
                        model.available_transitions.length ? true : false
                      "
                      :status="model.status"
                      :step-history="model.transitions"
                      :active-color="
                        model.status === 'rejected' ? '#cd201f' : '#09856e'
                      "
                      workflow="flight_reservations"
                    ></StepProgress>
                  </div>
                </div>
              </b-row>
            </b-col>
            <b-col>
              <div class="card-options float-right" v-if="!isNew">
                <b-btn
                  size="sm"
                  class="mr-1"
                  v-b-tooltip.hover
                  :title="$t('buttons.flight_reservations.generate_prf')"
                  @click="generatePrf"
                  v-if="model.status === 'prf_issuing' && !model.prf"
                >
                  <font-awesome-icon icon="newspaper"></font-awesome-icon>
                </b-btn>
                <b-btn
                  size="sm"
                  class="mr-1"
                  v-b-tooltip.hover
                  :title="$t('buttons.flight_reservations.view_prf')"
                  :to="{
                    name: 'payment_requisitions_edit',
                    params: { id: model.prf }
                  }"
                  v-else-if="model.prf"
                >
                  <font-awesome-icon
                    icon="file-invoice-dollar"
                  ></font-awesome-icon>
                </b-btn>
                <div v-if="model.status !== null">
                  <h3 class="mb-0">
                    <b-badge
                      :variant="
                        model.status === 'rejected' ? 'danger' : 'primary'
                      "
                    >
                      {{
                        $t(
                          `labels.backend.flight_reservations.states.${model.status}`
                        )
                      }}
                    </b-badge>
                  </h3>
                </div>
              </div>
            </b-col>
          </b-row>
        </template>

        <b-row class="justify-content-center">
          <b-col>
            <template v-if="!isNew">
              <b-form-group
                :label="$t('validation.attributes.requester')"
                :label-cols="4"
              >
                <h3 class="my-auto">
                  <b-badge variant="primary">
                    {{ model.requester_name }}
                  </b-badge>
                </h3>
              </b-form-group>
              <b-form-group
                :label="$t('validation.attributes.department')"
                :label-cols="4"
              >
                <h3>
                  <b-badge variant="primary">
                    {{ model.department_name }}
                  </b-badge>
                </h3>
              </b-form-group>
            </template>

            <b-form-group
              :label="$t('validation.attributes.form_type')"
              :label-cols="4"
              :invalid-feedback="feedback('form_type')"
              :state="state('form_type')"
            >
              <b-form-radio-group
                id="form_type"
                v-model="model.form_type"
                buttons
                button-variant="outline-primary"
                v-if="model.can_edit && !model.status"
                @change="model.form_target = null"
              >
                <b-form-radio
                  :value="type"
                  v-for="(type, index) in form_types"
                  :key="index"
                >
                  {{
                    $t(`labels.backend.flight_reservations.form_types.${type}`)
                  }}
                </b-form-radio>
              </b-form-radio-group>
              <h3 v-else>
                <b-badge variant="primary">
                  {{
                    $t(
                      `labels.backend.flight_reservations.form_types.${model.form_type}`
                    )
                  }}
                </b-badge>
              </h3>
            </b-form-group>

            <b-form-group
              :label="$t('validation.attributes.form_target')"
              label-for="form_target"
              :label-cols="4"
              :invalid-feedback="feedback('form_target')"
              :state="state('form_target')"
              v-if="model.form_type"
            >
              <multiselect
                v-model="model.form_target"
                :options="vessels"
                id="vessel"
                name="vessel"
                track-by="id"
                label="name"
                :searchable="false"
                :close-on-select="true"
                :show-labels="false"
                :disabled="
                  !(
                    model.can_edit &&
                    (RegExp('hod_approving').test(model.status) ||
                      !model.status)
                  )
                "
                :placeholder="
                  '-- ' + $t('validation.attributes.vessel') + ' --'
                "
                v-if="model.form_type === 'VESSEL'"
                :state="state('form_target')"
              ></multiselect>
              <b-form-input
                id="form_target"
                name="form_target"
                :placeholder="$t('validation.attributes.form_target')"
                v-model="model.form_target"
                :state="state('form_target')"
                :disabled="
                  !(
                    model.can_edit &&
                    (RegExp('hod_approving').test(model.status) ||
                      !model.status)
                  )
                "
                v-else
              ></b-form-input>
            </b-form-group>
            <b-form-group
              :label="$t('validation.attributes.destination')"
              label-for="departure_from"
              :label-cols="4"
            >
              <b-input-group>
                <b-form-input
                  id="departure_from"
                  name="departure_from"
                  :placeholder="$t('validation.attributes.departure_from')"
                  v-model="model.departure_from"
                  :state="state('departure_from')"
                  :disabled="
                    !(
                      model.can_edit &&
                      (RegExp('hod_approving').test(model.status) ||
                        !model.status)
                    )
                  "
                ></b-form-input>
                <span
                  class="input-group-btn"
                  style="font-size: x-large;font-weight: bolder"
                  >&#8646;</span
                >
                <b-form-input
                  id="departure_to"
                  name="departure_to"
                  :placeholder="$t('validation.attributes.departure_to')"
                  v-model="model.departure_to"
                  :state="state('departure_to')"
                  :disabled="
                    !(
                      model.can_edit &&
                      (RegExp('hod_approving').test(model.status) ||
                        !model.status)
                    )
                  "
                ></b-form-input>
              </b-input-group>
            </b-form-group>

            <fieldset class="form-fieldset">
              <h5 class="mb-5">
                {{
                  $t('labels.backend.flight_reservations.titles.transportation')
                }}
              </h5>

              <b-form-group
                :invalid-feedback="feedback('trips')"
                :state="state('trips')"
              >
              </b-form-group>

              <div>
                <b-row v-for="(trip, index) in model.trips" :key="index">
                  <template v-if="trip.id === null || trip.id !== 0">
                    <Trip
                      v-model="model.trips[index]"
                      :index="index"
                      @deleted="dropTrip"
                      :disabled="
                        !(
                          model.can_edit &&
                          (RegExp('hod_approving').test(model.status) ||
                            RegExp('budgeting').test(model.status) ||
                            !model.status)
                        )
                      "
                    ></Trip>
                  </template>
                </b-row>
                <div
                  class="float-right"
                  v-if="
                    model.can_edit &&
                      (RegExp('hod_approving').test(model.status) ||
                        RegExp('budgeting').test(model.status) ||
                        !model.status)
                  "
                >
                  <b-button
                    size="sm"
                    variant="primary"
                    v-b-tooltip.hover
                    :title="$t('buttons.flight_reservations.add_passenger')"
                    @click="addTrip()"
                  >
                    <i class="fe fe-plus-circle"></i>
                  </b-button>
                </div>
              </div>
            </fieldset>
          </b-col>
          <b-col class="mt-7">
            <fieldset class="form-fieldset">
              <h5 class="mb-5">
                {{ $t('labels.backend.flight_reservations.titles.passengers') }}
              </h5>
              <b-form-group
                :invalid-feedback="feedback('passengers')"
                :state="state('passengers')"
              >
              </b-form-group>

              <template v-for="(passenger, index) in model.passengers">
                <passenger
                  :disabled="
                    !(
                      model.can_edit &&
                      (RegExp('hod_approving').test(model.status) ||
                        RegExp('budgeting').test(model.status) ||
                        !model.status)
                    )
                  "
                  v-model="model.passengers[index]"
                  :index="index"
                  :ref="'passenger-' + index"
                  :key="index"
                  v-if="passenger.id === 0 || passenger.url !== null"
                  @deleted="
                    dropFile(
                      'passenger_passport-' + index,
                      passenger,
                      'passengers',
                      index
                    )
                  "
                  comp-name="passenger_passport"
                ></passenger>
              </template>

              <div
                class="float-right"
                v-if="
                  model.can_edit &&
                    (RegExp('hod_approving').test(model.status) ||
                      RegExp('budgeting').test(model.status) ||
                      !model.status)
                "
              >
                <b-button
                  size="sm"
                  variant="primary"
                  v-b-tooltip.hover
                  :title="$t('buttons.flight_reservations.add_passenger')"
                  @click="addPassenger()"
                >
                  <i class="fe fe-plus-circle"></i>
                </b-button>
              </div>
            </fieldset>

            <b-form-group
              :label="$t('validation.attributes.purpose')"
              label-for="purpose"
              :label-cols="4"
              :invalid-feedback="feedback('purpose')"
            >
              <b-form-textarea
                id="purpose"
                name="purpose"
                :rows="3"
                :placeholder="$t('validation.attributes.purpose')"
                v-model="model.purpose"
                :state="state('purpose')"
                :disabled="
                  !(
                    model.can_edit &&
                    (RegExp('hod_approving').test(model.status) ||
                      RegExp('budgeting').test(model.status) ||
                      !model.status)
                  )
                "
              ></b-form-textarea>
            </b-form-group>

            <!--            <FlightReservationQuotationList-->
            <!--              :readonly="!(model.can_edit && model.status === 'budgeting')"-->
            <!--              v-if="model.status === 'budgeting' || model.quotations_count > 0"-->
            <!--              :extra-search-criteria="{ flight_reservation_id: id }"-->
            <!--            ></FlightReservationQuotationList>-->

            <b-form-group
              :label="$t('validation.attributes.deduction')"
              label-for="deduction"
              :label-cols="4"
              :invalid-feedback="feedback('deduction')"
              :state="state('deduction')"
            >
              <b-input-group>
                <b-form-input
                  id="deduction"
                  name="deduction"
                  :placeholder="$t('validation.attributes.deduction')"
                  v-model="model.deduction"
                  :state="state('deduction')"
                  :disabled="
                    !(
                      model.can_edit &&
                      (RegExp('hod_approving').test(model.status) ||
                        !model.status)
                    )
                  "
                ></b-form-input>
                <b-input-group-append>
                  <b-form-select
                    v-model="model.deduction_type"
                    :options="['MYR', '%']"
                    class="mb-3"
                    :disabled="
                      !(
                        model.can_edit &&
                        (RegExp('hod_approving').test(model.status) ||
                          !model.status)
                      )
                    "
                  ></b-form-select>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>

            <b-form-group
              :label="$t('validation.attributes.final_price')"
              label-for="price"
              :label-cols="4"
              v-if="model.status === 'purchasing' || model.price"
              :invalid-feedback="feedback('price')"
              :state="state('price')"
            >
              <b-input-group size="lg" append="/PAX">
                <b-form-input
                  id="price"
                  name="price"
                  :placeholder="$t('validation.attributes.price')"
                  v-model="model.price"
                  :state="state('price')"
                  :disabled="!(model.can_edit && model.status === 'purchasing')"
                ></b-form-input>
              </b-input-group>
            </b-form-group>

            <fieldset v-if="model.status === 'purchasing' || model.price">
              <!--<h5 class="mb-5">-->
              <!--{{ $t('validation.attributes.receipts') }}-->
              <!--</h5>-->

              <b-form-group
                :label="$t('validation.attributes.receipts')"
                label-size="lg"
                label-class="font-weight-bold pt-0"
                class="mb-0"
                :invalid-feedback="feedback('receipts')"
                :state="state('receipts')"
              >
                <b-row>
                  <b-col
                    xl="12"
                    v-for="(receipt, index) in receipts"
                    :key="index"
                  >
                    <b-button
                      class="close"
                      size="sm"
                      variant="danger"
                      v-b-tooltip.hover
                      :title="$t('buttons.reset')"
                      @click="
                        dropFile('receipt-' + index, receipt, 'receipts', index)
                      "
                      v-if="model.can_edit && model.status === 'purchasing'"
                    >
                      <i class="fe fe-x"></i>
                    </b-button>
                    <b-form-group
                      :label="
                        $t('validation.attributes.receipt') + ' ' + (index + 1)
                      "
                      :label-cols="4"
                      :invalid-feedback="
                        feedback('receipts.' + index + '.file')
                      "
                      class="file-upload-group required"
                    >
                      <div class="media">
                        <div v-if="receipt.url" class="m-1 certificate_link">
                          <a :href="receipt.url" target="_blank">
                            {{ $t('validation.attributes.receipt') }}
                          </a>
                        </div>

                        <div
                          class="media-body"
                          v-if="model.can_edit && model.status === 'purchasing'"
                        >
                          <b-form-file
                            accept=".jpg, .png, .gif, .pdf"
                            :placeholder="$t('labels.no_file_chosen')"
                            v-model="receipt.file"
                            :key="'filekey-' + index"
                            :ref="'receipt-' + index"
                            :state="state('receipts.' + index + '.file')"
                          ></b-form-file>
                        </div>
                      </div>
                    </b-form-group>
                  </b-col>
                </b-row>
                <div
                  class="float-right"
                  v-if="model.can_edit && model.status === 'purchasing'"
                >
                  <b-button
                    size="sm"
                    variant="primary"
                    v-b-tooltip.hover
                    :title="$t('buttons.vessels.add_certificate')"
                    @click="addFile('receipts')"
                  >
                    <i class="fe fe-plus-circle"></i>
                  </b-button>
                </div>
              </b-form-group>
            </fieldset>
          </b-col>
        </b-row>

        <b-row slot="footer">
          <b-col md>
            <b-button
              :to="{ name: 'flight_reservations' }"
              variant="danger"
              size="sm"
            >
              {{ $t('buttons.back') }}
            </b-button>
          </b-col>
          <b-col md>
            <b-button-toolbar class="float-right">
              <WorkflowTransitions
                :available-transitions="model.available_transitions"
                :can-pass="model.can_pass"
                :can-pass-urgently="model.can_pass_urgently"
                :change-status="changeStatus"
                :get-transition-info="getTransitionInfo"
                :is-form="true"
                :id="id"
                v-if="!isNew"
              ></WorkflowTransitions>
              <b-button-group>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  :disabled="pending"
                  v-if="
                    (isNew &&
                      this.$app.user.can('create flight reservations')) ||
                      model.can_edit
                  "
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                </b-button>
              </b-button-group>
            </b-button-toolbar>
          </b-col>
        </b-row>
      </b-card>
      <comments
        ref="comments"
        model-name="FlightReservation"
        :model-id="id"
        v-if="!isNew"
      ></comments>
    </form>
  </div>
</template>

<script>
import axios from 'axios'
import form from '../mixins/form'
import Comments from './components/Comments'
import Multiselect from 'vue-multiselect'
import Passenger from './components/Passenger'
import workflow from '../mixins/workflow'
import WorkflowTransitions from './components/WorkflowTransitions'
import FlightReservationQuotationList from './FlightReservationQuotationList'
import Trip from './components/Trip'
import StepProgress from './components/StepProgress'

export default {
  name: 'FlightReservationForm',
  components: {
    Trip,
    Comments,
    WorkflowTransitions,
    Multiselect,
    Passenger,
    StepProgress
  },
  mixins: [form, workflow],
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true
      },
      modelName: 'flight_reservation',
      resourceRoute: 'flight_reservations',
      listPath: '/AdminForms/flight_reservations',
      item_type: 'flight_reservation',
      form_types: ['VESSEL', 'SITE'],
      trip_types: ['BUS', 'CAB', 'TRAIN', 'FERRY', 'FLIGHT', 'HOTEL'],
      vessels: [],
      model: {
        requester_id: null,
        requester_name: null,
        department_name: null,
        form_type: null,
        form_target: null,
        departure_from: null,
        departure_to: null,
        passengers: [],
        trips: [],
        receipts: [],
        purpose: null,
        status: null,
        price: null,
        deduction: null,
        deduction_type: 'MYR',
        available_transitions: [],
        // quotations_count: 0,
        transitions: {},
        prf: null,
        can_pass: true,
        can_pass_urgently: false,
        can_edit: true
      }
    }
  },
  computed: {
    receipts: function() {
      return this.model.receipts.filter(
        receipt => receipt.id === null || receipt.url !== null
      )
    }
  },
  watch: {
    'model.form_type': function(newVal, oldVal) {
      if (newVal === 'VESSEL' && this.model.status === null) {
        this.model.status = 'vessels_hod_approving'
      } else if (this.model.status === null) {
        this.model.status = 'sites_hod_approving'
      }
    }
  },
  async created() {
    var { data } = await axios.get(this.$app.route(`vessels.limited_search`))
    this.vessels = data.data
  },
  methods: {
    addPassenger() {
      this.model.passengers.push({
        id: 0,
        name: null,
        designation: null,
        passport: null,
        expiry: null,
        nationality: null,
        dob: null,
        hp: null,
        url: null,
        file: null,
        collapse_status: true
      })
    },
    addTrip() {
      this.model.trips.push({
        id: null,
        trip_type: null,
        trip_attributes: null
      })
    },
    dropFile(ref, file, collection = 'attachments', index = 0) {
      // this.$refs['passenger-' + index][0].$refs[ref].reset()
      this.$refs[ref][0].reset()
      if (file.id === 0 && file.url == null && file.file === null) {
        this.model[collection].splice(index, 1)
      }
      file.url = null
    },
    dropTrip(trip) {
      if (!this.model.trips[trip.index].id) {
        this.model.trips.splice(trip.index, 1)
      } else this.model.trips[trip.index].id = 0
    },
    async generatePrf() {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#dd4b39',
        confirmButtonText: this.$t('buttons.confirm')
      })

      if (result.value) {
        try {
          let { data } = await axios.post(
            this.$app.route('flight_reservations.generate_prf', {
              flight_reservation: this.id
            })
          )

          this.fetchData()
          this.$app.noty[data.status](data.message)
        } catch (e) {
          this.$app.error(e)
        }
      }
    },
    addFile(collection) {
      this.model[collection].push({
        id: null,
        url: null,
        file: null
      })
    }
  }
}
</script>

<style>
.is-invalid .btn-outline-primary {
  border-color: #cd201f !important;
  color: #cd201f !important;
}
</style>
