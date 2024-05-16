<script setup>
import axios from '@axios'
import congoImg from '@images/ic-clinic-service.png'

const _services = ref([])

onMounted(() => {
  axios.get('/services')
    .then(r => {
      const { services } = r.data

      _services.value = services
    })
})
</script>

<template>
  <section class="mb-6">
    <VRow>
      <VCol cols="12">
        <h2 class="text-h2">
          Servicios Clínicos
        </h2>
      </VCol>
      <VCol
        v-for="service in _services"
        :key="service.id"
        cols="12"
        md="4"
      >
        <VCard>
          <VRow no-gutters>
            <VCol cols="8">
              <VCardText>
                <h6 class="text-lg text-no-wrap font-weight-medium text-capitalize">
                  {{ service.name }} 🎉
                </h6>
                <p class="mb-2">
                  {{ service.duration }} mins
                </p>
                <h4 class="text-h4 font-weight-medium text-primary mb-1">
                  ${{ service.price }}
                </h4>
              </VCardText>
            </VCol>

            <VCol cols="4">
              <VCardText class="pb-0 px-0 position-relative h-100">
                <VImg
                  :src="congoImg"
                  height="120"
                  class="congo-john-img w-100"
                />
              </VCardText>
            </VCol>
          </VRow>
        </VCard>
      </VCol>
    </VRow>
  </section>
</template>