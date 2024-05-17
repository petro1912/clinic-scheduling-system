<script setup>
import { themeConfig } from '@themeConfig'

// Components
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'

import Schedule from '@/views/landing/schedule.vue'
import Services from '@/views/landing/services.vue'

</script>

<template>
  <div class="layout-wrapper layout-nav-type-horizontal layout-navbar-sticky layout-footer-static layout-content-width-boxed">
    <div
      class="layout-navbar-and-nav-container py-2"
      :class="header-blur"
    >
      <!-- 👉 Navbar -->
      <div class="layout-navbar">
        <div class="navbar-content-container">
          <a
            href="/"
            class="app-logo d-flex align-center gap-x-3"
          >
            <VNodeRenderer :nodes="themeConfig.app.logo" />

            <h1 class="app-title font-weight-bold leading-normal text-xl text-capitalize">
              {{ themeConfig.app.title }}
            </h1>
          </a>
          <VSpacer />

          <NavbarThemeSwitcher class="me-1" />
        </div>
      </div>
    </div>
    <main class="layout-page-content">
      <Services />
      <Schedule />
    </main>
  </div>
</template>

<style lang="scss">
.layout-wrapper.layout-nav-type-horizontal {
  .layout-navbar {
    border-block-end: none !important;
  }
}
</style>

<style lang="scss">
@use "@core-scss/template/libs/full-calendar";

.calendars-checkbox {
  .v-label {
    color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
    opacity: var(--v-high-emphasis-opacity);
  }
}

.calendar-add-event-drawer {
  &.v-navigation-drawer:not(.v-navigation-drawer--temporary) {
    border-end-start-radius: 0.375rem;
    border-start-start-radius: 0.375rem;
  }
}

.border-thin {
  border-right-width: thin;
  border-color: rgba(var(--v-border-color), var(--v-border-opacity));
  border-style: solid;
}

.calendar-date-picker {
  display: none;

  +.flatpickr-input {
    +.flatpickr-calendar.inline {
      border: none;
      box-shadow: none;

      .flatpickr-months {
        border-block-end: none;
      }
    }
  }

  & ~ .flatpickr-calendar .flatpickr-weekdays {
    margin-block: 0 4px;
  }
}
</style>

<route lang="yaml">
meta:
  layout: blank
  action: read
  subject: Auth
</route>
