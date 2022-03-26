<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.attendances.titles.List') }}
        </h3>
        <div class="card-options">
          <b-button
            v-b-toggle.create_attendance_form
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.attendances.create_representative')"
            size="sm"
            v-if="$app.user.can('create attendances')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-col>
        <b-collapse
          id="create_attendance_form"
          v-model="attendanceDialogOpen"
          v-if="$app.user.can('create attendances')"
        >
          <b-card title="Create Representative">
            <div class="row">
              <b-form-group
                :label="$t('validation.attributes.assigned_user')"
                label-for="assigned_user"
                class="col-4"
              >
                <multiselect
                  v-model="model.assigned_user"
                  :options="users"
                  id="assigned_user"
                  name="assigned_user"
                  track-by="id"
                  label="name"
                  :searchable="true"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="$t('validation.attributes.assigned_user')"
                ></multiselect>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.assigned_departments')"
                label-for="assigned_department"
                class="col-4"
              >
                <multiselect
                  v-model="model.assigned_department"
                  :options="departments"
                  id="assigned_department"
                  name="assigned_department"
                  track-by="id"
                  label="name"
                  :multiple="true"
                  :clear-on-select="false"
                  :close-on-select="false"
                  :searchable="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' +
                      $t('validation.attributes.assigned_departments') +
                      ' --'
                  "
                ></multiselect>
              </b-form-group>
              <b-form-group
                :label="$t('validation.attributes.assigned_branch')"
                label-for="assigned_branch"
                class="col-4"
              >
                <multiselect
                  v-model="model.assigned_branch"
                  :options="branches"
                  id="assigned_branch"
                  name="assigned_branch"
                  track-by="id"
                  label="name"
                  :searchable="true"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="$t('validation.attributes.assigned_branch')"
                ></multiselect>
              </b-form-group>
            </div>
            <div slot="footer" class="text-right">
              <b-button
                @click="addrepresent()"
                variant="success"
                size="sm"
                class="float-right"
                v-if="this.$app.user.can('create attendances')"
              >
                {{ $t('buttons.create') }}
              </b-button>
            </div>
          </b-card>
        </b-collapse>
      </b-col>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="attendance.representativesearch"
        delete-route="attendances.delete_represent"
        :length-change="true"
        :paging="true"
        :infos="false"
        :export-data="false"
      >
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
          sort-by="id"
          :sort-desc="true"
          :busy.sync="isBusy"
          @row-clicked="showDetails"
        >
          <template slot="departments" slot-scope="row">
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
              size="sm"
              variant="danger"
              v-b-tooltip.hover
              :title="$t('buttons.delete')"
              @click.stop="onDelete(row.item.id)"
            >
              <i class="fe fe-trash"></i>
            </b-button>
          </template>
          <template slot="row-details" slot-scope="row">
            <b-card>
              <h4 slot="header">{{ row.item.user_name }}</h4>
              <div class="row">
                <b-form-group
                  :label="$t('validation.attributes.assigned_department')"
                  label-for="department"
                  class="col-6"
                  :label-cols="3"
                >
                  <multiselect
                    v-model="model.assigned_department"
                    :options="departments"
                    id="d"
                    name="assigned_department"
                    track-by="id"
                    label="name"
                    :multiple="true"
                    :clear-on-select="false"
                    :close-on-select="false"
                    :searchable="true"
                    :show-labels="false"
                    :placeholder="
                      '-- ' +
                        $t('validation.attributes.assigned_department') +
                        ' --'
                    "
                  ></multiselect>
                </b-form-group>
                <b-form-group
                  label="Assigned Branch"
                  label-for="Branches"
                  class="col-6"
                  :label-cols="3"
                >
                  <multiselect
                    v-model="model.assigned_branch"
                    :options="branches"
                    id="branch"
                    name="assigned_user"
                    track-by="id"
                    label="name"
                    :multiple="false"
                    :close-on-select="true"
                    :searchable="true"
                    :show-labels="false"
                    placeholder="Assigned Branch"
                  ></multiselect>
                </b-form-group>
              </div>
            </b-card>
            <div slot="footer" class="text-right">
              <b-button
                @click="updaterepresent()"
                variant="success"
                size="sm"
                class="float-right"
              >
                {{ $t('buttons.update') }}
              </b-button>
            </div>
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
  name: 'RpresentativeList',
  components: { Multiselect },
  mixins: [list],
  data() {
    return {
      item_type: 'representative',
      isBusy: false,
      departments: [],
      users: [],
      branches: [],
      attendanceDialogOpen: false,
      model: {
        id: null,
        assigned_department: [],
        assigned_user: null,
        assigned_branch: null
      }
    }
  },
  computed: {
    fields() {
      let fields = [
        {
          key: 'id',
          label: this.$t('validation.attributes.id'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'user_name',
          label: 'User Name',
          class: 'text-center',
          sortable: false
        },
        {
          key: 'branch_name',
          label: 'Branch Name',
          class: 'text-center',
          sortable: false
        },
        {
          key: 'departments',
          label: 'Department Name',
          class: 'text-center',
          sortable: false
        },
        {
          key: 'actions',
          label: 'Actions',
          class: 'text-center',
          sortable: false
        }
      ]

      return fields
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
    ;({ data } = await axios.get(this.$app.route(`get_list_of_users`)))
    this.users = data
    // ;({ data } = await axios.get(this.$app.route(`branches.search`)))
    // this.branches = data
    ;({ data } = await axios.get(this.$app.route('branches.search'), {
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
    onDelete(id) {
      this.$refs.datasource.deleteRow({ representative: id })
    },
    async addrepresent() {
      this.loading = true
      try {
        let formData = this.$app.objectToFormData(this.model)
        let { data } = await axios.post(
          this.$app.route('attendances.store_represent'),
          formData
        )
        this.loading = false
        this.attendanceDialogOpen = false
        this.resetModel()
        this.onContextChanged()
        this.$app.noty[data.status](data.message)
      } catch (e) {
        if (e.hasOwnProperty('response') && e.response.status === 422) {
          this.$app.noty['error'](
            e.response.data.errors[Object.keys(e.response.data.errors)[0]]
          )
        } else {
          this.$app.error(e)
        }
        this.loading = false
      }
    },
    async updaterepresent() {
      this.loading = true
      try {
        let formData = this.$app.objectToFormData(this.model)
        let { data } = await axios.post(
          this.$app.route('attendances.update_represent', {
            representative: this.model.id
          }),
          formData
        )
        this.loading = false
        this.attendanceDialogOpen = false
        this.resetModel()
        this.onContextChanged()
        this.$app.noty[data.status](data.message)
      } catch (e) {
        if (e.hasOwnProperty('response') && e.response.status === 422) {
          this.$app.noty['error'](
            e.response.data.errors[Object.keys(e.response.data.errors)[0]]
          )
        } else {
          this.$app.error(e)
        }
        this.loading = false
      }
    },
    resetModel() {
      this.model = {
        assigned_branch: null,
        assigned_user: null,
        assigned_department: []
      }
    },
    showDetails(item) {
      this.resetModel()
      item._showDetails = !item._showDetails
      Object.keys(item.departments).forEach(function(id) {
        this.model.assigned_department.push({
          id: id,
          name: item.departments[id]
        })
      }, this)

      this.model.assigned_user = { id: item.user_id, name: item.user_name }

      this.model.assigned_branch = {
        id: item.branch_id,
        name: item.branch_name
      }
      this.model.id = item.id
    }
  }
}
</script>
