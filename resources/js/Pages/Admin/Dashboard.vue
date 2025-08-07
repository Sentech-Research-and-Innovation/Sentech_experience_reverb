<template>
  <div>
    <h1>Dashboard</h1>
    <p v-if="loading">Loading stats...</p>
    <p v-else-if="error">Failed to load stats.</p>
    <pre v-else>{{ stats }}</pre>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'

const stats = ref(null)
const loading = ref(true)
const error = ref(false)

onMounted(async () => {
  try {
    const response = await axios.get("/api/admin/dashboard/stats")
    stats.value = response.data
    console.log(response.data)
  } catch (e) {
    console.error("Failed to fetch dashboard stats", e)
    error.value = true
  } finally {
    loading.value = false
  }
})
</script>
