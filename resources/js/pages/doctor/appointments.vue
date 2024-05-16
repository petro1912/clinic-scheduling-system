<script setup>
import { avatarText } from '@/@core/utils/formatters'
import axios from '@axios'
import { VDataTable } from 'vuetify/labs/VDataTable'

const headers = [
  {
    title: '',
    key: 'data-table-expand',
  },
  {
    title: 'NAME',
    key: 'name',
  },
  {
    title: 'EMAIL/PHONE',
    key: 'email',
  },
  {
    title: 'PASSPORT',
    key: 'passport',
  },  
  {
    title: 'SERVICE',
    key: 'service',
  },
  {
    title: 'WHEN',
    key: 'booked_date',
  },
  {
    title: 'EXAM DOCUMENT',
    key: 'exam_result',
  },
]

const data = ref([])

const createLink = item => {
  
  const index = data.value.indexOf(item)

  axios.post('/doctor/meeting', { id: item.id })
    .then(r => {
      const { status, schedules } = r.data
      if (status == "success")
        data.value = schedules
    })
} 

onMounted(() => {
  axios.get('/doctor/schedules')
    .then(r => {
      data.value = r.data.schedules
    })
})
</script>

<template>  
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardItem>
          <VCardTitle>Equipo</VCardTitle>
        </VCardItem>
        <VCardText>
          <VDataTable
            :headers="headers"
            :items="data"
            :items-per-page="10"
            expand-on-click
          >
            <!-- Expanded Row Data -->
            <template #expanded-row="slotProps">
              <tr class="v-data-table__tr">
                <td :colspan="headers.length">
                  <div class="detail-view">
                    <VRow>
                      <VCol
                        cols="12"
                        md="6"
                      >
                        <VExpansionPanels
                          variant="accordion"
                          class="expansion-panels-width-border"
                        >
                          <VExpansionPanel>
                            <VExpansionPanelTitle>
                              <div class="text-error">
                                Schedule Info
                              </div>
                            </VExpansionPanelTitle>
                            <VExpansionPanelText>
                              <div class="detail-cell">
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  Service: {{ slotProps.item.raw.service.name }}
                                </p>
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  Service Duration: {{ slotProps.item.raw.service.duration }} min
                                </p>
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  Price: {{ slotProps.item.raw.service.price }}
                                </p>
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  Scheduled DATE: {{ slotProps.item.raw.booked_date }}
                                </p>
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  Scheduled TIME: {{ slotProps.item.raw.start_time }} - {{ slotProps.item.raw.end_time }}
                                </p>
                              </div>
                            </VExpansionPanelText>
                          </VExpansionPanel>
                          
                          <VExpansionPanel>
                            <VExpansionPanelTitle>
                              <div class="text-warning">
                                Patient Info
                              </div>
                            </VExpansionPanelTitle>
                            <VExpansionPanelText>
                              <div class="detail-cell">
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  Name: {{ slotProps.item.raw.patient.name }}
                                </p>
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  DOB: {{ slotProps.item.raw.patient.dob }}
                                </p> 
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  SEX: {{ slotProps.item.raw.patient.sex }}
                                </p>
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  ID TYPE: {{ slotProps.item.raw.patient.idType }}
                                </p> 
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  ID NUMBER: {{ slotProps.item.raw.patient.idNumber }}
                                </p> 
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  email: {{ slotProps.item.raw.patient.email }}
                                </p>
                                <p class="d-block font-weight-medium text--primary text-truncate">
                                  phone: {{ slotProps.item.raw.patient.phone }}
                                </p>
                              </div>
                            </VExpansionPanelText>
                          </VExpansionPanel>                          

                          <VExpansionPanel>
                            <VExpansionPanelTitle>
                              <div class="text-success">
                                Health Info
                              </div> 
                            </VExpansionPanelTitle>
                            <VExpansionPanelText>
                              <div class="detail-cell">
                                <p class="font-weight-bold">
                                  ¿Sufres de alguna enfermedad crónica?
                                </p>
                                <p>{{ slotProps.item.raw.health.chronicIllness }}</p>
                                <p class="font-weight-bold">
                                  ¿Tomas algún medicamento diariamente?
                                </p>
                                <p>{{ slotProps.item.raw.health.drugsDaily }}</p>
                                <p class="font-weight-bold">
                                  ¿Se ha sometido a una cirugía o procedimiento médico?
                                </p>
                                <p>{{ slotProps.item.raw.health.surgeryBefore }}</p>
                                <p class="font-weight-bold">
                                  ¿Tiene antecedents familiares de cáncer o diabetes?
                                </p>
                                <p>{{ slotProps.item.raw.health.cancerDiabetes }}</p>
                                <p class="font-weight-bold">
                                  Enviar orden médica: {{ slotProps.item.raw.health.isMedicalOrder ? "Sí" : "No" }}
                                </p>
                                <p class="font-weight-bold">
                                  Resultados de exámenes
                                </p>
                                <p>
                                  <a
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    :href="'/uploads/' + slotProps.item.raw.health.examResult"
                                  >{{ slotProps.item.raw.health.examResult }}</a>
                                </p>
                              </div>
                            </VExpansionPanelText>
                          </VExpansionPanel>
                        </vexpansionpanels>
                      </VCol>
                      <VCol
                        cols="12"
                        md="6"
                      >
                        <VCard class="mb-4">
                          <VCardItem>
                            <VCardTitle>Payment Info</VCardTitle>
                          </VCardItem>
                          <VCardText>
                            Paid
                          </VCardText>
                        </VCard>
                        <VBtn
                          v-if="slotProps.item.raw.link == '' || slotProps.item.raw.link == null"
                          block
                          @click="createLink(slotProps.item.raw)"
                        >
                          Create Link
                        </VBtn>
                        <div v-else>
                          <a :href="slotProps.item.raw.link">Join to Zoom meeting</a>
                        </div>
                      </VCol>
                    </VRow>
                  </div>
                </td>
              </tr>
            </template>

            <!-- full name -->
            <template #item.name="{ item }">
              <div class="d-flex align-center">
                <VAvatar
                  size="32"
                  color="primary"
                  class="v-avatar-light-bg primary--text"
                  variant="tonal"
                >
                  <span>{{ avatarText(item.raw.patient.name) }}</span>
                </VAvatar>
                <div class="d-flex flex-column ms-3">
                  <span class="d-block font-weight-medium text--primary text-truncate">{{ item.raw.patient.name }}</span>
                </div>
              </div>
            </template>
            <template #item.email="{ item }">
              <div class="d-flex align-center">
                <div class="d-flex flex-column ms-3">
                  <span class="d-block font-weight-medium text--primary text-truncate">{{ item.raw.patient.email }}</span>
                  <span class="d-block font-weight-medium text--primary text-truncate">{{ item.raw.patient.phone }}</span>
                </div>
              </div>
            </template>
            <template #item.booked_date="{ item }">
              <div class="d-flex align-center">
                <div class="d-flex flex-column ms-3">
                  <span class="d-block font-weight-medium text--primary text-truncate">
                    <span class="d-block font-weight-medium text-warning text-truncate">{{ item.raw.booked_date }}</span>
                    <span class="d-block font-weight-medium text-warning text-truncate">{{ item.raw.start_time }} - {{ item.raw.end_time }}</span>
                  </span>
                </div>
              </div>
            </template>
            <template #item.service="{ item }">
              <div class="d-flex align-center">
                <div class="d-flex flex-column ms-3">
                  <span class="d-block font-weight-medium text--primary text-truncate">
                    {{ item.raw.service.name }}
                  </span>
                </div>
              </div>
            </template>
            <template #item.passport="{ item }">
              <div class="d-flex align-center">
                <div class="d-flex flex-column ms-3">
                  <span class="d-block font-weight-medium text--primary text-truncate">
                    <span class="d-block font-weight-medium text--primary text-truncate">{{ item.raw.patient.idType }}</span>
                    <span class="d-block font-weight-medium text--primary text-truncate">{{ item.raw.patient.idNumber }}</span>
                  </span>
                </div>
              </div>
            </template>
            <template #item.exam_result="{ item }">
              <div class="d-flex align-center">
                <div class="d-flex flex-column ms-3">
                  <span class="d-block font-weight-medium text--primary text-truncate">
                    <a
                      target="_blank"
                      rel="noopener noreferrer"
                      :href="'/uploads/' + item.raw.health.examResult"
                    >{{ item.raw.health.examResult }}</a>
                  </span>
                </div>
              </div>
            </template>
          </VDataTable>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
.detail-view {
    padding: 20px 50px;
}
.detail-cell {
    padding-left: 20px;
    padding-right: 20px;
}
</style>