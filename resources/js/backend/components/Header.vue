<template>
  <AppHeader fixed>
    <SidebarToggler class="d-lg-none" display="md" mobile></SidebarToggler>
    <b-link class="navbar-brand" to="#">
      <img
        class="navbar-brand-full"
        src="../../../sass/vendor/tabler/brand/logo.png"
        height="30"
        alt="IKCP"
      />
      <img
        class="navbar-brand-minimized"
        src="../../../sass/vendor/tabler/brand/logo-symbol.png"
        width="30"
        height="30"
        alt="IKCP"
      />
    </b-link>
    <SidebarToggler class="d-md-down-none" display="lg"></SidebarToggler>
    <b-navbar-nav class="ml-auto">
      <HeaderDropdown right class="px-3 d-none d-md-block" :no-caret="true">
        <template slot="header">
          <span class="d-md-down-none">
            <font-awesome-icon
              :icon="['far', 'bell']"
              size="lg"
              :class="{
                bell: $store.state.counters.unreadNotificationsCount > 0
              }"
            ></font-awesome-icon>
            <b-badge variant="danger">{{
              $store.state.counters.unreadNotificationsCount
            }}</b-badge>
          </span>
        </template>
        <template slot="dropdown">
          <b-dropdown-header
            ><strong>{{
              $t('labels.notifications_count', {
                count: $store.state.counters.unreadNotificationsCount
              })
            }}</strong></b-dropdown-header
          >
          <b-dropdown-item
            v-for="(notification, index) in $store.state
              .latestUnreadNotifications"
            :key="index"
            :to="
              notification.data.link
                ? notification.data.link
                : { name: 'notifications' }
            "
            @click="
              $store.dispatch('MARK_NOTIFICATION_AS', {
                notification_id: notification.id,
                status: 'read'
              })
            "
            active-class=""
            exact-active-class=""
          >
            <div>
              <font-awesome-icon
                class=" text-primary"
                :icon="['far', 'bell']"
              ></font-awesome-icon>
              {{ notification.data.subject }}
              <b-badge variant="info">{{ notification.created_at }}</b-badge>
            </div>
          </b-dropdown-item>
          <b-dropdown-item class="text-center" :to="{ name: 'notifications' }">
            <font-awesome-icon
              class=" text-primary"
              icon="ellipsis-h"
            ></font-awesome-icon>
          </b-dropdown-item>
        </template>
      </HeaderDropdown>

      <HeaderDropdown right class="px-3 d-none d-md-block" v-if="users.length">
        <template slot="header">
          <span class="d-md-down-none">
            <font-awesome-icon icon="lock"></font-awesome-icon>
          </span>
        </template>
        <template slot="dropdown">
          <b-form-input v-model="search" type="text"></b-form-input>
          <b-dropdown-item
            v-for="(item, index) in users_list"
            :href="$app.route('users.impersonate', { user: item.id })"
            :key="index"
          >
            <font-awesome-icon
              class=" text-primary"
              icon="user"
            ></font-awesome-icon>
            {{ item.name }}
          </b-dropdown-item>
        </template>
      </HeaderDropdown>
      <HeaderDropdown
        right
        class="px-3 d-none d-md-block"
        v-if="create_list.length"
      >
        <template slot="header">
          <span class="d-md-down-none">
            <i class="fe fe-plus-circle"></i>&nbsp;&nbsp;{{
              $t('labels.add_new')
            }}
          </span>
        </template>
        <template slot="dropdown">
          <b-dropdown-item
            v-for="(item, index) in create_list"
            :to="item.link"
            :key="index"
          >
            <font-awesome-icon
              class=" text-primary"
              v-if="item.icon.match(/^fa\./)"
              :icon="item.icon.split('.')[1]"
            ></font-awesome-icon>
            <i :class="item.icon + ' text-primary'" v-else></i> {{ item.text }}
          </b-dropdown-item>
        </template>
      </HeaderDropdown>
      <HeaderDropdown right class="px-3">
        <template slot="header">
          <img
            :src="this.$app.user.avatar"
            class="img-avatar"
            :alt="$t('labels.user.avatar')"
          />
          <span class="d-md-down-none"> {{ this.$app.user.name }} </span>
        </template>
        <template slot="dropdown">
          <!--<b-dropdown-item to="/admin/user_profile" :href="$app.route('user.account')">-->
          <b-dropdown-item
            to="/user_profile"
            v-if="$app.user.can('change password')"
          >
            <i class="fe fe-user"></i>&nbsp;&nbsp;{{
              $t('labels.user.profile')
            }}
          </b-dropdown-item>
          <b-dropdown-item :href="$app.route('logout')">
            <i class="fe fe-log-out"></i>&nbsp;&nbsp;{{
              $t('labels.user.logout')
            }}
          </b-dropdown-item>
        </template>
      </HeaderDropdown>
    </b-navbar-nav>
    <!--<AsideToggler class="d-none d-lg-block"></AsideToggler>-->
  </AppHeader>
</template>

<script>
import axios from 'axios'
import AppHeader from '../../vendor/coreui/components/Header/Header'

export default {
  name: 'MyAppHeader',
  components: {
    AppHeader
  },
  data() {
    return {
      users: [],
      search: null
    }
  },
  computed: {
    create_list() {
      let list = [
        {
          link: '/Crew/seafarers/create',
          text: this.$t('labels.backend.new_menu.seafarer'),
          icon: 'fa.users',
          permission: this.$app.user.can('create seafarers'),
          mobile: false
        },
        {
          link: '/Access/users/create',
          text: this.$t('labels.backend.new_menu.user'),
          icon: 'fi flaticon-profession',
          permission: this.$app.user.can('create users'),
          mobile: false
        },
        {
          link: '/Access/roles/create',
          text: this.$t('labels.backend.new_menu.role'),
          icon: 'fa.shield-alt',
          permission: this.$app.user.can('create roles'),
          mobile: false
        }
      ]

      return list.filter(item => {
        return item.permission
      })
    },
    users_list() {
      if (!this.search) return this.users

      let myReg = new RegExp(this.search, 'i')

      return this.users.filter(user => {
        return user.name.match(myReg)
      })
    }
  },
  async created() {
    if (this.$app.user.can('impersonate users')) {
      let { data } = await axios.get(this.$app.route(`get_list_of_users`))
      this.users = data
    }
  }
}
</script>

