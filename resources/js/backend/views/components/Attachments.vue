<template>
  <fieldset>
    <h5 class="mb-5">
      {{ $t('validation.attributes.attachments') }}
    </h5>

    <b-row>
      <b-col xl="12" v-for="(attachment, index) in attachments" :key="index">
        <b-button
          class="close"
          size="sm"
          variant="danger"
          v-b-tooltip.hover
          :title="$t('buttons.reset')"
          @click="dropFile('file-' + index, attachment, 'attachments', index)"
          v-if="!disabled"
        >
          <i class="fe fe-x"></i>
        </b-button>
        <b-form-group
          :label="$t('validation.attributes.attachment') + ' ' + (index + 1)"
          :label-cols="4"
          :invalid-feedback="feedback('attachments.' + index + '.file')"
          class="file-upload-group required"
        >
          <div class="media">
            <div v-if="attachment.url" class="m-1 certificate_link">
              <a :href="attachment.url" target="_blank">
                {{ $t('validation.attributes.attachment') }}
              </a>
            </div>

            <div class="media-body" v-if="!disabled">
              <b-form-file
                accept=".jpg, .png, .gif, .pdf"
                :placeholder="$t('labels.no_file_chosen')"
                v-model="attachment.file"
                :key="'filekey-' + index"
                :ref="'file-' + index"
                :state="state('attachments.' + index + '.file')"
              ></b-form-file>
            </div>
          </div>
        </b-form-group>
      </b-col>
    </b-row>
    <div class="float-right" v-if="!disabled">
      <b-button
        size="sm"
        variant="primary"
        v-b-tooltip.hover
        :title="$t('buttons.add_attachment')"
        @click="addFile('attachments')"
      >
        <i class="fe fe-plus-circle"></i>
      </b-button>
    </div>
  </fieldset>
</template>

<script>
export default {
  name: 'Attachments',
  props: {
    value: {
      default: () => [],
      type: Array
    },
    disabled: {
      type: Boolean,
      default: false,
      required: false
    }
  },
  computed: {
    attachments: function() {
      return this.value.filter(
        attachment => attachment.id === null || attachment.url !== null
      )
    }
  },
  methods: {
    onFileChange(e, attachment) {
      const file = e.target.files[0]
      this.$emit('input', {
        id: this.value.id,
        file: file,
        url: this.value.url
      })
    },
    addFile(collection) {
      this.value.push({
        id: null,
        url: null,
        file: null
      })
    },
    dropFile(ref, file, collection = 'attachments', index = 0) {
      this.$refs[ref][0].reset()
      file.file = null
      if (file.id === null && file.url == null && file.file === null) {
        this.value.splice(index, 1)
      }

      file.url = null
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
