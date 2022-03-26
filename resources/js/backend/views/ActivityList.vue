<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.activities.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create activities')">
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
          >
            <font-awesome-icon icon="filter"></font-awesome-icon>
          </b-btn>
          <b-btn
            class="mr-2"
            variant="secondary"
            v-b-tooltip.hover
            :title="$t('buttons.print')"
            size="sm"
            v-if="$app.user.can('print activities')"
            :to="printUrl"
            target="_blank"
          >
            <font-awesome-icon icon="print"></font-awesome-icon>
          </b-btn>
          <b-btn
            v-b-toggle.create_activity_form
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.activities.create')"
            size="sm"
            v-if="$app.user.can('create activities')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-btn>
        </div>
      </template>
      <b-col>
        <b-collapse id="create_activity_form" v-model="activityDialogOpen">
          <b-card :title="$t('buttons.activities.create')">
            <div class="row">
              <div class="col-6">
                <div class="row">
                  <b-form-group
                    :label="$t('validation.attributes.title')"
                    label-for="title"
                    :label-cols="3"
                    class="col-6"
                  >
                    <b-form-input
                      id="title"
                      name="title"
                      v-model="model.title"
                      :placeholder="$t('validation.attributes.title')"
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group
                    :label="$t('validation.attributes.cost')"
                    label-for="cost"
                    :label-cols="3"
                    class="col-6"
                  >
                    <b-form-input
                      id="cost"
                      name="cost"
                      type="number"
                      :placeholder="$t('validation.attributes.cost')"
                      v-model="model.cost"
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group
                    :label="$t('validation.attributes.start_date')"
                    label-for="start_date"
                    :label-cols="3"
                    class="col-6"
                  >
                    <b-input-group>
                      <p-datetimepicker
                        :config="config"
                        id="start_date"
                        name="start_date"
                        v-model="model.start_date"
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
                  <b-form-group
                    :label="$t('validation.attributes.action_by')"
                    label-for="action_by"
                    class="col-6"
                    :label-cols="3"
                  >
                    <multiselect
                      v-model="model.action_by"
                      :options="departments"
                      id="action_by"
                      name="action_by"
                      track-by="id"
                      label="name"
                      :searchable="true"
                      :close-on-select="true"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.action_by') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </div>
              </div>

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
                @click="addActivity()"
                variant="success"
                size="sm"
                class="float-right"
                :disabled="pending"
                v-if="this.$app.user.can('create activities')"
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
        search-route="activities.search"
        delete-route="activities.destroy"
        :extra-search-criteria="extraSearchCriteria"
        :length-change="true"
        :paging="true"
        :export-data="false"
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="filter" class="mt-2">
              <b-row>
                <b-col md="6" lg="3">
                  <b-form-group
                    :label="$t('validation.attributes.action_by')"
                    label-for="action_by_dept"
                    :label-cols="3"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.action_by"
                      :options="departments"
                      id="action_by_dept"
                      name="action_by_dept"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.action_by') + ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
                <b-col md="6" lg="3">
                  <b-form-group
                    :label="$t('validation.attributes.action_from')"
                    label-for="action_from_dept"
                    :label-cols="3"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.action_from"
                      :options="departments"
                      id="action_from_dept"
                      name="action_from_dept"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.action_from') + ' --'
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
          striped
          bordered
          show-empty
          responsive
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
          <template slot="status" slot-scope="row">
            <h4>
              <b-badge :variant="row.value"> {{ row.value }} </b-badge>
            </h4>
          </template>
          <template slot="description" slot-scope="row">
            <span v-html="row.value"></span>
          </template>
          <template slot="action_by_department" slot-scope="row">
            <span v-text="row.item.action_by_department.name"></span>
          </template>
          <template slot="action_from_department" slot-scope="row">
            <span v-text="row.item.action_from_department.name"></span>
          </template>
          <template slot="row-details" slot-scope="row">
            <b-card>
              <h4 slot="header">{{ row.item.title }}</h4>
              <b-row class="mb-2">
                <b-col sm="4" class="text-justify">
                  <div class="card">
                    <div
                      :class="
                        'card-status card-status-left bg-' + row.item.status
                      "
                    ></div>
                    <div class="card-body" v-html="row.item.description"></div>
                    <div
                      class="card-footer"
                      v-if="row.item.status == 'pending'"
                    >
                      <div v-if="row.item.can_mark_as_done">
                        <b-form-group
                          :label="$t('validation.attributes.cost')"
                          label-for="cost"
                          :label-cols="3"
                        >
                          <b-form-input
                            id="name"
                            name="name"
                            type="number"
                            :placeholder="$t('validation.attributes.cost')"
                            v-model="row.item.cost"
                          ></b-form-input>
                        </b-form-group>
                        <b-form-group
                          :label="$t('validation.attributes.start_date')"
                          label-for="start_date"
                          :label-cols="3"
                        >
                          <b-input-group>
                            <p-datetimepicker
                              :config="config"
                              id="start_date"
                              name="start_date"
                              v-model="row.item.start_date"
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
                        <b-button
                          @click.stop="
                            edit(
                              row.item.id,
                              row.item.cost,
                              row.item.start_date
                            )
                          "
                          :disabled="pending"
                          variant="primary"
                          size="md"
                        >
                          <font-awesome-icon icon="edit"></font-awesome-icon>
                        </b-button>
                      </div>
                      <div
                        class="input-group"
                        v-if="$app.user.can('edit activities')"
                      >
                        <b-form-input
                          id="approved_cost"
                          name="approved_cost"
                          type="number"
                          :placeholder="
                            $t('validation.attributes.approved_cost')
                          "
                          v-model.number="row.item.approved_cost"
                          v-if="row.item.cost"
                        ></b-form-input>
                        <div class="input-group-append">
                          <b-button
                            @click.stop="
                              approve(
                                row.item.id,
                                'approved',
                                row.item.approved_cost
                              )
                            "
                            :disabled="
                              pending ||
                                (row.item.cost && !row.item.approved_cost)
                            "
                            variant="primary"
                            size="md"
                          >
                            <font-awesome-icon icon="check"></font-awesome-icon>
                          </b-button>
                        </div>
                      </div>
                    </div>
                  </div>
                </b-col>
                <b-col>
                  <comments
                    model-name="Activity"
                    :model-id="row.item.id"
                    :with-done="
                      row.item.status == 'approved' && row.item.can_mark_as_done
                    "
                    :with-input="
                      row.item.status != 'closed' &&
                        row.item.status != 'rejected'
                    "
                    @task-done="onContextChanged"
                  ></comments>
                </b-col>
              </b-row>
            </b-card>
          </template>
          <template slot="actions" slot-scope="row">
            <b-button
              v-if="$app.user.can('delete activities')"
              size="sm"
              variant="danger"
              v-b-tooltip.hover
              :title="$t('buttons.delete')"
              @click.stop="onDelete(row.item.id)"
            >
              <i class="fe fe-trash"></i>
            </b-button>
            <b-button
              v-if="
                $app.user.can('edit activities') && row.item.status == 'done'
              "
              size="sm"
              variant="success"
              v-b-tooltip.hover
              :title="$t('buttons.close')"
              @click.stop="changeStatus(row.item.id, 'close')"
            >
              <font-awesome-icon icon="check-double"></font-awesome-icon>
            </b-button>
            <b-button
              v-if="
                $app.user.can('edit activities') && row.item.status == 'done'
              "
              size="sm"
              variant="warning"
              v-b-tooltip.hover
              :title="$t('buttons.decline')"
              @click.stop="changeStatus(row.item.id, 'decline')"
            >
              <font-awesome-icon icon="ban"></font-awesome-icon>
            </b-button>
            <b-button
              v-if="
                $app.user.can('edit activities') && row.item.status == 'pending'
              "
              size="sm"
              variant="warning"
              v-b-tooltip.hover
              :title="$t('buttons.reject')"
              @click.stop="changeStatus(row.item.id, 'reject')"
            >
              <font-awesome-icon icon="ban"></font-awesome-icon>
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
import Comments from './components/ActivityComments'
import list from '../mixins/list'

