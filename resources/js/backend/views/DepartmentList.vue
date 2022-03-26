<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.departments.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create departments')">
          <b-tooltip
            target="create_department_button"
            placement="lefttop"
            :title="$t('buttons.departments.create')"
          ></b-tooltip>
          <b-button
            id="create_department_button"
            to="/Hierarchy/departments/create"
            variant="primary"
            size="sm"
            v-if="$app.user.can('create departments')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="departments.search"
        delete-route="departments.destroy"
        :length-change="true"
        :paging="true"
        :infos="false"
        :export-data="false"
      >
        <b-table
          ref="datatable"
          striped
          bordered
          show-empty
          stacked="md"
          no-local-sorting
          :empty-text="$t('labels.datatables.no_results')"
          :empty-filtered-text="$t('labels.datatables.no_matched_results')"
          :fields="fields"
          :items="dataLoadProvider"
          sort-by="id"
          :sort-desc="true"
          :busy.sync="isBusy"
        >
          <template slot="id" slot-scope="row">
            <router-link
              v-if="row.item.can_edit"
              :to="`/Hierarchy/departments/${row.item.id}/edit`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="hod" slot-scope="row">
            <span v-text="row.value.name"></span>
          </template>
          <template slot="actions" slot-scope="row">
            <b-button
              v-if="row.item.can_edit"
              size="sm"
              variant="primary"
              :to="`/Hierarchy/departments/${row.item.id}/edit`"
              v-b-tooltip.hover
              :title="$t('buttons.edit')"
              class="mr-1"
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
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import list from '../mixins/list'

export default {
  name: 'DepartmentList',
  mixins: [list],
  data() {
    return {
      isBusy: false,
      item_type: 'department'
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
          key: 'name',
          label: this.$t('validation.attributes.name'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'hod',
          label: this.$t('validation.attributes.hod'),
          class: 'text-center',
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit departments') ||
        this.$app.user.can('delete departments')
      ) {
        fields.push({
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap'
        })
      }
      return fields
    }
  },
  methods: {}
}
</script>
