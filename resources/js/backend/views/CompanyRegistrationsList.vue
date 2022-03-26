<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.company_registrations.titles.index') }}
        </h3>
        <div
          class="card-options"
          v-if="$app.user.can('create company registrations')"
        >
          <b-button
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.company_registrations.create')"
            size="sm"
            v-if="$app.user.can('create company registrations')"
            @click="
              id = null
              showCreateModal = !showCreateModal
            "
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
          <model-modal
            componenet-name="CompanyRegistrationsForm"
            :model-id="id ? id : 0"
            :show-modal.sync="showCreateModal"
            size="lg"
          ></model-modal>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="company_registrations.search"
        delete-route="company_registrations.destroy"
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
          <template slot="registration_file" slot-scope="row">
            <b-link
              v-if="row.item.registrations_file_url"
              :href="row.item.registrations_file_url"
              target="blank"
              >{{ row.item.registrations_file_name }}</b-link
            >
          </template>
          <template slot="id" slot-scope="row">
            <!--<router-link-->
            <!--v-if="row.item.can_edit"-->
            <!--:to="{-->
            <!--name: 'company_registrations_edit',-->
            <!--params: { id: row.item.id }-->
            <!--}"-->
            <!--v-text="row.value"-->
            <!--&gt;</router-link>-->
            <span v-text="row.value"></span>
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
  name: 'CompanyRegistrationsList',
  components: { ModelModal },
  mixins: [list],
  data() {
    return {
      item_type: 'company_registration',
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
          key: 'registration',
          label: this.$t(
            'validation.attributes.company_registrations.registration'
          ),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'registration_no',
          label: this.$t(
            'validation.attributes.company_registrations.registration_no'
          ),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'company',
          label: this.$t('validation.attributes.company'),
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
          key: 'grade',
          label: this.$t('validation.attributes.grade'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'validity_from',
          label: this.$t('validation.attributes.validity_from'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'validity_to',
          label: this.$t('validation.attributes.validity_to'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'registration_file',
          label: this.$t('validation.attributes.registration_file'),
          class: 'text-center',
          thStyle: { width: '30% !important' },
          sortable: false
        }
      ]
      if (
        this.$app.user.can('edit company registrations') ||
        this.$app.user.can('delete company registrations')
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
