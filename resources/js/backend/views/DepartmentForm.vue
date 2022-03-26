<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.departments.titles.create')
                  : $t('labels.backend.departments.titles.edit')
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
                  :label="$t('validation.attributes.hod')"
                  label-for="hod"
                  :label-cols="3"
                  :invalid-feedback="feedback('hod')"
                >
                  <multiselect
                    v-model="model.hod"
                    :options="hods"
                    id="hod"
                    name="hod"
                    track-by="id"
                    label="name"
                    :searchable="true"
                    :close-on-select="true"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.hod') + ' --'
                    "
                  ></multiselect>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  to="/Hierarchy/departments"
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
                    (isNew && this.$app.user.can('create departments')) ||
                      this.$app.user.can('edit departments')
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
import form from '../mixins/form'
import axios from 'axios'
import Multiselect from 'vue-multiselect'

export default {
  name: 'DepartmentForm',
  components: { Multiselect },
  mixins: [form],
  data() {
    return {
      modelName: 'department',
      resourceRoute: 'departments',
      listPath: '/Hierarchy/departments',
      hods: [],
      model: {
        name: null,
        hod: null
      }
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`get_list_of_users`))
    this.hods = data
  }
}
</script>
