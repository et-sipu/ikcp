<template>
  <div>
    <b-row>
      <b-col xl="12">
        <b-row>
          <b-col sm="3" v-if="$app.user.vessel">
            <b-card bg-variant="secondary" text-variant="white">
              <h4 class="mb-0">
                <router-link :to="`/VM/vessels/${$app.user.vessel.id}/imo`">
                  {{ $t('labels.backend.vessels.titles.imo_report') }}
                </router-link>
              </h4>
            </b-card>
          </b-col>
          <!--<b-col sm>-->
          <!--<b-card bg-variant="warning" text-variant="white">-->
          <!--<h4 class="mb-0">{{ this.$store.state.counters.pendingPostsCount }}</h4>-->
          <!--<p>{{ $t('labels.backend.dashboard.pending_posts') }}</p>-->
          <!--</b-card>-->
          <!--</b-col>-->
          <!--<b-col sm>-->
          <!--<b-card bg-variant="success" text-variant="white">-->
          <!--<h4 class="mb-0">{{ this.$store.state.counters.publishedPostsCount }}</h4>-->
          <!--<p>{{ $t('labels.backend.dashboard.published_posts') }}</p>-->
          <!--</b-card>-->
          <!--</b-col>-->
          <!--</b-row>-->
          <!--<b-row>-->
          <!--<b-col sm v-if="this.$app.user.can('view users')">-->
          <!--<b-card bg-variant="primary" text-variant="white">-->
          <!--<h4 class="mb-0">{{ this.$store.state.counters.activeUsersCount }}</h4>-->
          <!--<p>{{ $t('labels.backend.dashboard.active_users') }}</p>-->
          <!--</b-card>-->
          <!--</b-col>-->
          <!--<b-col sm v-if="this.$app.user.can('view form_submissions')">-->
          <!--<b-card bg-variant="info" text-variant="white">-->
          <!--<h4 class="mb-0">{{ this.$store.state.counters.formSubmissionsCount }}</h4>-->
          <!--<p>{{ $t('labels.backend.dashboard.form_submissions') }}</p>-->
          <!--</b-card>-->
          <!--</b-col>-->
        </b-row>
      </b-col>
      <b-col xl="12">
        <b-row>
          <b-card-group columns>
            <b-card>
              <h4 slot="header" class="card-title">Admin Forms</h4>
              <table class="table card-table">
                <tbody>
                  <tr>
                    <td>
                      <a href="forms/Requisition_Form.pdf" target="_blank">
                        {{ $t('labels.backend.forms.titles.rf') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a
                        href="forms/ACCESSORIES_REQUEST_FORM.pdf"
                        target="_blank"
                      >
                        {{ $t('labels.backend.forms.titles.arf') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a
                        href="forms/vehicle_maintenance_check_list.pdf"
                        target="_blank"
                      >
                        {{
                          $t(
                            'labels.backend.forms.titles.vehicle_maintenance_check_list'
                          )
                        }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="forms/flight_reservation.pdf" target="_blank">
                        {{
                          $t('labels.backend.forms.titles.flight_reservation')
                        }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a
                        href="forms/Vehicle_Requisition_Form.pdf"
                        target="_blank"
                      >
                        {{ $t('labels.backend.forms.titles.vrf') }}
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-card>
            <b-card>
              <h4 slot="header" class="card-title">General Documents</h4>
              <table class="table card-table">
                <tbody>
                  <tr>
                    <td>
                      <a
                        href="forms/1A. IKSB IMW SMS GROUP 2019 Rev 0.pdf"
                        target="_blank"
                      >
                        {{
                          $t(
                            'labels.backend.general_documents.IKSB_IMW_SMS_GROUP_Profile_2019'
                          )
                        }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="forms/IKSBGROUPCOVER2019.pdf" target="_blank">
                        {{
                          $t(
                            'labels.backend.general_documents.IKSB_GROUP_COVER_2019'
                          )
                        }}
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-card>
            <b-card>
              <h4 slot="header" class="card-title">HR Forms</h4>
              <table class="table card-table">
                <tbody>
                  <tr>
                    <td>
                      <a
                        href="forms/Staff_Information_System.pdf"
                        target="_blank"
                      >
                        {{ $t('labels.backend.forms.titles.sis') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="forms/Exit_Clearance_Form.pdf" target="_blank">
                        {{ $t('labels.backend.forms.titles.ecf') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a
                        href="forms/Employment_Application_Form.pdf"
                        target="_blank"
                      >
                        {{ $t('labels.backend.forms.titles.eaf') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a
                        href="forms/EMPLOYEE_REQUISITION_FORM.pdf"
                        target="_blank"
                      >
                        {{ $t('labels.backend.forms.titles.erf') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="forms/annual_leave.pdf" target="_blank">
                        {{ $t('labels.backend.forms.titles.annual_leave') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="forms/Replacement_Leave.pdf" target="_blank">
                        {{ $t('labels.backend.forms.titles.rl') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a
                        href="forms/Appraisal_for_Executive_Above.pdf"
                        target="_blank"
                      >
                        {{ $t('labels.backend.forms.titles.ar') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a
                        href="forms/Appraisal_for_Non-Executive_Technical_Staff.pdf"
                        target="_blank"
                      >
                        {{ $t('labels.backend.forms.titles.arn') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a
                        href="forms/Appraisal_for_Executive_Manager_&_Above.pdf"
                        target="_blank"
                      >
                        {{ $t('labels.backend.forms.titles.are') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="forms/Authorized_Task_Form.pdf" target="_blank">
                        {{ $t('labels.backend.forms.titles.atf') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="forms/Daily_Staff_Movement.pdf" target="_blank">
                        {{ $t('labels.backend.forms.titles.dodet') }}
                      </a>
                    </td>
                  </tr>
                  <!--<tr>-->
                  <!--<td>-->
                  <!--<a-->
                  <!--href="forms/Employee_Leave_Application.pdf"-->
                  <!--target="_blank"-->
                  <!--&gt;-->
                  <!--{{ $t('labels.backend.forms.titles.ela') }}-->
                  <!--</a>-->
                  <!--</td>-->
                  <!--</tr>-->
                  <tr>
                    <td>
                      <a
                        href="forms/Interview_Evaloation)(Non-Executive).pdf"
                        target="_blank"
                      >
                        {{ $t('labels.backend.forms.titles.ien') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a
                        href="forms/Interview_Evaluation_(Executive & Above).pdf"
                        target="_blank"
                      >
                        {{ $t('labels.backend.forms.titles.iee') }}
                      </a>
                    </td>
                  </tr>
                  <!--<tr>-->
                  <!--<td>-->
                  <!--<a href="forms/Re-Employment_Requisition_Form.pdf" target="_blank">-->
                  <!--{{ $t('labels.backend.forms.titles.rer') }}-->
                  <!--</a>-->
                  <!--</td>-->
                  <!--</tr>-->
                  <!--<tr>-->
                  <!--<td>-->
                  <!--<a href="forms/Time_Off_Form.pdf" target="_blank">-->
                  <!--{{ $t('labels.backend.forms.titles.tof') }}-->
                  <!--</a>-->
                  <!--</td>-->
                  <!--</tr>-->
                </tbody>
              </table>
            </b-card>
            <b-card>
              <h4 slot="header" class="card-title">Finance Forms</h4>
              <table class="table card-table">
                <tbody>
                  <tr>
                    <td>
                      <a href="forms/prf" target="_blank">
                        {{ $t('labels.backend.forms.titles.prf') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="forms/crf" target="_blank">
                        {{ $t('labels.backend.forms.titles.carf_old') }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <router-link :to="`/Finance/cash_requisitions/create`">
                        {{ $t('labels.backend.forms.titles.crf') }}
                      </router-link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-card>
            <b-card>
              <h4 slot="header" class="card-title">Procurements Forms</h4>
              <table class="table card-table">
                <tbody>
                  <tr>
                    <td>
                      <a href="forms/bunker_declaration.pdf" target="_blank">
                        {{
                          $t('labels.backend.forms.titles.bunker_declaration')
                        }}
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="forms/fuel_requestion.pdf" target="_blank">
                        {{ $t('labels.backend.forms.titles.fuel_requestion') }}
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-card>
          </b-card-group>
        </b-row>
      </b-col>
    </b-row>
  </div>
</template>

<script>
// import axios from 'axios'
// import chart from './chart'

export default {
  name: 'Dashboard',
  // components: { chart },
  data() {
    return {
      post_fields: {
        title: { label: this.$t('validation.attributes.title') },
        status: { label: this.$t('validation.attributes.status') },
        pinned: { label: this.$t('validation.attributes.pinned') },
        summary: { label: this.$t('validation.attributes.summary') },
        published_at: {
          label: this.$t('validation.attributes.published_at'),
          class: 'text-center'
        }
      },
      posts: [],
      chartdata: []
    }
  },
  async created() {
    // if (this.$app.user.can('view own posts')) {
    //   let {data} = await axios.get(this.$app.route('posts.latest'))
    //   this.posts = data
    // }
  },
  mounted() {
    // console.log(window.settings.pusher_config, window.location.hostname)
    // this.$toasted.show('hello billo', { type: 'success', theme: 'bubble' })
    // this.$toasted.show('hello billo', {
    //   type: 'error',
    //   theme: 'outline',
    //   icon: { name: 'stamp' }
    // })
    // this.$toasted.show('hello billo', { type: 'info' })
    // this.$toasted.show('hello billo')
    // this.$app.error('Not Authorized to Access')
    // this.$app.noty['error'](
    //   'NoNoNoNoNoaslkdj laksjdl qkwjelqkjewqlkej qlwkejqlejqlkkkkkeeiuoiuoiuqoweiuqoweuoiealealkejalkwejalewsjaljelawek'
    // )
    this.fillData()
    setInterval(
      function() {
        this.fillData()
      }.bind(this),
      5000
    )
  },
  methods: {
    fillData() {
      this.chartdata = {
        labels: ['First', 'Second', 'Third'],
        datasets: [
          {
            label: 'Data One',
            backgroundColor: '#f87979',
            borderColor: '#f87979',
            fill: false,
            data: [
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt()
            ]
          },
          {
            label: 'Data Two',
            borderColor: 'purple',
            backgroundColor: 'purple',
            fill: false,
            data: [this.getRandomInt(), this.getRandomInt()]
          }
        ]
      }
    },
    getRandomInt() {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5
    }
  }
}
</script>
