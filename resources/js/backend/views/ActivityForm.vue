<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="6">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.activities.titles.create')
                  : $t('labels.backend.activities.titles.edit')
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
                    id="name"
                    name="name"
                    :placeholder="$t('validation.attributes.title')"
                    v-model="model.title"
                    :state="state('title')"
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
                  :label="$t('validation.attributes.start_date')"
                  label-for="start_date"
                  :label-cols="3"
                  :invalid-feedback="feedback('start_date')"
                  :state="state('start_date')"
                >
                  <b-input-group>
                    <p-datetimepicker
                      :config="config"
                      id="start_date"
                      name="start_date"
                      v-model="model.start_date"
                    ></p-datetimepicker>
                    <b-input-group-append>
                      <b-input-group-text data-toggle>
                        <i class="fe fe-calendar"></i>
                      </b-input-group-text>
                      <b-input-group-text data-clear>
                        <i class="fe fe-x-circle"></i>
                      </b-input-group-text>
                    </b-input-group-append>
                  </b-input-group>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.due_date')"
                  label-for="due_date"
                  :label-cols="3"
                  :invalid-feedback="feedback('due_date')"
                  :state="state('due_date')"
                >
                  <b-input-group>
                    <p-datetimepicker
                      :config="config"
                      id="due_date"
                      name="due_date"
                      v-model="model.due_date"
                    ></p-datetimepicker>
                    <b-input-group-append>
                      <b-input-group-text data-toggle>
                        <i class="fe fe-calendar"></i>
                      </b-input-group-text>
                      <b-input-group-text data-clear>
                        <i class="fe fe-x-circle"></i>
                      </b-input-group-text>
                    </b-input-group-append>
                  </b-input-group>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.cost')"
                  label-for="cost"
                  :label-cols="3"
                  :invalid-feedback="feedback('cost')"
                >
                  <b-form-input
                    id="name"
                    name="name"
                    :placeholder="$t('validation.attributes.cost')"
                    v-model="model.cost"
                    :state="state('cost')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.department_id')"
                  label-for="department_id"
                  :label-cols="3"
                  :invalid-feedback="feedback('department_id')"
                >
                  <b-form-input
                    id="name"
                    name="name"
                    :placeholder="$t('validation.attributes.department_id')"
                    v-model="model.department_id"
                    :state="state('department_id')"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.status')"
                  label-for="status"
                  :label-cols="3"
                  :invalid-feedback="feedback('status')"
                >
                  <b-form-input
                    id="name"
                    name="name"
                    :placeholder="$t('validation.attributes.status')"
                    v-model="model.status"
                    :state="state('status')"
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button to="/activities" variant="danger" size="sm">
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
                    (isNew && this.$app.user.can('create activities')) ||
                      this.$app.user.can('edit activities')
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

export default {
  name: 'ActivityForm',
  components: {},
  mixins: [form],
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true
      },
      modelName: 'activity',
      resourceRoute: 'activities',
      listPath: '/activities',
      model: {
        title: null,
        description: null,
        start_date: null,
        due_date: null,
        cost: null,
        department_id: null,
        status: null
      }
    }
  }
}
</script>
