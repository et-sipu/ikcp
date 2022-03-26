<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.inventory_item_categories.titles.create')
                  : $t('labels.backend.inventory_item_categories.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.name')"
                  label-for="name"
                  :label-cols="3"
                  :invalid-feedback="feedback('name')"
                  :state="state('name')"
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
                  :label="$t('validation.attributes.parent')"
                  label-for="parent"
                  :label-cols="3"
                  :state="state('parent')"
                  :invalid-feedback="feedback('parent')"
                >
                  <treeselect
                    v-model="model.parent"
                    :multiple="false"
                    :options="options"
                    :placeholder="$t('validation.attributes.parent')"
                    :state="state('parent')"
                  ></treeselect>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  :to="{ name: 'inventory_item_categories' }"
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
                      this.$app.user.can('create inventory item categories')) ||
                      this.$app.user.can('edit inventory item categories')
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

export default {
  name: 'InventoryItemCategoryForm',
  components: { Treeselect },
  mixins: [form],
  data() {
    return {
      modelName: 'inventory_item_category',
      resourceRoute: 'inventory_item_categories',
      listPath: '/VM/inventory/inventory_item_categories',
      model: {
        name: null,
        parent: null
      },
      options: []
    }
  },
  async created() {
    let { data } = await axios.get(
      this.$app.route(`inventory_item_categories.get_as_tree`)
    )
    this.options = data
  }
}
</script>
