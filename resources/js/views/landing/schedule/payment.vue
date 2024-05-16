<script setup>
import { requiredValidatorWithKey } from '@validators'

const props = defineProps({
  currentStep: {
    type: Number,
    required: false,
  },
  cardData: {
    type: null,
    required: true,
  },
  scheduleData: {
    type: null,
    required: true,
  },
})

const emit = defineEmits([
  'submitAppointment',
  'update:card-data',
])


const isError = ref(false)
const errorMsg = ref(null)

const cardDataLocal = ref(props.cardData)
const scheduleDataLocal = ref(props.scheduleData)

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
  emit('update:card-data', cardDataLocal.value)
}

const nextStep = () => {
  const param = cardDataLocal.value
  if (
    requiredInformation(param, 'cardNumber') ||
    requiredInformation(param, 'cardHolder') ||
    requiredInformation(param, 'cardExpiry') ||
    requiredInformation(param, 'cardCVV')
  )
    return
  
  updateAddressData()
  emit('submitAppointment')
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

    <VCardText>
      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <VRow>
            <VCol cols="12">
              <h3>
                Card Information
              </h3>
            </VCol>
            <VCol cols="12">
              <AppTextField
                v-model="cardDataLocal.cardNumber"
                type="number"
                label="Card Number"
              />
            </VCol>

            <VCol
              cols="12"
              md="6"
            >
              <AppTextField
                v-model="cardDataLocal.cardHolder"
                label="Name"
              />
            </VCol>

            <VCol
              cols="6"
              md="3"
            >
              <AppTextField
                v-model="cardDataLocal.cardExpiry"
                label="Expiry"
              />
            </VCol>

            <VCol
              cols="6"
              md="3"
            >
              <AppTextField
                v-model="cardDataLocal.cardCVV"
                label="CVV"
                type="number"
              >
                <template #append-inner>
                  <VTooltip
                    text="Card Verification Value"
                    location="bottom"
                  >
                    <template #activator="{ props: tooltipProps }">
                      <VIcon
                        v-bind="tooltipProps"
                        size="20"
                        icon="tabler-help"
                      />
                    </template>
                  </VTooltip>
                </template>
              </AppTextField>
            </VCol>
            <VCol cols="12">
              <div class="mt-4">
                <VBtn
                  class="me-3"
                  @click="nextStep"
                >
                  Checkout
                </VBtn>
                <VBtn
                  variant="tonal"
                  color="secondary"
                >
                  Reset
                </VBtn>
              </div>
            </VCol>
          </VRow>
        </VCol>
        <VCol
          cols="0"
          md="1"
        />
        <VCol
          cols="12"
          md="5"
        >
          <VCard
            flat
            variant="outlined"
          >
            <VCardText>
              <h6 class="text-base font-weight-medium mb-4">
                Price Details
              </h6>

              <div class="d-flex justify-space-between text-base mb-2">
                <span class="text-high-emphasis">{{ scheduleDataLocal.service.name }}</span>
                <span>${{ scheduleDataLocal.service.price }}</span>
              </div>
            </VCardText>

            <VDivider />

            <VCardText>
              <div class="d-flex justify-space-between text-base mb-2">
                <span class="text-high-emphasis font-weight-medium">Total</span>
                <span>${{ scheduleDataLocal.service.price }}</span>
              </div>

              <div class="d-flex justify-space-between text-base mb-4">
                <span class="text-high-emphasis font-weight-medium">Deliver to:</span>
                <VChip
                  color="primary"
                  class="text-capitalize"
                  label
                >
                  Address of the Professional 
                </VChip>
              </div>
             
              <h6 class="text-base font-weight-medium">
                Professional Name
              </h6>
              <p class="text-base mb-1">
                Site Address : Phone Number of Professional 
              </p>
              <p class="text-base mb-3">
                Mobile : Phone Number of Professional 
              </p>
            </VCardText>
          </VCard>
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