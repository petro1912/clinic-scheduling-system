<script setup>
import axios from '@axios'

const services = ref([])

const editDialog = ref(false)
const deleteDialog = ref(false)
const isAddSuccess = ref(false)
const isRemoveSuccess = ref(false)

const defaultItem = ref({
  id: -1,
  name: '',
  duration: '',
  price: '',
})

const editedItem = ref(defaultItem.value)
const editedIndex = ref(-1)

const editItem = item => {
  editedIndex.value = services.value.indexOf(item)
  editedItem.value = { ...item }
  editDialog.value = true
}

const deleteItem = item => {
  editedIndex.value = services.value.indexOf(item)
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

const save = () => {
  const item = editedItem.value

  // if (item.name == "") {

  // } else if (item.price <= 0) {

  // } else if (item.duration <= 0) {

  // }
  axios.post('/doctor/services', { ...item })
    .then(r => {
      services.value = [...r.data.services]
      editedIndex.value = -1
      editedItem.value = { ...defaultItem.value }
      close()
      isAddSuccess.value = true
    })
}

const deleteItemConfirm = () => {
  if (editedIndex.value == -1)
    return

  const item = editedItem.value

  axios.delete(`/doctor/services/${item.id}`)
    .then(r => {
      services.value = [...r.data.services]
      editedIndex.value = -1
      editedItem.value = { ...defaultItem.value }
      closeDelete()
      isRemoveSuccess.value = true
    })
}

onMounted(() => {
  axios.get(`/doctor/services`)
    .then(r => {
      services.value = r.data.services  
    })
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardItem>
          <VCardTitle>Servicio médico</VCardTitle>
          <template #append>
            <VBtn
              variant="tonal"
              @click="editItem(defaultItem)"
            >
              <VIcon
                size="20"
                icon="tabler-plus"
              /> 
              Nuevo Servicio
            </VBtn>
          </template>
        </VCardItem>
        <VCardText>
          <VTable class="text-no-wrap">
            <thead>
              <tr>
                <th class="text-uppercase">
                  Servicio ID
                </th>
                <th class="text-uppercase text-center">
                  Servicio Nombre
                </th>
                <th class="text-uppercase text-center">
                  Duración (min)
                </th>
                <th class="text-uppercase text-center">
                  Precio (USD)
                </th>
                <th class="text-uppercase text-center">
                  Comportamiento
                </th>
              </tr>
            </thead>

            <tbody v-if="services">
              <tr
                v-for="item in services"
                :key="item.id"
              >
                <td>
                  {{ item.id }}
                </td>
                <td class="text-center">
                  {{ item.name }}
                </td>
                <td class="text-center">
                  {{ item.duration }}
                </td>
                <td class="text-center">
                  {{ item.price }}
                </td>
                <td>
                  <div class="d-flex gap-1 justify-center">
                    <IconBtn @click="editItem(item)">
                      <VIcon icon="mdi-pencil-outline" />
                    </IconBtn>
                    <IconBtn @click="deleteItem(item)">
                      <VIcon icon="mdi-delete-outline" />
                    </IconBtn>
                  </div>
                </td>
              </tr>
            </tbody>
          </VTable>
        </VCardText>
      </VCard>
    </VCol>
    <!-- 👉 Edit Dialog  -->
    <VDialog
      v-model="editDialog"
      max-width="600px"
    >
      <VCard>
        <VCardTitle>
          <span class="headline">Edit Item</span>
        </VCardTitle>

        <VCardText>
          {{ editedItem?.name }}
          <VContainer>
            <VRow>
              <!-- full_name -->
              <VCol cols="12">
                <VTextField
                  v-model="editedItem.name"
                  label="Service name"
                />
              </VCol>

              <!-- email -->
              <VCol cols="12">
                <VTextField
                  v-model="editedItem.duration"
                  label="Duration"
                  suffix="min"
                />
              </VCol>

              <!-- salary -->
              <VCol cols="12">
                <VTextField
                  v-model="editedItem.price"
                  label="Price"
                  prefix="$"
                  type="number"
                />
              </VCol>
            </VRow>
          </VContainer>
        </VCardText>

        <VCardActions>
          <VSpacer />

          <VBtn
            color="error"
            variant="outlined"
            @click="close"
          >
            Cancel
          </VBtn>

          <VBtn
            color="success"
            variant="elevated"
            @click="save"
          >
            Save
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <!-- 👉 Delete Dialog  -->
    <VDialog
      v-model="deleteDialog"
      max-width="500px"
    >
      <VCard>
        <VCardTitle>
          Are you sure you want to delete this item?
        </VCardTitle>

        <VCardActions>
          <VSpacer />

          <VBtn
            color="error"
            variant="outlined"
            @click="closeDelete"
          >
            Cancel
          </VBtn>

          <VBtn
            color="success"
            variant="elevated"
            @click="deleteItemConfirm"
          >
            OK
          </VBtn>

          <VSpacer />
        </VCardActions>
      </VCard>
    </VDialog>
    <VSnackbar
      v-model="isAddSuccess"
      :timeout="1000"
      location="top end"
      variant="tonal"
      color="primary"
    >
      Created a service successfully.
    </VSnackbar>
    <VSnackbar
      v-model="isRemoveSuccess"
      :timeout="1000"
      location="top end"
      variant="tonal"
      color="primary"
    >
      Removed a service successfully.
    </VSnackbar>
  </VRow>
</template>