<template>
  <div class="animated fadeIn">
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.users.titles.index') }}
        </h3>
        <div class="card-options" v-if="this.$app.user.can('create users')">
          <b-btn
            v-b-toggle.filter
            variant="primary"
            class="mr-1"
            size="sm"
            v-b-tooltip.hover
            :title="$t('buttons.show_hide_filters')"
          >
            <font-awesome-icon icon="filter"></font-awesome-icon>
          </b-btn>
          <b-button
            to="/Access/users/create"
            size="sm"
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.users.create')"
          >
            <i class="fe fe-plus-circle"></i>
          </b-button>
        </div>
      </template>
      <b-datatable
        ref="datasource"
        @context-changed="onContextChanged"
        search-route="users.search"
        delete-route="users.destroy"
        action-route="users.batch_action"
        :actions="actions"
        :extra-search-criteria="extraSearchCriteria"
        @bulk-action-success="onBulkActionSuccess"
      >
        <template slot="extra_filters">
          <b-col>
            <b-collapse id="filter" class="mt-2">
              <b-row>
                <b-col md="6" lg="6">
                  <b-form-group
                    label="Permissions"
                    label-for="Permissions"
                    :label-cols="2"
                  >
                    <multiselect
                      v-model="extraSearchCriteria.permission_selected"
                      id="ajax"
                      label="name"
                      track-by="id"
                      placeholder="Type to search"
                      open-direction="bottom"
                      :options="permissions"
                      :multiple="true"
                      :searchable="true"
                      :loading="isLoading"
                      :internal-search="false"
                      :clear-on-select="false"
                      :close-on-select="false"
                      :options-limit="300"
                      :limit="3"
                      :max-height="600"
                      :show-no-results="true"
                      :hide-selected="true"
                      @search-change="asyncFind"
                      @input="onContextChangedWithPage"
                    >
                    </multiselect>
                  </b-form-group>
                </b-col>
                <b-col md="6" lg="6">
                  <b-form-group label="Roles" label-for="Roles" :label-cols="2">
                    <multiselect
                      v-model="extraSearchCriteria.role_selected"
                      :options="roles"
                      id="status"
                      name="status"
                      @input="onContextChangedWithPage"
                      label="name"
                      track-by="id"
                      :multiple="true"
                      :close-on-select="false"
                      :show-labels="false"
                      :placeholder="
                        '-- ' + $t('validation.attributes.roles') + ' --'
                      "
                    >
                    </multiselect>
                  </b-form-group>
                </b-col>
              </b-row>
            </b-collapse>
          </b-col>
        </template>
        <b-table
          ref="datatable"
          striped
          bordered
          show-empty
          responsive
          no-local-sorting
          :empty-text="$t('labels.datatables.no_results')"
          :empty-filtered-text="$t('labels.datatables.no_matched_results')"
          :fields="fields"
          :items="dataLoadProvider"
          :busy.sync="isBusy"
        >
          <template slot="HEAD_checkbox" slot-scope="data"></template>
          <template slot="checkbox" slot-scope="row">
            <b-form-checkbox
              :value="row.item.id"
              v-model="selected"
            ></b-form-checkbox>
          </template>
          <template slot="name" slot-scope="row">
            <router-link
              v-if="row.item.can_edit"
              :to="`/Access/users/${row.item.id}/edit`"
              v-text="row.value"
            ></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="confirmed" slot-scope="row">
            <b-badge :variant="row.value ? 'success' : 'danger'">
              {{ row.value ? $t('labels.yes') : $t('labels.no') }}
            </b-badge>
          </template>
          <template slot="active" slot-scope="row">
            <c-switch
              v-if="row.item.can_edit"
              type="text"
              variant="primary"
              on="On"
              off="Off"
              :checked="row.value"
              @change="onActiveToggle(row.item.id)"
            ></c-switch>
          </template>
          <template slot="department_name" slot-scope="row">
            <template v-if="row.item.department">
              <router-link
                v-if="$app.user.can(`edit departments`)"
                :to="`/Hierarchy/departments/${row.item.department.id}/edit`"
                v-text="row.item.department.name"
              ></router-link>
              <span v-else v-text="row.item.department.name"></span>
            </template>
          </template>
          <template slot="roles" slot-scope="row">
            {{ formatRoles(row.value) }}
          </template>
          <template slot="actions" slot-scope="row">
            <b-button
              v-if="row.item.can_edit"
              size="sm"
              variant="primary"
              :to="`/Access/users/${row.item.id}/edit`"
              v-b-tooltip.hover
              :title="$t('buttons.edit')"
              class="mr-1"
            >
              <i class="fe fe-edit"></i>
            </b-button>
            <b-button
              v-if="row.item.can_impersonate"
              size="sm"
              variant="warning"
              :href="$app.route('users.impersonate', { user: row.item.id })"
              v-b-tooltip.hover
              :title="$t('buttons.login-as', { name: row.item.name })"
              class="mr-1"
            >
              <i class="fe fe-lock"></i>
            </b-button>
            <b-button
              v-if="row.item.can_delete"
              size="sm"
              variant="danger"
              v-b-tooltip.hover
              :title="$t('buttons.delete')"
              @click.stop="onDelete(row.item.id)"
            >
              <i class="fe fe-trash"></i>
            </b-button>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import list from '../mixins/list'
