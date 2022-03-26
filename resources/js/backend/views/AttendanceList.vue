<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.attendances.titles.index') }}
        </h3>
        <div class="card-options">
          <b-btn
            v-b-toggle.filter
            variant="primary"
            class="mr-1"
            size="sm"
            v-b-tooltip.hover
            :title="$t('buttons.show_hide_filters')"
          >
            <font-awesome-icon icon="filter"></font-awesome-icon>
          </b-btn>
          <b-btn
            class="mr-2"
            variant="secondary"
            v-b-tooltip.hover
            :title="$t('buttons.print')"
            size="sm"
            v-if="
              $app.user.can('export attendances') &&
                extraSearchCriteria.employees.length > 0 &&
                extraSearchCriteria.employees.length < 2 &&
                extraSearchCriteria.date
            "
            :to="printUrl"
            target="_blank"
          >
            <font-awesome-icon icon="print"></font-awesome-icon>
          </b-btn>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="attendances.search"
        delete-route="attendances.destroy"
        export-route="this.printUrl"
        :length-change="true"
        :paging="true"
        :infos="true"
        :export-data="false"
        :extra-search-criteria="extraSearchCriteria"
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="filter" class="mt-2">
              <b-row>
                <b-col md="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.departments')"
                    label-for="departments"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.departments"
                      :options="departments"
                      id="departments"
                      name="departments"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.departments') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col md="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.branches')"
                    label-for="branches"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.branches"
                      :options="branches"
                      id="branches"
                      name="branches"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.branches') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col md="4" lg="4">
                  <b-form-group
                    :label="$t('validation.attributes.employees')"
                    label-for="employee"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.employees"
                      :options="employees"
                      id="employees"
                      name="employees"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.employees') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col xl="6" lg="6">
                  <b-form-group
                    :label="$t('validation.attributes.date')"
                    label-for="start_date"
                    :label-cols="2"
                  >
                    <b-input-group>
                      <p-datetimepicker
                        :config="config"
                        id="start_date"
                        name="start_date"
                        v-model="extraSearchCriteria.date"
                        @input="onContextChangedWithPage"
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
                </b-col>
                <b-col xl="6" lg="6">
                  <b-form-group
                    :label="$t('validation.attributes.dept_movement')"
                    label-for="attendance"
                    :label-cols="2"
                  >
                    <multiselect
                      @input="onContextChangedWithPage"
                      v-model="extraSearchCriteria.dept_movements"
                      :options="dept_movements"
                      id="attendance"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                    >
                    </multiselect>
                  </b-form-group>
                </b-col>
              </b-row>
            </b-collapse>
          </b-col>
        </template>
        <b-table
          ref="datatable"
          striped
          bordered
          responsive
          show-empty
          no-local-sorting
          :empty-text="$t('labels.datatables.no_results')"
          :empty-filtered-text="$t('labels.datatables.no_matched_results')"
          :fields="fields"
          :items="dataLoadProvider"
          sort-by="attendance_date"
          :sort-desc="true"
          :busy.sync="isBusy"
          primary-key="id"
          @row-dblclicked="start_edit"
          @row-clicked="showDetails"
          v-on-clickaway="save"
        >
          <template slot="top-row" slot-scope="columns">
            <th class="custom-header"></th>
            <th class="custom-header"></th>
            <th class="custom-header"></th>
            <th class="custom-header"></th>
            <th class="custom-header text-center" colspan="2">
              {{ $t('validation.attributes.thumbprint') }}
            </th>
            <th class="custom-header text-center" colspan="2">
              {{ $t('validation.attributes.log_book') }}
            </th>
            <th class="custom-header"></th>
            <th class="custom-header"></th>
            <th class="custom-header"></th>
          </template>
          <template slot="bottom-row" slot-scope="columns">
            <td colspan="8"></td>
            <td class="text-center">{{ summery.summery }} Days</td>
            <td colspan="2"></td>
          </template>
          <template slot="HEAD_index" slot-scope="data">
            <p>{{ data.label }}</p>
          </template>
          <template slot="HEAD_employee_name" slot-scope="data">
            <p>{{ data.label }}</p>
          </template>
          <template slot="HEAD_attendance_date" slot-scope="data">
            <p>{{ data.label }}</p>
          </template>
          <template slot="HEAD_dept_movement" slot-scope="data">
            <p>{{ data.label }}</p>
          </template>
          <template slot="HEAD_hr_review" slot-scope="data">
            <p>{{ data.label }}</p>
          </template>
          <template slot="HEAD_ey_review" slot-scope="data">
            <p>{{ data.label }}</p>
          </template>
          <template slot="HEAD_remarks" slot-scope="data">
            <p>{{ data.label }}</p>
          </template>
          <template slot="index" slot-scope="data">
            {{ data.index + 1 }}
          </template>
          <template slot="dept_movement" slot-scope="row">
            <b-badge :variant="status_color_map[row.value]">
              {{ $t(`labels.backend.attendances.statuses.${row.value}`) }}
            </b-badge>
          </template>
          <template slot="logged_in_time" slot-scope="row">
            <span v-if="!row.item.editable">{{ row.value }}</span>
            <p-datetimepicker
              :config="time_config"
              id="flight_time"
              name="flight_time"
              v-model="item.logged_in_time"
              @input="dirty = true"
              @keyup.enter="save()"
              v-else
            ></p-datetimepicker>
          </template>
          <template slot="logged_out_time" slot-scope="row">
            <span v-if="!row.item.editable">{{ row.value }}</span>
            <p-datetimepicker
              :config="time_config"
              id="flight_time"
              name="flight_time"
              v-model="item.logged_out_time"
              @input="dirty = true"
              @keyup.enter="save()"
              v-else
            ></p-datetimepicker>
          </template>
          <template slot="hr_review" slot-scope="row">
            <span v-if="!row.item.editable">{{ row.value }}</span>
            <b-form-radio-group
              v-else
              id="hr_review"
              v-model="item.hr_review"
              buttons
              button-variant="outline-primary"
              @input="dirty = true"
              :options="['0', '0.5', '1']"
            >
            </b-form-radio-group>
          </template>
          <template slot="ey_review" slot-scope="row">
            <span v-if="!row.item.editable">{{ row.value }}</span>
            <b-form-input
              v-else
              :placeholder="$t('validation.attributes.ey_query')"
              v-model="item.ey_review"
              @input="dirty = true"
              @keyup.enter="save()"
            ></b-form-input>
          </template>
          <template slot="remarks" slot-scope="row">
            <span v-if="!row.item.editable">{{ row.value }}</span>
            <b-form-input
              v-else
              :placeholder="$t('validation.attributes.remarks')"
              v-model="item.remarks"
              @input="dirty = true"
              @keyup.enter="save()"
            ></b-form-input>
          </template>
          <template slot="row-details" slot-scope="row">
            <b-table :items="row.item.thumbs" head-variant="dark"></b-table>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import list from '../mixins/list'
