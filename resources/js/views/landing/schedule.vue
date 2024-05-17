<script setup>
import AppointmentContent from '@/views/landing/schedule/appointment.vue'
import PaymentContent from '@/views/landing/schedule/payment.vue'

import ExamDocument from '@/views/landing/schedule/exam-document.vue'
import HealthInfo from '@/views/landing/schedule/health-info.vue'
import PersonalInfo from '@/views/landing/schedule/personal-info.vue'
import axios from "@axios"
import { convertDateTime, requiredValidatorWithKey } from '@validators'


const router = useRouter()

const scheduleSteps = [
  {
    title: 'Cita',
    icon: 'tabler-calendar',
  },
  {
    title: 'Información personal',
    icon: 'custom-card',
  },
  {
    title: 'Información de salud',
    icon: 'custom-address',
  },
  {
    title: 'Resultados de exámenes',
    icon: 'custom-payment',
  },
  {
    title: 'Pago de consulta',
    icon: 'custom-payment',
  },
]

const scheduleData = ref({
  service: null,
  services: [],
  availability: {},
  schedules: [],
  booked_date: convertDateTime(new Date()),
  time: "",
   
  firstName: "",
  lastName: "",
  sex: -1,
  dob: "",
  idType: "",
  idNumber: "",
  email: "",
  phone: "",

  password: "",
  confirm_password: "",

  chronicIllness: "",
  drugsDaily: "",
  surgeryBefore: "",
  cancerDiabetes: "",

  clinicFiles: [],
  isMedicalOrder: false,
})

const isError = ref(false)
const errorMsg = ref(null)

const cardData = ref({
  cardNumber: "",
  cardHolder: "",
  cardExpiry: "",
  cardCVV: "",
});

const loading = ref(false);

const requiredInformation = (param, key) => {
  const required = requiredValidatorWithKey(param, key)
  if (required) {
    errorMsg.value = required
    isError.value = true
    
    return true
  }

  return false  
}

const submitAppointment = () => {
  const _checkout = { ...scheduleData.value }

  if (_checkout.service == null) {
    errorMsg.value = "You must select a service."
    isError.value = true
    
    return
  }

  _checkout.service_id = _checkout.service.id

  delete _checkout.services
  delete _checkout.service
  
  const param = {
    ..._checkout,
    ...cardData.value,
  }  

  param.booked_date = convertDateTime(param.booked_date)
  param.isMedicalOrder = param.isMedicalOrder? 1 : 0

  if (
    requiredInformation(param, 'firstName') ||
    requiredInformation(param, 'lastName') ||
    requiredInformation(param, 'dob') ||
    requiredInformation(param, 'idType') ||
    requiredInformation(param, 'idNumber') ||
    requiredInformation(param, 'email') ||
    requiredInformation(param, 'phone') ||
    requiredInformation(param, 'firstName') ||  
    requiredInformation(param, 'time') || 
    requiredInformation(param, 'chronicIllness') || 
    requiredInformation(param, 'drugsDaily') || 
    requiredInformation(param, 'surgeryBefore') || 
    requiredInformation(param, 'cancerDiabetes') ||
    requiredInformation(param, 'cardNumber') ||
    requiredInformation(param, 'cardHolder') ||
    requiredInformation(param, 'cardExpiry') ||
    requiredInformation(param, 'cardCVV') ||
    requiredInformation(param, 'password') ||
    requiredInformation(param, 'confirm_password')
  ) {
    return
  }

  if (!param.isMedicalOrder && param.clinicFiles.length === 0) {
    errorMsg.value = "You must attach the document for clinic result."
    isError.value = true
    
    return
  }

  if (param.password !== param.confirm_password) {
    errorMsg.value = "Password confirmation mismatch."
    isError.value = true
    
    return
  }

  const formData = new FormData()

  Object.keys(param).forEach(key => {
    if (key === 'clinicFiles')
      formData.append(key, param[key][0])
    else
      formData.append(key, param[key])
  })

  loading.value = true;
  axios.post('/schedule/register', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
    .then(r => {
      loading.value = false;
      const { status } = r.data;
      if (status === "success") {
        router.replace("/login");
      }
    })
  .catch(e => {
    loading.value = false;
    console.log(e.toString());
  });
}

defineExpose({
  submitAppointment,
})

onMounted(() => {
  axios.get('/services-schedules')
    .then(r => {
      const { services, availability, schedules } = r.data
      for (let service of services) {
        service.value = service
      }

      scheduleData.value.services = services
      scheduleData.value.availability = availability
      scheduleData.value.schedules = schedules
    })
})

const currentStep = ref(0)
</script>

<template>
  <section>
    <h2 class="text-h2 mb-4">
      Reservar Ahora
    </h2>
    <VCard class="mb-6">
      <VCardText>
        <!-- 👉 Stepper -->
        <AppStepper
          v-model:current-step="currentStep"
          class="checkout-stepper"
          :items="scheduleSteps"
          :direction="$vuetify.display.smAndUp ? 'horizontal' : 'vertical'"
        />
      </VCardText>

      <VDivider />

      <VCardText>
        <!-- 👉 stepper content -->
        <VWindow
          v-model="currentStep"
          class="disable-tab-transition"
        >
          <VWindowItem>
            <AppointmentContent
              v-model:current-step="currentStep"
              v-model:schedule-data="scheduleData"
            />
          </VWindowItem>

          <VWindowItem>
            <PersonalInfo
              v-model:current-step="currentStep"
              v-model:schedule-data="scheduleData"
            />
          </VWindowItem>

          <VWindowItem>
            <HealthInfo
              v-model:current-step="currentStep"
              v-model:schedule-data="scheduleData"
            />
          </VWindowItem>

          <VWindowItem>
            <ExamDocument
              v-model:current-step="currentStep"
              v-model:schedule-data="scheduleData"
            />
          </VWindowItem>

          <VWindowItem>
            <PaymentContent
              v-model:current-step="currentStep"
              v-model:schedule-data="scheduleData"
              v-model:card-data="cardData"
              v-model:loading="loading"
              @submit-appointment="submitAppointment"
            />
          </VWindowItem>
        </VWindow>
      </VCardText>
    </VCard>
    <VSnackbar
      v-model="isError"
      :timeout="1000"
      location="top end"
      color="error"
    >
      {{ errorMsg }}
    </VSnackbar>
  </section>
</template>

<style lang="scss">
.checkout-stepper {
  .stepper-icon-step {
    .step-wrapper + svg {
      margin-inline: 3.5rem !important;
    }
  }
}
</style>
