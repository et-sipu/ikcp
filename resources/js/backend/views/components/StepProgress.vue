<template>
  <div class="container">
    <ul
      id="ull"
      class="progressbar"
      :class="{
        'full-reject continue-reject':
          Object.keys(stepList)[nowStep - 1] === 'rejected'
      }"
    >
      <li
        id="uii"
        :class="{
          active: index < nowStep,
          contentx: label === 'rejected',
          contentr:
            label !== 'rejected' && index === Object.keys(stepList).length - 1
        }"
        v-for="(label, index) in Object.keys(stepList)"
        :key="index"
        :style="{ width: li_width + '%', color: activeColor }"
        v-b-tooltip.hover
        :title="
          $t(`labels.backend.${workflow}.states.${label}`) +
            (stepList[label] !== '' ? '@' + stepList[label] : '')
        "
      >
        <!--<label-->
        <!--class="v-step-item-label"-->
        <!--v-html="-->
        <!--index + 1 === nowStep-->
        <!--? $t(`labels.backend.${workflow}.states.${label}`)-->
        <!--: '<br>'-->
        <!--"-->
        <!--&gt;-->
        <!--</label>-->
      </li>
    </ul>
  </div>
</template>
<script>
import axios from 'axios'
export default {
  props: {
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
    },
    status: {
      type: String,
      required: true
    },
    availableTransitions: {
      type: Boolean,
      required: true
    },
    successStatus: {
      type: String,
      required: false,
      default: 'closed'
    },
    failStatus: {
      type: String,
      required: false,
      default: 'rejected'
    }
  },
  data() {
    return {
      statuses: []
    }
  },
  computed: {
    li_width: function() {
      return 100 / Object.keys(this.stepList).length
    },
    stepList: function() {
      if (
        this.status === this.successStatus ||
        this.status === this.failStatus
      ) {
        return this.stepHistory
      }

      let stepList = {}
      let last_step = null
      this.statuses.forEach(function(status) {
        if (this.stepHistory[status]) {
          stepList[status] = this.stepHistory[status]
          last_step = status
        }
      }, this)

      this.statuses
        .slice(this.statuses.indexOf(last_step) + 1)
        .forEach(function(status) {
          stepList[status] = ''
        }, this)
      return stepList
    },
    nowStep: function() {
      return Object.keys(this.stepList).indexOf(this.status) + 1
    }
  },
  async created() {
    // passTheStatus(status)
    let { data } = await axios.get(
      this.$app.route(`${this.workflow}.get_happy_path`)
    )
    this.statuses = data
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
    }
  }
}
</script>

<style scoped>
.container {
  text-align: left;
  left: 0;
  width: 100%;
  position: absolute;
  z-index: 1;
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
.progressbar li:before {
  content: counter(step);
  counter-increment: step;
  width: 30px;
  height: 30px;
  border: 2px solid #bebebe;
  display: block;
  margin: 0 auto 10px auto;
  border-radius: 50%;
  line-height: 27px;
  background: white;
  color: #bebebe;
  text-align: center;
  font-weight: bold;
}
.progressbar li:after {
  content: '';
  position: absolute;
  width: 100%;
  height: 3px;
  background: #979797;
  top: 15px;
  left: -50%;
  z-index: -1;
}
.progressbar li.active:before {
  border-color: #09856e;
  background: #09856e;
  color: white;
}
.progressbar li.active:after {
  background: #09856e;
}
.progressbar li:first-child:after {
  content: none;
}
.v-step-item-label {
  font-size: 12px;
  color: #666;
  display: block;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  padding-top: 0;
}
.rejected {
  font-size: 50px;
  content: 'x';
}
.full-reject li:before {
  background: #cd201f !important;
  color: white;
  border: 2px solid red;
  border-color: #cd201f !important;
}
.continue-reject li:after {
  background: #cd201f !important;
}
.contentx:before {
  content: 'x' !important;
}
.contentr:before {
  content: '✓' !important;
}
/*.progressbar li.rejected:before {*/
/*border-color: #3aac5d;*/
/*background: #3aac5d;*/
/*color: white;*/
/*}*/
/*.progressbar li.rejected:after {*/
/*background: #3aac5d;*/
/*}*/
</style>
