<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.inventory_item_categories.titles.index') }}
        </h3>
        <div
          class="card-options"
          v-if="$app.user.can('create inventory item categories')"
        >
          <b-button
            :to="{ name: 'inventory_item_categories_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.inventory_item_categories.create')"
            size="sm"
            v-if="$app.user.can('create inventory item categories')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="inventory_item_categories.search"
        delete-route="inventory_item_categories.destroy"
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
                name: 'inventory_item_categories_edit',
                params: { id: row.value }
              }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="parent_name" slot-scope="row">
            <router-link
              v-if="row.item.parent_id && row.item.can_edit"
              :to="{
                name: 'inventory_item_categories_edit',
                params: { id: row.item.parent_id }
              }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="actions" slot-scope="row">
            <b-btn-group>
              <b-button
                v-if="row.item.can_edit"
                size="sm"
                variant="primary"
                :to="{
                  name: 'inventory_item_categories_edit',
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
  name: 'InventoryItemCategoryList',
  mixins: [list],
  data() {
    return {
      isBusy: false,
      item_type: 'inventory_item_category'
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
          key: 'parent_name',
          label: this.$t('validation.attributes.parent'),
          class: 'text-center',
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit inventory item categories') ||
        this.$app.user.can('delete inventory item categories')
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
