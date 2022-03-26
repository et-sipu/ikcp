<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.inventory_transactions.ROB.main') }}
        </h3>
        <div class="card-options">
          <b-btn
            v-b-toggle.filter
            variant="primary"
            class="mr-1"
            size="sm"
            v-b-tooltip.hover
            :title="$t('buttons.show_hide_filters')"
          >
            <font-awesome-icon icon="filter"></font-awesome-icon>
          </b-btn>
          <!--          <b-button-->
          <!--            :to="{ name: 'equipment_create' }"-->
          <!--            variant="primary"-->
          <!--            v-b-tooltip.hover-->
          <!--            :title="$t('buttons.equipment.create')"-->
          <!--            size="sm"-->
          <!--            v-if="$app.user.can('create equipment')"-->
          <!--          >-->
          <!--            <i class="fe fe-plus-circle"></i>-->
          <!--          </b-button>-->
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="transactions.report"
        :extra-search-criteria="extraSearchCriteria"
        export-route="inventory_transactions.export"
        :length-change="true"
        :paging="true"
        :infos="true"
        :export-data="
          $app.user.can('view inventory transactions') ? true : false
        "
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="filter" class="mt-2">
              <b-row>
                <b-col md="6" lg="6">
                  <b-form-group
                    label="Inventory"
                    label-for="Inventory"
                    :label-cols="2"
                  >
                    <vue-multiselect
                      v-model="extraSearchCriteria.inventory_selected"
                      :options="inventories"
                      id="Inventory"
                      name="Inventory"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.inventories') + ' --'
                      "
                    >
                    </vue-multiselect>
                  </b-form-group>
                </b-col>
                <b-col md="6" lg="6">
                  <b-form-group label="Item" label-for="Item" :label-cols="2">
                    <vue-multiselect
                      v-model="extraSearchCriteria.item_selected"
                      :options="items"
                      id="Item"
                      name="Item"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.items') + ' --'
                      "
                    >
                    </vue-multiselect>
                  </b-form-group>
                </b-col>
                <b-col md="6" lg="6">
                  <b-form-group
                    :label="$t('validation.attributes.category')"
                    label-for="category"
                    :label-cols="2"
                  >
                    <treeselect
                      name="category"
                      instance-id="category"
                      v-model="extraSearchCriteria.category_selected"
                      :multiple="false"
                      :options="categories"
                      :placeholder="$t('validation.attributes.category')"
                      :disable-branch-nodes="false"
                      :show-count="true"
                      @input="atInput"
                    ></treeselect>
                  </b-form-group>
                </b-col>
              </b-row>
            </b-collapse>
          </b-col>
        </template>
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
          :sort-desc="false"
          :busy.sync="isBusy"
        >
          <template slot="category" slot-scope="row">
            <h4>
              <b-badge variant="primary">
                {{ row.item.parent_category }}&#8608;{{
                  row.item.category
                }}</b-badge
              >
            </h4>
          </template>
          <template slot="actions" slot-scope="row">
            <b-btn-group>
              <b-button
                size="sm"
                @click="
                  row.toggleDetails()
                  loadData(row.item.item_id, row)
                "
                v-b-tooltip.hover
                :title="$t('buttons.groups.show_members')"
                ><font-awesome-icon icon="chart-bar"></font-awesome-icon
              ></b-button> </b-btn-group
          ></template>
          <template slot="row-details" slot-scope="row">
            <b-card v-if="row.item.chart_data !== null">
              <div class="small">
                <line-chart
                  :width="100"
                  :height="30"
                  :chart-data="row.item.chart_data"
                  :options="getChartOptions(row)"
                ></line-chart>
              </div>
            </b-card>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import list from '../mixins/list'
import axios from 'axios'
import VueMultiselect from 'vue-multiselect/src/Multiselect'
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import LineChart from './components/Chart.vue'

export default {
  name: 'RobInventoryTransaction',
  components: { VueMultiselect, Treeselect, LineChart },
  mixins: [list],
  data() {
    return {
      isBusy: false,
      inventories: [],
      items: [],
      loaded: false,
      categories: [],
      datacollection: {
        labels: [],
        datasets: []
      },
      extraSearchCriteria: {
        inventory_selected: [],
        item_selected: [],
        category_selected: null
      },
      toto: null
    }
  },
  computed: {
    fields() {
      let fields = [
        {
          key: 'store',
          label: this.$t('validation.attributes.inventory'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'item',
          label: this.$t('validation.attributes.inventory_item'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'quantity',
          label: this.$t('validation.attributes.quantity'),
          class: 'text-center'
        },
        {
          key: 'category',
          label: this.$t('validation.attributes.category'),
          class: 'text-center'
        },
        {
          key: 'actions',
          label: this.$t('labels.actions'),
          class: 'text-center nowrap'
        }
      ]
      return fields
    },
    options() {
      return {
        tooltips: {
          enabled: true,
          callbacks: {
            label: (tooltipItems, data) => {
              // console.log(tooltipItems, data)
              return row.item.chart_info[tooltipItems.index].description
            }
          }
        }
      }
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`inventories.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    })
    this.inventories = data.data
    ;({ data } = await axios.get(this.$app.route(`inventory_items.search`)))
    this.items = data.data
    ;({ data } = await axios.get(
      this.$app.route(`inventory_item_categories.get_as_tree`)
    ))
    this.categories = data
  },
  methods: {
    getChartOptions(row) {
      return {
        tooltips: {
          enabled: true,
          callbacks: {
            label: (tooltipItems, data) => {
              return (
                tooltipItems.value +
                ': ' +
                row.item.chart_info[tooltipItems.index].description
              )
            }
          }
        }
      }
    },
    atInput(value) {
      this.checkClearance(value)
      this.onContextChangedWithPage()
    },
    checkClearance(value) {
      if (value === undefined) this.category_selected = null
    },
    loadData(item_id, row) {
      this.loaded = false
      try {
        axios
          .get(
            this.$app.route('transactions.chart', {
              inventory_item: item_id,
              store_id: row.item.store_id
            })
          )
          .then(response => {
            // JSON responses are automatically parsed.
            const responseData = response.data
            row.item.chart_info = responseData
            // this.datacollection = {
            row.item.chart_data = {
              labels: responseData.map(item => item.date),
              datasets: [
                {
                  label: `${responseData[0].name} in ${row.item.store}`,
                  borderColor: '#FC2525',
                  pointBackgroundColor: 'white',
                  borderWidth: 1,
                  pointBorderColor: 'red',
                  data: responseData.map(item => item.sum)
                }
              ]
            }
            this.loaded = true
          })
      } catch (e) {
        // console.error(e)
      }
    }
  }
}
</script>
<style>
.small {
  max-width: 100%;
  max-height: 500px;
}
</style>
