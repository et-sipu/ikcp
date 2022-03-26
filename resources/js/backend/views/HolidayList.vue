<template>
  <div class="animated fadeIn">
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
      integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"
      crossorigin="anonymous"
    />
    <b-card>
      <template slot="header">
        <h3 class="card-title">
          {{ $t('labels.backend.holidays.titles.index') }}
        </h3>
        <div class="card-options" v-if="$app.user.can('create holidays')">
          <b-btn
            variant="primary"
            v-b-tooltip.hover
            :title="$t('buttons.holidays.create')"
            size="sm"
            @click="
              id = null
              showCreateModal = !showCreateModal
            "
          >
            <i class="fe fe-plus-circle"></i>
          </b-btn>
        </div>
        <model-modal
          componenet-name="HolidayForm"
          :model-id="id ? id : 0"
          :show-modal.sync="showCreateModal"
          :reload-table="false"
          @form-submitted-successfully="getEvents()"
        ></model-modal>
      </template>
      <vue-cal
        style="height: 500px"
        class="vuecal--my-theme"
        :selected-date="new Date().toISOString().slice(0, 10)"
        :time="false"
        :disable-views="['years', 'week', 'day']"
        default-view="month"
        :events-on-month-view="true"
        :events="events"
        events-count-on-year-view
        :on-event-dblclick="onEventClick"
      ></vue-cal>
    </b-card>
  </div>
</template>

<script>
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'
import ModelModal from './components/ModelModal'
import axios from 'axios'

export default {
  name: 'HolidayList',
  components: { ModelModal, VueCal },
  data() {
    return {
      selectedEvent: {},
      showDialog: false,
      showCreateModal: false,
      id: null,
      events: []
    }
  },
  created() {
    this.getEvents()
  },
  methods: {
    async getEvents() {
      let { data } = await axios.get(this.$app.route(`holidays.per_year`))
      this.events = data.data
    },
    onEventClick(event, e) {
      this.id = event.id
      this.showCreateModal = true
      e.stopPropagation()
    }
  }
}
</script>

<style>
.vuecal__event.leisure {
  background-color: rgba(219, 177, 30, 0.85);
  border: 1px solid #dbb11e;
  color: #fff;
}
.ex--multiple-day-events .vuecal__event.leisure {
  background-color: rgba(219, 177, 30, 0.8);
  border: none;
  border-left: 3px solid rgba(219, 177, 30, 0.3);
  color: #dbb11e;
}
.ex--all-day-events.vuecal--day-view
  .vuecal__bg
  .vuecal__event--all-day.leisure,
.ex--all-day-events.vuecal--week-view
  .vuecal__bg
  .vuecal__event--all-day.leisure {
  left: 50%;
}
.vuecal--my-theme .vuecal__menu,
.vuecal--my-theme .vuecal__cell-events-count {
  background-color: #09856e;
}
.vuecal--my-theme .vuecal__menu li {
  border-bottom-color: #fff;
  color: #fff;
}
.vuecal--my-theme .vuecal__menu li.active {
  background-color: rgba(255, 255, 255, 0.15);
}
.vuecal--my-theme .vuecal__title {
  background-color: #e4f5ef;
}
.vuecal--my-theme .vuecal__cell.today,
.vuecal--my-theme .vuecal__cell.current {
  background-color: rgba(240, 240, 255, 0.4);
}
.vuecal--my-theme
  .vuecal:not(.vuecal--day-view)
  vuecal--my-theme
  .vuecal__cell.selected {
  background-color: rgba(235, 255, 245, 0.4);
}
.vuecal--my-theme .vuecal__cell.selected:before {
  border-color: rgba(66, 185, 131, 0.5);
}
</style>
