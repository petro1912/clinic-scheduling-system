<script setup>
import axios from '@axios'

const dates = [
  'Monday',
  'Tuesday',
  'Wednesday',
  'Thusday',
  'Friday',
  'Saturday',
  'Sunday',
]

const isNotYet = ref(false)
const isSaveSuccess = ref(false)

const selectedDate = ref([
  'Monday',
  'Thusday',
  'Tuesday',
  'Wednesday',
])

const start_time = ref('')
const end_time = ref('')
const period = ref('')

const refresh = res => {
  const { availability } = res
            
  if (!availability || !availability.id) {
    isNotYet.value = true
  } else {
    selectedDate.value = availability.days.split(",")
    start_time.value = availability.start_time
    end_time.value = availability.end_time
    period.value = [availability.start_date, availability.end_date]
  }
}

const saveAvailability = () => {
  const _period = period.value.split(' to ')

  const param = {
    days: selectedDate.value,
    start_date: _period[0],
    end_date: _period[1],
    start_time: start_time.value,
    end_time: end_time.value,
  }

  axios.post('/doctor/availability', param)
    .then(r => {
      refresh(r.data)
      isSaveSuccess.value = true
    })
}

onMounted(() => {
  axios.get('/doctor/availability')
    .then(r => {
      refresh(r.data)
    })
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardItem>
          <VCardTitle>Availability & Schedule</VCardTitle>
          <template #append>
            <VBtn
              variant="tonal"
              @click="saveAvailability"
            >
              <VIcon
                size="20"
                icon="tabler-save"
              /> 
              Save
            </VBtn>
          </template>
        </VCardItem>
        <VCardText>
          <VAlert
            v-if="isNotYet"
            variant="tonal"
            color="error"
            class="mb-4"
          >
            Availability and Schedule has not been set up yet.
          </VAlert>
          <VRow>
            <VCol cols="4">
              <AppCombobox
                v-model="selectedDate"
                :items="dates"
                label="Which Days"
                multiple
                chips
              />
            </VCol>

            <VCol cols="4">
              <AppDateTimePicker
                v-model="start_time"
                label="Start At"
                :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i' }"
              />

              <AppDateTimePicker
                v-model="end_time"
                label="End At"
                :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i' }"
              />
            </VCol>

            <VCol cols="4">
              <AppDateTimePicker
                v-model="period"
                label="Period"
                :config="{ mode: 'range' }"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
    <VSnackbar
      v-model="isSaveSuccess"
      :timeout="1000"
      location="top end"
      variant="flat"
      color="primary"
    >
      Saved availability successfully.
    </VSnackbar>
  </VRow>
</template>