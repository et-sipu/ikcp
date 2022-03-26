<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.seafarer_contracts.titles.index.contracts') }}
        </h3>
        <div class="card-options">
          <b-form-checkbox-group
            buttons
            button-variant="outline-primary"
            @input="onContextChangedWithPage"
            v-model="extraSearchCriteria.statuses"
            v-if="$app.user.can('view seafarer contracts')"
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
          <b-btn
            v-b-toggle.collapse1
            variant="primary"
            class="ml-1"
            size="sm"
            v-b-tooltip.hover
            :title="$t('buttons.show_hide_filters')"
          >
            <font-awesome-icon icon="filter"></font-awesome-icon>
          </b-btn>
        </div>
      </template>
      <b-modal
        ref="signOnModal"
        v-model="showSignModal"
        :no-close-on-backdrop="loading"
        :no-close-on-esc="loading"
        header-bg-variant="primary"
        footer-bg-variant="secondary"
        :title="'Sign ' + model.sign_type + ' Seafarer'"
      >
        <b-container fluid>
          <b-row class="mb-3">
            <b-col cols="4"> {{ $t('validation.attributes.seafarer') }} </b-col>
            <b-col>
              <b-form-input
                id="seafarer"
                name="seafarer"
                :placeholder="$t('validation.attributes.seafarer')"
                disabled
                v-model="model.seafarer"
              ></b-form-input>
            </b-col>
          </b-row>
          <b-row class="mb-3">
            <b-col cols="4">
              {{ $t('validation.attributes.sign_type') }}
            </b-col>
            <b-col>
              <h3>
                <b-badge variant="primary"> {{ model.sign_type }} </b-badge>
              </h3>
            </b-col>
          </b-row>
          <b-row class="mb-3">
            <b-col cols="4">
              {{
                $t(
                  `validation.attributes.sign_${model.sign_type.toLowerCase()}_date`
                )
              }}
            </b-col>
            <b-col>
              <b-input-group>
                <p-datetimepicker
                  :config="config"
                  id="insurance_expiry_date"
                  name="insurance_expiry_date"
                  v-model="model.sign_date"
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
            </b-col>
          </b-row>
        </b-container>
        <div slot="modal-footer" class="w-100">
          <b-btn
            size="sm"
            class="float-right"
            variant="primary"
            @click="
              onApproveSign(
                model.seafarer_contract_id,
                model.sign_id,
                model.sign_type,
                model.sign_date
              )
            "
            v-if="signWithEdit"
          >
            {{ $t('buttons.save_and_approve') }}
          </b-btn>
          <b-btn
            size="sm"
            class="float-right"
            variant="primary"
            @click="onSign()"
            v-else
          >
            {{ $t('buttons.save') }}
          </b-btn>
          <b-btn
            size="sm"
            class="float-left"
            variant="danger"
            @click="showSignModal = false"
          >
            {{ $t('buttons.close') }}
          </b-btn>
          <atom-spinner
            :animation-duration="1000"
            :size="200"
            :color="'#DBB11E'"
            :loading="loading"
          ></atom-spinner>
        </div>
      </b-modal>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="seafarer_contracts.search"
        delete-route="seafarer_contracts.destroy"
        :extra-search-criteria="extraSearchCriteria"
        :length-change="true"
        :paging="true"
        :infos="true"
        :export-data="false"
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="collapse1" class="mt-2">
              <b-row>
                <b-col md="6" lg="3">
                  <b-form-group
                    :label="$t('validation.attributes.capabilities_info.rank')"
                    label-for="rank"
                    :label-cols="3"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.ranks"
                      :options="ranks"
                      group-values="ranks"
                      group-label="department"
                      :group-select="true"
                      track-by="id"
                      label="name"
                      @input="onContextChangedWithPage"
                      :searchable="true"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' +
                          $t('validation.attributes.capabilities_info.rank') +
                          ' --'
                      "
                    ></multiselect>
                  </b-form-group>
                </b-col>
              </b-row>
            </b-collapse>
          </b-col>
        </template>
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
          sort-by="seafarer_name"
          :sort-desc="true"
          :busy.sync="isBusy"
        >
          <template v-slot:cell(id)="row">
            <router-link
              v-if="canEdit(row.item)"
              :to="`/Crew/seafarer_contracts/contracts/${row.item.id}/edit`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template v-slot:cell(vessel_name)="row">
            <router-link
              v-if="$app.user.can('edit vessels')"
              :to="`/VM/vessels/${row.item.vessel_id}/edit`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template v-slot:cell(seafarer_name)="row">
            <router-link
              v-if="$app.user.can(`own seafarers`)"
              :to="`/Crew/seafarers/${row.item.seafarer_id}/edit`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template v-slot:cell(sign_on_date)="row">
            <h4 v-if="typeof row.value == 'string'">
              <b-badge variant="primary"> {{ row.value }} </b-badge>
            </h4>
            <b-input-group
              v-else-if="row.value && $app.user.can('approve seafarer signs')"
            >
              <b-form-input disabled :value="row.value.date"></b-form-input>
              <b-input-group-append>
                <b-btn
                  class="px-1"
                  variant="outline-primary"
                  @click="onApproveSign(row.item.id, row.value.id, 'ON')"
                  v-b-tooltip.hover
                  :title="$t('buttons.approve')"
                >
                  <font-awesome-icon icon="stamp"></font-awesome-icon>
                </b-btn>
                <b-btn
                  class="px-1"
                  variant="outline-secondary"
                  @click="onEditSign(row.item, row.value, 'ON')"
                  v-b-tooltip.hover
                  :title="$t('buttons.edit_and_approve')"
                >
                  <font-awesome-icon icon="edit"></font-awesome-icon>
                </b-btn>
              </b-input-group-append>
            </b-input-group>
            <h4
              v-else-if="row.value && !$app.user.can('approve seafarer signs')"
            >
              <b-badge variant="warning"> {{ row.value.date }} </b-badge>
              <b-btn
                class="py-0"
                variant="outline-secondary"
                @click="onDeleteSign(row.item, row.value)"
                v-b-tooltip.hover
                :title="$t('buttons.delete_sign')"
                size="sm"
                v-if="$app.user.can('delete pending seafarer signs')"
              >
                <font-awesome-icon icon="times"></font-awesome-icon>
              </b-btn>
            </h4>
          </template>
          <template v-slot:cell(sign_off_date)="row">
            <h4 v-if="row.value && typeof row.value == 'string'">
              <b-badge variant="primary"> {{ row.value }} </b-badge>
            </h4>
            <b-input-group
              v-else-if="row.value && $app.user.can('approve seafarer signs')"
            >
              <b-form-input disabled :value="row.value.date"></b-form-input>
              <b-input-group-append>
                <b-btn
                  class="px-1"
                  variant="outline-primary"
                  @click="onApproveSign(row.item.id, row.value.id, 'OFF')"
                  v-b-tooltip.hover
                  :title="$t('buttons.approve')"
                >
                  <font-awesome-icon icon="stamp"></font-awesome-icon>
                </b-btn>
                <b-btn
                  class="px-1"
                  variant="outline-secondary"
                  @click="onEditSign(row.item, row.value, 'OFF')"
                  v-b-tooltip.hover
                  :title="$t('buttons.edit_and_approve')"
                >
                  <font-awesome-icon icon="edit"></font-awesome-icon>
                </b-btn>
              </b-input-group-append>
            </b-input-group>
            <h4
              v-else-if="row.value && !$app.user.can('approve seafarer signs')"
            >
              <b-badge variant="warning"> {{ row.value.date }} </b-badge>
              <b-btn
                class="py-0"
                variant="outline-secondary"
                @click="onDeleteSign(row.item, row.value)"
                v-b-tooltip.hover
                :title="$t('buttons.delete_sign')"
                size="sm"
                v-if="$app.user.can('delete pending seafarer signs')"
              >
                <font-awesome-icon icon="times"></font-awesome-icon>
              </b-btn>
            </h4>
          </template>
          <template v-slot:cell(duration_of_service)="row">
            <span
              v-text="row.value + ' ' + row.item.duration_of_service_unit + 's'"
            ></span>
          </template>
          <!--<template v-slot:cell(basic_salary)="row">-->
          <!--<span>{{ row.item.currency + ' ' + row.value + '/' + row.item.pay_frequency }}</span>-->
          <!--</template>-->
          <template v-slot:cell(status)="row">
            <h4>
              <b-badge :variant="row.value">
                {{ row.value.toUpperCase() }}
              </b-badge>
            </h4>
          </template>
          <template v-slot:cell(actions)="row">
            <b-dropdown
              v-if="!row.item.signed_contract_path"
              size="sm"
              variant="secondary"
              v-b-tooltip.hover
              :title="$t('buttons.print')"
              class="mr-1"
              no-caret
            >
              <template slot="button-content">
                <span class="fe fe-printer"></span>
              </template>
              <b-dropdown-item
                target="_blank"
                variant="secondary"
                :to="`/seafarer_contracts/${row.item.id}/print`"
              >
                CCA
              </b-dropdown-item>
              <b-dropdown-item
                target="_blank"
                variant="secondary"
                :to="`/seafarer_contracts/${row.item.id}/print_sea`"
              >
                SEA
              </b-dropdown-item>
              <b-dropdown-item
                target="_blank"
                variant="secondary"
                :to="`/seafarer_contracts/${row.item.id}/print_prop`"
              >
                CCA Prop
              </b-dropdown-item>
            </b-dropdown>
            <b-dropdown
              v-else-if="row.item.signed_contract_path"
              size="sm"
              variant="secondary"
              v-b-tooltip.hover
              :title="$t('buttons.view_signed_contract')"
              class="mr-1"
              no-caret
            >
              <template slot="button-content">
                <font-awesome-icon icon="file-contract"></font-awesome-icon>
              </template>
              <b-dropdown-item
                :href="row.item.signed_contract_path"
                target="_blank"
              >
                {{ $t('buttons.view_signed_contract') }}
              </b-dropdown-item>
              <b-dropdown-item
                target="_blank"
                variant="secondary"
                :to="`/seafarer_contracts/${row.item.id}/print_sea`"
              >
                SEA
              </b-dropdown-item>
            </b-dropdown>
            <b-button
              v-if="canDelete"
              size="sm"
              variant="danger"
              v-b-tooltip.hover
              :title="$t('buttons.delete')"
              @click.stop="onDelete(row.item.id)"
            >
              <i class="fe fe-trash"></i>
            </b-button>
            <b-button
              v-if="canEdit(row.item)"
              size="sm"
              variant="primary"
              :to="`/Crew/seafarer_contracts/contracts/${row.item.id}/edit`"
              v-b-tooltip.hover
              :title="$t('buttons.edit')"
              class="mr-1"
            >
              <i class="fe fe-edit"></i>
            </b-button>
            <b-button
              v-if="
                canAddSign &&
                  row.item.status == 'approved' &&
                  !row.item.sign_on_date
              "
              size="sm"
              variant="primary"
              v-b-tooltip.hover
              :title="$t('buttons.sign_on')"
              @click.stop="onSignOn(row.item)"
            >
              <font-awesome-icon icon="sign-in-alt"></font-awesome-icon>
            </b-button>
            <b-button
              v-if="
                canAddSign &&
                  row.item.status === 'approved' &&
                  row.item.sign_on_date &&
                  !row.item.sign_off_date
              "
              size="sm"
              variant="warning"
              v-b-tooltip.hover
              :title="$t('buttons.sign_off')"
              @click.stop="onSignOff(row.item)"
            >
              <font-awesome-icon icon="sign-out-alt"></font-awesome-icon>
            </b-button>
            <b-button
              v-if="canApprove && row.item.status === 'pending'"
              size="sm"
              variant="success"
              v-b-tooltip.hover
              :title="$t('buttons.approve')"
              @click.stop="onApproveContract(row.item.id)"
            >
              <font-awesome-icon icon="stamp"></font-awesome-icon>
            </b-button>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import AtomSpinner from '../components/Plugins/AtomSpinner.vue'
