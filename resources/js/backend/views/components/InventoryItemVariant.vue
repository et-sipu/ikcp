<template>
  <b-row class="item_variant pr-5">
    <b-button
      class="close"
      size="sm"
      variant="danger"
      v-b-tooltip.hover
      :title="$t('buttons.delete')"
      @click="dropItem()"
      v-if="!disabled && !deletable"
    >
      <i class="fe fe-x"></i>
    </b-button>

    <b-col xl="4">
      <b-form-group
        :label="$t('validation.attributes.variant_name')"
        label-for="name"
        :invalid-feedback="feedback(name_field)"
      >
        <b-form-input
          id="name"
          name="name"
          :placeholder="$t('validation.attributes.variant_name')"
          v-model="value.name"
          :state="state(name_field)"
          :plaintext="disabled"
        ></b-form-input>
      </b-form-group>
    </b-col>
    <b-col xl="8">
      <b-form-group
        :label="$t('validation.attributes.variant_options')"
        label-for="variant_options"
        :invalid-feedback="feedback(options_field)"
        :state="state(options_field)"
      >
        <tags-input
          element-id="variant_options"
          v-model="value.options"
          :placeholder="$t('labels.add_option')"
        ></tags-input>
      </b-form-group>
    </b-col>
  </b-row>
</template>
<script>
import TagsInput from '@voerro/vue-tagsinput'
import '@voerro/vue-tagsinput/dist/style.css'

export default {
  name: 'InventoryItemVariant',
  components: { TagsInput },
  props: {
    value: {
      default: () => ({
        name: null,
        options: []
      }),
      type: Object
    },
    index: {
      default: 0,
      type: Number
    },
    disabled: {
      type: Boolean,
      default: false,
      required: false
    },
    deletable: {
      type: Boolean,
      default: false,
      required: false
    }
  },
  data() {
    return {}
  },
  computed: {
    name_field: function() {
      return 'variants.' + this.index + '.name'
    },
    options_field: function() {
      return 'variants.' + this.index + '.options'
    }
  },
  methods: {
    feedback(name) {
      if (this.state(name)) {
        return this.$parent.$parent.validation.errors[name][0]
      }
    },
    state(name) {
      return this.$parent.$parent.validation.errors !== undefined &&
        this.$parent.$parent.validation.errors.hasOwnProperty(name)
        ? 'invalid'
        : null
    },
    dropItem() {
      this.$emit('deleted', { index: this.index })
    }
  }
}
</script>
<style scoped>
.item_variant {
  background-color: #f0fffd;
  padding-top: 8px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 3px;
  border: 1px solid #e9ecef;
  position: relative;
}
.close {
  position: absolute;
  right: 0px;
  top: 0px;
}
</style>