export default {
  name: 'ActivityList',
  components: { Multiselect, Comments },
  mixins: [list],
  data() {
    return {
      extraSearchCriteria: {
        action_by: [],
        action_from: [],
        statuses: ['pending', 'rejected', 'done', 'approved', 'closed']
      },
      config: {
        wrap: true,
        allowInput: true
      },
      isBusy: false,
      item_type: 'activity',
      pending: false,
      activityDialogOpen: false,
      departments: [],
      model: {
        title: null,
        description: null,
        start_date: null,
        due_date: null,
        cost: null,
        action_by: null,
        approved_cost: null
      },
      statuses: ['pending', 'rejected', 'done', 'approved', 'closed']
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
          class: 'text-justify',
          sortable: true
        },
        {
          key: 'description',
          label: this.$t('validation.attributes.description'),
          class: 'text-justify',
          sortable: false
        },
        {
          key: 'start_date',
          label: this.$t('validation.attributes.start_date'),
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
          key: 'cost',
          label: this.$t('validation.attributes.cost'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'approved_cost',
          label: this.$t('validation.attributes.approved_cost'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'action_by_department',
          label: this.$t('validation.attributes.action_by'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'action_from_department',
          label: this.$t('validation.attributes.action_from'),
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
        this.$app.user.can('edit activities') ||
        this.$app.user.can('delete activities')
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
        '/activities/print?extraSearch=' +
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
  },
  methods: {
    async addActivity() {
      this.loading = true
      try {
        let formData = this.$app.objectToFormData(this.model)
        let { data } = await axios.post(
          this.$app.route('activities.store'),
          formData
        )
        this.loading = false
        this.activityDialogOpen = false
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
    async approve(id, status, approved_cost = 0) {
      this.loading = true
      try {
        let formData = this.$app.objectToFormData({
          approved_cost: approved_cost
        })
        let { data } = await axios.post(
          this.$app.route('activities.approve', { activity: id }),
          formData
        )
        this.loading = false
        this.onContextChanged()
        this.$app.noty[data.status](data.message)
      } catch (e) {
        this.$app.error(e)
        this.loading = false
      }
    },
    async edit(id, cost, startDate) {
      this.loading = true
      try {
        let formData = this.$app.objectToFormData({
          cost: cost,
          start_date: startDate,
          _method: 'PATCH'
        })

        let { data } = await axios.post(
          this.$app.route('activities.update', { activity: id }),
          formData
        )
        this.loading = false
        this.onContextChanged()
        this.$app.noty[data.status](data.message)
      } catch (e) {
        this.$app.error(e)
        this.loading = false
      }
    },
    async changeStatus(id, status) {
      this.loading = true
      try {
        let { data } = await axios.post(
          this.$app.route('activities.change_status', {
            activity: id,
            status: status
          })
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
