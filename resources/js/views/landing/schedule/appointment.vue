<script setup>
import {convertDateTime, getTimeRanges, requiredValidatorWithKey} from '@validators'

const props = defineProps({
  currentStep: {
    type: Number,
    required: false,
  },
  scheduleData: {
    type: null,
    required: true,
  },
})

const emit = defineEmits([
  'update:currentStep',
  'update:schedule-data',
])


const isError = ref(false)
const errorMsg = ref(null)

const scheduleDataLocal = ref(props.scheduleData)

const wdays = [
  'Sunday',
  'Monday',
  'Tuesday',
  'Wednesday',
  'Thursday',
  'Friday',
  'Saturday',                
]

const getAvailableTimes = computed(() => {
  const service = scheduleDataLocal.value.service  
  const availability = scheduleDataLocal.value.availability
  const booked_date = scheduleDataLocal.value.booked_date 
  if (service == null || availability == null)
    return [] 

  const adays = availability.days.split(",")
  const availableDays = adays.map(day => wdays.findIndex(it => day === it))
  if (availableDays.length === 0)
    return []

  if (availableDays.findIndex(it => it === new Date(booked_date).getDay()) === -1) {
    return []
  }

  const scheduleOnDate = scheduleDataLocal.value.schedules.filter(item => item.booked_date === booked_date)
  let availableTimes =  getTimeRanges(availability.start_time, availability.end_time, service.duration)

  availableTimes = availableTimes.map(it => {
    const range = it.split(" - ")
    const start_time = range[0] + ":00"
    const end_time = range[1] + ":00"

    const overlapArr = scheduleOnDate.map(sod => {
      const start = sod.start_time
      const end = sod.end_time
      
      return [start_time, end_time, start, end, (start >= start_time && start < end_time) ||  (end > start_time && end <= end_time)]
    })
 
    const disabled = overlapArr.findIndex(it => it[4]) !== -1

    return {
      title: it,
      value: it,
      desc: disabled ?  'Already Scheduled' : 'Available',
      disabled: disabled,
    }
  })  

  return availableTimes
})

const updateAddressData = () => {
  emit('update:schedule-data', scheduleDataLocal.value)
}

const requiredInformation = (param, key) => {
  const required = requiredValidatorWithKey(param, key)

  if (required) {
    errorMsg.value = required
    isError.value = true
    
    return true
  }

  return false  
}

const nextStep = () => {
  const data = scheduleDataLocal.value

  console.log('data', data)

  if (data.service == null) {
    errorMsg.value = "You must select a service."
    isError.value = true
    
    return
  }

  if (requiredInformation(data, 'time')) 
    return

  updateAddressData()
  emit('update:currentStep', props.currentStep ? props.currentStep + 1 : 1)
}

watch(() => props.currentStep, updateAddressData)

const jumpToDate = val => {
  scheduleDataLocal.value.booked_date = convertDateTime(val)
  emit('update:schedule-data', scheduleDataLocal.value)
}
</script>

<template>
  <VRow class="personal-info-card">
    <VCol
      cols="12"
      md="12"
    >
      <!-- 👉 Delivery options -->
      <h3 class="font-weight-regular mb-4">
        1. Elija Servicio Clínico
      </h3>

      <!-- 👉 Delivery options custom input -->
      <CustomRadiosWithIcon
        v-model:selected-radio="scheduleDataLocal.service"
        :radio-content="scheduleDataLocal.services"
        :grid-column="{ cols: '12', sm: '4' }"
      >
        <template #default="{ item }">
          <div class="d-flex flex-column align-center gap-2 w-100">
            <div class="d-flex justify-end w-100 mb-n3">
              <VChip
                color="primary"
                label
              >
                {{
                  `$${item.price}`
                }}
              </VChip>
            </div>

            <VIcon icon="tabler-crown" />

            <h6 class="text-base font-weight-medium text-capitalize">
              {{ item.name }}
            </h6>
            <p class="text-sm text-center mb-0">
              {{ item.duration }} mins
            </p>
          </div>
        </template>
      </CustomRadiosWithIcon>
    </VCol>

    <VCol
      cols="12"
      md="12"
    >
      <h3 class="font-weight-regular">
        2. Elija fecha y hora disponibles
      </h3>
    </VCol>
      
    <VCol
      cols="12"
      md="4"
    >
      <VCard
        flat
        variant="outlined"
      >
        <!-- 👉 Delivery estimate date -->
        <VCardText>
          <div class="d-flex align-center justify-center pa-2 mb-3">
            <AppDateTimePicker
              :model-value="scheduleDataLocal.booked_date"
              :config="{ inline: true }"
              class="calendar-date-picker"
              @input="jumpToDate($event.target.value)"
            />
          </div>
        </VCardText>
      </VCard>
    </VCol>

    <VCol
      cols="12"
      md="8"
    >
      <VCard
        flat
        variant="outlined"
      >
        <!-- 👉 Delivery estimate date -->
        <VCardText>
          <h6 class="text-base font-weight-medium">
            Tiempo disponible
          </h6>
          <div class="d-flex align-center justify-center pa-2 mb-3">
            <CustomRadios
              v-model:selected-radio="scheduleDataLocal.time"
              class="d-flex justify-center align-center"
              :radio-content="getAvailableTimes"
              :grid-column="{ cols: '12', sm: '4' }"
            />
          </div>
        </VCardText>
      </VCard>
      <VBtn
        block
        class="my-4"
        @click="nextStep"
      >
        Place Order
      </VBtn>
    </VCol>
    <VSnackbar
      v-model="isError"
      :timeout="1000"
      location="top end"
      color="error"
    >
      {{ errorMsg }}
    </VSnackbar>
  </VRow>
</template>

<style lang="scss">
.personal-info-card {
  padding: 3% 6%;
}
</style>