<style>
.bell {
  display: block;
  color: #9e9e9e;
  -webkit-animation: ring 4s 0.7s ease-in-out infinite;
  -webkit-transform-origin: 50% 4px;
  -moz-animation: ring 4s 0.7s ease-in-out infinite;
  -moz-transform-origin: 50% 4px;
  animation: ring 4s 0.7s ease-in-out infinite;
  transform-origin: 50% 4px;
}

@-webkit-keyframes ring {
  0% {
    -webkit-transform: rotateZ(0);
  }
  1% {
    -webkit-transform: rotateZ(30deg);
  }
  3% {
    -webkit-transform: rotateZ(-28deg);
  }
  5% {
    -webkit-transform: rotateZ(34deg);
  }
  7% {
    -webkit-transform: rotateZ(-32deg);
  }
  9% {
    -webkit-transform: rotateZ(30deg);
  }
  11% {
    -webkit-transform: rotateZ(-28deg);
  }
  13% {
    -webkit-transform: rotateZ(26deg);
  }
  15% {
    -webkit-transform: rotateZ(-24deg);
  }
  17% {
    -webkit-transform: rotateZ(22deg);
  }
  19% {
    -webkit-transform: rotateZ(-20deg);
  }
  21% {
    -webkit-transform: rotateZ(18deg);
  }
  23% {
    -webkit-transform: rotateZ(-16deg);
  }
  25% {
    -webkit-transform: rotateZ(14deg);
  }
  27% {
    -webkit-transform: rotateZ(-12deg);
  }
  29% {
    -webkit-transform: rotateZ(10deg);
  }
  31% {
    -webkit-transform: rotateZ(-8deg);
  }
  33% {
    -webkit-transform: rotateZ(6deg);
  }
  35% {
    -webkit-transform: rotateZ(-4deg);
  }
  37% {
    -webkit-transform: rotateZ(2deg);
  }
  39% {
    -webkit-transform: rotateZ(-1deg);
  }
  41% {
    -webkit-transform: rotateZ(1deg);
  }

  43% {
    -webkit-transform: rotateZ(0);
  }
  100% {
    -webkit-transform: rotateZ(0);
  }
}

@-moz-keyframes ring {
  0% {
    -moz-transform: rotate(0);
  }
  1% {
    -moz-transform: rotate(30deg);
  }
  3% {
    -moz-transform: rotate(-28deg);
  }
  5% {
    -moz-transform: rotate(34deg);
  }
  7% {
    -moz-transform: rotate(-32deg);
  }
  9% {
    -moz-transform: rotate(30deg);
  }
  11% {
    -moz-transform: rotate(-28deg);
  }
  13% {
    -moz-transform: rotate(26deg);
  }
  15% {
    -moz-transform: rotate(-24deg);
  }
  17% {
    -moz-transform: rotate(22deg);
  }
  19% {
    -moz-transform: rotate(-20deg);
  }
  21% {
    -moz-transform: rotate(18deg);
  }
  23% {
    -moz-transform: rotate(-16deg);
  }
  25% {
    -moz-transform: rotate(14deg);
  }
  27% {
    -moz-transform: rotate(-12deg);
  }
  29% {
    -moz-transform: rotate(10deg);
  }
  31% {
    -moz-transform: rotate(-8deg);
  }
  33% {
    -moz-transform: rotate(6deg);
  }
  35% {
    -moz-transform: rotate(-4deg);
  }
  37% {
    -moz-transform: rotate(2deg);
  }
  39% {
    -moz-transform: rotate(-1deg);
  }
  41% {
    -moz-transform: rotate(1deg);
  }

  43% {
    -moz-transform: rotate(0);
  }
  100% {
    -moz-transform: rotate(0);
  }
}

@keyframes ring {
  0% {
    transform: rotate(0);
  }
  1% {
    transform: rotate(30deg);
  }
  3% {
    transform: rotate(-28deg);
  }
  5% {
    transform: rotate(34deg);
  }
  7% {
    transform: rotate(-32deg);
  }
  9% {
    transform: rotate(30deg);
  }
  11% {
    transform: rotate(-28deg);
  }
  13% {
    transform: rotate(26deg);
  }
  15% {
    transform: rotate(-24deg);
  }
  17% {
    transform: rotate(22deg);
  }
  19% {
    transform: rotate(-20deg);
  }
  21% {
    transform: rotate(18deg);
  }
  23% {
    transform: rotate(-16deg);
  }
  25% {
    transform: rotate(14deg);
  }
  27% {
    transform: rotate(-12deg);
  }
  29% {
    transform: rotate(10deg);
  }
  31% {
    transform: rotate(-8deg);
  }
  33% {
    transform: rotate(6deg);
  }
  35% {
    transform: rotate(-4deg);
  }
  37% {
    transform: rotate(2deg);
  }
  39% {
    transform: rotate(-1deg);
  }
  41% {
    transform: rotate(1deg);
  }

  43% {
    transform: rotate(0);
  }
  100% {
    transform: rotate(0);
  }
}
</style>

<style scoped>
.dropdown-item .badge {
  position: unset !important;
}
</style>
