<script setup>
import { useThemeConfig } from '@core/composable/useThemeConfig'

// Components
import Footer from '@/layouts/components/Footer.vue'
import NavBarI18n from '@/layouts/components/NavBarI18n.vue'
import NavBarNotifications from '@/layouts/components/NavBarNotifications.vue'
import NavbarShortcuts from '@/layouts/components/NavbarShortcuts.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import NavSearchBar from '@/layouts/components/NavSearchBar.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'

// @layouts plugin
import { VerticalNavLayout } from '@layouts'
import {onMounted} from "vue";
import admin from "@/navigation/vertical/admin";
import doctor from "@/navigation/vertical/doctor";
import patient from "@/navigation/vertical/patient";

const { appRouteTransition, isLessThanOverlayNavBreakpoint } = useThemeConfig()
const { width: windowWidth } = useWindowSize();

const navItems = ref([]);

onMounted(() => {
  const userData = JSON.parse(localStorage.getItem('userData') || '{}')
  const userRole = (userData && userData.role) ? userData.role : null

  switch (userRole) {
    case 'admin':
      navItems.value = [...admin, ...doctor]
      break
    case 'doctor':
      navItems.value = [...doctor]
      break
    case 'patient':
      navItems.value = [...patient]
  }
})
</script>

<template>
  <VerticalNavLayout :nav-items="navItems">
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <IconBtn
          v-if="isLessThanOverlayNavBreakpoint(windowWidth)"
          id="vertical-nav-toggle-btn"
          class="ms-n3"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon
            size="26"
            icon="tabler-menu-2"
          />
        </IconBtn>

        <VSpacer />

        <NavbarThemeSwitcher class="me-1" />
        <UserProfile />
      </div>
    </template>

    <!-- 👉 Pages -->
    <RouterView v-slot="{ Component }">
      <Transition
        :name="appRouteTransition"
        mode="out-in"
      >
        <Component :is="Component" />
      </Transition>
    </RouterView>

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>

    <!-- 👉 Customizer -->
    <TheCustomizer />
  </VerticalNavLayout>
</template>
