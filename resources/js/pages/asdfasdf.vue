<script setup>
import { themeConfig } from '@themeConfig'

// Components
import NavBarI18n from '@/layouts/components/NavBarI18n.vue'
import NavBarNotifications from '@/layouts/components/NavBarNotifications.vue'
import NavbarShortcuts from '@/layouts/components/NavbarShortcuts.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'

import axios from '@axios'
import congoImg from '@images/illustrations/congo-illustration.png'
import sittingGirlWithLaptop from '@images/illustrations/sitting-girl-with-laptop.png'

const faqSearchQuery = ref('')
const faqs = ref([])

const fetchFaqs = () => {
  return axios.get('/pages/faqs', { params: { q: faqSearchQuery.value } }).then(response => {
    faqs.value = response.data
  }).catch(error => {
    console.error(error)
  })
}

const activeTab = ref('Payment')
const activeQuestion = ref(0)

watch(activeTab, () => activeQuestion.value = 0)
watch(faqSearchQuery, fetchFaqs, { immediate: true })

////
import {
  blankEvent,
} from '@/views/apps/calendar/useCalendar'
import { useCalendarStore } from '@/views/apps/calendar/useCalendarStore'
import { useResponsiveLeftSidebar } from '@core/composable/useResponsiveSidebar'

// Components

const store = useCalendarStore()

// 👉 Event
const event = ref(structuredClone(blankEvent))
const isEventHandlerSidebarActive = ref(false)

watch(isEventHandlerSidebarActive, val => {
  if (!val)
    event.value = structuredClone(blankEvent)
})

const { isLeftSidebarOpen } = useResponsiveLeftSidebar()

// 👉 Check all
const checkAll = computed({
  get: () => store.selectedCalendars.length === store.availableCalendars.length,
  set: val => {
    if (val)
      store.selectedCalendars = store.availableCalendars.map(i => i.label)
    else if (store.selectedCalendars.length === store.availableCalendars.length)
      store.selectedCalendars = []
  },
})

