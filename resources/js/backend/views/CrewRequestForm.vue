<template>
  <div class="animated fadeIn">
    <b-row class="justify-content-center">
      <b-col xl="6">
        <form @submit.prevent="onSubmit">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t(
                      'labels.backend.seafarer_contracts.titles.create.crew_requests'
                    )
                  : $t(
                      'labels.backend.seafarer_contracts.titles.edit.crew_requests'
                    )
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.vessel')"
                  label-for="vessel"
                  :label-cols="3"
                  :invalid-feedback="feedback('vessel')"
                  :state="state('vessel')"
                >
                  <multiselect
                    v-model="model.vessel"
                    :options="vessels"
                    id="vessel"
                    name="vessel"
                    track-by="id"
                    label="name"
                    :searchable="false"
                    :close-on-select="true"
                    :show-labels="false"
                    :disabled="vessels.length == 0 || model.status == 'done'"
                    :placeholder="
                      '-- ' + $t('validation.attributes.vessel') + ' --'
                    "
                    @input="onVesselChanged"
                  ></multiselect>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.replacement_of')"
                  label-for="replaced_seafarer"
                  :label-cols="3"
                  :invalid-feedback="feedback('replaced_seafarer')"
                  :state="state('replaced_seafarer')"
                  class="required"
                >
                  <multiselect
                    v-model="model.replaced_seafarer"
                    :options="current_seafarers"
                    id="replaced_seafarer"
                    label="name"
                    track-by="id"
                    :disabled="model.status == 'done'"
                    :placeholder="
                      '-- ' + $t('validation.attributes.replacement_of') + ' --'
                    "
                    :searchable="true"
                    :show-labels="false"
                    :internal-search="false"
                    :clear-on-select="true"
                    :close-on-select="true"
                    :max-height="600"
                    :show-no-results="false"
                    @input="onReplacementOffChanged"
                  >
                  </multiselect>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.capabilities_info.rank')"
                  label-for="rank"
                  :label-cols="3"
                  :invalid-feedback="feedback('rank')"
                  :state="state('rank')"
                >
                  <multiselect
                    v-model="model.rank"
                    :options="ranks"
                    group-values="ranks"
                    group-label="department"
                    :group-select="true"
                    :disabled="model.status == 'done'"
                    :searchable="true"
                    :close-on-select="true"
                    :show-labels="false"
                    track-by="id"
                    label="name"
                    :placeholder="
                      '-- ' +
                        $t('validation.attributes.capabilities_info.rank') +
                        ' --'
                    "
                    @input="resetData"
                  ></multiselect>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.sign_on_date')"
                  label-for="to_sign_on"
                  :label-cols="3"
                  :invalid-feedback="feedback('to_sign_on')"
                  :state="state('to_sign_on')"
                >
                  <b-input-group>
                    <p-datetimepicker
                      :config="config"
                      id="to_sign_on"
                      name="to_sign_on"
                      v-model="model.to_sign_on"
                      :disabled="model.status == 'done'"
                    ></p-datetimepicker>
                    <b-input-group-append v-if="model.status != 'done'">
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
                  :label="$t('validation.attributes.suggested_seafarer')"
                  label-for="candidate_seafarer"
                  :label-cols="3"
                  :invalid-feedback="feedback('candidate_seafarer')"
                  :state="state('candidate_seafarer')"
                  class="required"
                >
                  <multiselect
                    v-model="model.candidate_seafarer"
                    :options="candidate_seafarers"
                    id="candidate_seafarer"
                    label="name"
                    track-by="id"
                    :disabled="model.status == 'done'"
                    :placeholder="
                      '-- ' +
                        $t('validation.attributes.suggested_seafarer') +
                        ' --'
                    "
                    :searchable="true"
                    :show-labels="false"
                    :loading="isLoading"
                    :internal-search="false"
                    :clear-on-select="true"
                    :close-on-select="true"
                    :options-limit="300"
                    :max-height="600"
                    :show-no-results="false"
                    @search-change="asyncFind"
                  >
                  </multiselect>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.remarks')"
                  label-for="remarks"
                  :label-cols="3"
                  :invalid-feedback="feedback('remarks')"
                >
                  <b-form-textarea
                    id="remarks"
                    name="remarks"
                    :rows="3"
                    :disabled="model.status == 'done'"
                    :placeholder="$t('validation.attributes.remarks')"
                    v-model="model.remarks"
                    :state="state('remarks')"
                  ></b-form-textarea>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row slot="footer">
              <b-col md>
                <b-button
                  to="/Crew/seafarer_contracts/crew_requests"
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
                    (isNew && this.$app.user.can('create crew requests')) ||
                      (this.$app.user.can('edit crew requests') &&
                        model.status != 'done')
                  "
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                </b-button>
                <b-button
                  variant="info"
                  size="sm"
                  class="float-right mr-2"
                  :disabled="pending"
                  :to="{
                    name: 'seafarer_contracts_create',
                    params: { seafarer_id: model.candidate_seafarer.id },
                    query: {
                      vessel: model.vessel.id,
                      crew_request_id: id,
                      sign_on_date: model.to_sign_on
                    }
                  }"
                  v-if="
                    !isNew &&
                      this.$app.user.can('edit crew requests') &&
                      model.candidate_seafarer &&
                      model.candidate_seafarer.id &&
                      model.status != 'done'
                  "
                >
                  {{ $t('buttons.crew_requests.convert_to_contract') }}
                </b-button>
              </b-col>
            </b-row>
            <atom-spinner
              :animation-duration="1000"
              :size="400"
              :color="'#DBB11E'"
              :loading="loading"
              :full="true"
            ></atom-spinner>
          </b-card>
        </form>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import axios from 'axios'
