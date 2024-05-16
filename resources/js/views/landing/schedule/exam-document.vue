<script setup>
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

const updateAddressData = () => {
  emit('update:schedule-data', scheduleDataLocal.value)
}

const nextStep = () => {
  const param = scheduleDataLocal.value
  if (!param.isMedicalOrder && param.clinicFiles.length == 0) {
    errorMsg.value = "You must attach the document for clinic result"
    isError.value = true

    return
  }
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
          <VAlert
            variant="outlined"
            color="warning"
            class="p-6"
          >
            <div>1. Si no adjunta documentos directamente, el estado debe ser "Enviar orden médica".</div>
            <div>2. Debe adjuntar los resultados de sus pruebas más recientes. Si no tiene uno, le enviaremos una orden médica a su correo electrónico.</div>
          </VAlert>
        </VCol>


        <VCol
          cols="12"
          md="12"
        >
          <div class="mb-2">
            Resultados de exámenes documentos
          </div>
          <VFileInput
            v-model="scheduleDataLocal.clinicFiles"
            multiple
            placeholder="PDF, JPG"
            label="Resultados de exámenes documentos"
            :disabled="scheduleDataLocal.isMedicalOrder"
            prepend-icon="tabler-paperclip"
          >
            <template #selection="{ fileNames }">
              <template
                v-for="fileName in fileNames"
                :key="fileName"
              >
                <VChip
                  label
                  size="small"
                  variant="outlined"
                  color="primary"
                  class="me-2"
                >
                  {{ fileName }}
                </VChip>
              </template>
            </template>
          </VFileInput>
        </VCol>

        
        <VCol
          cols="12"
          md="12"
        >
          <VCheckbox
     
            v-model="scheduleDataLocal.isMedicalOrder"
            label="Enviar orden médica"
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