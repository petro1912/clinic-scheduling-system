<script setup>
import axios from '@axios'

const idTypes = [
  'Rut',
  'Pasaporte',
]

const roles = [
  'admin',
  'doctor',
  'assistant',
]

const editDialog = ref(false)
const deleteDialog = ref(false)
const isAddSuccess = ref(false)
const isAddFailed = ref(false)
const isRemoveSuccess = ref(false)

const showPassword = ref(false)
const showPassword2 = ref(false)

const professionals = ref([])
const error = ref('')

const saveAccount = () => {
  const item = editedItem.value

  axios.post('/admin/accounts', item)
    .then(r => {
      const res = r.data

      close()
      if (res.status != 'success') {
        isAddFailed.value = true
        error.value = res.message
      } else {
        isAddSuccess.value = true
        professionals.value = res.accounts
      }
      
    })
}


const defaultItem = ref({
  id: -1,
  name: '',
  firstName: '',
  lastName: '',
  idType: '',
  idNumber: '',
  email: '',
  role: '',
  registerNumber: '',
  speciality: '',
})

const editedItem = ref(defaultItem.value)
const editedIndex = ref(-1)

const editItem = item => {
  const names = item.name.split(' ')
  
  editedIndex.value = professionals.value.indexOf(item)
  editedItem.value = { 
    ...item, 
    firstName: names[0], 
    lastName: names.length > 1? names[1] : '', 
  }
  editDialog.value = true
}

const deleteItem = item => {
  editedIndex.value = professionals.value.indexOf(item)
  editedItem.value = { ...item }
  deleteDialog.value = true
}

const close = () => {
  editDialog.value = false
  editedIndex.value = -1
  editedItem.value = { ...defaultItem.value }
}

const closeDelete = () => {
  deleteDialog.value = false
  editedIndex.value = -1
  editedItem.value = { ...defaultItem.value }
}

onMounted(() => {
  axios.get(`/admin/accounts`).then(r => {
    professionals.value = r.data.accounts
  })
})
</script>


<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardItem>
          <VCardTitle>Professionals</VCardTitle>
          <template #append>
            <VBtn
              variant="tonal"
              @click="editItem(defaultItem)"
            >
              <VIcon
                size="20"
                icon="tabler-plus"
              /> 
              Nueva usuaria
            </VBtn>
          </template>
        </VCardItem>
        <VCardText>
          <VTable class="text-no-wrap">
            <thead>
              <tr>
                <th class="text-uppercase">
                  ID
                </th>
                <th class="text-uppercase text-center">
                  Name
                </th>
                <th class="text-uppercase text-center">
                  Tipo de <br>Documento
                </th>
                <th class="text-uppercase text-center">
                  Número del <br>Documento
                </th>
                <th class="text-uppercase text-center">
                  correo
                </th>
                <th class="text-uppercase text-center">
                  Categoría 
                </th>
                <th class="text-uppercase text-center">
                  Número de <br>registro medico 
                </th>
                <th class="text-uppercase text-center">
                  Especialidad 
                </th>
                <th class="text-uppercase text-center">
                  Acción 
                </th>
              </tr>
            </thead>

            
            <tbody v-if="professionals">
              <tr
                v-for="(item, index) in professionals"
                :key="item.id"
              >
                <td>
                  {{ index + 1 }}
                </td>
                <td class="text-center">
                  {{ item.name }}
                </td>
                <td class="text-center">
                  {{ item.idType }}
                </td>
                <td class="text-center">
                  {{ item.idNumber }}
                </td>
                <td class="text-center">
                  {{ item.email }}
                </td>
                <td class="text-center">
                  <VChip
                    color="primary"
                    variant="elevated"
                    class="text-uppercase"
                  >
                    {{ item.role }}
                  </VChip>
                </td>
                <td class="text-center">
                  {{ item.registerNumber }}
                </td>
                <td class="text-center">
                  {{ item.speciality }}
                </td>
                <td>
                  <div class="d-flex gap-1 justify-center">
                    <IconBtn @click="editItem(item)">
                      <VIcon icon="mdi-pencil-outline" />
                    </IconBtn>
                  </div>
                </td>
              </tr>
            </tbody>
          </VTable>
        </VCardText>
      </VCard>
    </VCol>
    <VDialog
      v-model="editDialog"
      max-width="800px"
    >
      <VCard>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12">
                <VCard flat>
                  <VCardItem>
                    <VCardTitle>Editar cuenta</VCardTitle>
                  </VCardItem>
                  <VCardText>
                    <VRow>
                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppTextField
                          v-model="editedItem.firstName"
                          label="Nombre"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppTextField
                          v-model="editedItem.lastName"
                          label="Apellido"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppSelect
                          v-model="editedItem.idType"
                          :items="idTypes"
                          label="Tipo de document"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppTextField
                          v-model="editedItem.idNumber"
                          label="Número del Documento"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppTextField
                          v-model="editedItem.registerNumber"
                          label="Número de registro medico"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppTextField
                          v-model="editedItem.speciality"
                          label="Especialidad"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppTextField
                          v-model="editedItem.email"
                          label="Correo electrónico"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppSelect
                          v-model="editedItem.role"
                          :items="roles"
                          label="Categoría"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppTextField
                          v-model="editedItem.password"
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
                          v-model="editedItem.confirm_password"
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
                          @click="saveAccount"
                        >
                          Ahorrar
                        </VBtn>
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
          </VContainer>
        </VCardText>
      </VCard>
    </VDialog>
    <VSnackbar
      v-model="isAddSuccess"
      :timeout="1000"
      location="top end"
      variant="tonal"
      color="primary"
    >
      Created an account successfully.
    </VSnackbar>
    <VSnackbar
      v-model="isAddFailed"
      :timeout="1000"
      location="top end"
      variant="tonal"
      color="error"
    >
      {{ error }}
    </VSnackbar>
    <VSnackbar
      v-model="isRemoveSuccess"
      :timeout="1000"
      location="top end"
      variant="tonal"
      color="primary"
    >
      Removed an account successfully.
    </VSnackbar>
  </VRow>
</template>
