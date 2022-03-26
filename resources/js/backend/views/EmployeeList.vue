<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.employees.titles.index') }}
        </h3>
        <div class="card-options">
          <div class="btn-group" :class="{ show: columnMenuOpen }">
            <b-btn
              @click.stop.prevent="columnMenuOpen = !columnMenuOpen"
              size="sm"
              variant="primary"
              type="button"
              class="btn dropdown-toggle mr-1"
              data-toggle="dropdown"
              v-b-tooltip.hover
              :title="$t('buttons.show_hide_columns')"
              aria-haspopup="true"
            >
              <font-awesome-icon icon="list-alt"></font-awesome-icon>
            </b-btn>
            <ul
              class="dropdown-menu dropdown-menu-right"
              :class="{ show: columnMenuOpen }"
            >
              <li
                class="dropdown-item"
                v-for="(column, index) in all_fields"
                :key="index"
                @click.stop.prevent="toggleColumn(column)"
                style="cursor: pointer"
              >
                <a href="#">
                  <i
                    :class="{
                      'text-success': column.visible,
                      'font-weight-bold': column.visible
                    }"
                    class="fe fe-check"
                  ></i>
                  {{ column.label }}
                </a>
              </li>
            </ul>
          </div>
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
          <b-button
            :to="{ name: 'employees_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.employees.create')"
            size="sm"
            v-if="$app.user.can('create employees')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="employees.search"
        delete-route="employees.destroy"
        :extra-search-criteria="extraSearchCriteria"
        :length-change="true"
        :paging="true"
        :infos="true"
        :export-data="false"
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="filter" class="mt-2">
              <b-row>
                <b-col md="6" lg="3">
                  <b-form-group
                    :label="$t('validation.attributes.departments')"
                    label-for="departments"
                    :label-cols="4"
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
                <b-col md="6" lg="3">
                  <b-form-group
                    :label="$t('validation.attributes.branches')"
                    label-for="branches"
                    :label-cols="4"
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

                <b-col lg="6" xl="3">
                  <b-form-group
                    :label="
                      $t('validation.attributes.personal_info.nationality')
                    "
                    label-for="nationality"
                    :label-cols="4"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.nationalities"
                      :options="nationalities"
                      id="nationality"
                      name="nationality"
                      @input="onContextChangedWithPage"
                      label="nationality"
                      track-by="id"
                      :searchable="true"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' +
                          $t(
                            'validation.attributes.personal_info.nationality'
                          ) +
                          ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col lg="6" xl="3">
                  <b-form-group
                    :label="$t('validation.attributes.passport_no')"
                    label-for="passport_no"
                    :label-cols="4"
                  >
                    <b-form-input
                      id="passport_no"
                      name="passport_no"
                      @input="onContextChangedWithPage"
                      :placeholder="$t('validation.attributes.passport_no')"
                      v-model="extraSearchCriteria.passport_no"
                    >
                    </b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>
            </b-collapse>
          </b-col>
        </template>

        <b-table
          ref="datatable"
          responsive
          striped
          bordered
          show-empty
          no-local-sorting
          :empty-text="$t('labels.datatables.no_results')"
          :empty-filtered-text="$t('labels.datatables.no_matched_results')"
          :fields="fields"
          :items="dataLoadProvider"
          sort-by="full_name"
          :sort-desc="false"
          :busy.sync="isBusy"
          v-if="fields.length > 0"
        >
          <template slot="full_name" slot-scope="row">
            <router-link
              v-if="$app.user.can(`edit own employees`)"
              :to="{
                name: 'employees_edit',
                params: { id: row.item.id }
              }"
              v-text="row.value"
            ></router-link>
            <router-link
              v-else-if="$app.user.can(`show employee`)"
              :to="`/Crew/employees/${row.item.id}/view`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="passport_info" slot-scope="row">
            <span
              >{{ row.item.passport }} / {{ row.item.passport_expiry }}</span
            >
          </template>
          <template slot="actions" slot-scope="row">
            <b-btn-group>
              <b-button
                v-if="row.item.can_edit"
                size="sm"
                variant="primary"
                :to="{
                  name: 'employees_edit',
                  params: { id: row.item.id }
                }"
                v-b-tooltip.hover
                :title="$t('buttons.edit')"
              >
                <i class="fe fe-edit"></i>
              </b-button>
              <b-button
                v-if="row.item.can_delete"
                size="sm"
                variant="danger"
                v-b-tooltip.hover
                :title="$t('buttons.delete')"
                @click.stop="onDelete(row.item.id)"
              >
                <i class="fe fe-trash"></i>
              </b-button>
            </b-btn-group>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import list from '../mixins/list'
import axios from 'axios'
import Multiselect from 'vue-multiselect'

export default {
  name: 'EmployeeList',
  components: { Multiselect },
  mixins: [list],
  data() {
    return {
      extraSearchCriteria: {
        age: [16, 76],
        seniority: [0, 50],
        nationalities: [],
        departments: [],
        branches: [],
        passport_no: null
      },
      age_options: {
        eventType: 'auto',
        width: 'auto',
        min: 16,
        max: 76,
        bgStyle: {
          backgroundColor: '#666',
          boxShadow: 'inset 0.5px 0.5px 3px 1px rgba(0, 0, 0, .36)'
        },
        tooltipStyle: {
          backgroundColor: '#DBB11E',
          borderColor: '#DBB11E'
        },
        processStyle: {
          backgroundColor: '#09856E'
        }
      },
      seniority_options: {
        eventType: 'auto',
        width: 'auto',
        min: 0,
        max: 50,
        bgStyle: {
          backgroundColor: '#666',
          boxShadow: 'inset 0.5px 0.5px 3px 1px rgba(0, 0, 0, .36)'
        },
        tooltipStyle: {
          backgroundColor: '#DBB11E',
          borderColor: '#DBB11E'
        },
        processStyle: {
          backgroundColor: '#09856E'
        }
      },
      isBusy: false,
      item_type: 'employee',
      all_fields: [
        {
          key: 'id',
          label: this.$t('validation.attributes.personal_info.id'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'full_name',
          label: this.$t('validation.attributes.personal_info.name'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'nationality',
          label: this.$t('validation.attributes.personal_info.nationality'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'date_and_place_of_birth',
          label: this.$t(
            'validation.attributes.personal_info.date_and_place_of_birth'
          ),
          sortable: false,
          class: 'text-center',
          visible: true
        },
        {
          key: 'department_name',
          label: this.$t('validation.attributes.department'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'branch_name',
          label: this.$t('validation.attributes.branch'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'nric_no',
          label: this.$t('validation.attributes.personal_info.nric_no'),
          sortable: true,
          class: 'text-center',
          visible: false
        },
        {
          key: 'passport_info',
          label: this.$t('validation.attributes.passport_info'),
          sortable: false,
          class: 'text-center',
          visible: false
        },
        {
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap text-center',
          visible: true
        }
      ],
      nationalities: [],
      filters: false,
      columnMenuOpen: false,
      departments: [],
      branches: [],
      loading: false
    }
  },
  computed: {
    fields() {
      let fields = this.all_fields.filter(field => field.visible)

      return fields
    }
  },
  async created() {
    let { data } = await axios.get(
      this.$app.route(`get_list_of_constants`, { constant: 'nationalities' })
    )
    this.nationalities = Object.values(data)
    ;({ data } = await axios.get(this.$app.route(`departments.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    }))
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
  },
  methods: {
    toggleColumn: function(column) {
      column.visible = !column.visible
    }
  }
}
</script>
