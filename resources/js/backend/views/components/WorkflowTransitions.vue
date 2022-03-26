<template>
  <b-button-group>
    <template v-if="canPassUrgently">
      <template v-for="(transition, index) in availableTransitions">
        <b-button
          size="sm"
          :key="index"
          :variant="getTransitionInfo(transition).variant"
          v-b-tooltip.hover
          :title="$t(`buttons.${getTransitionInfo(transition).label}`)"
          @click.stop="changeStatus(id, transition, isForm)"
          v-if="getTransitionInfo(transition).urgent"
        >
          <font-awesome-icon
            :icon="getTransitionInfo(transition).icon"
          ></font-awesome-icon>
        </b-button>
      </template>
    </template>
    <template v-if="canPass">
      <template v-for="(transition, index) in availableTransitions">
        <b-button
          size="sm"
          :key="index"
          :variant="getTransitionInfo(transition).variant"
          v-b-tooltip.hover
          :title="$t(`buttons.${getTransitionInfo(transition).label}`)"
          @click.stop="changeStatus(id, transition, isForm)"
          v-if="!getTransitionInfo(transition).urgent"
        >
          <font-awesome-icon
            :icon="getTransitionInfo(transition).icon"
          ></font-awesome-icon>
        </b-button>
      </template>
    </template>
    <slot></slot>
  </b-button-group>
</template>

<script>
export default {
  name: 'WorkflowTransitions',
  props: {
    canPassUrgently: {
      type: Boolean,
      required: false,
      default: false
    },
    canPass: {
      type: Boolean,
      required: true
    },
    availableTransitions: {
      type: Array,
      required: true
    },
    changeStatus: {
      type: Function,
      required: true
    },
    getTransitionInfo: {
      type: Function,
      required: true
    },
    id: {
      type: [Number, String],
      required: true
    },
    isForm: {
      type: Boolean,
      default: false
    }
  }
}
</script>
