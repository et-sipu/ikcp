<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.doc_audits.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create doc audits')">
          <b-button
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.doc_audits.create')"
            size="sm"
            v-if="$app.user.can('create doc audits')"
            @click="
              id = null
              showCreateModal = !showCreateModal
            "
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
          <model-modal
            componenet-name="DocAuditForm"
            :model-id="id ? id : 0"
            :show-modal.sync="showCreateModal"
            size="lg"
          ></model-modal>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="doc_audits.search"
        delete-route="doc_audits.destroy"
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
            <span v-text="row.value"></span>
          </template>
          <template slot="audit_file" slot-scope="row">
            <b-link
              v-if="row.item.audit_file_url"
              :href="row.item.audit_file_url"
              target="blank"
              >{{ row.item.audit_file_name }}</b-link
            >
          </template>

          <template slot="actions" slot-scope="row">
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
  name: 'DocAuditList',
  components: { ModelModal },
  mixins: [list],
  data() {
    return {
      item_type: 'doc_audit',
      isBusy: false,
      id: null,
      showCreateModal: false
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
          key: 'audit_date',
          label: this.$t('validation.attributes.audit_date'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'nc',
          label: this.$t('validation.attributes.nc'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'obs',
          label: this.$t('validation.attributes.obs'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'audit_file',
          label: this.$t('validation.attributes.audit_file'),
          class: 'text-center',
          thStyle: { width: '30% !important' },
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit doc audits') ||
        this.$app.user.can('delete doc audits')
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
