<template>
  <div class="table-container">
    <b-row class="mb-3" v-if="extraSearchCriteria">
      <slot name="extra_filters"></slot>
    </b-row>
    <b-row v-if="search || infos || lengthChange">
      <b-col md="4" class="mb-3">
        <b-form inline v-if="lengthChange">
          <label class="mr-2">
            {{ $t('labels.datatables.show_per_page') }}
          </label>
          <b-form-select
            :options="pageOptions"
            v-model="perPage"
            class="mr-2"
            @input="onContextChangedWithPage"
          ></b-form-select>
          <label>{{ $t('labels.datatables.entries_per_page') }}</label>
        </b-form>
      </b-col>
      <b-col md="4" class="mb-3 text-center">
        <label class="mt-2" v-if="infos">
          {{
            $t('labels.datatables.infos', {
              offset_start: perPage * (currentPage - 1) + 1,
              offset_end: perPage * currentPage,
              total: totalRows
            })
          }}
        </label>
      </b-col>
      <b-col md="4" class="mb-3">
        <b-form
          inline
          v-if="search"
          class="d-flex justify-content-end"
          @submit.prevent
        >
          <label class="mr-2"> {{ $t('labels.datatables.search') }} </label>
          <b-form-input
            v-model="searchQuery"
            @input="debounceInput"
          ></b-form-input>
          <b-btn
            variant="info"
            class="ml-2"
            size="sm"
            v-b-tooltip.hover
            :title="$t('buttons.refresh')"
            @click="onContextChanged"
          >
            <font-awesome-icon icon="sync-alt"></font-awesome-icon>
          </b-btn>
        </b-form>
      </b-col>
    </b-row>
    <div style="position: relative;">
      <slot></slot>
      <atom-spinner
        :animation-duration="1000"
        :size="200"
        :color="'#DBB11E'"
        :loading="loading"
      ></atom-spinner>
    </div>
    <b-row>
      <b-col md="4">
        <!--<form class="form-inline" @submit.prevent="onBulkAction" v-if="actions && Object.keys(actions).length">-->
        <!--<div class="form-group form-group-sm">-->
        <!--<select name="action" class="form-control mr-1" v-model="action">-->
        <!--<option v-for="(action, value) in actions" :key="value" :value="value">{{ action }}</option>-->
        <!--</select>-->
        <!--<b-button type="submit" variant="danger">{{ $t('buttons.execute') }}</b-button>-->
        <!--</div>-->
        <!--</form>-->
        <form
          class="form-inline"
          @submit.prevent="onBulkAction"
          v-if="actions && Object.keys(actions).length"
        >
          <div class="form-group">
            <b-form-select
              :options="actions"
              v-model="action"
              class="mr-1"
            ></b-form-select>
            <b-button type="submit" variant="danger">
              {{ $t('labels.execute') }}
            </b-button>
          </div>
        </form>
      </b-col>
      <b-col md="4" style="padding: 0px">
        <b-pagination
          :total-rows="totalRows"
          :per-page="perPage"
          v-model="currentPage"
          v-if="paging && totalRows > perPage"
          class="justify-content-center"
          @input="onContextChanged"
        ></b-pagination>
      </b-col>
      <b-col md="4">
        <div v-if="exportData" class="d-flex justify-content-end">
          <b-button @click.prevent="onExportData">
            <i class="fe fe-download"></i> {{ $t('labels.export') }}
          </b-button>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import axios from 'axios'
import qs from 'qs'
import AtomSpinner from './AtomSpinner'
import _ from 'lodash'

export default {
  components: {
    AtomSpinner
  },
  props: {
    lengthChange: {
      type: Boolean,
      default: true
    },
    paging: {
      type: Boolean,
      default: true
    },
    infos: {
      type: Boolean,
      default: true
    },
    search: {
      type: Boolean,
      default: true
    },
    searchRoute: {
      type: String,
      default: null
    },
    exportData: {
      type: Boolean,
      default: false
    },
    exportRoute: {
      type: String,
      default: null
    },
    deleteRoute: {
      type: String,
      default: null
    },
    actionRoute: {
      type: String,
      default: null
    },
    actions: {
      type: Object,
      default: () => {}
    },
    otherSearchCriteria: {
      type: Object,
      default: null
    },
    extraSearchCriteria: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      currentPage: 1,
      perPage: 30,
      totalRows: 0,
      // pageOptions: [ 5, 10, 15, 25, 50 ],
      pageOptions: [10, 20, 30, 50, 100],
      searchQuery: null,
      selected: [],
      action: null,
      loading: false
    }
  },
  watch: {
    currentPage(newValue, oldValue) {
      if (oldValue !== newValue) window.location.hash = newValue
    }
  },
  mounted() {
    if (this.actions && Object.keys(this.actions).length > 0) {
      this.action = Object.keys(this.actions)[0]
    }
  },
  methods: {
    debounceInput: _.debounce(function() {
      // this.onContextChanged()
      this.onContextChangedWithPage()
    }, 1000),
    onContextChangedWithPage() {
      this.currentPage = 1
      this.onContextChanged()
    },
    onContextChanged() {
      this.$emit('context-changed')
    },
    async loadData(sortBy, sortDesc, summery = {}) {
      try {
        this.loading = true
        let { data } = await axios.get(this.$app.route(this.searchRoute), {
          params: {
            page: this.currentPage,
            perPage: this.perPage,
            column: sortBy,
            direction: sortDesc ? 'desc' : 'asc',
            search: this.searchQuery,
            otherSearch: this.otherSearchCriteria,
            extraSearch: this.extraSearchCriteria
          }
        })

        this.totalRows = data.meta.pagination.total
        this.loading = false
        summery.summery = data.summery
        return data.data
      } catch (e) {
        this.$app.error(e)
        this.loading = false
        return []
      }
    },
    onExportData() {
      window.location = `${this.$app.route(this.exportRoute)}?${qs.stringify({
        search: this.searchQuery,
        otherSearch: this.otherSearchCriteria,
        extraSearch: this.extraSearchCriteria
      })}`
    },
    async deleteRow(params) {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#dd4b39',
        confirmButtonText: this.$t('buttons.delete')
      })

      if (result.value) {
        try {
          let { data } = await axios.delete(
            this.$app.route(this.deleteRoute, params)
          )
          this.onContextChanged()
          this.$app.noty[data.status](data.message)
        } catch (e) {
          this.$app.error(e)
        }
      }
    },
    async onBulkAction() {
      let result = await window.swal({
        title: this.$t('labels.are_you_sure'),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: this.$t('buttons.cancel'),
        confirmButtonColor: '#dd4b39',
        confirmButtonText: this.$t('buttons.confirm')
      })

      if (result.value) {
        try {
          let { data } = await axios.post(this.$app.route(this.actionRoute), {
            action: this.action,
            ids: this.selected
          })

          this.onContextChanged()
          this.$emit('bulk-action-success')
          this.$app.noty[data.status](data.message)
        } catch (e) {
          this.$app.error(e)
        }
      }
    }
  }
}
</script>
