<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.inventory_items.titles.create')
                  : $t('labels.backend.inventory_items.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.part_no')"
                  label-for="part_no"
                  :label-cols="3"
                  :invalid-feedback="feedback('part_no')"
                >
                  <b-form-input
                    id="part_no"
                    name="part_no"
                    :placeholder="$t('validation.attributes.part_no')"
                    v-model="model.part_no"
                    :state="state('part_no')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.name')"
                  label-for="name"
                  :label-cols="3"
                  :invalid-feedback="feedback('name')"
                >
                  <b-form-input
                    id="name"
                    name="name"
                    :placeholder="$t('validation.attributes.name')"
                    v-model="model.name"
                    :state="state('name')"
                  ></b-form-input>
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
                  :label="$t('validation.attributes.category')"
                  label-for="category"
                  :label-cols="3"
                  :invalid-feedback="feedback('category')"
                  :state="state('category')"
                >
                  <treeselect
                    name="category"
                    instance-id="category"
                    v-model="model.category"
                    :multiple="false"
                    :options="categories"
                    :placeholder="$t('validation.attributes.category')"
                    :state="state('category')"
                    :disable-branch-nodes="true"
                    :show-count="true"
                    @input="checkClearance"
                  ></treeselect>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.unit')"
                  label-for="unit"
                  :label-cols="3"
                  :invalid-feedback="feedback('unit')"
                >
                  <b-form-input
                    id="unit"
                    name="unit"
                    :placeholder="$t('validation.attributes.unit')"
                    v-model="model.unit"
                    :state="state('unit')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.usage')"
                  label-for="usage"
                  :label-cols="3"
                  :invalid-feedback="feedback('usage')"
                >
                  <b-form-textarea
                    id="usage"
                    name="usage"
                    :rows="3"
                    :placeholder="$t('validation.attributes.usage')"
                    v-model="model.usage"
                    :state="state('usage')"
                  ></b-form-textarea>
                </b-form-group>
                <list-of-components
                  component-name="InventoryItemVariant"
                  :items="model.variants"
                  :label="$t('validation.attributes.variants')"
                  list-name="variants"
                  :sample="{
                    name: null,
                    options: []
                  }"
                ></list-of-components>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  :to="{ name: 'inventory_items' }"
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
                    (isNew && this.$app.user.can('create inventory items')) ||
                      this.$app.user.can('edit inventory items')
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
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import axios from 'axios'
import ListOfComponents from './components/ListOfComponents'

export default {
  name: 'InventoryItemForm',
  components: { ListOfComponents, Treeselect },
  mixins: [form],
  data() {
    return {
      modelName: 'inventory_item',
      resourceRoute: 'inventory_items',
      listPath: '/VM/inventory/inventory_items',
      model: {
        part_no: null,
        name: null,
        description: null,
        category: null,
        unit: null,
        usage: null,
        variants: []
      },
      categories: []
    }
  },
  async created() {
    let { data } = await axios.get(
      this.$app.route(`inventory_item_categories.get_as_tree`)
    )
    this.categories = data
  },
  methods: {
    checkClearance(value) {
      if (value === undefined) this.model.category = null
    }
  }
}
</script>
