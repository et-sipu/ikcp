<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.tasks.titles.index') }}
        </h3>
        <div class="card-options">
          <b-form-checkbox-group
            class="mr-2"
            @input="onContextChangedWithPage"
            v-model="extraSearchCriteria.statuses"
          >
            <b-form-checkbox
              v-for="(status, index) in statuses"
              :value="status"
              :key="index"
            >
              {{ status.toUpperCase() }}
            </b-form-checkbox>
          </b-form-checkbox-group>
          <b-btn
            v-b-toggle.filter
            variant="info"
            class="mr-1"
            size="sm"
            v-b-tooltip.hover
            :title="$t('buttons.show_hide_filters')"
            v-if="$app.user.can('create tasks')"
          >
            <font-awesome-icon icon="filter"></font-awesome-icon>
          </b-btn>
          <b-btn
            class="mr-2"
            variant="secondary"
            v-b-tooltip.hover
            :title="$t('buttons.print')"
            size="sm"
            v-if="$app.user.can('print tasks')"
            :to="printUrl"
            target="_blank"
          >
            <font-awesome-icon icon="print"></font-awesome-icon>
          </b-btn>
          <b-button
            v-b-toggle.create_task_form
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.tasks.create')"
            size="sm"
            v-if="$app.user.can('create tasks')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-col>
        <b-collapse
          id="create_task_form"
          v-model="taskDialogOpen"
          v-if="$app.user.can('create tasks')"
        >
          <b-card :title="$t('buttons.tasks.create')">
            <div class="row">
              <b-form-group
                :label="$t('validation.attributes.title')"
                label-for="title"
                :label-cols="3"
                class="col-2"
              >
                <b-form-input
                  id="title"
                  name="title"
                  v-model="model.title"
                  :placeholder="$t('validation.attributes.title')"
                ></b-form-input>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.assigned_department')"
                label-for="department"
                class="col-4"
                :label-cols="3"
              >
                <multiselect
                  v-model="model.assigned_department"
                  :options="departments"
                  id="assigned_department"
                  name="assigned_department"
                  track-by="id"
                  label="name"
                  :searchable="true"
                  :close-on-select="true"
                  :show-labels="false"
                  :placeholder="
                    '-- ' +
                      $t('validation.attributes.assigned_department') +
                      ' --'
                  "
                ></multiselect>
              </b-form-group>

              <b-form-group
                :label="$t('validation.attributes.description')"
                label-for="description"
                :label-cols="3"
                class="col-6"
              >
                <b-form-textarea
                  id="description"
                  name="description"
                  :rows="3"
                  v-model="model.description"
                  :placeholder="$t('validation.attributes.description')"
                ></b-form-textarea>
              </b-form-group>
            </div>
            <div slot="footer" class="text-right">
              <b-button
                @click="addTask()"
                variant="success"
                size="sm"
                class="float-right"
                :disabled="pending"
                v-if="this.$app.user.can('create tasks')"
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
        search-route="tasks.search"
        delete-route="tasks.destroy"
        :extra-search-criteria="extraSearchCriteria"
        :length-change="true"
        :paging="true"
        :infos="false"
        :export-data="false"
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="filter" class="mt-2">
              <b-row>
                <b-col class="col-6">
                  <b-form-group
                    :label="$t('validation.attributes.assigned_department')"
                    label-for="action_by_dept"
                    :label-cols="4"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.assigned_department"
                      :options="departments"
                      id="ass_depat"
                      name="assigned_department"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' +
                          $t('validation.attributes.assigned_department') +
                          ' --'
                      "
                    ></multiselect>
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
          sort-by="id"
          :sort-desc="true"
          :busy.sync="isBusy"
          @row-clicked="showDetails"
        >
          <template slot="department_name" slot-scope="row">
            <span v-text="row.item.assigned_department.name"></span>
          </template>
          <template slot="description" slot-scope="row">
            <span>{{ row.item.description | part }}</span>
          </template>
          <template slot="status" slot-scope="row">
            <h4>
              <b-badge :variant="row.value"> {{ row.value }} </b-badge>
            </h4>
          </template>
          <template slot="actions" slot-scope="row">
            <!--<b-button @click="row.toggleDetails" size="sm" variant="primary" v-b-tooltip.hover :title="$t('buttons.edit')" class="mr-1">-->
            <!--<i class="fe fe-edit"></i>-->
            <!--</b-button>-->
            <b-button
              v-if="$app.user.can('delete tasks')"
              size="sm"
              variant="danger"
              v-b-tooltip.hover
              :title="$t('buttons.delete')"
              @click.stop="onDelete(row.item.id)"
            >
              <i class="fe fe-trash"></i>
            </b-button>
            <b-button
              v-if="$app.user.can('edit tasks') && row.item.status == 'done'"
              size="sm"
              variant="success"
              v-b-tooltip.hover
              :title="$t('buttons.close')"
              @click.stop="changeStatus(row.item.id, 'close')"
            >
              <font-awesome-icon icon="check-double"></font-awesome-icon>
            </b-button>
            <b-button
              v-if="$app.user.can('edit tasks') && row.item.status == 'done'"
              size="sm"
              variant="warning"
              v-b-tooltip.hover
              :title="$t('buttons.reject')"
              @click.stop="changeStatus(row.item.id, 'decline')"
            >
              <font-awesome-icon icon="ban"></font-awesome-icon>
            </b-button>
          </template>
          <template slot="row-details" slot-scope="row">
            <b-card>
              <h4 slot="header">{{ row.item.title }}</h4>
              <b-row class="mb-2">
                <b-col sm="4" class="text-justify">
                  {{ row.item.description }}
                </b-col>
                <b-col>
                  <comments
                    model-name="Task"
                    :model-id="row.item.id"
                    :with-done="
                      row.item.status == 'pending' && row.item.can_mark_as_done
                    "
                    :with-input="row.item.status != 'closed'"
                    @task-done="onContextChanged"
                  ></comments>
                </b-col>
              </b-row>
            </b-card>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import Comments from './components/TaskComments'
