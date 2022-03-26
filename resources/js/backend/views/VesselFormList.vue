<template>
  <div class="animated fadeIn">
    <model-modal
      componenet-name="VesselFormForm"
      :model-id="id ? id : 0"
      :show-modal.sync="showCreateModal"
    ></model-modal>
    <b-collapse id="filter" class="mt-2">
      <b-card>
        <b-row>
          <b-col md="6" lg="4">
            <b-form-group
              :label="$t('validation.attributes.templates')"
              label-for="templates"
              :label-cols="3"
            >
              <multiselect
                v-model="extraSearchCriteria.templates"
                :options="templates"
                id="templates"
                name="templates"
                @input="onContextChangedWithPage"
                label="name"
                track-by="id"
                :multiple="true"
                :close-on-select="false"
                :show-labels="false"
                :placeholder="
                  '-- ' + $t('validation.attributes.templates') + ' --'
                "
              ></multiselect>
            </b-form-group>
          </b-col>
          <b-col md="6" lg="4">
            <b-form-group
              :label="$t('validation.attributes.template_type')"
              label-for="template_type"
              :label-cols="3"
            >
              <multiselect
                v-model="extraSearchCriteria.template_type"
                :options="[
                  {
                    id: 'ISM',
                    name: $t('labels.backend.doc_templates.template_types.ISM')
                  },
                  {
                    id: 'Reports',
                    name: $t(
                      'labels.backend.doc_templates.template_types.Reports'
                    )
                  }
                ]"
                id="template_type"
                name="template_type"
                @input="onContextChangedWithPage"
                label="name"
                track-by="id"
                :close-on-select="false"
                :show-labels="false"
                :placeholder="
                  '-- ' + $t('validation.attributes.templates') + ' --'
                "
              ></multiselect>
            </b-form-group>
          </b-col>
          <b-col md="6" lg="4">
            <b-form-group
              :label="$t('validation.attributes.date')"
              label-for="start_date"
              :label-cols="3"
            >
              <b-input-group>
                <p-datetimepicker
                  :config="config"
                  id="start_date"
                  name="start_date"
                  v-model="extraSearchCriteria.date"
                  @input="onContextChangedWithPage"
                ></p-datetimepicker>
                <b-input-group-append>
                  <b-input-group-text data-toggle>
                    <i class="fe fe-calendar"></i>
                  </b-input-group-text>
                  <b-input-group-text data-clear>
                    <i class="fe fe-x-circle"></i>
                  </b-input-group-text>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-row>
      </b-card>
    </b-collapse>
    <b-datatable
      ref="datasource"
      @context-changed="onContextChanged"
      search-route="vessel_forms.search"
      delete-route="vessel_forms.destroy"
      :length-change="true"
      :paging="true"
      :infos="true"
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
        :sort-desc="true"
        :busy.sync="isBusy"
      >
        <template slot="id" slot-scope="row">
          <span v-text="row.value"></span>
        </template>
        <template slot="vessel_name" slot-scope="row">
          <router-link
            v-if="$app.user.can('edit vessels')"
            :to="`/VM/vessels/${row.item.vessel_id}/edit`"
            v-text="row.value"
          ></router-link>
          <span v-else v-text="row.value"></span>
        </template>
        <template slot="template_name" slot-scope="row">
          <span v-text="row.value"></span>
          <b-badge variant="primary">{{ row.item.template_type }}</b-badge>
        </template>
        <template slot="form" slot-scope="row">
          <b-link
            v-if="row.item.form_url"
            :href="row.item.form_url"
            target="blank"
            >{{ row.item.form_file_name }}</b-link
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
import { get_doc_templates } from '../lib/options'
import Multiselect from 'vue-multiselect'

export default {
  name: 'VesselFormList',
  components: { ModelModal, Multiselect },
  mixins: [list],
  props: {
    vesselId: {
      type: [Number, String],
      required: true
    }
  },
  data() {
    return {
      isBusy: false,
      showCreateModal: false,
      item_type: 'vessel_form',
      id: null,
      config: {
        wrap: true,
        allowInput: true,
        mode: 'range'
      },
      extraSearchCriteria: {
        templates: [],
        template_type: null,
        date: null,
        vessel_id: null
      },
      templates: []
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
        // {
        //   key: 'template_name',
        //   label: this.$t('validation.attributes.template_name'),
        //   class: 'text-center',
        //   sortable: true
        // },
        {
          key: 'form',
          label: this.$t('validation.attributes.form_file'),
          class: 'text-center',
          sortable: false
        },
        {
          key: 'created_at',
          label: this.$t('validation.attributes.creation_date'),
          class: 'text-center',
          sortable: true
        }
      ]
      if (
        this.$app.user.can('edit vessel forms') ||
        this.$app.user.can('delete vessel forms')
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
  async created() {
    this.extraSearchCriteria.vessel_id = this.vesselId

    this.templates = await get_doc_templates(this.$app)
  },
  methods: {}
}
</script>
