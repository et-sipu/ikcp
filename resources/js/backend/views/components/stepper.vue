<template>
  <div :class="[`v-step-warp-${direction}`, styleType]">
    <div class="v-step-progress-bg">
      <div class="v-step-progress-value" :style="progressStyle"></div>
    </div>
    <ul class="v-step-list progressbar">
      <li
        v-for="(label, index) in stepList"
        :key="index"
        class="v-step-item"
        :style="horizontalItemWidth"
        @click="selectItem(index)"
      >
        <label
          class="v-step-item-label"
          v-html="
            index + 1 === nowStep
              ? $t(`labels.backend.${workflow}.states.${label}`)
              : '<br>'
          "
        >
        </label>
        <div
          class="v-step-item-number"
          :class="label == 'rejected' ? 'rejected' : ''"
          :style="itemNumberStyle(index)"
          v-b-tooltip.hover
          :title="
            $t(`labels.backend.${workflow}.states.${label}`) +
              (typeof stepHistory[label] !== 'undefined'
                ? '@' + stepHistory[label]
                : '')
          "
        >
          <!--{{ showItemNumber(index) }}-->
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'VueStep',
  props: {
    nowStep: {
      type: Number,
      required: true
    },
    stepList: {
      type: Array,
      required: true
    },
    stepHistory: {
      type: Object,
      required: true
    },
    activeColor: {
      type: String,
      default: '#3aac5d'
    },
    styleType: {
      type: String,
      default: 'style1',
      validator: value => {
        return ['style1', 'style2'].indexOf(value) !== -1
      }
    },
    direction: {
      type: String,
      default: 'horizontal'
    },
    workflow: {
      type: String,
      required: true
    }
  },
  computed: {
    stepItemWidth() {
      return 100 / this.stepList.length
    },
    horizontalItemWidth() {
      return this.direction === 'vertical'
        ? ''
        : { width: this.stepItemWidth + '%' }
    },
    progressStyle() {
      let oStyle = {
        'background-color': this.activeColor
      }
      if (this.direction === 'vertical') {
        oStyle.height = this.stepItemWidth * this.nowStep + '%'
      } else {
        oStyle.width = this.stepItemWidth * this.nowStep + '%'
      }
      return oStyle
    }
  },
  methods: {
    itemNumberStyle(index) {
      let style = ''
      if (index < this.nowStep) {
        style = {
          'background-color': this.activeColor,
          color: '#fff'
        }
      }
      return style
    },
    showItemNumber(index) {
      return this.styleType !== 'style2' ? index + 1 : ''
    },
    selectItem(itemIndex) {
      this.$emit('selected', this.showItemNumber(itemIndex))
    }
  }
}
</script>

<style>
.v-step-warp-horizontal {
  position: relative;
  padding: 10px 0;
  z-index: 2;
}
.v-step-warp-horizontal .v-step-progress-bg {
  clip-path: polygon(5% 0%, 95% 0, 95% 100%, 5% 100%);
  position: absolute;
  width: 100%;
  height: 4px;
  bottom: 28px;
  background-color: #ddd;
}
.v-step-warp-horizontal .v-step-progress-value {
  position: inherit;
  top: 0;
  left: 0;
  height: inherit;
}
.v-step-list {
  position: relative;
  display: -webkit-flex;
  display: flex;
  cursor: pointer;
  user-select: none;
}
.v-step-list,
.v-step-item {
  margin: 0;
  padding: 0;
  list-style-type: none;
}
.v-step-warp-horizontal .v-step-list {
  justify-content: space-around;
  text-align: center;
}
.v-step-item-label {
  font-size: 12px;
  color: #666;
}
.v-step-item-number::before {
  font-size: 20px;
  content: counter(step);
  counter-increment: step;
}
.v-step-item-number.rejected::before {
  font-size: 20px;
  content: 'x';
}
.v-step-item-number {
  border-radius: 50%;
  content: counter(step);
  width: 30px;
  height: 30px;
  border: 2px solid #bebebe;
  display: block;
  border-radius: 50%;
  margin: 0 auto 10px auto;
  line-height: 24px;
  background: white;
  color: #bebebe;
  text-align: center;
}
.v-step-warp-horizontal .v-step-item-label {
  display: block;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.v-step-warp-horizontal .v-step-item-number {
  margin: 5px auto;
}
.v-step-warp-horizontal.style2 .v-step-progress-bg {
  height: 2px;
  bottom: 19px;
}
.v-step-warp-horizontal.style2 .v-step-item-number {
  width: 10px;
  height: 10px;
  line-height: 10px;
}
.v-step-warp-vertical {
  position: relative;
  padding: 0;
  z-index: 2;
}
.v-step-warp-vertical .v-step-progress-bg {
  position: absolute;
  width: 4px;
  height: 100%;
  top: 0;
  left: 15px;
  background-color: #ddd;
}
.v-step-warp-vertical .v-step-progress-value {
  position: absolute;
  top: 0;
  left: 0;
  width: inherit;
}
.v-step-warp-vertical .v-step-list {
  height: inherit;
  flex-direction: column;
  padding-left: 8px;
}
.v-step-warp-vertical .v-step-item {
  display: -webkit-flex;
  display: flex;
  height: 25%;
  align-items: center;
}
.v-step-warp-vertical .v-step-item-number {
  text-align: center;
}
.v-step-warp-vertical .v-step-item-label {
  position: absolute;
  order: 1;
  padding-left: 34px;
}
.v-step-warp-vertical.style2 .v-step-progress-bg {
  width: 2px;
}
.v-step-warp-vertical.style2 .v-step-item-number {
  width: 10px;
  height: 10px;
  line-height: 10px;
  margin-left: 3px;
}
.v-step-warp-vertical.style2 .v-step-item-label {
  padding-top: 0;
}
.progressbar {
  counter-reset: step;
}
.progressbar li {
  list-style: none;
  float: left;
  position: relative;
  text-align: center;
}
.progressbar li:first-child:after {
  content: none;
}
</style>
