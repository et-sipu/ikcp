<template>
  <div>
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="12">
          <b-card>
            <h3 class="card-title" slot="header">
              {{
                isNew
                  ? $t('labels.backend.roles.titles.create')
                  : $t('labels.backend.roles.titles.edit')
              }}
            </h3>

            <b-form-group
              :label="$t('validation.attributes.name')"
              label-for="name"
              :label-cols="2"
              :invalid-feedback="feedback('name')"
            >
              <b-row>
                <b-col md="6">
                  <b-form-input
                    id="name"
                    name="name"
                    required
                    :placeholder="$t('validation.attributes.name')"
                    v-model="model.name"
                    :state="state('name')"
                  ></b-form-input>
                </b-col>
              </b-row>
            </b-form-group>

            <b-form-group
              :label="$t('validation.attributes.display_name')"
              label-for="display_name"
              horizontal
              :label-cols="2"
              :invalid-feedback="feedback('display_name')"
            >
              <b-row>
                <b-col md="6">
                  <b-form-input
                    id="display_name"
                    name="display_name"
                    required
                    :placeholder="$t('validation.attributes.display_name')"
                    v-model="model.display_name"
                    :state="state('display_name')"
                  ></b-form-input>
                </b-col>
              </b-row>
            </b-form-group>

            <b-form-group
              :label="$t('validation.attributes.description')"
              label-for="description"
              :label-cols="2"
              :invalid-feedback="feedback('description')"
            >
              <b-form-input
                id="description"
                name="description"
                :placeholder="$t('validation.attributes.description')"
                v-model="model.description"
                :state="state('description')"
              ></b-form-input>
            </b-form-group>

            <!--<b-form-group-->
            <!--:label="$t('validation.attributes.order')"-->
            <!--label-for="order"-->
            <!--horizontal-->
            <!--:label-cols="2"-->
            <!--&gt;-->
            <!--<b-row>-->
            <!--<b-col md="3">-->
            <!--<b-form-input-->
            <!--id="order"-->
            <!--name="order"-->
            <!--type="number"-->
            <!--required-->
            <!--v-model="model.order"-->
            <!--&gt;</b-form-input>-->
            <!--</b-col>-->
            <!--</b-row>-->
            <!--</b-form-group>-->

            <div class="form-group">
              <b-row>
                <label class="col-sm-2 col-form-label">
                  {{ $t('validation.attributes.permissions') }}
                </label>
                <b-card-group columns>
                  <b-card
                    v-for="category in permissions"
                    :key="category.title"
                    :title="$t(category.title)"
                  >
                    <b-form-checkbox-group
                      stacked
                      v-model="model.permissions"
                      name="permissions[]"
                    >
                      <b-form-checkbox
                        v-for="permission in category.permissions"
                        :key="permission.name"
                        v-b-tooltip.left
                        :title="getPermissionTitle(permission)"
                        :value="permission.name"
                      >
                        {{ $t(permission.display_name) }}
                      </b-form-checkbox>
                    </b-form-checkbox-group>
                  </b-card>
                </b-card-group>
              </b-row>
            </div>

            <b-row slot="footer">
              <b-col md>
                <b-button to="/Access/roles" exact variant="danger" size="sm">
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
                  v-if="isNew || this.$app.user.can('edit roles')"
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
import form from '../mixins/form'

import _ from 'lodash'
// eslint-disable-next-line no-unused-vars
import { groupBy, forEach } from 'lodash/collection'

export default {
  name: 'RoleForm',
  mixins: [form],
  data() {
    return {
      permissions: [],
      modelName: 'role',
      resourceRoute: 'roles',
      listPath: '/Access/roles',
      model: {
        name: null,
        display_name: null,
        description: null,
        order: 0,
        permissions: []
      }
    }
  },
  async created() {
    this.fetchData()

    let { data } = await axios.get(this.$app.route(`roles.get_permissions`))

    let categories = _.groupBy(
      _.forEach(data, (value, key) => {
        value['name'] = key
      }),
      'category'
    )

    this.permissions = Object.keys(categories).map(key => {
      return {
        title: key,
        permissions: categories[key]
      }
    })
  },
  methods: {
    onModelChanged() {
      if (this.model.permissions) {
        this.model.permissions = this.model.permissions.map(item => {
          return item.name
        })
      }
    },
    getPermissionTitle(permission) {
      let description = ''

      permission.roles.forEach(function(role) {
        description += role.role_name + '\n'
      })
      return this.$t(permission.description) + ':\n' + description
    }
  }
}
</script>