import Multiselect from 'vue-multiselect'
import axios from 'axios'
import { directive as onClickaway } from 'vue-clickaway'

export default {
  name: 'AttendanceList',
  directives: {
    onClickaway: onClickaway
  },
  components: {
    Multiselect
  },
  mixins: [list],
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true,
        mode: 'range'
      },
      extraSearchCriteria: {
        dept_movements: [],
        departments: [],
        branches: [],
        employees: [],
        date: null
      },
      departments: [],
      branches: [],
      employees: [],
      dept_movements: [
        { name: this.$t('labels.backend.attendances.statuses.A'), id: 'A' },
        { name: this.$t('labels.backend.attendances.statuses.P'), id: 'P' },
        { name: this.$t('labels.backend.attendances.statuses.MC'), id: 'MC' },
        { name: this.$t('labels.backend.attendances.statuses.AL'), id: 'AL' },
        { name: this.$t('labels.backend.attendances.statuses.ET'), id: 'ET' },
        { name: this.$t('labels.backend.attendances.statuses.OB'), id: 'OB' },
        { name: this.$t('labels.backend.attendances.statuses.CL'), id: 'CL' },
        { name: this.$t('labels.backend.attendances.statuses.ML'), id: 'ML' },
        { name: this.$t('labels.backend.attendances.statuses.PL'), id: 'PL' },
        { name: this.$t('labels.backend.attendances.statuses.RL'), id: 'RL' },
        { name: this.$t('labels.backend.attendances.statuses.UL'), id: 'UL' },
        { name: this.$t('labels.backend.attendances.statuses.SL'), id: 'SL' }
      ],
      isBusy: false,
      editing: false,
      item: null,
      dirty: false,
      time_config: {
        // wrap: true,
        allowInput: true,
        clickOpens: true,
        enableTime: true,
        noCalendar: true,
        time_24hr: true,
        minuteIncrement: 1
      },
      status_color_map: {
        A: 'danger',
        P: 'success',
        MC: 'warning',
        AL: 'warning',
        ET: 'success',
        OB: 'success',
        CL: 'warning',
        ML: 'warning',
        PL: 'warning'
      },
      summery: {
        summery: 0
      }
    }
  },
  computed: {
    fields() {
      let fields = [
        {
          key: 'index',
          label: this.$t('validation.attributes.no'),
          class: 'text-center table-header',
          sortable: false
        },
        {
          key: 'employee_name',
          label: this.$t('validation.attributes.employee'),
          class: 'text-left table-header',
          sortable: true,
          thStyle: { width: '25% !important' }
        },
        {
          key: 'attendance_date',
          label: this.$t('validation.attributes.attendance_date'),
          class: 'text-center table-header',
          sortable: true
        },
        {
          key: 'dept_movement',
          label: this.$t('validation.attributes.dept_movement'),
          class: 'text-center table-header',
          sortable: true
        },
        {
          key: 'first_thumb',
          label: 'IN',
          class: 'text-center',
          sortable: false
        },
        {
          key: 'last_thumb',
          label: 'OUT',
          class: 'text-center',
          sortable: false
        },
        // {
        //   key: 'thumb3',
        //   label: 'thumb3',
        //   class: 'text-center',
        //   sortable: false
        // },
        // {
        //   key: 'thumb4',
        //   label: 'thumb4',
        //   class: 'text-center',
        //   sortable: false
        // },
        // {
        //   key: 'thumb5',
        //   label: 'thumb5',
        //   class: 'text-center',
        //   sortable: false
        // },
        // {
        //   key: 'thumb6',
        //   label: 'thumb6',
        //   class: 'text-center',
        //   sortable: false
        // },
        {
          key: 'logged_in_time',
          label: this.$t('validation.attributes.logged_in_time'),
          class: 'text-center',
          sortable: false
        },
        {
          key: 'logged_out_time',
          label: this.$t('validation.attributes.logged_out_time'),
          class: 'text-center',
          sortable: false
        },
        {
          key: 'hr_review',
          label: this.$t('validation.attributes.hr_review'),
          class: 'text-center table-header',
          sortable: false
        },
        {
          key: 'ey_review',
          label: this.$t('validation.attributes.ey_query'),
          class: 'text-center table-header',
          sortable: false
        },
        {
          key: 'remarks',
          label: this.$t('validation.attributes.remarks'),
          class: 'text-center table-header',
          sortable: false
        }
      ]

      return fields
    },
    printUrl() {
      return (
        '/attendance/export?extraSearch=' +
        JSON.stringify(this.extraSearchCriteria)
      )
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`departments.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    })
    this.departments = data.data

    this.departments = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
    ;({ data } = await axios.get(this.$app.route(`branches.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    }))
    this.branches = data.data

    this.branches = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
    ;({ data } = await axios.get(this.$app.route(`get_list_of_employees`)))
    this.employees = data
  },
  mounted() {
    if (!this.$refs.datatable) {
      return
    }
    var headers = this.$refs.datatable.$el.querySelectorAll('thead tr')
    var top = this.$refs.datatable.$el.querySelectorAll('tbody tr')

    if (headers.length > 1) {
      return //nothing to do, header row already created
    }

    headers[0].parentNode.insertBefore(top[0], headers[0])
  },
  methods: {
    start_edit(item) {
      // if (this.item) this.save()
      if (!this.$app.user.can('edit attendances')) return
      this.$refs.datatable.localItems.forEach(item => (item.editable = false))

      this.dirty = false
      this.editing = true
      item.editable = true
      this.item = Object.assign({}, item)
    },
    async save(e = null) {
      if (!this.$app.user.can('edit attendances')) return

      if (e && this.hasSomeParentTheClass(e.target, 'flatpickr-calendar'))
        return

      if (!this.dirty) {
        this.$refs.datatable.localItems.forEach(item => (item.editable = false))
        return
      }

      this.$refs.datatable.localItems.forEach(item => (item.editable = false))

      try {
        // this.working = true

        let formData = this.$app.objectToFormData(this.item)
        formData.append('_method', 'PATCH')

        let { data } = await axios.post(
          this.$app.route('attendances.update', { attendance: this.item.id }),
          formData
        )

        this.onContextChangedWithPage()
        this.$app.noty[data.status](data.message)
        this.dirty = false
      } catch (e) {
        this.$app.error(e)
      }
    },
    hasSomeParentTheClass(element, classname) {
      if (
        element.className === undefined ||
        typeof element.className === 'object'
      )
        return false
      if (element.className.split(' ').indexOf(classname) >= 0) return true
      return (
        element.parentNode &&
        this.hasSomeParentTheClass(element.parentNode, classname)
      )
    },
    dataLoadProvider(ctx) {
      let summery = {}
      let data = this.$refs.datasource.loadData(
        ctx.sortBy,
        ctx.sortDesc,
        summery
      )

      this.summery = summery

      return data
    },
    showDetails(item) {
      item._showDetails = !item._showDetails
    }
  }
}
</script>

<style>
.custom-header {
  border-bottom: 0px !important;
}
th.table-header {
  border-top: 0px !important;
  /*top: -20px;*/
  outline: none;
  text-align: center !important;
}
th.table-header p {
  top: -20px;
  position: relative;
}
.b-table.table > thead > tr > th.table-header[aria-sort]::after {
  top: -10px !important;
  bottom: unset !important;
}
</style>