import form from '../mixins/form'
import Multiselect from 'vue-multiselect'

export default {
  name: 'CrewRequestForm',
  components: { Multiselect },
  mixins: [form],
  props: {
    seafarerId: {
      type: String,
      required: false,
      default: ''
    }
  },
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true
      },
      modelName: 'crew_request',
      resourceRoute: 'crew_requests',
      listPath: '/seafarer_contracts/crew_requests',
      vessels: [],
      current_seafarers: [],
      candidate_seafarers: [],
      ranks: [],
      model: {
        vessel: null,
        replaced_seafarer: null,
        candidate_seafarer: null,
        to_sign_on: null,
        rank: null,
        remarks: null,
        status: 'pending'
      },
      isLoading: false
    }
  },
  watch: {
    'model.to_sign_on': function(newVal, oldVal) {
      if (oldVal !== null) {
        this.resetData()
      }
    },
    'model.vessel': function(newVal, oldVal) {
      if (this.model.vessel) {
        this.get_current_seafarers()
      }
    }
  },
  async created() {
    if (this.$app.user.can('edit crew_requests')) {
      var { data } = await axios.get(this.$app.route(`vessels.search`))
      this.vessels = data.data

      this.vessels = this.vessels.map(vessel => {
        return {
          id: vessel.id,
          name: vessel.name
        }
      })
    } else if (this.$app.user.vessel) {
      this.model.vessel = {
        id: this.$app.user.vessel.id,
        name: this.$app.user.vessel.name
      }
    }

    // ({data} = await axios.get(this.$app.route(`get_list_of_constants`, {constant: 'ranks'})))
    ;({ data } = await axios.get(this.$app.route(`get_list_of_ranks`)))

    var ranks = []

    for (let department in data) {
      var dep = { department: department, ranks: [] }

      for (let rank in data[department]) {
        dep.ranks.push(data[department][rank])
      }
      ranks.push(dep)
    }
    this.ranks = ranks

    document.querySelectorAll('.flatpickr-calendar').forEach(calendar => {
      calendar.style.width = ''
    })
  },
  methods: {
    async get_current_seafarers() {
      let { data } = await axios.get(
        this.$app.route(`seafarers.onboard`, { vessel: this.model.vessel.id })
      )
      this.current_seafarers = data
    },
    async onVesselChanged() {
      this.model.candidate_seafarer = null
      this.model.replaced_seafarer = null
      // if (this.model.vessel) {
      // let {data} = await axios.get(this.$app.route(`seafarers.onboard`, {vessel: this.model.vessel.id}))
      // this.current_seafarers = data
      // this.get_current_seafarers()
      // }
      this.model.rank = null
      this.model.to_sign_on = ''
    },
    resetData() {
      this.candidate_seafarers = []
      this.model.candidate_seafarer = null
    },
    onReplacementOffChanged() {
      if (this.model.replaced_seafarer) {
        this.model.to_sign_on = this.model.replaced_seafarer.scheduled_sign_off_date
        this.model.rank = this.model.replaced_seafarer.rank
      } else {
        this.model.to_sign_on = null
        this.model.rank = null
      }
      this.candidate_seafarers = []
      this.model.candidate_seafarer = null
    },
    async asyncFind(query, id) {
      if (
        this.model.to_sign_on === null ||
        this.model.to_sign_on === '' ||
        this.model.rank === null
      )
        return

      this.isLoading = true
      let params = {
        page: 1,
        perPage: 30,
        search: query
      }
      params.extraSearch = {
        available_for_contracting: this.model.to_sign_on,
        ranks: [this.model.rank]
      }

      let { data } = await axios.get(this.$app.route(`seafarers.search`), {
        params: params
      })

      let d = data.data
      this.candidate_seafarers = d.map(item => {
        return {
          id: item.id,
          name: item.full_name
        }
      })

      this.isLoading = false
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
</style>