import list from '../mixins/list'

export default {
  name: 'SeafarerContractList',
  components: {
    Multiselect,
    AtomSpinner
  },
  mixins: [list],
  data() {
    return {
      extraSearchCriteria: {
        statuses: [],
        ranks: []
      },
      ranks: [],
      isBusy: false,
      item_type: 'seafarer_contract',
      fields: [
        {
          key: 'id',
          label: this.$t('validation.attributes.id'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'vessel_name',
          label: this.$t('validation.attributes.vessel'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'seafarer_name',
          label: this.$t('validation.attributes.seafarer'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'duration_of_service',
          label: this.$t('validation.attributes.duration_of_service'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'rank_name',
          label: this.$t('validation.attributes.rank'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        // {
        //   key: 'basic_salary',
        //   label: this.$t('validation.attributes.basic_salary'),
        //   sortable: true,
        //   class: 'text-center',
        //   visible: true
        // },
        // { key: 'pay_leave', label: this.$t('validation.attributes.pay_leave'), sortable: true, class: 'text-center', visible: true },
        {
          key: 'scheduled_sign_on_date',
          label: this.$t('validation.attributes.scheduled_sign_on_date'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'sign_on_date',
          label: this.$t('validation.attributes.sign_on_date'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        {
          key: 'sign_off_date',
          label: this.$t('validation.attributes.sign_off_date'),
          sortable: true,
          class: 'text-center',
          visible: true
        },
        // { key: 'sign_on_port_name', label: this.$t('validation.attributes.sign_on_port'), sortable: false, class: 'text-center', visible: true },
        {
          key: 'status',
          label: this.$t('validation.attributes.status'),
          sortable: false,
          class: 'text-center',
          visible: true
        },
        {
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'nowrap',
          visible: true
        }
      ],
      filters: false,
      statuses: ['approved', 'pending', 'archived'],
      model: {
        sign_type: 'ON',
        seafarer: null,
        seafarer_contract_id: null,
        sign_date: null,
        sign_id: null
      },
      config: {
        wrap: true,
        allowInput: true
      },
      showSignModal: false,
      signWithEdit: false,
      loading: false
    }
  },
  computed: {
    canApprove() {
      return this.$app.user.can('approve seafarer signs')
    },
    canDelete() {
      return this.$app.user.can('delete seafarer contracts')
    },
    canAddSign() {
      return this.$app.user.can('edit own seafarer contracts')
    }
  },
  async created() {
    this.extraSearchCriteria.statuses = this.statuses
    let { data } = await axios.get(this.$app.route(`get_list_of_ranks`))

    let ranks = []

    for (let department in data) {
      let dep = { department: department, ranks: [] }

      for (let rank in data[department]) {
        dep.ranks.push(data[department][rank])
      }
      ranks.push(dep)
    }
    this.ranks = ranks
  },
  methods: {
    async onApproveContract(id) {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        text: this.$t(
          'labels.backend.seafarer_contracts.text.approve_contract_msg'
        ),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#4bb21f',
        confirmButtonText: this.$t('buttons.approve')
      })

      if (result.value) {
        try {
          let { data } = await axios.post(
            this.$app.route('seafarer_contracts.approve', {
              seafarer_contract: id
            })
          )
          this.onContextChanged()
          this.$app.noty[data.status](data.message)
        } catch (e) {
          this.$app.error(e)
        }
      }
    },
    async onDeleteSign(contractId, signId) {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        text: this.$t('labels.backend.seafarer_contracts.text.delete_sign'),
        type: 'success',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#4bb21f',
        confirmButtonText: this.$t('buttons.delete')
      })

      if (result.value) {
        try {
          this.$refs.datasource.loading = true
          let { data } = await axios.delete(
            this.$app.route('seafarer_contracts.delete_sign', {
              seafarer_contract: contractId,
              sign: signId
            })
          )
          this.onContextChanged()
          this.$app.noty[data.status](data.message)
        } catch (e) {
          this.$app.error(e)
          this.$refs.datasource.loading = false
        }
      }
    },
    async onApproveSign(contractId, signId, signType, signDate = null) {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        text:
          signType === 'ON'
            ? this.$t(
                'labels.backend.seafarer_contracts.text.approve_sign_on_msg'
              )
            : this.$t(
                'labels.backend.seafarer_contracts.text.approve_sign_off_msg'
              ),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#4bb21f',
        confirmButtonText: this.$t('buttons.approve')
      })

      if (result.value) {
        this.loading = true
        try {
          if (!signDate) {
            this.$refs.datasource.loading = true
          }
          let formData = this.$app.objectToFormData({ sign_date: signDate })
          let { data } = await axios.post(
            this.$app.route('seafarer_contracts.approve_sign', {
              seafarer_contract: contractId,
              sign: signId
            }),
            formData
          )
          this.loading = false
          this.$refs.datasource.loading = false
          this.showSignModal = false
          this.onContextChanged()
          this.$app.noty[data.status](data.message)
        } catch (e) {
          this.$app.error(e)
          this.loading = false
          this.$refs.datasource.loading = false
        }
      }
    },
    onSignOn(item) {
      this.model.sign_type = 'ON'
      this.showSigningModal(item)
    },
    onSignOff(item) {
      this.model.sign_type = 'OFF'
      this.showSigningModal(item)
    },
    showSigningModal(item) {
      this.model.seafarer = item.seafarer_name
      this.model.seafarer_contract_id = item.id
      this.model.sign_date = null
      this.model.sign_id = null
      this.signWithEdit = false
      this.showSignModal = true
    },
    onEditSign(item, sign, signType) {
      this.model.seafarer = item.seafarer_name
      this.model.sign_type = signType
      this.model.seafarer_contract_id = item.id
      this.model.sign_date = sign.date
      this.model.sign_id = sign.id
      this.signWithEdit = true
      this.showSignModal = true
    },
    async onSign() {
      this.loading = true
      try {
        let formData = this.$app.objectToFormData(this.model)
        let { data } = await axios.post(
          this.$app.route('seafarer_contracts.signons', {
            seafarer_contract: this.model.seafarer_contract_id
          }),
          formData
        )
        this.loading = false
        this.showSignModal = false
        this.onContextChanged()
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
    },
    canEdit(item) {
      return (
        (this.$app.user.can('edit own seafarer contracts') &&
          item.status === 'approved') ||
        (this.$app.user.can('edit seafarer contracts') &&
          (item.status === 'approved' || item.status === 'pending'))
      )
    }
  }
}
</script>
