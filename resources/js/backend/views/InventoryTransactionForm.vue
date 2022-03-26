<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.inventory_transactions.titles.create')
                  : $t('labels.backend.inventory_transactions.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.transaction_type')"
                  label-for="transaction_type"
                  :label-cols="3"
                  :invalid-feedback="feedback('transaction_type')"
                  :state="state('transaction_type')"
                >
                  <b-form-radio-group
                    buttons
                    button-variant="outline-primary"
                    v-model="model.transaction_type"
                    :state="state('transaction_type')"
                    name="transaction_type"
                    @change="get_variations(false)"
                  >
                    <b-form-radio
                      :value="trasaction_type"
                      v-for="(trasaction_type, index) in [
                        'check-in',
                        'check-out'
                      ]"
                      :key="index"
                    >
                      {{
                        $t(
                          `labels.backend.inventory_transactions.transaction_types.${trasaction_type}`
                        )
                      }}
                    </b-form-radio>
                  </b-form-radio-group>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.inventory')"
                  label-for="inventory"
                  :label-cols="3"
                  :invalid-feedback="feedback('inventory')"
                  :state="state('inventory')"
                >
                  <multiselect
                    id="inventory"
                    name="inventory"
                    track-by="id"
                    label="name"
                    :searchable="true"
                    v-model="model.inventory"
                    :options="inventories"
                    :close-on-select="true"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.inventory') + ' --'
                    "
                  ></multiselect>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.location')"
                  label-for="location"
                  :label-cols="3"
                  :invalid-feedback="feedback('location')"
                >
                  <b-form-input
                    id="location"
                    name="location"
                    :placeholder="$t('validation.attributes.location')"
                    v-model="model.location"
                    :state="state('location')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.inventory_item')"
                  label-for="inventory_item"
                  :label-cols="3"
                  :invalid-feedback="feedback('inventory_item')"
                  :state="state('inventory_item')"
                >
                  <multiselect
                    id="inventory_item"
                    name="inventory_item"
                    track-by="id"
                    label="name"
                    :searchable="true"
                    v-model="model.inventory_item"
                    :options="inventory_items"
                    :close-on-select="true"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.inventory_item') + ' --'
                    "
                    @input="get_variations(false)"
                  ></multiselect>
                </b-form-group>

                <fieldset
                  class="form-fieldset"
                  v-if="variations !== null && variations.length > 0"
                >
                  <h5>
                    {{ $t('validation.attributes.variants') }}
                  </h5>

                  <b-form-group
                    :invalid-feedback="feedback('variations')"
                    :state="state('variations')"
                  >
                  </b-form-group>

                  <b-form-group
                    :label="variation.name"
                    :label-for="variation.name"
                    :label-cols="3"
                    :invalid-feedback="feedback(`variations.${variation.name}`)"
                    :state="state(`variations.${variation.name}`)"
                    v-for="(variation, index) in variations"
                    :key="index"
                  >
                    <multiselect
                      :id="variation.name"
                      :name="variation.name"
                      :searchable="true"
                      v-model="model.variations[variation.name]"
                      :options="variation.options"
                      :close-on-select="true"
                      :show-labels="false"
                      :placeholder="'-- ' + variation.name + ' --'"
                      @input="get_quantities"
                    ></multiselect>
                  </b-form-group>
                </fieldset>

                <b-form-group
                  :label="$t('validation.attributes.quantity')"
                  label-for="quantity"
                  :label-cols="3"
                  :invalid-feedback="feedback('quantity')"
                >
                  <b-input-group>
                    <b-form-input
                      id="quantity"
                      name="quantity"
                      :placeholder="$t('validation.attributes.quantity')"
                      v-model="model.quantity"
                      :state="state('quantity')"
                    ></b-form-input>
                    <b-input-group-text
                      slot="prepend"
                      v-if="total_quantity !== null"
                      ><strong class="text-primary">{{
                        total_quantity
                      }}</strong></b-input-group-text
                    >
                    <b-input-group-text
                      slot="append"
                      v-if="model.inventory_item"
                      ><strong class="text-primary">{{
                        model.inventory_item.unit
                      }}</strong></b-input-group-text
                    >
                  </b-input-group>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.description')"
                  label-for="description"
                  :label-cols="3"
                  :invalid-feedback="feedback('description')"
                >
                  <b-form-textarea
                    id="description"
                    name="description"
                    :rows="3"
                    :placeholder="$t('validation.attributes.description')"
                    v-model="model.description"
                    :state="state('description')"
                  ></b-form-textarea>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.note')"
                  label-for="note"
                  :label-cols="3"
                  :invalid-feedback="feedback('note')"
                >
                  <b-form-textarea
                    id="note"
                    name="note"
                    :rows="3"
                    :placeholder="$t('validation.attributes.note')"
                    v-model="model.note"
                    :state="state('note')"
                  ></b-form-textarea>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.expired_at')"
                  label-for="expired_at"
                  :label-cols="3"
                  :invalid-feedback="feedback('expired_at')"
                  :state="state('expired_at')"
                >
                  <b-input-group>
                    <p-datetimepicker
                      :config="config"
                      id="expired_at"
                      name="expired_at"
                      v-model="model.expired_at"
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

                <b-form-group
                  :label="$t('validation.attributes.transaction_date')"
                  label-for="transaction_date"
                  :label-cols="3"
                  :invalid-feedback="feedback('transaction_date')"
                  :state="state('transaction_date')"
                >
                  <b-input-group>
                    <p-datetimepicker
                      :config="config"
                      id="transaction_date"
                      name="transaction_date"
                      v-model="model.transaction_date"
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

            <b-row slot="footer">
              <b-col md>
                <b-button
                  :to="{ name: 'inventory_transactions' }"
                  variant="danger"
                  size="sm"
                >
                  {{ $t('buttons.back') }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  class="float-right"
                  :disabled="pending"
                  v-if="
                    (isNew &&
                      this.$app.user.can('create inventory transactions')) ||
                      this.$app.user.can('edit inventory transactions')
                  "
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                </b-button>
              </b-col>
            </b-row>
          </b-card>
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
import form from '../mixins/form'
import axios from 'axios'
import Multiselect from 'vue-multiselect'

export default {
  name: 'InventoryTransactionForm',
  components: { Multiselect },
  mixins: [form],
  data() {
    return {
      modelName: 'inventory_transaction',
      resourceRoute: 'inventory_transactions',
      listPath: '/VM/inventory/inventory_transactions',
      inventories: [],
      inventory_items: [],
      variations: null,
      total_quantity: null,
      model: {
        transaction_type: null,
        inventory_item: null,
        variations: {},
        quantity: null,
        location: null,
        inventory: null,
        description: null,
        expired_at: null,
        transaction_date: null,
        note: null
      },
      config: {
        wrap: true,
        allowInput: true
      }
    }
  },
  computed: {
    check_variations() {
      if (Object.keys(this.model.variations).length === 0) return true
      else {
        return (
          Object.keys(this.model.variations).filter(
            key => this.model.variations[key] === null
          ).length === 0
        )
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

    this.inventories = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
    ;({ data } = await axios.get(this.$app.route(`inventory_items.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    }))
    this.inventory_items = data.data

    this.inventory_items = data.data.map(item => {
      return { id: item.id, name: item.name, unit: item.unit }
    })

    this.get_variations(true)
  },
  methods: {
    async get_variations(initial = false) {
      if (!this.model.inventory_item || !this.model.transaction_type) return
      if (!initial) this.model.variations = {}

      let { data } = await axios.get(
        this.$app.route(`inventory_items.get_variants`, {
          inventory_item: this.model.inventory_item.id
        })
      )
      this.variations = data

      if (initial || this.variations.length === 0) {
        this.get_quantities()
      } else if (!initial) {
        this.variations.forEach(variation => {
          this.$set(this.model.variations, variation.name, null)
        })
      }
    },
    async get_quantities() {
      if (
        this.model.inventory_item &&
        this.model.inventory &&
        this.model.transaction_type === 'check-out' &&
        this.check_variations
      ) {
        let formData = this.$app.objectToFormData({
          variations: this.model.variations
        })

        let { data } = await axios.post(
          this.$app.route(`inventories.check_quantity`, {
            inventory_item: this.model.inventory_item.id,
            inventory: this.model.inventory.id
          }),
          formData
        )
        this.total_quantity = data
      }
    }
  }
}
</script>