const jumpToDate = val => {
    
}
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
          <RouterLink
            to="/"
            class="app-logo d-flex align-center gap-x-3"
          >
            <VNodeRenderer :nodes="themeConfig.app.logo" />

            <h1 class="app-title font-weight-bold leading-normal text-xl text-capitalize">
              {{ themeConfig.app.title }}
            </h1>
          </RouterLink>
          <VSpacer />

          <NavBarI18n class="me-1" />
          <NavbarThemeSwitcher class="me-1" />
          <NavbarShortcuts class="me-1" />
          <NavBarNotifications class="me-2" />
          <UserProfile />
        </div>
      </div>
    </div>
    <main class="layout-page-content">
      <section class="mb-6">
        <VRow>
          <VCol cols="12">
            <h2 class="text-h2">
              Clinical Services
            </h2>
          </VCol>
          <VCol cols="4">
            <VCard>
              <VRow no-gutters>
                <VCol cols="8">
                  <VCardText>
                    <h6 class="text-lg text-no-wrap font-weight-medium">
                      Congratulations John! 🎉
                    </h6>
                    <p class="mb-2">
                      Best seller of the month
                    </p>
                    <h4 class="text-h4 font-weight-medium text-primary mb-1">
                      $48.9k
                    </h4>
                    <VBtn>View Sales</VBtn>
                  </VCardText>
                </VCol>

                <VCol cols="4">
                  <VCardText class="pb-0 px-0 position-relative h-100">
                    <VImg
                      :src="congoImg"
                      height="147"
                      class="congo-john-img w-100"
                    />
                  </VCardText>
                </VCol>
              </VRow>
            </VCard>
          </VCol>
          <VCol cols="4">
            <VCard>
              <VRow no-gutters>
                <VCol cols="8">
                  <VCardText>
                    <h6 class="text-lg text-no-wrap font-weight-medium">
                      Congratulations John! 🎉
                    </h6>
                    <p class="mb-2">
                      Best seller of the month
                    </p>
                    <h4 class="text-h4 font-weight-medium text-primary mb-1">
                      $48.9k
                    </h4>
                    <VBtn>View Sales</VBtn>
                  </VCardText>
                </VCol>

                <VCol cols="4">
                  <VCardText class="pb-0 px-0 position-relative h-100">
                    <VImg
                      :src="congoImg"
                      height="147"
                      class="congo-john-img w-100"
                    />
                  </VCardText>
                </VCol>
              </VRow>
            </VCard>
          </VCol>
          <VCol cols="4">
            <VCard>
              <VRow no-gutters>
                <VCol cols="8">
                  <VCardText>
                    <h6 class="text-lg text-no-wrap font-weight-medium">
                      Congratulations John! 🎉
                    </h6>
                    <p class="mb-2">
                      Best seller of the month
                    </p>
                    <h4 class="text-h4 font-weight-medium text-primary mb-1">
                      $48.9k
                    </h4>
                    <VBtn>View Sales</VBtn>
                  </VCardText>
                </VCol>

                <VCol cols="4">
                  <VCardText class="pb-0 px-0 position-relative h-100">
                    <VImg
                      :src="congoImg"
                      height="147"
                      class="congo-john-img w-100"
                    />
                  </VCardText>
                </VCol>
              </VRow>
            </VCard>
          </VCol>
        </VRow>
      </section>
      <section class="mb-6">
        <VRow>
          <VCol cols="12">
            <h2 class="text-h2">
              Available Schedule
            </h2>
          </VCol>
          <VCol cols="12">
            <VCard>
              <VRow>
                <VCol
                  cols="3"
                  class="border-thin"
                >
                  <div style="margin: 1.4rem;">
                    <VBtn
                      block
                      prepend-icon="tabler-plus"
                      @click="isEventHandlerSidebarActive = true"
                    >
                      Add event
                    </VBtn>
                  </div>

                  <VDivider />

                  <div class="d-flex align-center justify-center pa-2 mb-3">
                    <AppDateTimePicker
                      :model-value="new Date().toJSON().slice(0, 10)"
                      :config="{ inline: true }"
                      class="calendar-date-picker"
                      @input="jumpToDate($event.target.value)"
                    />
                  </div>

                  <VDivider />
                  <div class="pa-7">
                    <p class="text-sm text-uppercase text-disabled mb-3">
                      FILTER
                    </p>

                    <div class="d-flex flex-column calendars-checkbox">
                      <VCheckbox
                        v-model="checkAll"
                        label="View all"
                      />
                      <VCheckbox
                        v-for="calendar in store.availableCalendars"
                        :key="calendar.label"
                        v-model="store.selectedCalendars"
                        :value="calendar.label"
                        :color="calendar.color"
                        :label="calendar.label"
                      />
                    </div>
                  </div>
                </VCol>
                <VCol cols="9" />
              </VRow>
            </VCard>
          </VCol>
        </VRow>
      </section>
      <section>
        <!-- 👉 Search -->
        <AppSearchHeader
          v-model="faqSearchQuery"
          title="Frequently Asked Questions"
          subtitle="or select a category to quickly find the help you require"
          custom-class="mb-7"
        />

        <!-- 👉 Faq sections and questions -->
        <VRow>
          <VCol
            v-show="faqs.length"
            cols="12"
            sm="4"
            lg="3"
            class="position-relative"
          >
            <!-- 👉 Tabs -->
            <VTabs
              v-model="activeTab"
              direction="vertical"
              class="v-tabs-pill"
              grow
            >
              <VTab
                v-for="faq in faqs"
                :key="faq.faqTitle"
                :value="faq.faqTitle"
                class="text-high-emphasis"
              >
                <VIcon
                  :icon="faq.faqIcon"
                  :size="20"
                  start
                />
                {{ faq.faqTitle }}
              </VTab>
            </VTabs>
            <VImg
              :width="245"
              :src="sittingGirlWithLaptop"
              class="d-none d-sm-block mt-10 mx-auto"
            />
          </VCol>

          <VCol
            cols="12"
            sm="8"
            lg="9"
          >
            <!-- 👉 Windows -->
            <VWindow
              v-model="activeTab"
              class="faq-v-window disable-tab-transition"
            >
              <VWindowItem
                v-for="faq in faqs"
                :key="faq.faqTitle"
                :value="faq.faqTitle"
              >
                <div class="d-flex align-center mb-6">
                  <VAvatar
                    rounded
                    color="primary"
                    variant="tonal"
                    class="me-3"
                    size="large"
                  >
                    <VIcon
                      :size="32"
                      :icon="faq.faqIcon"
                    />
                  </VAvatar>

                  <div>
                    <h6 class="text-h6">
                      {{ faq.faqTitle }}
                    </h6>
                    <span class="text-sm">{{ faq.faqSubtitle }}</span>
                  </div>
                </div>

                <VExpansionPanels
                  v-model="activeQuestion"
                  multiple
                >
                  <VExpansionPanel
                    v-for="item in faq.faqs"
                    :key="item.question"
                    :title="item.question"
                    :text="item.answer"
                  />
                </VExpansionPanels>
              </VWindowItem>
            </VWindow>
          </VCol>

          <VCol
            v-show="!faqs.length"
            cols="12"
            :class="!faqs.length ? 'd-flex justify-center align-center' : ''"
          >
            <VIcon
              icon="tabler-help"
              start
              size="20"
            />
            <span class="text-base font-weight-medium">
              No Results Found!!
            </span>
          </VCol>
        </VRow>

        <!-- 👉 You still have a question? -->
        <div class="text-center pt-15">
          <VChip
            label
            color="primary"
            size="small"
            class="mb-2"
          >
            QUESTION?
          </VChip>

          <h5 class="text-h5 mb-2">
            You still have a question?
          </h5>
          <p>
            If you can't find question in our FAQ, you can contact us. We'll answer you shortly!
          </p>

          <!-- contacts -->
          <VRow class="mt-4">
            <VCol
              v-for="contact in contactUs"
              :key="contact.icon"
              sm="6"
              cols="12"
            >
              <VCard
                flat
                class="bg-var-theme-background"
              >
                <VCardText>
                  <VAvatar
                    rounded
                    color="primary"
                    variant="tonal"
                    class="me-3"
                  >
                    <VIcon :icon="contact.icon" />
                  </VAvatar>
                </VCardText>
                <VCardText>
                  <h6 class="text-h6 mb-2">
                    {{ contact.via }}
                  </h6>
                  <span>{{ contact.tagLine }}</span>
                </VCardText>
              </VCard>
            </VCol>
          </VRow>
        </div>
      </section>
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
      redirectIfLoggedIn: false
</route>