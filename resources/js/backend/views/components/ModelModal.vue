<template>
  <div>
    <b-modal
      id="modal1"
      title="Bootstrap-Vue"
      :size="size"
      lazy
      :hide-header="true"
      :hide-footer="true"
      modal-class="create-model-modal"
      v-model="myShoModal"
      no-close-on-esc
    >
      <component
        :is="componenetName"
        :from-router="false"
        @form-submitted-successfully="onFormSubmitted()"
        @form-submission-canceled="onFormSubmissionCanceled()"
        v-bind="componenetProps"
        v-if="modelId === 0"
      ></component>
      <component
        :is="componenetName"
        :from-router="false"
        @form-submitted-successfully="onFormSubmitted()"
        @form-submission-canceled="onFormSubmissionCanceled()"
        v-bind="componenetProps"
        :id="modelId"
        v-else
      ></component>
    </b-modal>
  </div>
</template>

<script>
import components from '../views'

export default {
  name: 'ModelModal',
  components,
  props: {
    componenetName: {
      type: String,
      required: true
    },
    componenetProps: {
      type: Object,
      required: false,
      default: () => {}
    },
    modelId: {
      default: '',
      type: [String, Number],
      required: true
    },
    showModal: {
      default: false,
      type: Boolean
    },
    size: {
      default: '',
      type: String
    },
    reloadTable: {
      default: true,
      type: Boolean
    }
  },
  computed: {
    myShoModal: {
      get() {
        return this.showModal
      },
      set(val) {
        this.$emit('update:showModal', val)
      }
    }
  },
  methods: {
    onFormSubmitted() {
      if (this.reloadTable) this.$parent.onContextChangedWithPage()
      else this.$emit('form-submitted-successfully')

      this.$emit('update:showModal', false)
    },
    onFormSubmissionCanceled() {
      this.$emit('update:showModal', false)
    }
  }
}
</script>

<style>
.create-model-modal .modal-body {
  padding: 0 !important;
}
.create-model-modal .card {
  margin-bottom: 0 !important;
}
</style>
