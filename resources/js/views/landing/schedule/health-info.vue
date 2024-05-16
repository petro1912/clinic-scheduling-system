<script setup>
import { requiredValidatorWithKey } from '@validators'

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

const scheduleDataLocal = ref(props.scheduleData)

const isError = ref(false)
const errorMsg = ref(null)

const requiredInformation = (param, key) => {
  const required = requiredValidatorWithKey(param, key)
  if (required) {
    errorMsg.value = required
    isError.value = true
    
    return true
  }

  return false  
}

const updateAddressData = () => {
  emit('update:schedule-data', scheduleDataLocal.value)
}

const nextStep = () => {
  const param = scheduleDataLocal.value
  if (
    requiredInformation(param, 'chronicIllness') || 
    requiredInformation(param, 'drugsDaily') || 
    requiredInformation(param, 'surgeryBefore') || 
    requiredInformation(param, 'cancerDiabetes')
  )
    return

  updateAddressData()
  emit('update:currentStep', props.currentStep ? props.currentStep + 1 : 1)
}

watch(() => props.currentStep, updateAddressData)
</script>

<template>
  <VCard
    class="pa-sm-8 pa-5"
    flat
  >
    <VCardItem class="text-center">
      <VCardTitle class="text-h3 mb-6">
        La historia clínica inicial o basal
      </VCardTitle>
    </VCardItem>

    <VCardText class="personal-info-card">
      <VRow>
        <VCol
          cols="12"
          md="12"
        >
          <AppTextarea
            v-model="scheduleDataLocal.chronicIllness"
            clearable
            clear-icon="tabler-circle-x"
            label="¿Sufres de alguna enfermedad crónica?"
          />
        </VCol>

        <VCol
          cols="12"
          md="12"
        >
          <AppTextarea
            v-model="scheduleDataLocal.drugsDaily"
            clearable
            clear-icon="tabler-circle-x"
            label="¿Tomas algún medicamento diariamente?"
          />
        </VCol>

        <VCol
          cols="12"
          md="12"
        >
          <AppTextarea
            v-model="scheduleDataLocal.surgeryBefore"
            clearable
            clear-icon="tabler-circle-x"
            label="¿Se ha sometido a una cirugía o procedimiento médico?"
          />
        </VCol>

        <VCol
          cols="12"
          md="12"
        >
          <AppTextarea
            v-model="scheduleDataLocal.cancerDiabetes"
            clearable
            clear-icon="tabler-circle-x"
            label="¿Tiene antecedents familiares de cáncer o diabetes?"
          />
        </VCol>
        

        <VCol cols="12">
          <VBtn
            block
            class="my-4"
            @click="nextStep"
          >
            Place Order
          </VBtn>
        </VCol>
      </VRow>
    </VCardText>
    <VSnackbar
      v-model="isError"
      :timeout="1000"
      location="top end"
      color="error"
    >
      {{ errorMsg }}
    </VSnackbar>
  </VCard>
</template>

<style lang="scss">
.personal-info-card {
  padding-left: 8%;
  padding-right: 8%;
}
</style>