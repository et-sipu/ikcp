<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.ports.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create ports')">
          <b-button
            to="/Crew/Settings/ports/create"
            variant="success"
            size="sm"
          >
            <i class="fe fe-plus-circle"></i> {{ $t('buttons.ports.create') }}
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="ports.search"
        delete-route="ports.destroy"
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
          sort-by="name"
          :sort-desc="true"
          :busy.sync="isBusy"
        >
          <template slot="name" slot-scope="row">
            <router-link
              v-if="row.item.can_edit"
              :to="`/Crew/Settings/ports/${row.item.id}/edit`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="actions" slot-scope="row">
            <b-button
              v-if="row.item.can_edit"
              size="sm"
              variant="primary"
              :to="`/Crew/Settings/ports/${row.item.id}/edit`"
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
  name: 'PortList',
  mixins: [list],
  data() {
    return {
      isBusy: false,
      item_type: 'port'
    }
  },
  computed: {
    fields() {
      let fields = [
        {
          key: 'id',
          label: this.$t('validation.attributes.id'),
          sortable: true
        },
        {
          key: 'country',
          label: this.$t('validation.attributes.country'),
          sortable: true
        },
        {
          key: 'name',
          label: this.$t('validation.attributes.port_name'),
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit ports') ||
        this.$app.user.can('delete ports')
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