import Multiselect from 'vue-multiselect'
import _ from 'lodash'

export default {
  name: 'UserList',
  components: { Multiselect },
  mixins: [list],
  data() {
    return {
      value: [{ name: 'Javascript', code: 'js' }],
      options: [
        { name: 'vue.js', code: 'vu' },
        { name: 'Jacascript', code: 'js' },
        { name: 'Open Source', code: 'os' }
      ],
      extraSearchCriteria: {
        permission_selected: [],
        role_selected: []
      },
      isLoading: false,
      selected: [],
      permissions: [],
      roles: [],
      isBusy: false,
      item_type: 'user',
      fields: [
        { key: 'checkbox' },
        {
          key: 'id',
          label: this.$t('validation.attributes.id'),
          sortable: true
        },
        {
          key: 'name',
          label: this.$t('validation.attributes.name'),
          sortable: true
        },
        {
          key: 'email',
          label: this.$t('validation.attributes.email'),
          sortable: true
        },
        {
          key: 'confirmed',
          label: this.$t('validation.attributes.confirmed'),
          class: 'text-center'
        },
        { key: 'roles', label: this.$t('validation.attributes.roles') },
        {
          key: 'last_access_at',
          label: this.$t('labels.last_access_at'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'department_name',
          label: this.$t('validation.attributes.department'),
          sortable: true
        },
        {
          key: 'active',
          label: this.$t('validation.attributes.active'),
          class: 'text-center'
        },
        {
          key: 'created_at',
          label: this.$t('labels.created_at'),
          class: 'text-center',
          sortable: true
        },
        {
          key: 'updated_at',
          label: this.$t('labels.updated_at'),
          class: 'text-center',
          sortable: true
        },
        { key: 'actions', label: this.$t('labels.actions'), class: 'nowrap' }
      ],
      actions: {
        destroy: this.$t('labels.backend.users.actions.destroy'),
        enable: this.$t('labels.backend.users.actions.enable'),
        disable: this.$t('labels.backend.users.actions.disable')
      }
    }
  },
  watch: {
    selected(value) {
      this.$refs.datasource.selected = value
    }
  },
  async created() {
    let { data } = await axios.get(this.$app.route(`roles.search`), {
      params: {
        perPage: 100
      }
    })
    this.roles = data.data
  },
  methods: {
    async asyncFind(query) {
      this.isLoading = true
      let { data } = await axios.get(this.$app.route(`users.get_permission`), {
        params: {
          perPage: 100,
          search: query
        }
      })
      if (query.length === 0) this.permissions = []
      else {
        this.permissions = data
        // this.permissions = data.map(item => {
        //   return { id: item, name: item.name }
        // })
      }
      this.isLoading = false
    },
    clearAll() {
      this.permission_selected = []
    },
    limitText(count) {
      return `and ${count} other permissions`
    },
    getIntersect(first, second) {
      return _.union(_.keys(first), _.keys(second))
    },
    onActiveToggle(id) {
      axios
        .post(this.$app.route('users.active', { user: id }))
        .then(response => {
          this.$app.noty[response.data.status](response.data.message)
        })
        .catch(error => {
          this.$app.error(error)
        })
    },
    onBulkActionSuccess() {
      this.selected = []
    },
    formatRoles(roles) {
      return roles.data
        .map(key => {
          return key.display_name
        })
        .join(', ')
    }
  }
}
</script>
