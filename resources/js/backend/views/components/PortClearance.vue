<template>
  <b-row class="record pr-5" style="position: relative">
    <b-button
      class="close"
      size="sm"
      variant="danger"
      v-b-tooltip.hover
      :title="$t('buttons.delete')"
      @click="dropRecord()"
      v-if="!disabled"
    >
      <i class="fe fe-x"></i>
    </b-button>

    <b-col xl="6">
      <b-form-group
        :label="$t('validation.attributes.departure_port')"
        :label-cols="4"
        :invalid-feedback="feedback(departure_port_field)"
        :state="state(departure_port_field)"
      >
        <b-form-input
          id="departure_port"
          name="departure_port"
          :placeholder="$t('validation.attributes.departure_port')"
          v-model="value.departure_port"
          :state="state(departure_port_field)"
          :disabled="disabled"
        ></b-form-input>
      </b-form-group>
    </b-col>
    <b-col xl="6">
      <b-form-group
        :label="$t('validation.attributes.departure_date')"
        :label-cols="4"
        :invalid-feedback="feedback(departure_date_field)"
        :state="state(departure_date_field)"
      >
        <b-input-group v-if="!disabled">
          <p-datetimepicker
            :config="config"
            :value="value.departure_date"
            ref="departure_date"
            @input="updateInput"
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
        <h3 v-else>
          <b-badge variant="primary" class="mr-1">
            {{ value.departure_date ? value.departure_date : ' -- ' }}
          </b-badge>
        </h3>
      </b-form-group>
    </b-col>
    <b-col xl="12">
      <b-form-group
        :label="$t('validation.attributes.port_clearance_file')"
        :label-cols="3"
        :invalid-feedback="feedback(record_file_field)"
        class="file-upload-group required"
      >
        <div class="media">
          <div v-if="value.url" class="m-1 record_link">
            <a :href="value.url" target="_blank">
              {{ $t('validation.attributes.port_clearance_file') }}
            </a>
          </div>

          <div class="media-body" v-if="!disabled">
            <b-form-file
              :placeholder="$t('labels.no_file_chosen')"
              v-model="value.file"
              :ref="compName + '-' + index"
              :state="state('value.file')"
              @change="onFileChange"
            ></b-form-file>
          </div>
        </div>
      </b-form-group>
    </b-col>
  </b-row>
</template>
<script>
import validation from '../../mixins/validation'

export default {
  name: 'PortClearance',
  mixins: [validation],
  props: {
    value: {
      default: () => ({
        id: 0,
        departure_port: null,
        departure_date: null,
        fill: null,
        url: null
      }),
      type: Object
    },
    index: {
      default: 0,
      type: Number
    },
    compName: {
      default: 'file',
      type: String,
      required: false
    },
    disabled: {
      type: Boolean,
      default: false,
      required: false
    }
  },
  data() {
    return {
      config: {
        wrap: true,
        allowInput: true
      }
    }
  },
  computed: {
    departure_port_field: function() {
      return 'port_clearance.' + this.index + '.departure_port'
    },
    departure_date_field: function() {
      return 'port_clearance.' + this.index + '.departure_date'
    },
    record_file_field: function() {
      return 'port_clearance.' + this.index + '.file'
    }
  },
  methods: {
    updateInput: function() {
      this.$emit('input', {
        id: this.value.id,
        departure_port: this.value.departure_port,
        departure_date: this.$refs.departure_date
          ? this.$refs.departure_date.$el.value
          : null,
        file: this.$refs[`${this.compName}-${this.index}`].$el.firstChild.files
          .length
          ? this.$refs[`${this.compName}-${this.index}`].$el.firstChild.files[0]
          : null,
        url: this.value.url
      })
    },
    onFileChange(e) {
      const file = e.target.files[0]
      this.$emit('input', {
        id: this.value.id,
        departure_port: this.value.departure_port,
        departure_date: this.$refs.departure_date
          ? this.$refs.departure_date.$el.value
          : null,
        file: file,
        url: this.value.url
      })
    },
    dropRecord() {
      this.$emit('deleted', { index: this.index })
    }
  }
}
</script>

<style scoped>
.btn-group >>> .btn.active {
  z-index: 0 !important;
}
.is-invalid >>> .multiselect__tags {
  border-color: #f86c6b !important;
}
.is-invalid >>> .form-control {
  border-color: #f86c6b !important;
}
.record_link {
  width: 30%;
}
.record {
  background-color: #f0fffd;
  padding-top: 8px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 3px;
  border: 1px solid #e9ecef;
}
.record:hover {
  background-color: #e4fffd;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
}
.close {
  position: absolute;
  right: 0px;
  top: 0px;
}
</style>
