<template>
  <div class="app">
    <MyAppHeader></MyAppHeader>
    <div class="app-body">
      <Sidebar fixed>
        <div class="sidebar-header">
          <i class="fe fe-user"></i>&nbsp;&nbsp;{{ $app.user.name }}
        </div>
        <SidebarNav :nav-items="nav"></SidebarNav>
        <SidebarFooter></SidebarFooter>
        <SidebarMinimizer></SidebarMinimizer>
      </Sidebar>
      <main class="main">
        <breadcrumb :list="$route.matched"></breadcrumb>
        <div class="container-fluid">
          <router-view :key="$route.name"></router-view>
        </div>
      </main>
      <!--<Aside fixed></Aside>-->
    </div>
    <AppFooter
      :name="$app.appName"
      :editor-name="$app.editorName"
      :editor-site-url="$app.editorSiteUrl"
    ></AppFooter>
  </div>
</template>

<script>
import nav from '../_nav'

import AppFooter from '../components/Footer'
import MyAppHeader from '../components/Header'

export default {
  name: 'Full',
  components: {
    MyAppHeader,
    AppFooter
  },
  data() {
    return {
      nav: []
    }
  },
  watch: {
    $route: 'fetchData'
  },
  created() {
    this.initNav()
    this.fetchData()
  },
  methods: {
    initNav() {
      this.nav = nav(
        this.$app,
        this.$i18n,
        this.$store.state.counters.newPostsCount,
        this.$store.state.counters.pendingPostsCount
      )
    },
    async fetchData() {
      await this.$store.dispatch('LOAD_COUNTERS')
      await this.$store.dispatch('GET_NOTIFICATIONS')
      this.initNav()
    }
  }
}
</script>

<style>
.dropdown-item svg {
  width: 20px;
  margin-right: 10px;
  margin-left: -10px;
  color: rgba(0, 40, 100, 0.12);
  text-align: center;
}
.dropdown-item.active svg {
  color: white !important;
}
.file-upload-group .invalid-feedback {
  display: block;
}
.multiselect--active,
.multiselect__content-wrapper {
  z-index: 1030 !important;
}
.multiselect .multiselect__tags {
  border-radius: 0px !important;
  border: 1px solid #c2cfd6 !important;
}
.custom-file-label {
  overflow: hidden;
  white-space: nowrap;
}
.form-fieldset {
  background: #f7fffd;
  border: 1px solid #e9ecef;
  padding: 1rem;
  border-radius: 3px;
  margin-bottom: 1rem;
}
.is-invalid .flatpickr-input {
  border-color: #cd201f;
}
.attachment_link {
  width: 30%;
}
.action-toolbar {
  justify-content: center !important;
}
.is-invalid .multiselect__tags {
  border-color: #cd201f !important;
}
.is-invalid .vue-treeselect__control {
  border-color: #cd201f !important;
}
.is-invalid .vue-treeselect__control-arrow {
  color: #cd201f !important;
}
.form-control-plaintext {
  font-weight: bolder;
  font-style: italic;
}
.is-invalid .btn-outline-primary {
  border-color: #cd201f !important;
  color: #cd201f !important;
}
.nav-fill .nav-link.active {
  border-top: 2px solid #09856e !important;
  border-top-color: rgb(9, 133, 110) !important;
  outline: none;
}
.toasted > svg:first-child {
  margin-right: 0.7rem;
}
.toasted-noty {
  max-width: 500px !important;
}
.toasted-noty > span {
  width: 100%;
  word-break: break-all;
}
.nav-link {
  outline: none;
}
.dropdown-item {
  border-bottom: 1px solid rgba(0, 40, 100, 0.12) !important;
}
</style>
