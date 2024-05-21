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

const showPassword = ref(false)
const showPassword2 = ref(false)
const isError = ref(false)
const errorMsg = ref(null)

const items = [
  'Rut',
  'Pasaporte',
]

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
  emit('update:schedule-data', scheduleDataLocal.value)
}

const nextStep = () => {
  const param = scheduleDataLocal.value
  if (
    requiredInformation(param, 'firstName') ||
    requiredInformation(param, 'lastName') ||
    requiredInformation(param, 'dob') ||
    requiredInformation(param, 'idType') ||
    requiredInformation(param, 'idNumber') ||
    requiredInformation(param, 'email') ||
    requiredInformation(param, 'phone') ||
    requiredInformation(param, 'firstName')
  )
    return

  if (param.sex == -1) {
    errorMsg.value = "You must select sex"
    isError.value = true
    
    return  
  }

  if (param.password != param.confirm_password) {
    errorMsg.value = "Password confirmation mismatch"
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
        Informacion personal
      </VCardTitle>
    </VCardItem>

    <VCardText class="personal-info-card">
      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="scheduleDataLocal.firstName"
            label="Nombre"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="scheduleDataLocal.lastName"
            label="Apellido"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <div>
            Sexo
          </div>
          <VRadioGroup
            v-model="scheduleDataLocal.sex"
            inline
          >
            <VRadio
              label="masculino"
              value="1"
            />
            <VRadio
              label="femenino"
              value="2"
            />
          </VRadioGroup>
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppDateTimePicker
            v-model="scheduleDataLocal.dob"
            label="Fecha de nacimiento"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppSelect
            v-model="scheduleDataLocal.idType"
            :items="items"
            label="Tipo de document"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="scheduleDataLocal.idNumber"
            label="Número del Documento"
          />
        </VCol>

        
        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="scheduleDataLocal.email"
            label="Correo electrónico"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="scheduleDataLocal.phone"
            label="Teléfono"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="scheduleDataLocal.password"
            :append-inner-icon="showPassword ? 'tabler-eye-off' : 'tabler-eye' "
            :type="showPassword ? 'text' : 'password'"
            label="contraseña"
            hint="At least 8 characters"
            counter
            @click:append-inner="showPassword = !showPassword"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="scheduleDataLocal.confirm_password"
            :append-inner-icon="showPassword2 ? 'tabler-eye-off' : 'tabler-eye' "
            :type="showPassword2 ? 'text' : 'password'"
            label="confirmar Contraseña"
            hint="At least 8 characters"
            counter
            @click:append-inner="showPassword2 = !showPassword2"
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
