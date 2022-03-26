<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{
            $t('labels.backend.seafarer_contracts.titles.index.crew_requests')
          }}
        </h3>
        <div class="card-options">
          <b-form-checkbox-group
            class="mr-2"
            buttons
            button-variant="outline-primary"
            @input="onContextChangedWithPage"
            v-model="extraSearchCriteria.statuses"
          >
            <b-form-checkbox
              v-for="(status, index) in statuses"
              :value="status"
              :key="index"
              :button-variant="'outline-' + status"
            >
              {{ status.toUpperCase() }}
            </b-form-checkbox>
          </b-form-checkbox-group>
          <b-button
            to="/Crew/seafarer_contracts/crew_requests/create"
            variant="primary"
            size="sm"
            v-b-tooltip.hover
            :title="$t('buttons.crew_requests.create')"
            v-if="$app.user.can('create crew requests')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="crew_requests.search"
        delete-route="crew_requests.destroy"
        :extra-search-criteria="extraSearchCriteria"
        :length-change="true"
        :paging="true"
        :infos="true"
        :export-data="false"
      >
        <b-table
          ref="datatable"
          responsive
          striped
          bordered
          show-empty
          no-local-sorting
          :empty-text="$t('labels.datatables.no_results')"
          :empty-filtered-text="$t('labels.datatables.no_matched_results')"
          :fields="fields"
          :items="dataLoadProvider"
          sort-by="id"
          :sort-desc="true"
          :busy.sync="isBusy"
        >
          <template slot="vessel_name" slot-scope="row">
            <router-link
              v-if="$app.user.can('edit vessels')"
              :to="`/VM/vessels/${row.item.vessel.id}/edit`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="rank" slot-scope="row">
            <span v-text="row.value.name"></span>
          </template>
          <template slot="replacement" slot-scope="row">
            <router-link
              v-if="$app.user.can(`edit own seafarers`)"
              :to="`/Crew/seafarers/${row.item.replaced_seafarer.id}/edit`"
              v-text="row.item.replaced_seafarer.name"
            ></router-link>
            <span v-else v-text="row.item.replaced_seafarer.name"></span>
          </template>
          <template slot="suggested" slot-scope="row">
            <router-link
              v-if="$app.user.can(`edit own seafarers`)"
              :to="`/Crew/seafarers/${row.item.candidate_seafarer.id}/edit`"
              v-text="row.item.candidate_seafarer.name"
            ></router-link>
            <span v-else v-text="row.item.candidate_seafarer.name"></span>
          </template>
          <template slot="rank_name" slot-scope="row">
            <span v-text="row.item.rank.name"></span>
          </template>
          <template slot="status" slot-scope="row">
            <h4>
              <b-badge :variant="row.value">
                {{ row.value.toUpperCase() }}
              </b-badge>
            </h4>
          </template>
          <template slot="actions" slot-scope="row">
            <b-button
              v-if="row.item.can_edit && row.item.status == 'pending'"
              size="sm"
              variant="success"
              v-b-tooltip.hover
              :title="$t('buttons.mark_as_done')"
              @click.stop="onDone(row.item.id)"
              class="mr-1"
            >
              <font-awesome-icon icon="check"></font-awesome-icon>
            </b-button>
            <b-button
              v-if="row.item.can_edit"
              size="sm"
              variant="primary"
              :to="`/Crew/seafarer_contracts/crew_requests/${row.item.id}/edit`"
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
import axios from 'axios'
import list from '../mixins/list'

export default {
  name: 'CrewRequestList',
  mixins: [list],
  data() {
    return {
      extraSearchCriteria: {
        statuses: ['pending', 'done']
      },
      isBusy: false,
      item_type: 'crew_request',
      fields: [
        {
          key: 'id',
          label: this.$t('validation.attributes.id'),
          sortable: true,
          class: 'text-right'
        },
        {
          key: 'vessel_code',
          label: this.$t('validation.attributes.vessel'),
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'replacement',
          label: this.$t('validation.attributes.replacement_of'),
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'suggested',
          label: this.$t('validation.attributes.suggested_seafarer'),
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'rank_name',
          label: this.$t('validation.attributes.rank'),
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'to_sign_on',
          label: this.$t('validation.attributes.sign_on_date'),
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'status',
          label: this.$t('validation.attributes.status'),
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'created_at',
          label: this.$t('validation.attributes.request_date'),
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap',
          visible: true
        }
      ],
      filters: false,
      statuses: ['pending', 'done']
    }
  },
  computed: {},
  async created() {},
  methods: {
    async onDone(id) {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        text: this.$t(
          'labels.backend.seafarer_contracts.text.mark_as_done_msg'
        ),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#4bb21f',
        confirmButtonText: this.$t('buttons.yes')
      })

      if (result.value) {
        try {
          let { data } = await axios.post(
            this.$app.route('crew_requests.mark_as_done', { crew_request: id })
          )
          this.onContextChanged()
          this.$app.noty[data.status](data.message)
        } catch (e) {
          this.$app.error(e)
        }
      }
    }
  }
}
</script>
