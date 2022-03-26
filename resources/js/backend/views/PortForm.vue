<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.ports.titles.create')
                  : $t('labels.backend.ports.titles.edit')
              }}
            </h4>

            <b-form-group
              :label="$t('validation.attributes.country')"
              label-for="country"
              :label-cols="3"
              :invalid-feedback="feedback('country')"
              :state="state('country')"
            >
              <multiselect
                v-model="model.country"
                :options="all_countries"
                id="country"
                name="country"
                :searchable="true"
                :close-on-select="true"
                :show-labels="false"
                :placeholder="
                  '-- ' + $t('validation.attributes.country') + ' --'
                "
              ></multiselect>
            </b-form-group>

            <b-form-group
              :label="$t('validation.attributes.port_name')"
              label-for="name"
              :label-cols="3"
              :invalid-feedback="feedback('name')"
            >
              <b-form-input
                id="name"
                name="name"
                required
                :placeholder="$t('validation.attributes.port_name')"
                v-model="model.name"
                :state="state('name')"
              ></b-form-input>
            </b-form-group>

            <b-row slot="footer">
              <b-col md>
                <b-button to="/Crew/Settings/ports" variant="danger" size="sm">
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
                    (isNew && this.$app.user.can('create ports')) ||
                      this.$app.user.can('edit ports')
                  "
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.edit') }}
                </b-button>
              </b-col>
            </b-row>
            <atom-spinner
              :animation-duration="1000"
              :size="400"
              :color="'#DBB11E'"
              :loading="loading"
              :full="true"
            ></atom-spinner>
          </b-card>
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import form from '../mixins/form'

export default {
  name: 'PortForm',
  components: { Multiselect },
  mixins: [form],
  data() {
    return {
      all_countries: [],
      modelName: 'port',
      resourceRoute: 'ports',
      listPath: '/ports',
      model: {
        name: null,
        country: null
      }
    }
  },
  async created() {
    // this.fetchData()
    var { data } = await axios.get(
      this.$app.route(`get_list_of_constants`, { constant: 'all_countries' })
    )
    this.all_countries = Object.values(data)
  }
}
</script>
