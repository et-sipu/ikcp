<template>
  <fieldset class="form-fieldset">
    <h5>
      {{ label }}
    </h5>

    <b-form-group
      :invalid-feedback="feedback(listName)"
      :state="state(listName)"
    >
    </b-form-group>

    <template v-for="(item, index) in items">
      <component
        v-model="items[index]"
        :is="componentName"
        :index="index"
        :ref="listName + '-' + index"
        :key="index"
        v-bind="componentProps"
        @deleted="drop(index)"
        v-if="item.id === null || item.id !== 0"
      ></component>
    </template>

    <div class="float-right">
      <b-button
        size="sm"
        variant="primary"
        v-b-tooltip.hover
        :title="$t('buttons.add_more')"
        @click="add()"
      >
        <i class="fe fe-plus-circle"></i>
      </b-button>
    </div></fieldset
></template>

<script>
import components from './widgets'

export default {
  name: 'ListOfComponents',
  components,
  props: {
    componentName: {
      type: String,
      required: true
    },
    componentProps: {
      type: Object,
      required: false,
      default: () => {}
    },
    items: {
      default: () => [],
      type: Array
    },
    listName: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    sample: {
      type: Object,
      required: true,
      default: () => {}
    }
  },
  methods: {
    add() {
      this.items.push(this.sample)
    },
    drop(index) {
      // console.log(index)
      if (!this.items[index].id) {
        this.items.splice(index, 1)
      } else this.items[index].id = 0
    },
    feedback(name) {
      if (this.state(name)) {
        return this.$parent.validation.errors[name][0]
      }
    },
    state(name) {
      return this.$parent.validation.errors !== undefined &&
        this.$parent.validation.errors.hasOwnProperty(name)
        ? 'invalid'
        : null
    }
  }
}
</script>

<style scoped></style>
