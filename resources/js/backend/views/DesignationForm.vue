<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.designations.titles.create')
                  : $t('labels.backend.designations.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.title')"
                  label-for="title"
                  :label-cols="3"
                  :invalid-feedback="feedback('title')"
                >
                  <b-form-input
                    id="title"
                    name="title"
                    :placeholder="$t('validation.attributes.title')"
                    v-model="model.title"
                    :state="state('title')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.department')"
                  label-for="department"
                  :label-cols="3"
                  :invalid-feedback="feedback('department')"
                  :state="state('department')"
                >
                  <multiselect
                    v-model="model.department"
                    :options="departments"
                    id="department"
                    name="department"
                    track-by="id"
                    label="name"
                    :searchable="true"
                    :close-on-select="true"
                    :show-labels="false"
                    :placeholder="
                      '-- ' + $t('validation.attributes.department') + ' --'
                    "
                  ></multiselect>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  :to="{ name: 'designations' }"
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
                    (isNew && this.$app.user.can('create designations')) ||
                      this.$app.user.can('edit designations')
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
  name: 'DesignationForm',
  components: { Multiselect },
  mixins: [form],
  data() {
    return {
      modelName: 'designation',
      resourceRoute: 'designations',
      listPath: '/Hierarchy/designations',
      model: {
        title: null,
        department: null
      },
      departments: []
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`departments.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    })
    this.departments = data.data

    this.departments = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
  }
}
</script>
