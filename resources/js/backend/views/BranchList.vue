<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.branches.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create branches')">
          <b-button
            :to="{ name: 'branches_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.branches.create')"
            size="sm"
            v-if="$app.user.can('create branches')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="branches.search"
        delete-route="branches.destroy"
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
          :sort-desc="false"
          :busy.sync="isBusy"
        >
          <template slot="id" slot-scope="row">
            <router-link
              v-if="row.item.can_edit"
              :to="{ name: 'branches_edit', params: { id: row.item.id } }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="actions" slot-scope="row">
            <b-button
              v-if="row.item.can_edit"
              size="sm"
              variant="primary"
              :to="{ name: 'branches_edit', params: { id: row.item.id } }"
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
  name: 'BranchList',
  mixins: [list],
  data() {
    return {
      isBusy: false
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
        }
      ]
      if (
        this.$app.user.can('edit branches') ||
        this.$app.user.can('delete branches')
      ) {
        fields.push({
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap text-center'
        })
      }
      return fields
    }
  },
  methods: {}
}
</script>
