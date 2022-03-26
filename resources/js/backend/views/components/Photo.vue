<template>
  <b-form-group
    :label="label"
    :label-for="fieldName"
    :label-cols="3"
    :invalid-feedback="feedback(fieldName)"
    :state="state(fieldName)"
    class="file-upload-group required"
  >
    <div v-if="photo_url || imagePath" class="preview">
      <img
        class="mr-2 mb-2 img-thumbnail"
        :src="photo_url ? photo_url : imagePath"
        alt=""
        width="150px"
        @click="$refs.imagefile.click()"
      />
      <input
        style="display: none"
        type="file"
        :id="fieldName"
        :name="fieldName"
        :placeholder="$t('labels.no_file_chosen')"
        :state="state(fieldName)"
        @change="onFileChange"
        ref="imagefile"
      />
    </div>

    <div class="media" v-if="!imagePath || (imagePath && editable)">
      <div class="media-body">
        <b-form-file
          :id="fieldName"
          :name="fieldName"
          :placeholder="$t('labels.no_file_chosen')"
          v-model="value"
          :state="state(fieldName)"
          @change="onFileChange"
          v-if="!(photo_url || imagePath)"
        ></b-form-file>
        <p class="form-text text-muted">
          {{ $t('labels.descriptions.allowed_image_types') }}
        </p>
      </div>
    </div>
  </b-form-group>
</template>

<script>
import validation from '../../mixins/validation'

export default {
  name: 'Photo',
  mixins: [validation],
  props: {
    fieldName: {
      default: () => 'field',
      type: String,
      required: true
    },
    label: {
      default: () => 'label',
      type: String,
      required: true
    },
    imagePath: {
      default: () => '',
      type: String
    },
    value: {
      default: null,
      type: File
    },
    editable: {
      default: true,
      type: Boolean
    }
  },
  data() {
    return {
      photo_url: null
    }
  },
  methods: {
    onFileChange(e) {
      const file = e.target.files[0]
      this.photo_url = URL.createObjectURL(file)
      this.$emit('input', file)
    }
  }
}
</script>

<style>
.preview {
  display: flex;
  justify-content: center;
  align-items: center;
}

.preview img {
  max-width: 50%;
}
</style>