import list from '../mixins/list'

export default {
  name: 'TaskList',
  components: { Multiselect, Comments },
  filters: {
    // Filter definitions
    part(value) {
      return value.length <= 50 ? value : value.substr(0, 50) + '...'
    }
  },
  mixins: [list],
  data() {
    return {
      extraSearchCriteria: {
        id: null,
        assigned_department: [],
        statuses: ['pending', 'done', 'closed']
      },
      isBusy: false,
      item_type: 'task',
      departments: [],
      pending: false,
      taskDialogOpen: false,
      model: {
        id: null,
        title: null,
        description: null,
        assigned_department: null
      },
      statuses: ['pending', 'done', 'closed']
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
          key: 'title',
          label: this.$t('validation.attributes.title'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'description',
          label: this.$t('validation.attributes.description'),
          class: 'text-center'
        },
        {
          key: 'department_name',
          label: this.$t('validation.attributes.assigned_department'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'created_at',
          label: this.$t('validation.attributes.creation_date'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'completion_date',
          label: this.$t('validation.attributes.completion_date'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'status',
          label: this.$t('validation.attributes.status'),
          class: 'text-center',
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit tasks') ||
        this.$app.user.can('delete tasks')
      ) {
        fields.push({
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap'
        })
      }
      return fields
    },
    printUrl() {
      return (
        '/tasks/print?extraSearch=' + JSON.stringify(this.extraSearchCriteria)
      )
    }
  },
  mounted() {
    if (this.$route.query.id)
      this.extraSearchCriteria.id = JSON.parse(this.$route.query.id)
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
  },
  methods: {
    onDelete(id) {
      this.$refs.datasource.deleteRow({ task: id })
    },
    async addTask() {
      this.loading = true
      try {
        let formData = this.$app.objectToFormData(this.model)
        let { data } = await axios.post(
          this.$app.route('tasks.store'),
          formData
        )
        this.loading = false
        this.taskDialogOpen = false
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
        title: null,
        description: null,
        assigned_department: null
      }
    },
    showDetails(item) {
      item._showDetails = !item._showDetails
      item._rowVariant = item._showDetails ? 'primary' : ''
    },
    async changeStatus(id, status) {
      this.loading = true
      try {
        let { data } = await axios.post(
          this.$app.route('tasks.change_status', { task: id, status: status })
        )
        this.loading = false
        this.onContextChanged()
        this.$app.noty[data.status](data.message)
      } catch (e) {
        this.$app.error(e)
        this.loading = false
      }
    }
  }
}
</script>
