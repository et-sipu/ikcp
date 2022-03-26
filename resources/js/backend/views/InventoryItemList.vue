<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.inventory_items.titles.index') }}
        </h3>
        <div
          class="card-options"
          v-if="$app.user.can('create inventory items')"
        >
          <b-button
            :to="{ name: 'inventory_items_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.inventory_items.create')"
            size="sm"
            v-if="$app.user.can('create inventory items')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="inventory_items.search"
        delete-route="inventory_items.destroy"
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
          :sort-desc="true"
          :busy.sync="isBusy"
        >
          <template slot="id" slot-scope="row">
            <router-link
              v-if="row.item.can_edit"
              :to="{
                name: 'inventory_items_edit',
                params: { id: row.item.id }
              }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="category_name" slot-scope="row">
            <h4>
              <b-badge variant="primary">
                {{ row.item.parent_category_name }}&#8608;{{
                  row.item.category_name
                }}</b-badge
              >
            </h4>
          </template>
          <template slot="actions" slot-scope="row">
            <b-btn-group>
              <b-button
                v-if="row.item.can_edit"
                size="sm"
                variant="primary"
                :to="{
                  name: 'inventory_items_edit',
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

export default {
  name: 'InventoryItemList',
  mixins: [list],
  data() {
    return {
      isBusy: false,
      item_type: 'inventory_item'
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
          key: 'part_no',
          label: this.$t('validation.attributes.part_no'),
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
          key: 'category_name',
          label: this.$t('validation.attributes.category'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'unit',
          label: this.$t('validation.attributes.unit'),
          class: 'text-center',
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit inventory items') ||
        this.$app.user.can('delete inventory items')
      ) {
        fields.push({
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'text-center nowrap'
        })
      }
      return fields
    }
  },
  methods: {}
}
</script>
