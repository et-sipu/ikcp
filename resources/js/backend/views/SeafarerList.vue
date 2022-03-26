<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.seafarers.titles.index') }}
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
            to="/Crew/seafarers/create"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.seafarers.create')"
            size="sm"
            v-if="$app.user.can('create seafarers')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="seafarers.search"
        delete-route="seafarers.destroy"
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
                    :label="$t('validation.attributes.capabilities_info.rank')"
                    label-for="rank"
                    :label-cols="4"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.ranks"
                      :options="ranks"
                      group-values="ranks"
                      group-label="department"
                      :group-select="true"
                      track-by="id"
                      label="name"
                      @input="onContextChangedWithPage"
                      :searchable="true"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' +
                          $t('validation.attributes.capabilities_info.rank') +
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
                <b-col lg="6" xl="3">
                  <b-form-group
                    :label="$t('validation.attributes.smc_reg_no')"
                    label-for="smc_reg_no"
                    :label-cols="4"
                  >
                    <b-form-input
                      id="smc_reg_no"
                      name="smc_reg_no"
                      @input="onContextChangedWithPage"
                      :placeholder="$t('validation.attributes.smc_reg_no')"
                      v-model="extraSearchCriteria.smc_reg_no"
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
              v-if="$app.user.can(`edit own seafarers`)"
              :to="`/Crew/seafarers/${row.item.id}/edit`"
              v-text="row.value"
            ></router-link>
            <router-link
              v-else-if="$app.user.can(`show seafarer`)"
              :to="`/Crew/seafarers/${row.item.id}/view`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="passport_info" slot-scope="row">
            <span
              >{{ row.item.passport }} / {{ row.item.passport_expiry }}</span
            >
          </template>
          <template slot="security_courses" slot-scope="row">
            <b-badge
              v-for="(value, index) in row.value"
              :key="index"
              variant="primary"
              class="mr-1"
            >
              {{ value }}
            </b-badge>
          </template>
          <template slot="other_courses" slot-scope="row">
            <b-badge
              v-for="(value, index) in row.value"
              :key="index"
              variant="primary"
              class="mr-1"
            >
              {{ value }}
            </b-badge>
          </template>
          <template slot="actions" slot-scope="row">
            <b-button
              v-if="
                $app.user.can('create seafarer contracts') &&
                  (!row.item.blacklisted || row.item.blacklisted.length == 0)
              "
              size="sm"
              variant="secondary"
              :to="`/Crew/seafarers/${row.item.id}/create_contract`"
              v-b-tooltip.hover
              :title="$t('buttons.seafarer_contracts.create')"
            >
              <font-awesome-icon icon="file-signature"></font-awesome-icon>
            </b-button>
            <b-button
              v-if="$app.user.can(`edit own seafarers`)"
              size="sm"
              variant="primary"
              :to="`/Crew/seafarers/${row.item.id}/edit`"
              v-b-tooltip.hover
              :title="$t('buttons.edit')"
              class="mr-1"
            >
              <i class="fe fe-edit"></i>
            </b-button>
            <b-button
              v-if="$app.user.can(`show seafarer`)"
              size="sm"
              variant="info"
              :to="`/Crew/seafarers/${row.item.id}/view`"
              v-b-tooltip.hover
              :title="$t('buttons.view')"
              class="mr-1"
            >
              <i class="fe fe-eye"></i>
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
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import list from '../mixins/list'

export default {
  name: 'SeafarerList',
  components: { Multiselect },
  mixins: [list],
  data() {
    return {
      extraSearchCriteria: {
        age: [16, 76],
        seniority: [0, 50],
        nationalities: [],
        ranks: [],
        coc_certificate_types: [],
        security_courses: [],
        other_courses: [],
        passport_no: null,
        smc_reg_no: null
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
      item_type: 'seafarer',
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
          key: 'rank',
          label: this.$t('validation.attributes.capabilities_info.rank'),
          sortable: false,
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
          key: 'nric_no',
          label: this.$t('validation.attributes.personal_info.nric_no'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'coc_type',
          label: this.$t(
            'validation.attributes.capabilities_info.coc_certificate_type'
          ),
          sortable: false,
          class: 'text-center',
          visible: true
        },
        {
          key: 'coc_expiry',
          label: this.$t(
            'validation.attributes.documents_info.COC_CERT.coc_cert_expiry'
          ),
          sortable: false,
          class: 'text-center',
          visible: true
        },
        {
          key: 'passport_info',
          label: this.$t('validation.attributes.passport_info'),
          sortable: false,
          class: 'text-center',
          visible: true
        },
        {
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap',
          visible: true
        }
      ],
      nationalities: [],
      ranks: [],
      coc_certificate_types: [
        'C/E Officer of 3000kW or more UL',
        'Engineering watch of 750kW or NC',
        'C/E Officer of 3000kW or more NC',
        'C/E OFFICER CLASS 1',
        'WKO OF 500GT OR MORE NC',
        'WKE 750KW UL',
        'CHIEF MATE of 3000 GT or MORE',
        'MASTER OF 3000GT OR MORE UL',
        'ASE'
      ],
      security_course_options: ['DSD', 'SSA', 'SSO'],
      other_courses_options: [
        'BST',
        'TANK FAM',
        'AFF',
        'MFA',
        'SUR CRAFT',
        'Food Handling'
      ],
      filters: false,
      columnMenuOpen: false,
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

    // ({data} = await axios.get(this.$app.route(`get_list_of_constants`, {constant: 'ranks'})))
    ;({ data } = await axios.get(this.$app.route(`get_list_of_ranks`)))

    let ranks = []

    for (let department in data) {
      let dep = { department: department, ranks: [] }

      for (let rank in data[department]) {
        dep.ranks.push(data[department][rank])
      }
      ranks.push(dep)
    }
    this.ranks = ranks
  },
  methods: {
    toggleColumn: function(column) {
      column.visible = !column.visible
    }
  }
}
</script>
