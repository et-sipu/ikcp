<template>
  <div>
    <model-modal
      componenet-name="DocTemplateForm"
      :model-id="id ? id : 0"
      :show-modal.sync="showCreateModal"
    ></model-modal>

    <b-datatable
      ref="datasource"
      @context-changed="onContextChanged"
      search-route="doc_templates.search"
      delete-route="doc_templates.destroy"
      :length-change="false"
      :paging="true"
      :search="false"
      :infos="false"
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
          <span v-text="row.value"></span>
        </template>
        <template slot="template" slot-scope="row">
          <b-link
            v-if="row.item.template_url"
            :href="row.item.template_url"
            target="blank"
            >{{ row.item.template_file_name }}</b-link
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
  </div>
</template>

<script>
import list from '../mixins/list'
import ModelModal from './components/ModelModal'

export default {
  name: 'DocTemplatePerType',
  components: { ModelModal },
  mixins: [list],
  props: {
    extraSearchCriteria: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      isBusy: false,
      showCreateModal: false,
      item_type: 'doc_template',
      id: null
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
          key: 'code',
          label: this.$t('validation.attributes.code'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'title',
          label: this.$t('validation.attributes.title'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'template',
          label: this.$t('validation.attributes.template'),
          class: 'text-center',
          thStyle: { width: '30% !important' },
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit doc templates') ||
        this.$app.user.can('delete doc templates')
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
