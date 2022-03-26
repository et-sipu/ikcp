<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="12">
          <b-card>
            <template slot="header">
              <h4 class="card-title">
                {{ $t('labels.backend.attendances.titles.dept_movement') }}
              </h4>
              <div class="card-options" v-if="current_date">
                <h3>
                  <b-badge variant="info">{{ current_date }}</b-badge>
                </h3>
              </div>
            </template>

            <b-row
              class="justify-content-center"
              v-for="(attendance, index) in attendances"
              :key="index"
            >
              <b-col xl="9">
                <b-form-group
                  :label="attendance.user_name"
                  label-for="attendance"
                  :label-cols="3"
                >
                  <b-form-radio-group
                    id="attendance"
                    v-model="attendance.dept_movement"
                    buttons
                    button-variant="outline-primary"
                    size="sm"
                  >
                    <b-form-radio
                      :value="status"
                      v-for="(status, index1) in statuses"
                      :key="index1"
                    >
                      {{ $t(`labels.backend.attendances.statuses.${status}`) }}
                    </b-form-radio>
                  </b-form-radio-group>
                </b-form-group>
              </b-col>
              <b-col xl="3">
                <b-form-input
                  :placeholder="$t('validation.attributes.remarks')"
                  v-model="attendance.remarks"
                ></b-form-input>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  class="float-right"
                  :disabled="loading"
                >
                  {{ $t('buttons.save') }}
                </b-button>
              </b-col>
            </b-row>
          </b-card>
          <atom-spinner
            :animation-duration="1000"
            :size="400"
            :color="'#DBB11E'"
            :loading="loading"
            :full="false"
          ></atom-spinner>
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
import axios from 'axios'
import AtomSpinner from '../components/Plugins/AtomSpinner.vue'

export default {
  name: 'AttendanceForm',
  components: { AtomSpinner },
  data() {
    return {
      loading: true,
      modelName: 'attendance',
      resourceRoute: 'attendances',
      listPath: 'Attendance/attendances',
      attendances: [],
      statuses: [
        'A',
        'P',
        'MC',
        'AL',
        'ET',
        'OB',
        'CL',
        'ML',
        'PL',
        'UL',
        'RL',
        'SL'
      ],
      current_date: null
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route('attendances.dept_movement'))
    this.attendances = data
    this.loading = false
    ;({ data } = await axios.get(this.$app.route('current_date')))
    this.current_date = data
  },
  methods: {
    async onSubmit() {
      try {
        this.loading = true
        let formData = this.$app.objectToFormData(this.attendances)

        let { data } = await axios.post(
          this.$app.route('attendances.save_dept_movement'),
          formData
        )

        this.loading = false

        this.$app.noty[data.status](data.message)
      } catch (e) {
        this.loading = false
        this.$app.error(e)
      }
    }
  }
}
</script>
