<template>
  <div class="animated fadeIn">
    <form @submit.prevent ref="myform">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{ $t('labels.backend.vessels.titles.imo_report') }} -
              {{ vessel ? vessel.name : '' }}
            </h4>

            <b-form-group
              :label="$t('validation.attributes.movement_type')"
              label-for="movement_type"
              :label-cols="3"
              :invalid-feedback="feedback('movement_type')"
              :state="state('movement_type')"
            >
              <b-form-radio-group
                buttons
                button-variant="outline-primary"
                v-model="model.movement_type"
                :options="movement_types"
                name="tested_by"
                :state="state('movement_type')"
              ></b-form-radio-group>
            </b-form-group>

            <b-form-group
              :label="
                $t('validation.attributes.port_of') + ' ' + model.movement_type
              "
              label-for="goal_port"
              :label-cols="3"
              :invalid-feedback="feedback('goal_port')"
            >
              <b-form-input
                id="goal_port"
                name="goal_port"
                v-model="model.goal_port"
                required
                :placeholder="
                  $t('validation.attributes.port_of') +
                    ' ' +
                    model.movement_type
                "
                :state="state('goal_port')"
              ></b-form-input>
            </b-form-group>

            <b-form-group
              :label="$t('validation.attributes.last_port')"
              label-for="last_port"
              :label-cols="3"
              :invalid-feedback="feedback('last_port')"
            >
              <b-form-input
                id="last_port"
                name="last_port"
                required
                :placeholder="$t('validation.attributes.last_port')"
                v-model="model.last_port"
                :state="state('last_port')"
              ></b-form-input>
            </b-form-group>

            <b-form-group
              :label="$t('validation.attributes.movement_date')"
              label-for="scheduled_sign_on_date"
              :label-cols="3"
              :invalid-feedback="feedback('movement_date')"
              :state="state('movement_date')"
            >
              <b-input-group>
                <p-datetimepicker
                  :config="config"
                  id="movement_date"
                  name="movement_date"
                  v-model="model.movement_date"
                  required
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

            <b-row slot="footer">
              <b-col md>
                <b-button to="/VM/vessels" variant="danger" size="sm">
                  {{ $t('buttons.back') }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button
                  @click="onGenerate"
                  variant="success"
                  size="sm"
                  class="float-right ml-2"
                  v-if="this.$app.user.can('generate own vessel imo')"
                >
                  {{ $t('buttons.generate') }}
                </b-button>
                <b-button
                  type="submit"
                  @click="onPreview"
                  variant="info"
                  size="sm"
                  class="float-right"
                  v-if="this.$app.user.can('view own vessel imo')"
                >
                  <font-awesome-icon icon="search"></font-awesome-icon>
                  {{ $t('buttons.preview') }}
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
        </b-col>
        <b-col xl="6">
          <div class="card">
            <div class="card-header"><h2 class="card-title">Reports</h2></div>
            <table class="table card-table">
              <tbody>
                <tr v-for="(report, index) in reports" :key="index">
                  <td>
                    <a :href="report.url" target="_blank">
                      {{ report.name }}
                    </a>
                  </td>
                  <td class="text-right">
                    <h4>
                      <b-badge variant="info"> {{ report.date }} </b-badge>
                    </h4>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
import axios from 'axios'
import states from '../mixins/states'
import fileReader from '../lib/fileReader'
import AtomSpinner from '../components/Plugins/AtomSpinner.vue'

export default {
  name: 'VesselIMOReport',
  components: { AtomSpinner },
  mixins: [states],
  props: {
    id: {
      type: String,
      required: false,
      default: () => '1'
    }
  },
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true
      },
      ports: [],
      reports: [],
      movement_types: ['Arrival', 'Departure'],
      model: {
        movement_type: 'Arrival',
        movement_date: null,
        goal_port: null,
        last_port: null,
        generate: false
      },
      vessel: null
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`get_list_of_ports`))
    this.ports = data
    ;({ data } = await axios.get(
      this.$app.route(`vessels.show`, { id: this.id })
    ))
    this.vessel = data

    this.get_reports()
  },
  methods: {
    async get_reports() {
      let { data } = await axios.get(
        this.$app.route(`vessels.imo_report`, { id: this.id })
      )
      this.reports = data
    },
    async onPreview() {
      if (!this.$refs.myform.checkValidity()) {
        this.$refs.myform.reportValidity()
        return
      }
      this.pending = true
      this.loading = true

      let formData = this.$app.objectToFormData(this.model)
      let url = null
      try {
        let { data } = await axios({
          url: this.$app.route('vessels.process_imo_report', {
            vessel: this.id
          }),
          responseType: 'blob',
          method: 'POST',
          data: formData
        })

        url = window.URL.createObjectURL(
          new Blob([data], { type: 'application/pdf' })
        )
        const link = document.createElement('a')
        link.href = url
        link.target = '_blank'
        // link.setAttribute('download', 'file1.pdf')
        document.body.appendChild(link)

        link.click()
        this.pending = false
        this.loading = false
      } catch (e) {
        this.pending = false
        this.loading = false
        // console.log(e.response.data)

        // Validation errors
        if (e.hasOwnProperty('response') && e.response.status === 422) {
          const file = await fileReader(e.response.data)
          // Parse content and retrieve 'message'
          const errors = JSON.parse(file)

          this.validation = errors
          return
        } else {
          this.validation = []
        }
        this.$app.error(e)
      }
      if (url) {
        setTimeout(() => {
          window.URL.revokeObjectURL(url)
        }, 10000)
      }
    },
    async onGenerate() {
      if (!this.$refs.myform.checkValidity()) {
        this.$refs.myform.reportValidity()
        return
      }
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        text: this.$t('labels.backend.vessels.text.approve_generate_msg'),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#4bb21f',
        confirmButtonText: this.$t('buttons.generate')
      })

      if (result.value) {
        this.pending = true
        this.loading = true
        this.model.generate = true
        let formData = this.$app.objectToFormData(this.model)
        this.model.generate = false

        try {
          let { data } = await axios({
            url: this.$app.route('vessels.process_imo_report', {
              vessel: this.id
            }),
            method: 'POST',
            data: formData
          })

          this.$app.noty[data.status](data.message)
          this.pending = false
          this.loading = false
          this.get_reports()
        } catch (e) {
          this.pending = false
          this.loading = false
          if (e.hasOwnProperty('response') && e.response.status === 422) {
            this.validation = e.response.data
            return
          } else {
            this.validation = []
          }

          this.$app.error(e)
        }
      }
    }
  }
}
</script>
