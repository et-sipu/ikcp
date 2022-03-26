<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.inventories.titles.create')
                  : $t('labels.backend.inventories.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
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
                  :label="$t('validation.attributes.vessel')"
                  label-for="vessel"
                  :label-cols="3"
                  :invalid-feedback="feedback('vessel')"
                  :state="state('vessel')"
                >
                  <multiselect
                    id="vessel"
                    name="vessel"
                    track-by="id"
                    label="name"
                    :searchable="true"
                    v-model="model.vessel"
                    :options="vessels"
                    :close-on-select="true"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.vessel') + ' --'
                    "
                  ></multiselect>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  :to="{ name: 'inventories' }"
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
                    (isNew && this.$app.user.can('create inventories')) ||
                      this.$app.user.can('edit inventories')
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
  name: 'InventoryForm',
  components: { Multiselect },
  mixins: [form],
  data() {
    return {
      modelName: 'inventory',
      resourceRoute: 'inventories',
      listPath: '/VM/inventory/inventories',
      vessels: [],
      model: {
        name: null,
        vessel: null
      }
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`vessels.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    })
    this.vessels = data.data

    this.vessels = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
  }
}
</script>
