<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.groups.titles.create')
                  : $t('labels.backend.groups.titles.edit')
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
                  :label="$t('validation.attributes.members')"
                  label-for="members"
                  :label-cols="3"
                  :plaintext="false"
                  :invalid-feedback="feedback('destination.cc')"
                  :state="state('destination.cc')"
                >
                  <vue-multiselect
                    v-model="model.members"
                    id="members"
                    label="name"
                    track-by="id"
                    :options="users"
                    :multiple="true"
                    :close-on-select="false"
                    :hide-selected="true"
                  ></vue-multiselect>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button :to="{ name: 'groups' }" variant="danger" size="sm">
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
                    (isNew && this.$app.user.can('create groups')) ||
                      this.$app.user.can('edit groups')
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
import VueMultiselect from 'vue-multiselect/src/Multiselect'

export default {
  name: 'GroupForm',
  components: { VueMultiselect },
  mixins: [form],
  data() {
    return {
      modelName: 'group',
      resourceRoute: 'groups',
      listPath: '/Access/groups',
      model: {
        members: [],
        name: null
      },
      users: []
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`get_list_of_users`))
    this.users = data
  }
}
</script>
