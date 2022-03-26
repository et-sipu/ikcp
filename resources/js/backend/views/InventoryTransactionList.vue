<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.inventory_transactions.titles.index') }}
        </h3>
        <div
          class="card-options"
          v-if="$app.user.can('create inventory transactions')"
        >
          <b-button
            :to="{ name: 'inventory_transactions_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.inventory_transactions.create')"
            size="sm"
            v-if="$app.user.can('create inventory transactions')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="inventory_transactions.search"
        delete-route="inventory_transactions.destroy"
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
                name: 'inventory_transactions_edit',
                params: { id: row.item.id }
              }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="transaction_type" slot-scope="row">
            <h4>
              <b-badge variant="primary">{{
                $t(
                  `labels.backend.inventory_transactions.transaction_types.${row.value}`
                )
              }}</b-badge>
            </h4>
          </template>
          <template slot="variations" slot-scope="row">
            <h4 v-for="(key, index) in Object.keys(row.value)" :key="index">
              <b-badge variant="secondary"
                >{{ key }} => {{ row.value[key] }}</b-badge
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
                  name: 'inventory_transactions_edit',
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
  name: 'InventoryTransactionList',
  mixins: [list],
  data() {
    return {
      isBusy: false,
      item_type: 'inventory_transaction'
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
          key: 'inventory_name',
          label: this.$t('validation.attributes.inventory'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'transaction_type',
          label: this.$t('validation.attributes.transaction_type'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'inventory_item_name',
          label: this.$t('validation.attributes.inventory_item'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'variations',
          label: this.$t('validation.attributes.variations'),
          class: 'text-center'
        },
        {
          key: 'quantity',
          label: this.$t('validation.attributes.quantity'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'transaction_date',
          label: this.$t('validation.attributes.transaction_date'),
          class: 'text-center',
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit inventory transactions') ||
        this.$app.user.can('delete inventory transactions')
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
