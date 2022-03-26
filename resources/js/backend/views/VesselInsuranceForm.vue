<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.vessel_insurances.titles.create')
                  : $t('labels.backend.vessel_insurances.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.name')"
                  label-for="name"
                  :label-cols="3"
                  :invalid-feedback="feedback('name')"
                >
                  <b-form-input
                    id="name"
                    name="name"
                    :placeholder="$t('validation.attributes.name')"
                    v-model="model.name"
                    :state="state('name')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.type')"
                  label-for="type"
                  :label-cols="3"
                  :state="state('type')"
                >
                  <multiselect
                    v-model="model.type"
                    :options="types"
                    id="type"
                    name="type"
                    :searchable="true"
                    :close-on-select="true"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.type') + ' --'
                    "
                  ></multiselect>
                </b-form-group>
                <p>
                  {{
                    $t(
                      'labels.backend.vessel_insurances.titles.covered_vessels'
                    )
                  }}:
                </p>
                <fieldset class="form-fieldset">
                  <template v-for="(vessel_covered, index) in model.vessels">
                    <Covered_Vessels
                      v-model="model.vessels[index]"
                      :index="index"
                      :ref="'vessel_covered-' + index"
                      :key="index"
                      :vessels="vessels"
                      @deleted="dropCoveredVessel"
                    ></Covered_Vessels>
                  </template>

                  <div class="float-right">
                    <b-button
                      size="sm"
                      variant="primary"
                      v-b-tooltip.hover
                      :title="$t('buttons.flight_reservations.add_passenger')"
                      @click="addCoverdVessel()"
                    >
                      <i class="fe fe-plus-circle"></i>
                    </b-button>
                  </div>
                </fieldset>

                <b-form-group
                  :label="$t('validation.attributes.expiry_date')"
                  label-for="expiry_date"
                  :label-cols="3"
                  :invalid-feedback="feedback('expiry_date')"
                  :state="state('expiry_date')"
                >
                  <b-input-group>
                    <p-datetimepicker
                      :config="config"
                      id="expiry_date"
                      name="expiry_date"
                      v-model="model.expiry_date"
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
                <Attachments v-model="model.attachments"></Attachments>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  :to="{ name: 'vessel_insurances' }"
                  variant="danger"
                  size="sm"
                >
                  {{ $t('buttons.back') }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  class="float-right"
                  :disabled="pending"
                  v-if="
                    (isNew && this.$app.user.can('create vessel insurances')) ||
                      this.$app.user.can('edit vessel insurances')
                  "
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
import form from '../mixins/form'
import axios from 'axios'
import Attachments from './components/Attachments'
import Multiselect from 'vue-multiselect'
import Covered_Vessels from './components/Covered_Vessels'

export default {
  name: 'VesselInsuranceForm',
  components: { Covered_Vessels, Attachments, Multiselect },
  mixins: [form],
  data() {
    return {
      types: [],
      config: {
        wrap: true,
        allowInput: true
      },
      modelName: 'vessel_insurance',
      resourceRoute: 'vessel_insurances',
      listPath: '/vessel_insurances',
      vessels: [],
      model: {
        name: null,
        type: null,
        expiry_date: null,
        vessels: [],
        attachments: []
      }
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`vessels.limited_search`))
    this.vessels = data.data
    ;({ data } = await axios.get(
      this.$app.route(`get_list_of_constants`, {
        constant: 'vessel_insurance_types'
      })
    ))
    this.types = Object.values(data)
  },
  methods: {
    addCoverdVessel() {
      this.model.vessels.push({
        vessel_covered: null,
        value_covered: null,
        collapse_status: true
      })
    },
    dropCoveredVessel(vessel) {
      if (!this.model.vessels[vessel.index].id) {
        this.model.vessels.splice(vessel.index, 1)
      } else this.model.vessels[vessel.index].id = 0
    }
  }
}
</script>
