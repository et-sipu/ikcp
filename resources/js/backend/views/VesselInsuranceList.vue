<template>
  <div class="animated fadeIn">
    <b-card :body-class="{ 'p-0': forInclude }">
      <template slot="header" v-if="!forInclude">
        <h3 class="card-title">
          {{ $t('labels.backend.vessel_insurances.titles.index') }}
        </h3>
        <div
          class="card-options"
          v-if="$app.user.can('create vessel insurances')"
        >
          <b-button
            :to="{ name: 'vessel_insurances_create' }"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.vessel_insurances.create')"
            size="sm"
            v-if="$app.user.can('create vessel insurances')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="vessel_insurances.search"
        delete-route="vessel_insurances.destroy"
        :length-change="!forInclude"
        :paging="!forInclude"
        :infos="!forInclude"
        :search="!forInclude"
        :export-data="false"
        :extra-search-criteria="extraSearchCriteria"
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
              :to="{
                name: 'vessel_insurances_edit',
                params: { id: row.item.id }
              }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="vessels" slot-scope="row">
            <h4 v-for="(vessel, index) in row.value" :key="index">
              <b-badge
                variant="primary"
                v-text="vessel.vessel_covered.name"
              ></b-badge>
              ->
              <b-badge
                variant="primary"
                v-text="vessel.value_covered"
              ></b-badge>
            </h4>
          </template>
          <template slot="file" slot-scope="row">
            <h4 v-for="(file, index) in row.item.attachments" :key="index">
              <a :href="file.url" target="_blank">
                {{ $t('validation.attributes.attachment') }}
              </a>
            </h4>
          </template>
          <template slot="actions" slot-scope="row">
            <b-btn-group>
              <b-button
                v-if="row.item.can_edit"
                size="sm"
                variant="primary"
                :to="{
                  name: 'vessel_insurances_edit',
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
  name: 'VesselInsuranceList',
  mixins: [list],
  props: {
    forInclude: {
      default: false,
      type: Boolean
    },
    extraSearchCriteria: {
      type: Object,
      required: false,
      default: null
    }
  },
  data() {
    return {
      isBusy: false,
      item_type: 'vessel_insurance'
    }
  },
  computed: {
    fields() {
      if (this.forInclude) {
        return [
          {
            key: 'name',
            label: this.$t('validation.attributes.name'),
            class: 'text-center',
            sortable: true
          },
          {
            key: 'type',
            label: this.$t('validation.attributes.type'),
            class: 'text-center',
            sortable: true
          },
          {
            key: 'expiry_date',
            label: this.$t('validation.attributes.expiry_date'),
            class: 'text-center',
            sortable: true
          },
          {
            key: 'file',
            label: 'file',
            class: 'text-center',
            sortable: true
          }
        ]
      }

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
          key: 'type',
          label: this.$t('validation.attributes.type'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'expiry_date',
          label: this.$t('validation.attributes.expiry_date'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'vessels',
          label: 'vessels',
          class: 'text-center',
          sortable: true
        },
        {
          key: 'file',
          label: 'file',
          class: 'text-center',
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit vessel insurances') ||
        this.$app.user.can('delete vessel insurances')
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
