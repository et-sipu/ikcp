<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.equipment.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create equipment')">
          <b-button
            :to="{ name: 'equipment_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.equipment.create')"
            size="sm"
            v-if="$app.user.can('create equipment')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="equipment.search"
        delete-route="equipment.destroy"
        :length-change="true"
        :paging="true"
        :infos="true"
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
              :to="{ name: 'equipment_edit', params: { id: row.item.id } }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="vessel" slot-scope="row">
            {{ row.item.vessel.name }}
          </template>
          <template slot="brand" slot-scope="row">
            {{ row.item.brand.name }}
          </template>
          <template slot="actions" slot-scope="row">
            <b-btn-group>
              <b-button
                v-if="row.item.can_edit"
                size="sm"
                variant="primary"
                :to="{ name: 'equipment_edit', params: { id: row.item.id } }"
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

export default {
  name: 'EquipmentList',
  mixins: [list],
  data() {
    return {
      isBusy: false,
      item_type: 'equipment'
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
          key: 'brand',
          label: this.$t('validation.attributes.brand'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'model',
          label: this.$t('validation.attributes.model'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'location',
          label: this.$t('validation.attributes.location'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'serial_num',
          label: this.$t('validation.attributes.serial_num'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'status',
          label: this.$t('validation.attributes.status'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'vessel_name',
          label: this.$t('validation.attributes.vessel'),
          class: 'text-center',
          sortable: true
        }
      ]
      fields.push({
        key: 'actions',
        label: this.$t('labels.actions'),
        class: 'text-center nowrap'
      })
      return fields
    }
  },
  methods: {}
}
</script>
