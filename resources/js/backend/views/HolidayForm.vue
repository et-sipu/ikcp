<template>
  <div class="animated fadeIn">
    <form @submit.prevent="onSubmit">
      <b-row class="justify-content-center">
        <b-col xl="12">
          <b-card>
            <h4 slot="header">
              {{
                isNew
                  ? $t('labels.backend.holidays.titles.create')
                  : $t('labels.backend.holidays.titles.edit')
              }}
            </h4>

            <b-row class="justify-content-center">
              <b-col xl="12">
                <b-form-group
                  :label="$t('validation.attributes.occasion')"
                  label-for="occasion"
                  :label-cols="3"
                  :invalid-feedback="feedback('occasion')"
                >
                  <b-form-input
                    id="occasion"
                    name="occasion"
                    :placeholder="$t('validation.attributes.occasion')"
                    v-model="model.occasion"
                    :state="state('occasion')"
                    :plaintext="readonly"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  :label="$t('validation.attributes.start_date')"
                  label-for="start_date"
                  :label-cols="3"
                  :invalid-feedback="feedback('start_date')"
                  :state="state('start_date')"
                >
                  <b-input-group v-if="!readonly">
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
                  <h4 v-else>
                    <b-badge variant="primary" class="mr-1">
                      {{ model.start_date ? model.start_date : ' -- ' }}
                    </b-badge>
                  </h4>
                </b-form-group>
                <b-form-group
                  :label="$t('validation.attributes.end_date')"
                  label-for="end_date"
                  :label-cols="3"
                  :invalid-feedback="feedback('end_date')"
                  :state="state('end_date')"
                >
                  <b-input-group v-if="!readonly">
                    <p-datetimepicker
                      :config="config"
                      id="end_date"
                      name="end_date"
                      v-model="model.end_date"
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
                  <h4 v-else>
                    <b-badge variant="primary" class="mr-1">
                      {{ model.end_date ? model.end_date : ' -- ' }}
                    </b-badge>
                  </h4>
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
                    :plaintext="readonly"
                  ></b-form-textarea>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row slot="footer">
              <b-col md>
                <b-button
                  variant="danger"
                  size="sm"
                  @click="$emit('form-submission-canceled')"
                >
                  {{ $t('buttons.cancel') }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button
                  variant="warning"
                  size="sm"
                  class="float-right"
                  @click="readonly = !readonly"
                  v-if="!isNew && $app.user.can('edit holidays')"
                >
                  {{ $t('buttons.edit') }}
                </b-button>
                <b-button
                  type="submit"
                  variant="success"
                  size="sm"
                  class="float-right"
                  :disabled="pending"
                  v-if="
                    (isNew && this.$app.user.can('create holidays')) ||
                      ($app.user.can('edit holidays') && !readonly)
                  "
                >
                  {{ isNew ? $t('buttons.create') : $t('buttons.save') }}
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
  name: 'HolidayForm',
  components: {},
  mixins: [form],
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true
      },
      modelName: 'holiday',
      resourceRoute: 'holidays',
      listPath: '/HR/holidays',
      model: {
        occasion: null,
        start_date: null,
        end_date: null,
        description: null
      },
      readonly: true
    }
  },
  methods: {
    afterSave() {
      return true
    }
  }
}
</script>
