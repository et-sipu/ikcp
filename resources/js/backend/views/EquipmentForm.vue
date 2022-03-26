<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.equipment.titles.create')
                  : $t('labels.backend.equipment.titles.edit')
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
                  :label="$t('validation.attributes.brand')"
                  label-for="brand"
                  :label-cols="3"
                  :invalid-feedback="feedback('brand')"
                >
                  <multiselect
                    v-model="model.brand"
                    :options="brands"
                    tag-placeholder="Add this as new brand"
                    name="brands"
                    label="name"
                    track-by="name"
                    :multiple="false"
                    :taggable="true"
                    :close-on-select="true"
                    :show-labels="true"
                    :hide-selected="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.brand') + ' --'
                    "
                    @tag="addTag"
                  ></multiselect>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.model')"
                  label-for="model"
                  :label-cols="3"
                  :invalid-feedback="feedback('model')"
                >
                  <b-form-input
                    id="model"
                    name="model"
                    :placeholder="$t('validation.attributes.model')"
                    v-model="model.model"
                    :state="state('model')"
                  ></b-form-input>
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
                  :label="$t('validation.attributes.serial_num')"
                  label-for="serial_num"
                  :label-cols="3"
                  :invalid-feedback="feedback('serial_num')"
                >
                  <b-form-input
                    id="serial_num"
                    name="serial_num"
                    :placeholder="$t('validation.attributes.serial_num')"
                    v-model="model.serial_num"
                    :state="state('serial_num')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.status')"
                  label-for="status"
                  :label-cols="3"
                  :invalid-feedback="feedback('status')"
                >
                  <b-form-input
                    id="status"
                    name="status"
                    :placeholder="$t('validation.attributes.status')"
                    v-model="model.status"
                    :state="state('status')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  v-if="this.$app.user.can('view equipment')"
                  :label="$t('validation.attributes.vessel')"
                  label-for="vessel_id"
                  :label-cols="3"
                  :invalid-feedback="feedback('vessel_id')"
                >
                  <multiselect
                    v-model="model.vessel"
                    :options="vessels"
                    id="vessels"
                    name="vessels"
                    label="name"
                    track-by="id"
                    :close-on-select="true"
                    :show-labels="true"
                    :hide-selected="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.vessel') + ' --'
                    "
                    :disabled="model.can_create_own"
                  ></multiselect>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button :to="listPath" variant="danger" size="sm">
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
                    (isNew && this.$app.user.can('create equipment')) ||
                      this.$app.user.can('edit equipment')
                  "
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                </b-button>
              </b-col>
            </b-row>
          </b-card>
        </b-col>
      </b-row>
      <comments
        ref="comments"
        model-name="Equipment"
        :model-id="id"
        v-if="!isNew"
      ></comments>
    </form>
  </div>
</template>

<script>
import form from '../mixins/form'
import Comments from './components/Comments'
import axios from 'axios'
import Multiselect from 'vue-multiselect'

export default {
  name: 'EquipmentForm',
  components: { Comments, Multiselect },
  mixins: [form],
  data() {
    return {
      modelName: 'equipment',
      resourceRoute: 'equipment',
      brands: [],
      vessels: [],
      model: {
        name: null,
        brand: null,
        model: null,
        location: null,
        serial_num: null,
        status: null,
        vessel: null,
        can_create_own: null
      }
    }
  },
  computed: {
    listPath() {
      let route = this.$router.resolve({
        name: this.modelName
      })

      return route.href
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`equipments.brands`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    })
    this.brands = data

    this.brands = data.map(item => {
      return { name: item }
    })
    ;({ data } = await axios.get(this.$app.route(`vessels.limited_search`)))
    this.vessels = data.data
  },
  methods: {
    addTag(newTag) {
      this.brands.push({ name: newTag })
      this.model.brand = { name: newTag }
    }
    // addvessel(newvessel) {
    //   this.model.vessel_id = newvessel.id
    // }
  }
}
</script>
