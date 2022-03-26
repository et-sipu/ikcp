<template>
  <router-link tag="li" class="nav-item nav-dropdown" :to="url" disabled>
    <div class="nav-link nav-dropdown-toggle" @click="handleClick">
      <!--<i :class="classIcon"></i> {{ name }}-->
      <font-awesome-icon class="nav-icon" v-if="icon.match(/^fa\./)" :icon="icon.split('.')[1]"></font-awesome-icon>
      <font-awesome-icon class="nav-icon" v-else-if="icon.match(/^fas\./)" :icon="['fas', icon.split('.')[1]]"></font-awesome-icon>
      <font-awesome-icon class="nav-icon" v-else-if="icon.match(/^fab\./)" :icon="['fab', icon.split('.')[1]]"></font-awesome-icon>
      <i :class="classIcon" v-else></i> {{ name }}
    </div>
    <ul class="nav-dropdown-items ml-2">
      <slot></slot>
    </ul>
  </router-link>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      default: ''
    },
    url: {
      type: String,
      default: ''
    },
    icon: {
      type: String,
      default: ''
    }
  },
  computed: {
    classIcon () {
      return [
        'nav-icon',
        this.icon
      ]
    }
  },
  methods: {
    handleClick (e) {
      e.preventDefault()
      e.target.parentElement.classList.toggle('open')
    }
  }
}
</script>

<style scoped lang="css">
  .nav-link {
    cursor:pointer;
  }
</style>
