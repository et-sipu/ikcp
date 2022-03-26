<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.attendances.titles.fingerprints.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('import fingerprints')">
          <b-button
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.fingerprints.import')"
            size="sm"
            v-if="$app.user.can('import fingerprints')"
            @click.stop="showImportModal = true"
          >
            <font-awesome-icon icon="file-import"></font-awesome-icon>
          </b-button>
          <b-modal
            id="import_modal"
            title-tag="h4"
            :title="$t('labels.backend.attendances.titles.fingerprints.import')"
            @hidden="resetImportModal()"
            v-model="showImportModal"
          >
            <b-form-group
              :label="$t('validation.attributes.branch')"
              label-for="branch"
              :label-cols="3"
            >
              <b-form-radio-group
                v-model="import_model.branch"
                :options="['HQ', 'PI']"
                name="branch"
              >
              </b-form-radio-group>
            </b-form-group>
            <b-form-group
              :label="$t('validation.attributes.fingerprints_file')"
              :label-cols="3"
              class="file-upload-group required"
            >
              <b-form-file
                accept=".csv"
                :placeholder="$t('labels.no_file_chosen')"
                v-model="import_model.file"
                ref="fileinput"
              ></b-form-file>
            </b-form-group>
            <button
              slot="modal-footer"
              type="button"
              class="btn btn-primary"
              :disabled="!import_model.file || loading"
              @click="importFingerprints()"
            >
              {{ $t('buttons.import') }}
            </button>
            <atom-spinner
              :animation-duration="1000"
              :color="'#FEB401'"
              :loading="loading"
              :full="false"
            ></atom-spinner>
          </b-modal>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="fingerprints.search"
        delete-route="fingerprints.destroy"
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
              :to="{ name: 'fingerprints_edit', params: { id: row.item.id } }"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import list from '../mixins/list'
import axios from 'axios'
import AtomSpinner from '../components/Plugins/AtomSpinner'

export default {
  name: 'FingerprintList',
  components: { AtomSpinner },
  mixins: [list],
  data() {
    return {
      isBusy: false,
      showImportModal: false,
      import_model: {
        branch: null,
        file: null
      },
      loading: false
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
          key: 'staff_id',
          label: this.$t('validation.attributes.staff_id'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'staff_name',
          label: this.$t('validation.attributes.staff_name'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'clocking',
          label: this.$t('validation.attributes.clocking'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'terminal',
          label: this.$t('validation.attributes.terminal'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'branch_name',
          label: this.$t('validation.attributes.branch'),
          class: 'text-center',
          sortable: true
        }
      ]
      return fields
    }
  },
  methods: {
    resetImportModal() {
      this.$refs.fileinput.reset()
      this.import_model.file = null
      this.import_model.branch = null
    },
    async importFingerprints() {
      this.loading = true
      try {
        let formData = this.$app.objectToFormData(this.import_model)
        let { data } = await axios.post(
          this.$app.route('fingerprints.import'),
          formData
        )
        this.loading = false
        this.onContextChanged()
        this.showImportModal = false
        this.resetImportModal()
        this.$app.noty[data.status](data.message)
      } catch (e) {
        if (e.hasOwnProperty('response') && e.response.status === 422) {
          this.$app.noty['error'](
            e.response.data.errors[Object.keys(e.response.data.errors)[0]]
          )
        } else {
          this.$app.error(e)
        }
        this.loading = false
      }
    }
  }
}
</script>
