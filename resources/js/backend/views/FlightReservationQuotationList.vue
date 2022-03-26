<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.flight_reservation_quotations.titles.index') }}
        </h3>
        <div class="card-options" v-if="!readonly">
          <b-btn
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.flight_reservation_quotations.create')"
            size="sm"
            @click="
              id = null
              showCreateModal = !showCreateModal
            "
          >
            <i class="fe fe-plus-circle"></i>
          </b-btn>
          <model-modal
            v-if="!readonly"
            componenet-name="FlightReservationQuotationForm"
            :model-id="id ? id : 0"
            :show-modal.sync="showCreateModal"
            :componenet-props="{
              flightReservationId: extraSearchCriteria.flight_reservation_id
            }"
          ></model-modal>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="flight_reservation_quotations.search"
        delete-route="flight_reservation_quotations.destroy"
        :length-change="false"
        :paging="false"
        :infos="false"
        :export-data="false"
        :search="false"
        :extra-search-criteria="extraSearchCriteria"
      >
        <b-table
          ref="datatable"
          striped
          bordered
          show-empty
          responsiev
          no-local-sorting
          :empty-text="$t('labels.datatables.no_results')"
          :empty-filtered-text="$t('labels.datatables.no_matched_results')"
          :fields="fields"
          :items="dataLoadProvider"
          sort-by="id"
          :sort-desc="true"
          :busy.sync="isBusy"
        >
          <template slot="website" slot-scope="row">
            <span style="word-break: break-all">{{ row.value }}</span>
          </template>
          <template slot="actions" slot-scope="row" v-if="!readonly">
            <b-btn-group>
              <b-button
                v-if="row.item.can_edit"
                size="sm"
                variant="primary"
                @click="
                  id = row.item.id
                  showCreateModal = !showCreateModal
                "
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
import ModelModal from './components/ModelModal'

export default {
  name: 'FlightReservationQuotationList',
  components: { ModelModal },
  mixins: [list],
  props: {
    extraSearchCriteria: {
      type: Object,
      required: true
    },
    readonly: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  data() {
    return {
      isBusy: false,
      item_type: 'flight_reservation_quotation',
      showCreateModal: false,
      id: null
    }
  },
  computed: {
    fields() {
      let fields = [
        {
          key: 'heading_name',
          label: this.$t('validation.attributes.heading'),
          class: 'text-center'
        },
        {
          key: 'flight_time',
          label: this.$t('validation.attributes.flight_time'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'airlines',
          label: this.$t('validation.attributes.airlines'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'website',
          label: this.$t('validation.attributes.website'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'price',
          label: this.$t('validation.attributes.price'),
          class: 'text-center',
          sortable: true
        }
      ]
      if (!this.readonly) {
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

<style scoped>
.card-body {
  padding: 0px !important;
}
</style>
