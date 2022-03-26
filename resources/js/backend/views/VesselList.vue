<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.vessels.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create vessels')">
          <b-button
            to="/VM/vessels/create"
            size="sm"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.vessels.create')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="vessels.search"
        delete-route="vessels.destroy"
        :length-change="false"
        :paging="false"
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
          :sort-desc="false"
          :busy.sync="isBusy"
        >
          <template slot="name" slot-scope="row">
            <router-link
              v-if="row.item.can_edit"
              :to="`/VM/vessels/${row.item.id}/edit`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="actions" slot-scope="row">
            <b-button
              v-if="row.item.can_edit"
              size="sm"
              variant="primary"
              :to="`/VM/vessels/${row.item.id}/edit`"
              v-b-tooltip.hover
              :title="$t('buttons.edit')"
              class="mr-1"
            >
              <i class="fe fe-edit"></i>
            </b-button>
            <b-button
              v-if="row.item.can_edit"
              size="sm"
              variant="info"
              :to="`/VM/vessels/${row.item.id}/imo`"
              v-b-tooltip.hover
              :title="$t('buttons.imo')"
              class="mr-1"
            >
              IMO
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
  name: 'VesselList',
  mixins: [list],
  data() {
    return {
      isBusy: false,
      item_type: 'vessel'
    }
  },
  computed: {
    fields() {
      let fields = [
        {
          key: 'name',
          label: this.$t('validation.attributes.name'),
          sortable: true
        },
        {
          key: 'code',
          label: this.$t('validation.attributes.code'),
          sortable: true
        },
        {
          key: 'imo_number',
          label: this.$t('validation.attributes.imo_number'),
          sortable: true
        },
        {
          key: 'port_of_registry_name',
          label: this.$t('validation.attributes.port_of_registry'),
          sortable: false
        },
        {
          key: 'pilot_name',
          label: this.$t('validation.attributes.piloted_by'),
          sortable: false
        },
        {
          key: 'flag',
          label: this.$t('validation.attributes.flag'),
          sortable: true
        },
        {
          key: 'created_at',
          label: this.$t('labels.created_at'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'updated_at',
          label: this.$t('labels.updated_at'),
          class: 'text-center',
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit own vessels') ||
        this.$app.user.can('delete vessels')
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
