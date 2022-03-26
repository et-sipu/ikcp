<template>
  <div role="tablist">
    <div class="animated fadeIn">
      <b-card>
        <template slot="header">
          <h3 class="card-title">
            {{ $t('labels.backend.doc_templates.titles.index') }}
          </h3>
          <div
            class="card-options"
            v-if="$app.user.can('create doc templates')"
          >
            <b-btn
              variant="primary"
              v-b-tooltip.hover
              :title="$t('buttons.doc_templates.create')"
              size="sm"
              @click="
                id = null
                showCreateModal = !showCreateModal
              "
            >
              <i class="fe fe-plus-circle"></i>
            </b-btn>
            <model-modal
              componenet-name="DocTemplateForm"
              :model-id="id ? id : 0"
              :show-modal.sync="showCreateModal"
              :reload-table="false"
              size="md"
              @form-submitted-successfully="onDocTemplateCreated"
            ></model-modal>
          </div>
        </template>

        <b-card no-body class="mb-1">
          <b-card-header header-tag="header" class="p-1" role="tab">
            <b-button block href="#" v-b-toggle.accordion-1 variant="primary">{{
              $t('labels.backend.doc_templates.template_types.SMS')
            }}</b-button>
          </b-card-header>
          <b-collapse id="accordion-1" accordion="my-accordion" role="tabpanel">
            <b-card-body>
              <DocTemplatePerType
                ref="sms_templates_list"
                :extra-search-criteria="{ template_type: 'SMS' }"
              ></DocTemplatePerType>
            </b-card-body>
          </b-collapse>
        </b-card>

        <b-card no-body class="mb-1">
          <b-card-header header-tag="header" class="p-1" role="tab">
            <b-button block href="#" v-b-toggle.accordion-2 variant="primary">{{
              $t('labels.backend.doc_templates.template_types.ISM')
            }}</b-button>
          </b-card-header>
          <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
            <b-card-body>
              <b-card-text>
                <DocTemplatePerType
                  ref="ism_templates_list"
                  :extra-search-criteria="{ template_type: 'ISM' }"
                ></DocTemplatePerType>
              </b-card-text>
            </b-card-body>
          </b-collapse>
        </b-card>

        <b-card no-body class="mb-1">
          <b-card-header header-tag="header" class="p-1" role="tab">
            <b-button block href="#" v-b-toggle.accordion-3 variant="primary">{{
              $t('labels.backend.doc_templates.template_types.Training')
            }}</b-button>
          </b-card-header>
          <b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
            <b-card-body>
              <b-card-text>
                <DocTemplatePerType
                  ref="training_templates_list"
                  :extra-search-criteria="{ template_type: 'Training' }"
                ></DocTemplatePerType>
              </b-card-text>
            </b-card-body>
          </b-collapse>
        </b-card>

        <b-card no-body class="mb-1">
          <b-card-header header-tag="header" class="p-1" role="tab">
            <b-button block href="#" v-b-toggle.accordion-4 variant="primary">{{
              $t('labels.backend.doc_templates.template_types.Reports')
            }}</b-button>
          </b-card-header>
          <b-collapse id="accordion-4" accordion="my-accordion" role="tabpanel">
            <b-card-body>
              <b-card-text>
                <DocTemplatePerType
                  ref="reports_templates_list"
                  :extra-search-criteria="{ template_type: 'Reports' }"
                ></DocTemplatePerType>
              </b-card-text>
            </b-card-body>
          </b-collapse>
        </b-card>

        <b-card no-body class="mb-1" v-if="$app.user.can('view doc audits')">
          <b-card-header header-tag="header" class="p-1" role="tab">
            <b-button block href="#" v-b-toggle.accordion-5 variant="primary">{{
              $t('labels.backend.doc_audits.titles.title')
            }}</b-button>
          </b-card-header>
          <b-collapse id="accordion-5" accordion="my-accordion" role="tabpanel">
            <b-card-body>
              <b-card-text>
                <DocAuditList></DocAuditList>
              </b-card-text>
            </b-card-body>
          </b-collapse>
        </b-card>

        <b-card
          no-body
          class="mb-1"
          v-if="$app.user.can('view company registrations')"
        >
          <b-card-header header-tag="header" class="p-1" role="tab">
            <b-button block href="#" v-b-toggle.accordion-6 variant="primary">{{
              $t('labels.backend.doc_templates.template_types.Registrations')
            }}</b-button>
          </b-card-header>
          <b-collapse id="accordion-6" accordion="my-accordion" role="tabpanel">
            <b-card-body>
              <b-card-text>
                <CompanyRegistrationsList></CompanyRegistrationsList>
              </b-card-text>
            </b-card-body>
          </b-collapse>
        </b-card>
      </b-card>
    </div>
  </div>
</template>

<script>
import DocTemplatePerType from './DocTemplatePerType'
import ModelModal from './components/ModelModal'
import DocAuditList from './DocAuditList'
import CompanyRegistrationsList from './CompanyRegistrationsList'

export default {
  name: 'DocTemplateList',
  components: {
    CompanyRegistrationsList,
    DocAuditList,
    ModelModal,
    DocTemplatePerType
  },
  data() {
    return {
      isBusy: false,
      showCreateModal: false,
      id: null
    }
  },
  methods: {
    onDocTemplateCreated() {
      this.$refs.sms_templates_list.onContextChanged()
      this.$refs.ism_templates_list.onContextChanged()
      this.$refs.training_templates_list.onContextChanged()
      this.$refs.reports_templates_list.onContextChanged()
    }
  }
}
</script>
